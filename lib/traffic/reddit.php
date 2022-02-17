<?php
// require_once CBAFFMACH_DIR.'/lib/libs/pinterest/nxs-api/nxs-api.php';
// require_once CBAFFMACH_DIR.'/lib/libs/pinterest/nxs-api/nxs-http.php';
// require_once CBAFFMACH_DIR.'/lib/libs/pinterest/inc/nxs-functions.php';

function cbaffmach_traffic_reddit( $post_id, $traffic, $settings ) {
	$subreddit = $settings->subreddit;
	$res = cbaffmach_share_reddit( $subreddit, get_the_title( $post_id), cbaffmach_get_the_excerpt( $post_id ), get_permalink( $post_id ), cbaffmach_get_thumbnail( $post_id ) );
	if( $res )
		cbaffmach_log_traffic( $post_id, CBAFFMACH_TRAFFIC_REDDIT );
}

function cbaffmach_share_reddit( $subreddit, $title, $text, $post_url, $image_url = false ) {

	$reddit_settings = cbaffmach_get_settings( array( 'social', 'reddit' ) );

	if ( ! isset( $reddit_settings['email'] ) || empty( $reddit_settings['email'] ) || ! isset( $reddit_settings['pass'] ) || empty( $reddit_settings['pass'] ) ) {
		cbaffmach_debug( 'Error with Reddit Credentials', 'error' );
		return false;
	}
	cbaffmach_include_nextgen();

	// var_dump($reddit_settings);
	$email = $reddit_settings['email'];
	$password = $reddit_settings['pass'];

	$nt         = new nxsAPI_RD();
	$loginError = $nt->connect( $email, $password );
// var_dump($loginError);
	if ( !$loginError ) {
		//$msg, $title, $sr, $url
	  $return = $nt->post( $text, $title, $subreddit, $post_url );
		cbaffmach_debug( 'Shared on Reddit', 'notice' );
	  return true;
	}
	cbaffmach_debug( 'Error connecting with Reddit', 'error' );
	return false;
}

function cbaffmach_getsubreddits() {
	return cbaffmach_do_get_subreddits( );
    $transient_name = 'cbaffmach_subreddits';
    if ( false === ( $subreddits = get_transient( $transient_name ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
         $subreddits = cbaffmach_do_get_subreddits( );
         set_transient( $transient_name, $subreddits, 3 * HOUR_IN_SECONDS );
    }
    return $subreddits;
}

function cbaffmach_do_get_subreddits() {
	$reddit_settings = cbaffmach_get_settings( array( 'social', 'reddit' ) );

	if ( ! isset( $reddit_settings['email'] ) || empty( $reddit_settings['email'] ) || ! isset( $reddit_settings['pass'] ) || empty( $reddit_settings['pass'] ) )
		return false;

	// var_dump($reddit_settings);
	$email = $reddit_settings['email'];
	$password = $reddit_settings['pass'];

	$nt         = new nxsAPI_RD();
	$loginError = $nt->connect( $email, $password );
// var_dump($loginError);
	if ( !$loginError ) {
		return $nt->getSubReddits();
	}
	return array();
}
?>