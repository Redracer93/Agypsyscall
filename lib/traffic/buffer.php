<?php
function cbaffmach_traffic_buffer( $post_id ) {
	$res = cbaffmach_share_buffer( get_the_title( $post_id), cbaffmach_get_content_by_id( $post_id ), get_permalink( $post_id ), cbaffmach_get_thumbnail( $post_id ) );
	// if( $res )
	// 	cbaffmach_log_traffic( $post_id, CBAFFMACH_TRAFFIC_BUFFER );
}

function cbaffmach_share_buffer( $title, $text, $post_url, $image_url = false ) {
	require_once CBAFFMACH_DIR.'lib/libs/buffer/buffer.php';
	$buffer_settings = cbaffmach_get_settings( array( 'social', 'buffer' ) );
	// var_dump($buffer_settings);
	if ( ! isset( $buffer_settings['client_id'] ) || empty( $buffer_settings['client_id'] ) || ! isset( $buffer_settings['client_secret'] ) || empty( $buffer_settings['client_secret'] ) || ! isset( $buffer_settings['token'] ) || empty( $buffer_settings['token'] ) ) {
		cbaffmach_debug( 'Error with Buffer Credentials', 'error' );
		return false;
	}

	$buffer = new CBBufferApp( $buffer_settings['client_id'], $buffer_settings['client_secret'], admin_url('/admin.php?page=affiliate-machine-settings&buffer_auth=true'), $buffer_settings['token'] );

	$profiles = $buffer->go('/profiles');
	if ( is_array($profiles ) ) {
	    foreach ($profiles as $profile) {
	        //this creates a status on each one
	        $update_post = array(
	            'text' => cbaffmach_before_upload( $title ).' '.$post_url,
	            'profile_ids[]' => $profile->id,
	            'shorten'=>true,
	            'now'=>true,
	            'top'=>true
	        );

	        if( $image_url ) {
	        	$update_post['media[picture]'] = $image_url;
	        	$update_post['media[thumbnail]'] = $image_url;
	        	$update_post['media[title]'] = $title;
	        	$update_post['media[link]'] = $post_url;
	        }
	        $buffer->go('/updates/create', $update_post );
	    }
	    return true;
    }
	cbaffmach_debug( 'Error Sharing on Buffer', 'error' );
	return false;
}

add_action( 'init', 'cbaffmach_try_buffer_auth' );

function cbaffmach_try_buffer_auth() {
    require_once CBAFFMACH_DIR.'/lib/libs/buffer/buffer.php';
    $buffer_settings = cbaffmach_get_settings( array( 'social', 'buffer' ) );
    // var_dump($buffer_settings);
    if ( ! isset( $buffer_settings['client_id'] ) || empty( $buffer_settings['client_id'] ) || ! isset( $buffer_settings['client_secret'] ) || empty( $buffer_settings['client_secret'] ) )
        return false;

	if( isset( $_GET['buffer_auth'] ) ) {
        $buffer = new CBBufferApp( $buffer_settings['client_id'], $buffer_settings['client_secret'], admin_url('/admin.php?page=affiliate-machine-settings&buffer_auth=true') );
        if ( !$buffer->ok ) {
            echo 'Error with Buffer Login.';
        } else {
            $settings = cbaffmach_get_plugin_settings();
            // $buffer_settings = cbaffmach_get_settings( array( 'social', 'buffer' ) );
            $token = $buffer->get_access_token();
            $settings['social']['buffer']['token'] = $token;
            cbaffmach_save_plugin_settings( $settings );
        }
    }
}
?>