<?php
function cbaffmach_screen_help() {
    if ( !current_user_can ( 'manage_options' ))
        wp_die ( 'You don\'t have access to this page.' );
    if ( !user_can_access_admin_page ())
        wp_die ( __ ( 'You do not have sufficient permissions to access this page', 'wp-freshstart' ) );
    ?>
    <div class="wrap">
        <?php cbaffmach_header();?>
        <h1>Help</h1>
        <br/>
        <?php
            if( isset( $_GET['tm2test']) ) {
                cbaffmach_selftest();
            }
            else
                cbaffmach_help();
}

function cbaffmach_selftest() {
?>
    <h3>Testing your hosting...</h3>
    <?php $curl_enabled = ( extension_loaded('curl') && function_exists('curl_version') ); ?>
    <p><h4 style="padding-left:20px;">Curl enabled <?php cbaffmach_test_check( $curl_enabled ); ?></h4></p>
    <?php if( !$curl_enabled ) { ?>
        <p style="padding-left:30px;">Curl is not enabled in your hosting. This is required to be able to connect to external websites (to import content). Please open a support ticket to your hosting company and ask them to enable curl for your site; after they enable it for you, you will be able to import content using CB Automator.</p>
    <?php } ?>
    <?php
        $ext_enabled = cbaffmach_test_ext();
    ?>
    <p><h4 style="padding-left:20px;">External Connections allowed <?php cbaffmach_test_check( $ext_enabled ); ?></h4></p>
    <?php if( !$ext_enabled ) { ?>
        <p style="padding-left:30px;">Your hosting seems to be blocking external connections, so the plugin cannot connect to any sites to get content on autopilot. Please open a support ticket to your hosting company and ask them to enable external connections for your site; after they enable it for you, you will be able to import content using CB Automator.</p>
    <?php } ?>
    <?php $php_ok =  ( version_compare( PHP_VERSION, '5.5.0' ) >= 0 ); ?>
    <p><h4 style="padding-left:20px;">PHP Version <?php echo phpversion( ); ?> <?php cbaffmach_test_check( $php_ok ); ?></h4></p>
    <?php if( !$php_ok ) { ?>
        <p style="padding-left:30px;">Your hosting is using a very old PHP version, which is an issue for stability, functionality and speed. Please open a support ticket to your hosting company and ask them to update your PHP version.</p>
    <?php } ?>
    <?php
        global $wp_version;
        $wpv_ok = ( version_compare( $wp_version, '4.4', '>=' ) ); ?>
    <p><h4 style="padding-left:20px;">WordPress Version <?php echo $wp_version; ?> <?php cbaffmach_test_check( $wpv_ok ); ?></h4></p>
    <?php if( !$wpv_ok ) { ?>
        <p style="padding-left:30px;">You are using a very old WordPress version. It is recommended to upgrade to get the latest features and security patches. To upgrade go to <a href="<?php echo admin_url('update-core.php');?>">Dashboard > Updates</a></p>
    <?php } ?>
    <?php
        if( !$curl_enabled || !$ext_enabled || !$php_ok ) {
            echo '<p>Your hosting company does not seem to have all the requirements needed to use CB Automator; however it might still work, so we recommend you create a campaign and test. If you can\'t get any content imported, please talk to your hosting company, or move to a different hosting company (we recommend <a href="https://wpautocontent.com/support/bluehost" target="_blank">Bluehost</a> or <a href="https://wpautocontent.com/support/hostgator" target="_blank">Hostgator</a>; CB Automator is guaranteed to work there)</p>';
        }
        else {
           echo '<p>Great! Your hosting company seems to be ok.</p><p>However, if you are still having issues with importing, related to your hosting, please talk to them, or move to a different hosting company. We recommend <a href="https://wpautocontent.com/support/bluehost" target="_blank">Bluehost</a> or <a href="https://wpautocontent.com/support/hostgator" target="_blank">Hostgator</a>; CB Automator is guaranteed to work there</p>';
        }
    ?>
    <a href="<?php echo admin_url('/admin.php?page=cb-automator-support');?>" class="button button-secondary"><i class="fas fa-undo"></i> Return</a>
<?php
}

function cbaffmach_test_check( $val ) {
    if( $val )
        echo '<i class="fa fa-check" style="color:green"></i>';
    else
        echo '<i class="fas fa-times" style="color:red"></i>';
}

function cbaffmach_test_ext() {
    $response = wp_remote_get( 'https://knighterrant.s3.amazonaws.com/autotag/test.json' );
    if ( is_array( $response ) ) {
      $header = $response['headers']; // array of http header lines
      $body = $response['body']; // use the content
      if( empty( $body ) )
        return false;
      if( $body == '12345' )
        return true;
    }
    return false;
}

function cbaffmach_help() {
        ?>
        <h3>Watch this welcome video from Ankur: </h3>
        <iframe width="853" height="480" src="https://www.youtube.com/embed/Epf2fY_WLW8?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        <br/>
        <p>If you are having any issues with the plugin, please do the self-test so you can check if your hosting is 100% compatible with the plugin:</p>
        <p><a href="<?php echo admin_url('/admin.php?page=cb-automator-support&tm2test=1');?>" class="button button-secondary"><i class="far fa-check-square"></i> Run Self Test</a>
        </p>
        <br/>
        <a class="" href="https://wpsocialcontact.com/products/commissionhero?tid=cbautohelp" target="_blank"><b>Click here to Learn how you can increase your Clickbank Earnings in <?php echo date( 'Y' );?> in this FREE Webinar by the #1 Clickbank Affiliate in the World</b></a>
        <br/>
        <br/>

        <br/>
        <a class="button button-primary" href="https://cbautomator.com/support/" target="_blank"><i class="fa fa-question"></i> Click here to get support</a>
        <br/>
    <?php
}
?>