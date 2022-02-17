<?php

include CBAFFMACH_DIR.'lib/traffic/backlinkmachine.php';
include CBAFFMACH_DIR.'lib/traffic/linkindexers.php';
include CBAFFMACH_DIR.'lib/traffic/twitter.php';
include CBAFFMACH_DIR.'lib/traffic/facebook.php';
include CBAFFMACH_DIR.'lib/traffic/pinterest.php';
include CBAFFMACH_DIR.'lib/traffic/instagram.php';
include CBAFFMACH_DIR.'lib/traffic/googleplus.php';
include CBAFFMACH_DIR.'lib/traffic/reddit.php';
include CBAFFMACH_DIR.'lib/traffic/stumbleupon.php';
include CBAFFMACH_DIR.'lib/traffic/medium.php';
include CBAFFMACH_DIR.'lib/traffic/tumblr.php';
include CBAFFMACH_DIR.'lib/traffic/linkedin.php';
include CBAFFMACH_DIR.'lib/traffic/buffer.php';


add_action( 'cbaffmach_insert_post' , 'cbaffmach_new_post', 10 , 2 );
function cbaffmach_new_post( $post_id , $campaign_id )
{
	if ( !cbaffmach_is_traffic() )
		return;

	if( get_post_type( $post_id ) != 'post' )
		return;

	if ( wp_is_post_revision( $post_id ) )
		return;

	// if ( $post->post_date == $post->post_modified )
	// {

	$campaign_id = get_post_meta( $post_id, '_wpac_cid', true );
	if( !$campaign_id )
		return;
	// New post, get traffic!

	$traffics = cbaffmach_get_traffic_elements( $campaign_id );
	if( !$traffics )
		return $post_id;
	$i = 0;
// $settings = json_decode( $content->settings );

	foreach( $traffics as $traffic ) {
		if( !isset( $traffic->settings ) )
			continue;
		$settings = cbaffmach_json_decode_nice( $traffic->settings );
		switch ( $traffic->type ) {
			case CBAFFMACH_TRAFFIC_BACKLINKMACHINE:
				$done = cbaffmach_traffic_backlinkmachine( $post_id, $traffic, $settings );
				break;
			case CBAFFMACH_TRAFFIC_ILI:
				$done = cbaffmach_traffic_ili( $post_id, $traffic, $settings );
				break;
			case CBAFFMACH_TRAFFIC_BLI:
				$done = cbaffmach_traffic_bli( $post_id, $traffic, $settings );
				break;
			case CBAFFMACH_TRAFFIC_TWITTER:
				$done = cbaffmach_traffic_twitter( $post_id, $traffic, $settings );
				break;
			case CBAFFMACH_TRAFFIC_FACEBOOK:
				$done = cbaffmach_traffic_facebook( $post_id, $traffic, $settings );
				break;
			case CBAFFMACH_TRAFFIC_PINTEREST:
				$done = cbaffmach_traffic_pinterest( $post_id, $traffic, $settings );
				break;
			case CBAFFMACH_TRAFFIC_STUMBLEUPON:
				$done = cbaffmach_traffic_stumbleupon( $post_id, $traffic, $settings );
				break;
			case CBAFFMACH_TRAFFIC_MEDIUM:
				$done = cbaffmach_traffic_medium( $post_id, $traffic, $settings );
				break;
			case CBAFFMACH_TRAFFIC_TUMBLR:
				$done = cbaffmach_traffic_tumblr( $post_id, $traffic, $settings );
				break;
			case CBAFFMACH_TRAFFIC_LINKEDIN:
				$done = cbaffmach_traffic_linkedin( $post_id, $traffic, $settings );
				break;
			case CBAFFMACH_TRAFFIC_BUFFER:
				$done = cbaffmach_traffic_buffer( $post_id, $traffic, $settings );
				break;
			case CBAFFMACH_TRAFFIC_REDDIT:
				$done = cbaffmach_traffic_reddit( $post_id, $traffic, $settings );
				break;
			case CBAFFMACH_TRAFFIC_INSTAGRAM:
				$done = cbaffmach_traffic_instagram( $post_id, $traffic, $settings );
				break;
			case CBAFFMACH_TRAFFIC_GOOGLEPLUS:
				$done = cbaffmach_traffic_googleplus( $post_id, $traffic, $settings );
				break;
			default:
				break;
		}
	}
	// }
	return;
}

function cbaffmach_log_traffic( $post_id, $type ) {
	add_post_meta( $post_id, 'wpac_traf', $type, false );
}

function cbaffmach_get_traffic( $post_id ) {
	return get_post_meta( $post_id, 'wpac_traf', false );
}
?>