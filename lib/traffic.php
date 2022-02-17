<?php
/* Traffic */

/* Traffic */
function cbaffmach_post_traffic( $post_id, $tags = array() ) {
    $settings = cbaffmach_get_plugin_settings();
    $settings = isset( $settings['import'] ) ? $settings['import'] : false;
    $shareauto = isset( $settings['shareauto'] ) ? intval( $settings['shareauto'] ) : 0;
    $bmachine = isset( $settings['bmachine'] ) ? intval( $settings['bmachine'] ) : 0;
    $blindexer = isset( $settings['blindexer'] ) ? intval( $settings['blindexer'] ) : 0;
    $ilindexer = isset( $settings['ilindexer'] ) ? intval( $settings['ilindexer'] ) : 0;
    $createvideo = isset( $settings['createvideo'] ) ? intval( $settings['createvideo'] ) : 0;

    @set_time_limit( 70 );
    if( $bmachine ) {
        // $bmachine_num = isset( $settings['bmachine_num'] ) ? intval( $settings['bmachine_num'] ) : 10;
// var_dump($tags);
        if( empty( $tags ) )
            $tags = array( 'review', 'internet marketing', 'product review', 'online marketing', 'make money on the internet' );
        else if( !is_array( $tags ) )
            $tags = explode( ',', $tags );
        $res = cbaffmach_blmachine_submit_job( get_permalink( $post_id ), $tags, 80 );
    }
    if( $shareauto ) {
        $sharingsites = isset( $settings['sharingsites'] ) ? ( $settings['sharingsites'] ) : array();

        if( isset( $sharingsites['facebook'] ) && $sharingsites['facebook'] )
            cbaffmach_traffic_facebook( $post_id );
        if( isset( $sharingsites['twitter'] ) && $sharingsites['twitter'] )
            cbaffmach_traffic_twitter( $post_id );
        if( isset( $sharingsites['medium'] ) && $sharingsites['medium'] )
            cbaffmach_traffic_medium( $post_id );
        if( isset( $sharingsites['tumblr'] ) && $sharingsites['tumblr'] )
            cbaffmach_traffic_tumblr( $post_id );
        if( isset( $sharingsites['linkedin'] ) && $sharingsites['linkedin'] )
            cbaffmach_traffic_linkedin( $post_id );
        if( isset( $sharingsites['buffer'] ) && $sharingsites['buffer'] )
            cbaffmach_traffic_buffer( $post_id );
    }
    if( $blindexer )
        cbaffmach_bli_submit_links_indexing( get_permalink( $post_id ) );
    if( $ilindexer )
        cbaffmach_ili_submit_links_indexing( get_permalink( $post_id ) );
    if( $createvideo ) {
        $title = cbaffmach_shorten_text( get_the_title( $post_id ), 88 );
        $content = cbaffmach_shorten_text( cbaffmach_get_content_by_id( $post_id ), 1800 );
        $content = html_entity_decode( strip_tags( $content ) );
        $images = array( );
        $thumb = cbaffmach_get_thumbnail( $post_id );
        if( $thumb )
            $images = array( $thumb );

        // $prod_name = get_post_meta( $post_id, '_cbaffmach_prodname', true );
        // if( !empty( $prod_name ) )
        //     $yt_title = $prod_name;
        // else
        //     $yt_title = $title;

        $youtube_description = 'More info at '.get_permalink( $post_id );
        $doc = new DOMDocument();
        @$doc->loadHTML(cbaffmach_get_content_by_id( $post_id ));
        $tags = $doc->getElementsByTagName('img');
        foreach ($tags as $tag) {
               $src = $tag->getAttribute('src');
               $images[] = $src;
        }
        cbaffmach_wpavmaker_submit_job( $title, $content, $images, 'en-us', 1, $title, $youtube_description );
    }
}

?>