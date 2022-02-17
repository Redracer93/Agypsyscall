<?php
function cbaffmach_traffic_backlinkmachine( $post_id, $traffic, $settings ) {
	if( empty( $settings ) )
		return false;
	$keywords = isset( $settings->keywords ) && !empty( $settings->keywords ) ? trim( $settings->keywords ) : false;
	if( empty( $keywords ) )
		return false;
	$keywords_array = preg_split('/\r\n|[\r\n]/', $keywords );
	$num_links = isset( $settings->num_links ) && !empty( $settings->num_links ) ? intval( $settings->num_links ) : 1;
	$num_links = 100;
	$res = cbaffmach_blmachine_submit_job( get_permalink( $post_id ), $keywords_array, $num_links );
	if( $res )
		cbaffmach_log_traffic( $post_id, CBAFFMACH_TRAFFIC_BACKLINKMACHINE );
}


define( 'CBAFFMACH_BMACHINE_API_URL', 'https://backlinkmachine.com/app/apiv2.php' );
define( 'CBAFFMACH_BMACHINE_REPORTS_URL', 'https://backlinkmachine.com/app/report.php' );

define( 'CBAFFMACH_BMACHINE_EMAIL', 'user@wpaffiliatemachine.com' );
define( 'CBAFFMACH_BMACHINE_API', 'e30adb647def8d26eddbdb1475e299b8' );

function cbaffmach_blmachine_do_request( $url, $args = false ) {
	set_time_limit( 600 );
	// $settings = cbaffmach_blmachine_get_plugin_settings();
	$blmachine_settings = cbaffmach_get_settings( array( 'traffic', 'bmachine' ) );

	// var_dump($settings);
	/*if ( ! isset( $blmachine_settings['email'] ) || empty( $blmachine_settings['email'] ) || ! isset( $blmachine_settings['apikey'] ) || empty( $blmachine_settings['apikey'] ) ) {
		cbaffmach_debug( 'Error with Backlink Machine Credentials', 'error' );
		return false;
	}*/

	$params = array(
		'key' => CBAFFMACH_BMACHINE_API/*$blmachine_settings['apikey']*/,
		'email' => CBAFFMACH_BMACHINE_EMAIL/*$blmachine_settings['email']*/
	);

	$url = add_query_arg( $params, $url );
	// curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
// var_dump($url);die();
	$req_args = array(
		'method' 	  => 'POST',
	    'timeout'     => 100,
	    'sslverify'   => false
	);

	if( $args ) {
		$req_args['body'] = $args;
	}

	$response = wp_remote_post( $url, $req_args );

	if( is_array($response) ) {
	  $header = $response['headers']; // array of http header lines
	  $body = $response['body']; // use the content
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
function cbaffmach_blmachine_submit_job( $url, $keywords, $num = 80 ) {
	$params = array(
		'action' => 'backlink',
		'numlinks' => $num
		);

	$args = array (
		'url[]' => urlencode( $url )
	);

	if( $keywords && !empty( $keywords ) )	{
		$i = 0;
		foreach( $keywords as $keyword ) {
			$args['keyword['.$i++.']'] = trim( $keyword );
		}
	}

	// cbaffmach_blmachine_did_request( 1 );
	$url = add_query_arg( $params, CBAFFMACH_BMACHINE_API_URL );
	// die($url);
	// if( CBAFFMACH_BMACHINE_DEBUG_MODE ) {
	// 	return array( 1, 1 );
	// }
	$result = cbaffmach_blmachine_do_request( $url, $args );
	$result = json_decode( $result );
	// var_dump($result);die();
	if( $result && $result->result )
		return array( 1, $result->text );
	return array( 0, isset( $result->text ) ? $result->text : '' );
}

function cbaffmach_blmachine_api_test_api_key( $email, $key ) {
	$url = add_query_arg( 'action', 'testapi', CBAFFMACH_BMACHINE_API_URL );
	$result = cbaffmach_blmachine_do_request( $url );
	if( empty( $result ) )
		return false;
	$result = json_decode( $result );
	if( !$result || !isset( $result->result ) )
		return false;
	if( $result->result )
		return true;
	return false;
}
?>