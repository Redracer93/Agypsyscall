<?php
function cbaffmach_traffic_linkedin( $post_id ) {
	$res = cbaffmach_share_linkedin( get_the_title( $post_id), cbaffmach_get_content_by_id( $post_id ), get_permalink( $post_id ) );
	if( $res )
		cbaffmach_log_traffic( $post_id, CBAFFMACH_TRAFFIC_LINKEDIN );
}

function cbaffmach_share_linkedin( $title, $text, $post_url, $image_url = false ) {
	require_once CBAFFMACH_DIR.'/lib/libs/linkedin/LinkedIn.php';
	$linkedin_settings = cbaffmach_get_settings( array( 'social', 'linkedin' ) );
	// var_dump($linkedin_settings);
	if ( ! isset( $linkedin_settings['app_id'] ) || empty( $linkedin_settings['app_id'] ) || ! isset( $linkedin_settings['app_secret'] ) || empty( $linkedin_settings['app_secret'] ) ) {
		cbaffmach_debug( 'Error with Linkedin Credentials', 'error' );
		return false;
	}

	$li = new LinkedIn(
	  array(
	    'api_key' => $linkedin_settings['app_id'],
	    'api_secret' => $linkedin_settings['app_secret'],
	    'callback_url' => admin_url('/admin.php?page=affiliate-machine-settings&lin_auth=true')
	  )
	);

	$li->setAccessToken( $linkedin_settings['token'] );

	/*$post = array(
	'comment' => 'Test social Share',
	'content' => array(
		'title' => $title,
		'description' => 'test description', //Maxlen(255)
		'submitted_url' => $post_url
	 ),
	'visibility' => array(
		'code' => 'anyone'
	));*/

	$post = array(
	'comment' => $title.' '.$post_url,
	'visibility' => array(
		'code' => 'anyone'
	));
	$info = $li->post('/people/~/shares', $post );
	if( $info )
		return true;
	return false;
}

function cbaffmach_try_linkedin_auth( $linkedin_app_id, $linkedin_app_secret ) {
	require_once CBAFFMACH_DIR.'/lib/libs/linkedin/LinkedIn.php';

	if( isset( $_GET['code'] ) && isset( $_GET['lin_auth'] ) ) {
		// We are trying to authenticate
		$settings = cbaffmach_get_plugin_settings();
		$linkedin_settings = cbaffmach_get_settings( array( 'social', 'linkedin' ) );

		$li = new LinkedIn(
		  array(
		    'api_key' => $linkedin_settings['app_id'],
		    'api_secret' => $linkedin_settings['app_secret'],
		    'callback_url' => admin_url('/admin.php?page=affiliate-machine-settings&lin_auth=true')
		  )
		);

		$token = $li->getAccessToken( $_REQUEST['code'] );
		$token_expires = $li->getAccessTokenExpiration();

		$settings['social']['linkedin']['token'] = $token;
		cbaffmach_save_plugin_settings( $settings );
	}
}
?>