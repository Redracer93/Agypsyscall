<?php
define( 'CBAFFMACH_RECURRING_SERVER', 'https://cbautomator.com/recurrings/api.php' );
/* Recurring */

function cbaffmach_settings_recurring() {
    if (! current_user_can ( 'manage_options' ))
        wp_die ( 'You don\'t have access to this page.' );
    if (! user_can_access_admin_page ())
        wp_die ( __ ( 'You do not have sufficient permissions to access this page', 'wp-tag-machine' ) );
    // delete_transient( 'cbaffmach_recurring' );
    // update_option( 'cbaffm_rec_email', 'invalid@email.com' );
    if( isset( $_POST['do_check_recurring'] ) ) {
        delete_transient( 'cbaffmach_recurring' );
        if( isset( $_POST['cbaffm_recurring_email'] ) )
            cbaffmach_set_recurring_email( $_POST['cbaffm_recurring_email'] );
    }
    ?>
    <div class="wrap">
    <?php cbaffmach_header();?>
    <h1>Monthly Content</h1>
    <br/>
    <?php if( cbaffmach_is_recurring() ) {
        cbaffmach_recurring_log();
    } else { ?>
        <center><a href="http://cbautomator.convertri.com/monthlycontent/" target="_blank"><img src="<?php echo CBAFFMACH_URL;?>/img/monthly12.png" alt="" /></a>
        <br/>
        <br/>
        <form action="" method="POST"><input type="hidden" name="do_check_recurring" value="1">
            <input type="text" name="cbaffm_recurring_email" value="<?php echo cbaffmach_settings_recurring_email();?>">
            <button type="submit" class="button button-primary">Re-check my license</button></form>
            <p>Click this button if you have purchased the monthly package but you are still seeing this banner.</p>
        <br/>
        </center>
        <br/>
    <?php
    }
}

function cbaffmach_validate_recurring() {
    $email = cbaffmach_settings_recurring_email();
    if( empty( $email ) )
        return false;
    $url = CBAFFMACH_RECURRING_SERVER.'?email='.$email.'&action=active';
    $res = wp_remote_get( $url );
    if ( is_wp_error( $res ) )
        return false;
    $data = wp_remote_retrieve_body( $res );

    if ( is_wp_error( $data ) )
        return false;
    $data = json_decode( $data );
    // var_dump($data);
    if( empty( $data ) )
        return false;
    if( $data->result == 0 )
        return false;
    if( isset( $data->text ) ) {
        if( isset( $data->text[0] ) && ( $data->text[0] ) )
            return true;
    }
    return false;
}

function cbaffmach_is_recurring() {

    // Get any existing copy of our transient data
    if ( false === ( $recurring = get_transient( 'cbaffmach_recurring' ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
        $recurring = cbaffmach_validate_recurring();
        set_transient( 'cbaffmach_recurring', $recurring, 2 * HOUR_IN_SECONDS );
    }
    return $recurring;
}

function cbaffmach_recurring_log() {
?>
<form method="POST" action=""><button type="submit" class="button button-secondary" style="float:right;margin-top:-20px">Check for New Monthly Articles</button><input type="hidden" name="cbaffmach_import_recurring" value="1"></form>
<br/>
<?php cbaffmach_print_log( true ); ?>
<div id="amshare-modal" class="ammodal" style="display:none">
    <h3>Share link on: </h3>
    <div style="padding-left:18px;">
    <?php cbaffmach_share_list(); ?>
    </div>
    <br/>
    <button type="button" class="button button-primary" id="btn-share-aff-link"><i class="fas fa-share"></i> Share</button>

</div>
<div id="amlink-modal" class="ammodal" style="display:none">
    <h3>Enter your custom affiliate link: </h3>
    <input type="text" id="custom-aff-link" style="width:100%;display:block;margin-bottom:10px">
    <button type="button" class="button button-primary" id="btn-save-aff-link">Save</button>
</div>
<div id="amvideo-modal" class="ammodal" style="display:none">
    <h3>Create a new video for your review: </h3>
    <p>Video creation can take between 5 and 30mins based on video length, so after you click on "Create Video" please come back to this page in a few minutes to download your video.</p>
    <button type="button" class="button button-primary" id="btn-do-create-video"><i class="fas fa-check"></i> Create Video</button>
</div>
<?php
}

function cbaffmach_settings_recurring_email() {
    $email = get_option( 'cbaffm_rec_email' );
    if( empty( $email ) )
        $email = get_option( 'ask_php_style28' );
    return $email;
}

function cbaffmach_set_recurring_email( $email ) {
    $email = trim( $email );
    if( !empty( $email ) )
        update_option( 'cbaffm_rec_email', $email );
}
?>