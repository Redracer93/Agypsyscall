<?php
function cbaffmach_traffic_medium( $post_id ) {
	$res = cbaffmach_share_medium( get_the_title( $post_id), cbaffmach_get_content_by_id( $post_id ), get_permalink( $post_id ) );
	// if( $res )
	// 	cbaffmach_log_traffic( $post_id, CBAFFMACH_TRAFFIC_MEDIUM );
}

// TO-DO tags, imagen
function cbaffmach_share_medium( $title, $text, $post_url, $image_url = false ) {
	require_once CBAFFMACH_DIR.'lib/libs/medium/MediumClient.php';
	require_once CBAFFMACH_DIR.'lib/libs/medium/Medium.php';
	$medium_settings = cbaffmach_get_settings( array( 'social', 'medium' ) );

	if ( ! isset( $medium_settings['token'] ) || empty( $medium_settings['token'] ) ) {
		cbaffmach_debug( 'Error with Medium Credentials', 'error' );
		return false;
	}
	$medium = new Medium( $medium_settings['token'] );

	$user = $medium->getAuthenticatedUser();

    $data = array(
        'title' => $title,
        'contentFormat' => 'html',
        'content' => $text.'<br/>Read more at: <a href="'.$post_url.'">'.$post_url.'</a>',
        'publishStatus' => 'public',
        'canonicalUrl' => $post_url,
        'notifyFollowers' => true
    );

    $post = false;
    if( $medium && $user)
    	$post = $medium->createPost( $user->id, $data );
    if( $post )
    	return true;
    return false;
}
?>