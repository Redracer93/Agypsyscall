<?php
add_action ( 'admin_menu', 'cbaffmach_add_admin_menu', 16 );

function cbaffmach_add_admin_menu() {
    include CBAFFMACH_DIR.'/lib/styles/styles.php';
    // ask28_delete_license();
    $style28 = ask28_lic_status();
    // set_transient( 'cbaffmach_recurring', 0 );

	$page1 = add_menu_page( 'CB Automator', 'CB Automator', 'manage_options', 'cb-automator', ($style28 ? 'cbaffmach_screen_log' : 'ask28_settings'), CBAFFMACH_URL.'/img/icons/logo.png' );
    add_action( "admin_print_scripts-$page1", 'cbaffmach_admin_scripts' );
    add_action( "admin_print_styles-$page1", 'cbaffmach_admin_styles' );
    add_action( "admin_print_scripts", 'cbaffmach_admin_scriptsc' );

    if( !$style28 ) {
        $page6 = add_submenu_page('cb-automator', 'Help', 'Help', 'manage_options', 'cb-automator-support', 'cbaffmach_screen_help');
        add_action( "admin_print_scripts-$page6", 'cbaffmach_admin_scripts' );
        add_action( "admin_print_styles-$page6", 'cbaffmach_admin_styles' );
        return;
    }
    // $page8 = add_submenu_page('cb-automator', 'Research', 'Research', 'manage_options', 'cb-automator-research', 'cbaffmach_settings_research');
    $page2 = add_submenu_page('cb-automator', 'Settings', 'Settings', 'manage_options', 'cb-automator-settings', 'cbaffmach_settings_monetize');
    $page7 = add_submenu_page('cb-automator', 'Monthly Content', 'Monthly Content', 'manage_options', 'cb-automator-monthly', 'cbaffmach_settings_recurring');
    if( cbaffmach_is_pro() ) {
        $page3 = add_submenu_page('cb-automator', 'Inline Links', 'Inline Links', 'manage_options', 'cb-automator-inline', 'cbaffmach_inline_links');
        $page4 = add_submenu_page('cb-automator', 'Leads', 'Leads', 'manage_options', 'cb-automator-leads', 'cbaffmach_screen_leads');
        $page5 = add_submenu_page('cb-automator', 'Autoresponders', 'Autoresponders', 'manage_options', 'cb-automator-ar', 'cbaffmach_screen_autoresponders');
    }

    $page6 = add_submenu_page('cb-automator', 'Help', 'Help', 'manage_options', 'cb-automator-support', 'cbaffmach_screen_help');


	add_action( "admin_print_scripts-$page2", 'cbaffmach_admin_scripts' );
    add_action( "admin_print_styles-$page2", 'cbaffmach_admin_styles' );
    if( cbaffmach_is_pro() ) {
        add_action( "admin_print_scripts-$page3", 'cbaffmach_admin_scripts' );
        add_action( "admin_print_styles-$page3", 'cbaffmach_admin_styles' );
        add_action( "admin_print_scripts-$page4", 'cbaffmach_admin_scripts' );
        add_action( "admin_print_styles-$page4", 'cbaffmach_admin_styles' );
        add_action( "admin_print_scripts-$page5", 'cbaffmach_admin_scripts' );
        add_action( "admin_print_styles-$page5", 'cbaffmach_admin_styles' );
    }
	add_action( "admin_print_scripts-$page6", 'cbaffmach_admin_scripts' );
    add_action( "admin_print_styles-$page6", 'cbaffmach_admin_styles' );
    add_action( "admin_print_scripts-$page7", 'cbaffmach_admin_scripts' );
    add_action( "admin_print_styles-$page7", 'cbaffmach_admin_styles' );
    // add_action( "admin_print_scripts-$page8", 'cbaffmach_admin_scripts' );
    // add_action( "admin_print_styles-$page8", 'cbaffmach_admin_styles' );
}

