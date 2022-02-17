<?php
// require_once CBAFFMACH_DIR.'/lib/libs/pinterest/nxs-api/nxs-api.php';
// require_once CBAFFMACH_DIR.'/lib/libs/pinterest/nxs-api/nxs-http.php';
// require_once CBAFFMACH_DIR.'/lib/libs/pinterest/inc/nxs-functions.php';

function cbaffmach_traffic_instagram( $post_id, $traffic, $settings ) {
	$res = cbaffmach_share_instagram( get_the_title( $post_id ), cbaffmach_get_the_excerpt( $post_id ), get_permalink( $post_id ), cbaffmach_get_thumbnail( $post_id ) );
	if( $res )
		cbaffmach_log_traffic( $post_id, CBAFFMACH_TRAFFIC_INSTAGRAM );
}

function cbaffmach_share_instagram( $title, $text, $post_url, $image_url = false ) {
	$instagram_settings = cbaffmach_get_settings( array( 'social', 'instagram' ) );

	if ( ! isset( $instagram_settings['email'] ) || empty( $instagram_settings['email'] ) || ! isset( $instagram_settings['pass'] ) || empty( $instagram_settings['pass'] ) ) {
		cbaffmach_debug( 'Error with Instagram Credentials', 'error' );
		return false;
	}
	cbaffmach_include_nextgen();

	$email = $instagram_settings['email'];
	$password = $instagram_settings['pass'];

	$imgFormat = 'E'; // 'E' (Extended) or 'C' (Cropped) or 'U' (Untouched)
	$nt = new nxsAPI_IG();
	$loginError = $nt->connect( $email, $password );
	if (!$loginError)
	  {
	    $result = $nt->post( cbaffmach_before_upload( $title ).' '.$post_url, $image_url, $imgFormat );
	    if( $result ) {
			cbaffmach_debug( 'Shared on Instagram', 'notice' );
	    	return true;
	    }
	  }
	else {
		cbaffmach_debug( 'Instagram - error with login', 'error' );
		return false;
	}
	return false;
}

?>