<?php
function cbaffmach_get_settings( $path = array() ) {
	$settings = cbaffmach_get_plugin_settings();
	if( empty( $path ) )
		return $settings;
	$val = $settings;
	foreach( $path as $field ) {
		$val = isset( $val[ $field ] ) ? $val[ $field ] : false;
	}
	return $val;
}

function cbaffmach_dismiss_notice_ajax() {
	update_option( 'cbaffmach-ndis', 1 );
	echo 1;
	exit();
}

function cbaffmach_get_import_settings() {
	$settings = cbaffmach_get_plugin_settings();
	if( isset( $settings['import'] ) )
		return $settings['import'];
	return array();
}

function cbaffmach_get_plugin_settings() {
	return get_option( 'cbaffmach_settings' );
}

function cbaffmach_save_plugin_settings( $settings ) {
	return update_option( 'cbaffmach_settings', $settings );
}

function cbaffmach_escape_input_txt( $text ) {
	return htmlentities( $text, ENT_QUOTES, 'UTF-8' );
}

function cbaffmach_debug( $text, $level = 'notice' ) {
	if( defined( 'WPAUTOC_CRON_DEBUG' ) )
		echo '<p>'.$text.'</p>';
}

function cbaffmach_linkify($s) {
    return preg_replace('/https?:\/\/[\w\-\.!~#?&=+\*\'"(),\/]+/','<a href="$0" target="_blank">$0</a>',$s);
}

add_filter( 'plugin_action_links_' . CBAFFMACH_PLUGIN_NAME.'/'.CBAFFMACH_PLUGIN_NAME.'.php', 'cbaffmach_add_plugin_action_links' );

function cbaffmach_add_plugin_action_links( $links ) {
	$cbaffmach_options = array(
			'Support' => '<a target="_blank" href="https://cbautomator.com/support/">Support</a>'
	);

	if( !cbaffmach_is_pro() )
		$cbaffmach_options['pro'] = '<a target="_blank" style="color: red" href="https://cbautomator.convertri.com/pro">Upgrade to Pro</a>';
	if( !cbaffmach_is_recurring() )
		$cbaffmach_options['recurring'] = '<a target="_blank" style="color: blue" href="http://cbautomator.convertri.com/monthlycontent/">Get more Reviews</a>';
		return array_merge(
		$links,
		$cbaffmach_options
	);
}

function cbaffmach_is_plugin_there($plugin_dir) {
	$plugins = get_plugins($plugin_dir);
	if ($plugins) return true;
	return false;
}

function cbaffmach_get_the_excerpt( $post_id ) {
  global $post;
  $save_post = $post;
  $post = get_post($post_id);
  $output = @get_the_excerpt($post);
  $post = $save_post;
  return $output;
}

function cbaffmach_get_content_by_id( $post_id ) {
	return apply_filters('the_content', get_post_field('post_content', $post_id));
}

function cbaffmach_get_thumbnail( $post_id, $size = 'full' ) {
	$image_url = has_post_thumbnail( $post_id ) ? wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' ) : false;
	if( $image_url ) {
		if( isset( $image_url[0] ) && !empty( $image_url[0] ) )
			return $image_url[0];
	}
	return false;
}

function cbaffmach_short( $text, $max_len = 140 ) {
	return cbaffmach_shorten_text( $text, $max_len, false );
}

function cbaffmach_before_upload( $text ) {
	return html_entity_decode( $text );
}

function cbaffmach_shorten_text( $input, $length = 140, $ellipses = true, $strip_html = true ) {
    //strip tags, if desired
    if ($strip_html) {
        $input = strip_tags($input);
    }

    //no need to trim, already shorter than trim length
    if (strlen($input) <= $length) {
        return $input;
    }

    //find last space within length
    $last_space = strrpos(substr($input, 0, $length), ' ');
    if($last_space !== false) {
        $trimmed_text = substr($input, 0, $last_space);
    } else {
        $trimmed_text = substr($input, 0, $length);
    }
    //add ellipses (...)
    if ($ellipses) {
        $trimmed_text .= '...';
    }

    return $trimmed_text;
}

function cbaffmach_activation() {
	if (! wp_next_scheduled ( 'cbaffmach_cron_job' ) ) {
	  wp_schedule_event( time(), 'daily', 'cbaffmach_cron_job' );
	}

	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate ();

	$table_name = $wpdb->prefix . "cbaffmach_optins";
	$sql1 = "CREATE TABLE IF NOT EXISTS $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		name varchar(128) NOT NULL,
		email varchar(128) NOT NULL,
		post_id int(11)NOT NULL DEFAULT '0',
		created_at datetime NOT NULL,
	   PRIMARY KEY id (id)
	   ) $charset_collate;";
	   require_once ( ABSPATH . 'wp-admin/includes/upgrade.php' );
	   dbDelta ( $sql1 );
}

function cbaffmach_deactivation() {
  wp_clear_scheduled_hook( 'cbaffmach_cron_job' );
}

function cbaffmach_get_aff_networks( ) {
	$networks = array(
		array( 'id' => 0, 'name' => 'Clickbank'),
		array( 'id' => 1, 'name' => 'Warrior Plus'),
		array( 'id' => 2, 'name' => 'JVZoo'),
		array( 'id' => 3, 'name' => 'Other'),
	);
	return $networks;
}

function cbaffmach_since( $timestamp, $level=6 ) {
    global $lang;
    $date = new DateTime( $timestamp );
    // var_dump($timestamp);
    // $date->setTimestamp( $timestamp );
    $date = $date->diff(new DateTime());
    // build array
    $since = json_decode($date->format('{"year":%y,"month":%m,"day":%d,"hour":%h,"minute":%i,"second":%s}'), true);
    // remove empty date values
    $since = array_filter($since);
    // output only the first x date values
    $since = array_slice($since, 0, $level);
    // build string
    $last_key = key(array_slice($since, -1, 1, true));
    $string = '';
    foreach ($since as $key => $val) {
        // separator
        if ($string) {
            $string .= $key != $last_key ? ', ' : ' ' . 'y' . ' ';
        }
        // set plural
        $key .= $val > 1 ? 's' : '';
        // add date value
        $string .= $val . ' ' . $key;
    }
    return $string;
}

function cbaffmach_network_name( $network_id ) {
	$networks = cbaffmach_get_aff_networks( );
	foreach( $networks as $network ) {
		if( $network['id'] == $network_id )
			return $network['name'];
	}
	return '';
}

/* enable sessions */
if ( !function_exists( 'cbaffmach_session_enable' ) ) {
	function cbaffmach_session_enable() {
	 	if(!session_id()){
	    	session_start();
    	}
    }
}
add_action( 'init','cbaffmach_session_enable', 1 );

function cbaffmach_is_pro() {
	// return true;
	return false;
}

add_action ( 'wp_ajax_cbaffmach_optin_submit', 'cbaffmach_add_optin_ajax' );
add_action ( 'wp_ajax_nopriv_cbaffmach_optin_submit', 'cbaffmach_add_optin_ajax' );
?>