function cbaffmach_admin_scripts() {
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_media( );
    wp_register_script( 'cbaffmach_admin_js', CBAFFMACH_URL . '/js/cbaffmach-admin.js', array( 'jquery' ), CBAFFMACH_VERSION );

    $aweber_lists = cbaffmach_get_aweber_mailing_lists();
    $getresponse_lists = cbaffmach_get_getresponse_mailing_lists();
    $icontact_lists = cbaffmach_get_icontact_mailing_lists();
    $mailchimp_lists = cbaffmach_get_mailchimp_mailing_lists();
    $ccontact_lists = cbaffmach_get_constantcontact_mailing_lists();
    $sendreach_lists = cbaffmach_get_sendreach_mailing_lists();
    // $sendy_lists = cbaffmach_get_sendy_mailing_lists();
    $activecampaign_lists = cbaffmach_get_activecampaign_mailing_lists();
    $mailit_lists = cbaffmach_get_mailit_mailing_lists();
    $sendlane_lists = cbaffmach_get_sendlane_mailing_lists();

    $script_vars = array(
        'plugin_url' => CBAFFMACH_URL,
        'admin_url' => admin_url(),
        'aweber_lists' => $aweber_lists,
        'getresponse_lists' => $getresponse_lists,
        'icontact_lists' => $icontact_lists,
        'mailchimp_lists' => $mailchimp_lists,
        'ccontact_lists' => $ccontact_lists,
        'sendreach_lists' => $sendreach_lists,
        'mailit_lists' => $mailit_lists,
        'activecampaign_lists' => $activecampaign_lists,
        'sendlane_lists' => $sendlane_lists,
        // 'admin_url' => admin_url( 'admin.php?page=affiliate-machine' )
     );

    wp_localize_script( 'cbaffmach_admin_js', 'cbaffmach_vars', $script_vars );
    wp_enqueue_script( 'cbaffmach_admin_js' );

    wp_register_script( 'cbaffmach_popoverjs', CBAFFMACH_URL . '/js/jquery.webui-popover.min.js', array('jquery'), CBAFFMACH_VERSION );
    wp_enqueue_script( 'cbaffmach_popoverjs');

    wp_register_script( 'cbaffmach_modaljs', CBAFFMACH_URL . '/js/jquery.modal.js', array('jquery'), CBAFFMACH_VERSION );
    wp_enqueue_script( 'cbaffmach_modaljs');

    wp_register_script( 'cbaffmach_clipboard', CBAFFMACH_URL . '/js/clipboard.min.js', array('jquery'), CBAFFMACH_VERSION );
    wp_enqueue_script( 'cbaffmach_clipboard');
}

function cbaffmach_admin_styles() {
    wp_register_style('cbaffmach_font_awesome_css', 'https://use.fontawesome.com/releases/v5.6.3/css/all.css', array(), CBAFFMACH_VERSION );
    wp_enqueue_style('cbaffmach_font_awesome_css');

    wp_register_style('cbaffmach_popover', CBAFFMACH_URL . '/css/jquery.webui-popover.min.css' );
    wp_enqueue_style( 'cbaffmach_popover' );

    wp_register_style( 'cbaffmach_custom_css', CBAFFMACH_URL . '/css/cbaffmach-admin.css', array(), CBAFFMACH_VERSION );
    wp_enqueue_style( 'cbaffmach_custom_css' );
}

function cbaffmach_admin_scriptsc() {
    wp_register_script( 'cbaffmach_admin_jsc', CBAFFMACH_URL . '/js/cbaffmach-admin2.js', array('jquery'), CBAFFMACH_VERSION);
    wp_enqueue_script( 'cbaffmach_admin_jsc');
}

function cbaffmach_screen_autoresponders() {
    if (! current_user_can ( 'manage_options' ))
        wp_die ( 'You don\'t have access to this page.' );
    if (! user_can_access_admin_page ())
        wp_die ( __ ( 'You do not have sufficient permissions to access this page', 'wp-auto-content' ) );
        ?>
    <div class="wrap">
        <?php cbaffmach_header();?>
        <h1>Autoresponders</h1>
        <?php cbaffmach_admin_autoresponders(); ?>
        <br/>
    </div>
        <?php
}

function cbaffmach_screen_leads() {
    if (! current_user_can ( 'manage_options' ))
        wp_die ( 'You don\'t have access to this page.' );
    if (! user_can_access_admin_page ())
        wp_die ( __ ( 'You do not have sufficient permissions to access this page', 'affiliate-machine' ) );
        ?>
    <div class="wrap">
        <?php cbaffmach_header();?>
        <h1>Leads</h1>
        <?php cbaffmach_leads_screen(); ?>
        <br/>
    </div>
        <?php
}

function cbaffmach_header() {
    echo '<img src="'.CBAFFMACH_URL.'/img/logo.png" />';
}

// Notice in backend
add_action( 'admin_init', 'cbaffmach_check_initial_notice' );

function cbaffmach_check_initial_notice() {
    $ndis = get_option( 'cbaffmach-ndis' );
    if( empty( $ndis ) ) {
        add_action( 'admin_notices', 'cbaffmach_initial_admin_notice', -1 );
    }
}

function cbaffmach_initial_admin_notice() {
    ?>
    <div class="notice updated cbaffmach-anotice is-dismissible" >
        <p><?php _e( 'Would you like to increase your Clickbank Earnings in '.date( 'Y' ).'? The number #1 Clickbank Affiliate in the world shares his secrets to generate <b>$1000 per day or more</b> in his Free Webinar <a href="https://wpsocialcontact.com/products/commissionhero?tid=cbautonot" target="_blank"><b>Join here for FREE!</b> (limited seats)</a>', 'wp-auto-content' ); ?></p>
    </div>
    <?php
}
?>