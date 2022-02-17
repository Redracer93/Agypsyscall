<?php
/**
 * Plugin Name: CB Automator
 * Plugin URI: https://cbautomator.com/
 * Description: Automatically Import done-for-you review articles with your affiliate link to earn commissions on complete autopilot from Clickbank.
 * Author: Ankur Shukla
    Version: 1.11
 * Author URI: https://ankurshukla.com/
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Constants
define( 'CBAFFMACH_VERSION', '1.11' );

define( 'CBAFFMACH_PLUGIN_NAME', dirname( plugin_basename( __FILE__ ) ) );
define( 'CBAFFMACH_DIR', plugin_dir_path( __FILE__ ) );
define( 'CBAFFMACH_URL', plugins_url( CBAFFMACH_PLUGIN_NAME ) );

if ( is_admin() ) {
    require CBAFFMACH_DIR.'/lib/fields.php';
    require CBAFFMACH_DIR.'/lib/settings.php';
    require CBAFFMACH_DIR.'/lib/inline.php';
    require CBAFFMACH_DIR.'/lib/log.php';
    require CBAFFMACH_DIR.'/lib/help.php';
    require CBAFFMACH_DIR.'/lib/research.php';
    require CBAFFMACH_DIR.'/lib/ui.php';
    require CBAFFMACH_DIR.'/lib/styles/dashboard.php';
    add_action( 'wp_ajax_cbaffmach_remove_lead', 'cbaffmach_remove_lead_ajax' );
    add_action( 'wp_ajax_cbaffmach_save_aff_link', 'cbaffmach_save_aff_link_ajax' );
    add_action( 'wp_ajax_cbaffmach_share_link', 'cbaffmach_share_link_ajax' );
    add_action( 'wp_ajax_cbaffmach_create_video', 'cbaffmach_create_video_ajax' );
    add_action( 'wp_ajax_cbaffmach_get_cb_link', 'cbaffmach_get_cb_link_ajax' );
}
else {
    include CBAFFMACH_DIR.'lib/monetize/monetize_fe.php';
	include CBAFFMACH_DIR.'lib/frontend.php';
}

require CBAFFMACH_DIR.'/lib/pdf.php';
require CBAFFMACH_DIR.'/lib/common.php';
require CBAFFMACH_DIR.'/lib/recurring.php';
include CBAFFMACH_DIR.'/lib/monetize/monetization.php';
require CBAFFMACH_DIR.'/lib/importing.php';
require CBAFFMACH_DIR.'/lib/traffic/avmaker.php';
include CBAFFMACH_DIR.'/lib/libs/autoresponders/autoresponders.php';
include_once CBAFFMACH_DIR.'lib/traffic.php';
include_once CBAFFMACH_DIR.'lib/traffic/traffic.php';

add_action ( 'wp_ajax_cbaffmach_dismiss_notice', 'cbaffmach_dismiss_notice_ajax' );

register_activation_hook( __FILE__, 'cbaffmach_activation' );
register_deactivation_hook( __FILE__, 'cbaffmach_deactivation' );

/* Automatic Updates */
require CBAFFMACH_DIR.'/lib/plugin-updates/plugin-update-checker.php';
$tagmachine_update_checker = new Puc_v4p10_Plugin_UpdateChecker(
    ( cbaffmach_is_pro() ? 'https://knighterrant.s3.us-west-2.amazonaws.com/software/cb-automator/2-pro/info_wpca2.json' :
    'https://knighterrant.s3.us-west-2.amazonaws.com/software/cb-automator/1-std/info_wpca1.json' ),
    CBAFFMACH_DIR.CBAFFMACH_PLUGIN_NAME.'.php'
);
?>