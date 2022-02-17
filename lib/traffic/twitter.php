<?php
function cbaffmach_traffic_twitter( $post_id ) {
	/*var_dump($settings);
	if( empty( $settings ) )
		return false;*/
	$res = cbaffmach_share_twitter( cbaffmach_get_the_excerpt( $post_id ), get_permalink( $post_id ), get_the_title( $post_id ) );
	// if( $res )
	// 	cbaffmach_log_traffic( $post_id, CBAFFMACH_TRAFFIC_TWITTER );
}

function cbaffmach_share_twitter( $text, $post_url, $title = '', $image_url = false ) {
	require_once CBAFFMACH_DIR.'/lib/libs/twitter/TwitterAPIExchange.php';
	$twitter_settings = cbaffmach_get_settings( array( 'social', 'twitter' ) );

	if ( ! isset( $twitter_settings['key'] ) || empty( $twitter_settings['key'] )
		|| ! isset( $twitter_settings['secret'] ) || empty( $twitter_settings['secret'] )
		|| ! isset( $twitter_settings['oauth_token'] ) || empty( $twitter_settings['oauth_token'] )
		|| ! isset( $twitter_settings['oauth_secret'] ) || empty( $twitter_settings['oauth_secret'] )
) {
		cbaffmach_debug( 'Error with Twitter Credentials', 'error' );
		return false;
	}

	$settings = array(
	    'consumer_key' => $twitter_settings['key'],
	    'consumer_secret' => $twitter_settings['secret'],
	    'oauth_access_token' => $twitter_settings['oauth_token'],
	    'oauth_access_token_secret' => $twitter_settings['oauth_secret'],
	);

	$media_id = 0;
	/*if( 1 && $image_url ) {
		$url = 'https://api.twitter.com/1.1/media/upload.json';
		$requestMethod = 'POST';
		$twitter = new TwitterAPIExchange( $settings );
		$code = $twitter->buildOauth( $url, $requestMethod )
		             ->setPostfields( $postfields )
		             ->performRequest();
	}*/

	$postfields = array(
	    'status' => $title.' '. $post_url
	);

	/*if( $media_id )
		$postfields['media_ids'] = $media_id;*/

	$url = 'https://api.twitter.com/1.1/statuses/update.json';
	$requestMethod = 'POST';
	$twitter = new TwitterAPIExchange( $settings );
	$code = $twitter->buildOauth( $url, $requestMethod )
	             ->setPostfields( $postfields )
	             ->performRequest();
	// var_dump($code);
	cbaffmach_debug( 'Shared on your Twitter Timeline', 'notice' );
	return true;
}
?>