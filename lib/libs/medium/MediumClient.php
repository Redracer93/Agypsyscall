<?php

// namespace JonathanTorres\MediumSdk;

class MediumClient
{
    /**
     * Medium api url
     *
     * @var string
     */
    private $url = 'https://api.medium.com/v1/';

    /**
     * Guzzle http client
     *
     * @var GuzzleHttp\Client
     */
    private $client;

    private $access_token;
    /**
     * Ask medium for the access and refresh token
     * using the provided authorization code.
     *
     * @todo  This requestTokens() method uses some repeated code
     *        exchangeRefreshToken(), refactor to make the code less redundant and re-usable
     *
     * @param string $authorizationCode
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUrl
     *
     * @return StdClass
     */
    public function requestTokens($authorizationCode, $clientId, $clientSecret, $redirectUrl)
    {
        $data = [
            'form_params' => [
                'code' => $authorizationCode,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $redirectUrl,
            ],
        ];

        $client = new GuzzleClient([
            'base_uri' => $this->url,
            'exceptions' => false,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Accept-Charset' => 'utf-8',
            ],
        ]);

        $response = $client->request('POST', 'tokens', $data);

        return json_decode($response->getBody());
    }

    /**
     * Request a new access token using the refresh token.
     *
     * @todo  This exchangeRefreshToken() method uses some repeated code
     *        requestTokens(), refactor to make the code less redundant and re-usable
     *
     * @param string $refreshToken
     * @param string $clientId
     * @param string $clientSecret
     *
     * @return string
     */
    public function exchangeRefreshToken($refreshToken, $clientId, $clientSecret)
    {
        $data = [
            'form_params' => [
                'refresh_token' => $refreshToken,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'grant_type' => 'refresh_token',
            ],
        ];

        $client = new GuzzleClient([
            'base_uri' => $this->url,
            'exceptions' => false,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Accept-Charset' => 'utf-8',
            ],
        ]);

        $response = $client->request('POST', 'tokens', $data);

        return json_decode($response->getBody())->access_token;
    }

    /**
     * Authenticate client to make authenticated requests.
     *
     * @param string $accessToken
     *
     * @return void
     */
    public function authenticate( $accessToken )
    {
        $this->access_token = $accessToken;
        // $this
        /*$this->client = new GuzzleClient([
            'base_uri' => $this->url,
            'exceptions' => false,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Accept-Charset' => 'utf-8',
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);*/
    }

    /**
     * Make a request to medium's api.
     *
     * @param string $method
     * @param string $endpoint
     * @param array $data
     *
     * @return StdClass
     */
    public function makeRequest( $method, $endpoint, array $data = [] )
    {
        $req_url = $this->url.$endpoint;
        // echo '<h2>Antes data</h2>';
        // var_dump($data);
        // echo '<br>----<br/>';
        $headers = array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Accept-Charset' => 'utf-8',
            'Authorization' => 'Bearer ' . $this->access_token,
        );
        if( $method == 'POST' ) {
            $req_args = array(
                'method'      => 'POST',
                'timeout'     => 40,
                'headers'     => $headers,
                'cookies'     => array(),
                'sslverify'   => false,
                "user-agent" => "MonkeyMagic/1.0"
                /*,
                'body' => $data ,
                'stream'      => false,
                'timeout'     => 15,
                'cookies'     => true,
                'sslverify'   => false
                'filename'    => null */
            );
            if( $data ) {
                $req_args['body'] = json_encode($data);
            }

            try {
                $response = wp_remote_post( $req_url, $req_args );

            } catch (Exception $e) {
                   }
        }
        else if( $method == 'GET' ){
            $req_args = array(
                'timeout'     => 40,
                'headers'     => $headers,
                'sslverify'   => false,
                "user-agent" => "MonkeyMagic/1.0"
            );
            // var_dump( $req_args );
            // echo $req_url;
            $response = wp_remote_get( $req_url, $req_args );
            // echo '<h1>Response</h1>';
            // var_dump($response);
        }
        // $response = $this->client->request($method, $endpoint, $data);
        $body = wp_remote_retrieve_body( $response );
        $decoded = json_decode( $body );
        if( !empty( $decoded ) && isset( $decoded->data ) )
            return $decoded->data;
        return false;
    }
}
