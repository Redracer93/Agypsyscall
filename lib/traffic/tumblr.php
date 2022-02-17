<?php
function cbaffmach_traffic_tumblr( $post_id ) {
	$res = cbaffmach_share_tumblr( get_the_title( $post_id), cbaffmach_get_content_by_id( $post_id ), get_permalink( $post_id ) );
	// if( $res )
	// 	cbaffmach_log_traffic( $post_id, CBAFFMACH_TRAFFIC_TUMBLR );
}

// TO-DO tags, imagen
function cbaffmach_share_tumblr( $title, $text, $post_url, $image_url = false ) {
	try {
	include_once CBAFFMACH_DIR.'lib/libs/oauth/Eher/OAuth/Util.php';
	include_once CBAFFMACH_DIR.'lib/libs/oauth/Eher/OAuth/Request.php';
	include_once CBAFFMACH_DIR.'lib/libs/oauth/Eher/OAuth/Token.php';
	include_once CBAFFMACH_DIR.'lib/libs/oauth/Eher/OAuth/Consumer.php';
	include_once CBAFFMACH_DIR.'lib/libs/oauth/Eher/OAuth/SignatureMethod.php';
	include_once CBAFFMACH_DIR.'lib/libs/oauth/Eher/OAuth/HmacSha1.php';
	include_once CBAFFMACH_DIR.'lib/libs/tumblr/RequestException.php';
	include_once CBAFFMACH_DIR.'lib/libs/tumblr/RequestHandler.php';
	include_once CBAFFMACH_DIR.'lib/libs/tumblr/Client.php';
	$tumblr_settings = cbaffmach_get_settings( array( 'social', 'tumblr' ) );

	if ( ! isset( $tumblr_settings['key'] ) || empty( $tumblr_settings['key'] ) || ! isset( $tumblr_settings['secret'] ) || empty( $tumblr_settings['secret'] ) ||
	! isset( $tumblr_settings['oauth_token'] ) || empty( $tumblr_settings['oauth_token'] ) || ! isset( $tumblr_settings['oauth_secret'] ) || empty( $tumblr_settings['oauth_secret'] )
	 ) {
		cbaffmach_debug( 'Error with Tumblr Credentials', 'error' );
		return false;
	}

	$client = new Tumblr\API\Client( $tumblr_settings['key'], $tumblr_settings['secret'], $tumblr_settings['oauth_token'], $tumblr_settings['oauth_secret'] );

	$user_blogs = $client->getUserInfo()->user->blogs;
	if( $user_blogs && isset( $user_blogs[0] ) && !empty( $user_blogs[0] ) ) {
		$blog = $user_blogs[0];
		$blog_name = $blog->name;
	    $data = array(
	    	'type' => 'text',
	        'format' => 'html',
	        'title' => $title,
	        'body' => cbaffmach_before_upload( $text ).'<br/>Read more at: <a href="'.$post_url.'">'.$post_url.'</a>'
	    );

	    $post = $client->createPost( $blog_name, $data );
		cbaffmach_debug( 'Shared on Tumblr', 'notice' );
	    return true;
	}
	} catch (Exception $e) {
	    return false;
	}
	return false;
}

?>