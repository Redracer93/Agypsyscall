<?php
/* Inline Links */

function cbaffmach_inline_links() {
    if (! current_user_can ( 'manage_options' ))
        wp_die ( 'You don\'t have access to this page.' );
    if (! user_can_access_admin_page ())
    wp_die ( __ ( 'You do not have sufficient permissions to access this page', 'wp-tag-machine' ) );
    ?>
    <div class="wrap">
    <?php cbaffmach_header();?>
    <h1>Inline Links</h1>
    <p>Here you can automatically link certain keywords to any url of your choice.</p>
    <?php
        $settings = cbaffmach_get_plugin_settings();
        if( isset( $_POST['cbaffmach_save_inline'] ) ) {
            $inline_links = isset( $_POST['cbaffmach_monetize_links'] ) ? $_POST['cbaffmach_monetize_links'] : array();
            $settings['inline'] = array(
                    'links' => $inline_links
                );
            cbaffmach_save_plugin_settings( $settings );
            echo '<div class="notice notice-success is-dismissible inline"><p><i class="fa fa-check"></i> Settings saved!</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
        }
        $settings = isset( $settings['inline']['links'] ) ? $settings['inline']['links'] : false;
        echo '<form method="POST">';
        cbaffmach_inline_links_html( $settings );
        cbaffmach_field_hidden( 'cbaffmach_save_inline', 1 );
        echo '<br/><input type="submit" class="button button-primary" value="Save All Changes" /></form>';
        echo '</div>';
}

function cbaffmach_inline_links_html( $links ) {
    $str = '<div class="cbaffmach-inline-links">
        <table id="cbaffmach-inline-links-table" class="cbaffmach-inline-links-table widefat form-table"><thead><tr><th scope="col" style="width:34%">Keyword '.cbaffmach_pop_admin('The keyword to be linked inside the article').'</th><th scope="col" style="width:60%">URL '.cbaffmach_pop_admin('The URL to link that keyword').'</th><th scope="col" style="width:6%">&nbsp;</th></tr></thead><tbody>';
    // var_dump($links);
    if( $links ) {
        $i = 0;
        foreach( $links as $link ) {
            $str .= cbaffmach_inline_links_row( $link, $i++ );
        }
    }
    $str .= '</tbody></table><br/>';
    $str .= '<button type="button" class="button button-secondary cbaffmach-add-inline-link"><i class="fa fa-plus"></i> Add Link</button></div>';
    echo $str;
}

function cbaffmach_inline_links_row( $link, $i ) {
    $field1_name = 'cbaffmach_monetize_links['.$i.'][keyword]';
    $field2_name = 'cbaffmach_monetize_links['.$i.'][url]';
    $keyword = $link['keyword'];
    $url = $link['url'];
    return '<tr class="cbaffmach_links_row"><td><input type="text" style="width:100%" name="'.$field1_name.'" value="'.$keyword.'" placeholder="keyword" /></td><td><input type="text" style="width:100%" name="'.$field2_name.'" value="'.$url.'" placeholder="URL to link" /></td>
        <td><button type="button" class="button button-secondary cbaffmach_remove_link_row"><i class="fas fa-times"></i></button></td></tr>';
}
?>