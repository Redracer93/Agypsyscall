<?php

namespace Tumblr\API;

/**
 * A request handler for Tumblr authentication
 * and requests
 */
class RequestHandler
{

    private $consumer;
    private $token;
    private $signatureMethod;

    private $baseUrl;
    private $version;

    /**
     * Instantiate a new RequestHandler
     */
    public function __construct()
    {
        $this->baseUrl = 'https://api.tumblr.com/';
        $this->version = '0.1.2';

        $this->signatureMethod = new \Eher\OAuth\HmacSha1();
        // $this->client = new \GuzzleHttp\Client(array(
        //     'allow_redirects' => false,
        // ));
    }

    /**
     * Set the consumer for this request handler
     *
     * @param string $key    the consumer key
     * @param string $secret the consumer secret
     */
    public function setConsumer($key, $secret)
    {
        $this->consumer = new \Eher\OAuth\Consumer($key, $secret);
    }

    /**
     * Set the token for this request handler
     *
     * @param string $token  the oauth token
     * @param string $secret the oauth secret
     */
    public function setToken($token, $secret)
    {
        $this->token = new \Eher\OAuth\Token($token, $secret);
    }

    /**
     * Set the base url for this request handler.
     *
     * @param string $url The base url (e.g. https://api.tumblr.com)
     */
    public function setBaseUrl($url)
    {
        // Ensure we have a trailing slash since it is expected in {@link request}.
        if (substr($url, -1) !== '/') {
            $url .= '/';
        }

        $this->baseUrl = $url;
    }

    /**
     * Make a request with this request handler
     *
     * @param string $method  one of GET, POST
     * @param string $path    the path to hit
     * @param array  $options the array of params
     *
     * @return \stdClass response object
     */
    public function request($method, $path, $options)
    {
        // Ensure we have options
        $options = $options ?: array();
// var_dump($options);
        // Take off the data param, we'll add it back after signing
        $file = isset($options['data']) ? $options['data'] : false;
        unset($options['data']);

        // Get the oauth signature to put in the request header
        $url = $this->baseUrl . $path;
        $oauth = \Eher\OAuth\Request::from_consumer_and_token(
            $this->consumer,
            $this->token,
            $method,
            $url,
            $options
        );
        $oauth->sign_request($this->signatureMethod, $this->consumer, $this->token);
        $authHeader = $oauth->to_header();
        $pieces = explode(' ', $authHeader, 2);
        $authString = $pieces[1];


        // Set up the request and get the response
        // $uri = new \GuzzleHttp\Psr7\Uri($url);
        // var_dump($uri);
        // var_dump($url);
        $uri = $url;
        $guzzleOptions = [
            'headers' => [
                'Authorization' => $authString,
                'User-Agent' => 'tumblr.php/' . $this->version,
            ],
            // Swallow exceptions since \Tumblr\API\Client will handle them
            'http_errors' => false,
        ];
        if ($method === 'GET') {
// var_dump($options);
            // $uri = $uri->withQuery(http_build_query($options));
            $uri = add_query_arg( $options, $uri );
        } elseif ($method === 'POST') {
            // var_dump($file);
            // var_dump($options);
            if (!$file) {
                $guzzleOptions['form_params'] = $options;
            } else {
                // Add the files back now that we have the signature without them
                $content_type = 'multipart';
                $form = [];
                foreach ($options as $name => $contents) {
                    $form[] = [
                        'name'      => $name,
                        'contents'  => $contents,
                    ];
                }
                foreach ((array) $file as $idx => $path) {
                    $form[] = [
                        'name'      => "data[$idx]",
                        'contents'  => file_get_contents($path),
                        'filename'  => pathinfo($path, PATHINFO_FILENAME),
                    ];
                }
                $guzzleOptions['multipart'] = $form;
            }
        }

        $headers = array(
            'Authorization' => $authString,
            'User-Agent' => 'tumblr.php/' . $this->version,
        );
        if( $method == 'GET') {
            $req_args = array(
                'timeout'     => 40,
                'headers'     => $headers,
                'sslverify'   => false,
                "user-agent" => "MonkeyMagic/1.0"
            );
            // var_dump( $req_args );
            // echo $req_url;
            $response = wp_remote_get( $uri, $req_args );
        }
        else if( $method == 'POST') {
            // $headers['Content-Type'] = "multipart/form-data;";
            $req_args = array(
                'method'      => 'POST',
                'timeout'     => 40,
                'headers'     => $headers,
                'cookies'     => array()/*,
                'sslverify'   => false,
                "user-agent" => 'tumblr.php/' . $this->version*/
            );
            if( isset( $options ) && !empty( $options ) ) {

                $form = [];
                foreach ($options as $name => $contents) {
                    $form[] = [
                        'name'      => $name,
                        'contents'  => $contents,
                    ];
                }
                // $req_args['body'] = json_encode($data);
                // $req_args['body'] = json_encode($form);
                $req_args['body'] = $options;
            }

            try {
                $response = wp_remote_post( $uri, $req_args );

            } catch (Exception $e) {
                   }
        }
        // var_dump($response);
        // $response = $this->client->request($method, $uri, $guzzleOptions);

        // Construct the object that the Client expects to see, and return it
        $obj = new \stdClass;
        $obj->status = wp_remote_retrieve_response_code( $response );
        // Turn the stream into a string
        $obj->body = wp_remote_retrieve_body( $response );
        // var_dump($obj->body);

        $obj->headers = wp_remote_retrieve_headers( $response );

        return $obj;
    }

}
