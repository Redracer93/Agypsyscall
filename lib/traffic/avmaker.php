<?php
	define( 'CBAFFMACH_WPAVMAKER_API_URL', 'https://azonvideomaker.com/app/apiv3.php' );
	// define( 'WPAVMAKER_API_URL', 'http://localhost/videotrafficx/apiv3.php' );
	define( 'CBAFFMACH_WPAVMAKER_REPORTS_URL', 'https://azonvideomaker.com/app/report.php' );

	define( 'CBAFFMACH_WPAVMAKER_EMAIL', 'user@wpaffiliatemachine.com' );
	define( 'CBAFFMACH_WPAVMAKER_API', 'e30adb647def8d26eddbdb1475e299b8' );

	function cbaffmach_wpavmaker_do_request( $url, $args = false ) {
		set_time_limit( 600 );
		$settings = cbaffmach_get_settings( array( 'video', 'avmaker' ) );
		// var_dump($settings);
		if ( ! isset( $settings['email'] ) || empty( $settings['email'] ) || ! isset( $settings['apikey'] ) || empty( $settings['apikey'] ) ) {
			$settings = array();
			$settings['email'] = CBAFFMACH_WPAVMAKER_EMAIL;
			$settings['apikey'] = CBAFFMACH_WPAVMAKER_API;
		}

		$params = array(
			'key' => $settings['apikey'],
			'email' => $settings['email']
		);

		$url = add_query_arg( $params, $url );

		// curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$req_args = array(
			'method' 	  => 'POST',
		    'timeout'     => 100,
		    /*'redirection' => 5,
		    'httpversion' => '1.0',
		    'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),
		    'blocking'    => true,
		    'headers'     => array(),
		    'cookies'     => array(),
		    'body'        => null,
		    'compress'    => false,
		    'decompress'  => true,*/
		    'sslverify'   => false/*,
		    'stream'      => false,
		    'filename'    => null*/
		);
		if( $args ) {
			$req_args['body'] = $args;
		}

// echo $url.'<br/>';
		$response = wp_remote_post( $url, $req_args );
		// var_dump($response);
		// die();
		if( is_array($response) ) {
		  $header = $response['headers']; // array of http header lines
		  $body = $response['body']; // use the content
		  // var_dump($url);
		  // var_dump($body);
		  // die();
		  return $body;
		}
		else {
		    $ch = curl_init();
		    curl_setopt($ch,CURLOPT_URL, $url);
    		curl_setopt($ch,CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
    		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
    		if( $args ) {
				curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
    		}
		    $data = curl_exec($ch);
		    curl_close($ch);
		    return $data;
		}
		return false;
	}

	/* Keywords is an array of keywords */
	function cbaffmach_wpavmaker_submit_job( $title, $content, $images, $video_language, $upload_youtube, $youtube_title, $youtube_description ) {
		// return 2136866;
		$post_params = array(
			'action' => 'video',
			'name' => $title,
			'text' => $content,
			'images' => /*json_encode(*/ $images /*)*/ ,
			'voiceLanguage' => $video_language,
			'ytUploadEnabled' => $upload_youtube,
			'videoTitle' => $youtube_title,
			'videoDescription' => $youtube_description,
			'disableAnimation' => 1
			);

			$get_params =  array(
			'action' => 'video'
			);
			// echo '<pre>';
			// print_r($post_params);
			// echo '</pre>';
// var_dump($params);
		// $args = array (
		// 	'url[]' => urlencode( $url )
		// );

		// if( $keywords )	{
		// 	$i = 0;
		// 	foreach( $keywords as $keyword ) {
		// 		$args['keyword['.$i++.']'] = trim( $keyword );
		// 	}
		// }

		cbaffmach_wpavmaker_did_request( 1 );
		$url = add_query_arg( $get_params, CBAFFMACH_WPAVMAKER_API_URL );
		// die($url);
		if( 0 ) {
			return array( 1, 1 );
		}
		$args = false;
		$result = cbaffmach_wpavmaker_do_request( $url, $post_params );
		// var_dump($post_params);
		// var_dump($url);
		// var_dump($result);
		// exit();
		$result = json_decode( $result );
		if( $result && $result->result )
			return array( 1, $result->text );
		return array( 0, $result->text );
	}

	function cbaffmach_wpavmaker_get_remaining_links( $force_read = 0 ) {
		// rmi353, transient
		// delete_transient( 'cbaffmach_wpavmaker_rem_links' );
		$cbaffmach_wpavmaker_rem_links = 0;
		if ( ( get_cbaffmach_wpavmaker_did_request() == 1  ) || (false === ( $cbaffmach_wpavmaker_rem_links = get_transient( 'cbaffmach_wpavmaker_rem_links' ) ) ) ) {
		    // It wasn't there, so regenerate the data and save the transient
		    $url = add_query_arg( 'action', 'credits', CBAFFMACH_WPAVMAKER_API_URL );
		    $result = cbaffmach_wpavmaker_do_request( $url );
		    $result = json_decode( $result );
		    // var_dump($result);
		    if( !$result || !isset( $result->result ) )
		    	return 0;
		    if( $result->result )
		     $cbaffmach_wpavmaker_rem_links = $result->text;
		 	else
		 		$cbaffmach_wpavmaker_rem_links = 0;
		     set_transient( 'cbaffmach_wpavmaker_rem_links', $cbaffmach_wpavmaker_rem_links, 1 * HOUR_IN_SECONDS );
		     cbaffmach_wpavmaker_did_request( 0 );
		}

		return $cbaffmach_wpavmaker_rem_links;
		return 0;
	}

	function cbaffmach_wpavmaker_api_test_api_key( $email, $key ) {
		$url = add_query_arg( 'action', 'testapi', CBAFFMACH_WPAVMAKER_API_URL );
		$result = cbaffmach_wpavmaker_do_request( $url );
		if( empty( $result ) )
			return false;
		$result = json_decode( $result );
		if( !$result || !isset( $result->result ) )
			return false;
		if( $result->result )
			return true;
		return false;
	}

	function cbaffmach_wpavmaker_did_request( $val ) {
		update_option( 'cbaffmach_wpavmaker_did_request', $val );
	}

	function get_cbaffmach_wpavmaker_did_request() {
		return get_option( 'cbaffmach_wpavmaker_did_request', 0 );
	}
?>