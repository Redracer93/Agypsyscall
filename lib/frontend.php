<?php
/* Frontend */

add_action( 'the_content', 'cbaffmach_scrolling_content' );

function cbaffmach_scrolling_content( $content ) {
    global $post;
    if( !is_singular( 'post' ) )
        return $content;
    $post_id = $post->ID;
    $prod_id = get_post_meta( $post_id, '_cbaffmach_prodid', true );
    if( empty( $prod_id ) )
        return $content;
    $title = get_the_title( $post_id );

    $prod_name = get_post_meta( $post_id, '_cbaffmach_prodname', true );
    if( empty( $prod_name ) )
        $prod_name = $title;

    $afflink = get_post_meta( $post_id, '_cbaffmach_afflink', true );
    if( empty( $afflink ) )
        $afflink = 'https://jvz7.com/c/98745/302709';

    $settings = cbaffmach_get_plugin_settings();
    $scrolling_settings = isset( $settings['scrolling'] ) ? $settings['scrolling'] : array();
    $enabled = isset( $scrolling_settings['enabled'] ) ? intval( $scrolling_settings['enabled'] ) : 0;
    $related = isset( $scrolling_settings['related'] ) ? intval( $scrolling_settings['related'] ) : 0;

    $show_disclaimer = isset( $scrolling_settings['show_disclaimer'] ) ? intval( $scrolling_settings['show_disclaimer'] ) : 0;


    if( $show_disclaimer ) {
        $disclaimer_txt = isset( $scrolling_settings['disclaimer_txt'] ) ? trim( $scrolling_settings['disclaimer_txt'] ) : 'Blog posts on this website may contain affiliate links.';
        $disclaimer_affid = isset( $scrolling_settings['disclaimer_affid'] ) ? intval( $scrolling_settings['disclaimer_affid'] ) : 0;
        if( $disclaimer_affid ) {
            $clickbankid = isset( $settings['import']['clickbankid'] ) ? trim( $settings['import']['clickbankid'] ) : '';
            $disclaimer_txt .= ' Affiliate ID = '.$clickbankid;
        }
        $content = $content.'<p><small><em>'.$disclaimer_txt.'</em></small></p>';
    }
    $optin_settings = isset( $settings['optin'] ) ? $settings['optin'] : array();
    $optin_enabled = isset( $optin_settings['enabled'] ) ? intval( $optin_settings['enabled'] ) : 0;
    if( $optin_enabled )
        $content = cbaffmach_monetize_optin( $content, $optin_settings );
    if( $enabled ) {

        $scrolling_txt = ( isset( $scrolling_settings['scrolling_txt'] ) && !empty( $scrolling_settings['scrolling_txt'] ) ) ? trim( $scrolling_settings['scrolling_txt'] ) : 'Would you like to know more?';
        $button_txt = ( isset( $scrolling_settings['button_txt'] ) && !empty( $scrolling_settings['button_txt'] ) ) ? trim( $scrolling_settings['button_txt'] ) : 'Click here';
        $video = isset( $scrolling_settings['video'] ) ? intval( $scrolling_settings['video'] ) : 0;

        $afflink = cbaffmach_replace_link( $afflink );
        $youtube = false;
        $youtube_txt = '';
        if( $video ) {
            $id = cbaffmach_get_youtube_id_from_url( $content );
            $vimeo_id = cbaffmach_getVimeoVideoIdFromUrl( $content );
        }
        else {
            $id = 0;
            $vimeo_id = 0;
        }
        // var_dump($vimeo_id);
        if( $video && $id ) {
            $youtube = true;
            $youtube_txt = '<p><iframe src="https://www.youtube.com/embed/'.$id.'?feature=oembed&controls=0&modestbranding=1&rel=0&showinfo=0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen width="315" height="177" frameborder="0"></iframe></p>';
        }
        else if( $video && $vimeo_id ) {
            $youtube = true;
            $youtube_txt = '<p><iframe src="https://player.vimeo.com/video/'.$vimeo_id.'?byline=0&amp;portrait=0&title=0" width="315" height="177" frameborder="0" allowfullscreen="allowfullscreen"></iframe></p>';
        }
        else {
            $image =  wp_get_attachment_url(get_post_thumbnail_id($post_id));
            if( $image )
                $youtube_txt = '<a href="'.$afflink.'" target="_blank"><img src="'.$image.'" /></a>';
        }

        // var_dump($id);
        /*
         if(preg_match_all('~(http://www\.youtube\.com/watch\?v=[%&=#\w-]*)~',$content,$m)){
     // matches found in $m
            $youtube = true;
            var_dump($m);
        }*/
        $box = '<div id="wpaffm-get-started-box" class="wpaffm wpaffm-get-started-box wpaffm-bottom-right" style="overflow-y: auto; max-height: none;"><div class="wpaffm-content">
            <h4>'.$prod_name.'</h4>'.
            $youtube_txt.
            '<p>'.$scrolling_txt.'</p>
            <p><a class="button" target="_blank" href="'.$afflink.'" target="_blank">'.$button_txt.'</a></p>
        </div><span class="wpaffm-close-icon">×</span></div>';

        $js = '<script>
        var ashowing = false;
        jQuery(document).ready(function(){

        jQuery(window).resize(function(e){
            console.log(e);
        });

        jQuery(window).scroll(function (event) {
            var sc = jQuery(window).scrollTop();
            if( sc > 400 ) {
                if( !ashowing ) {
                    jQuery("#wpaffm-get-started-box").slideDown();
                    ashowing = true;
                }
            }
            else {
                if( ashowing ) {
                    jQuery("#wpaffm-get-started-box").slideUp();
                    ashowing = false;
                }
            }
            //console.log(sc);
        });

        jQuery(".wpaffm-close-icon").click(function(e){
            e.preventDefault();
            jQuery("#wpaffm-get-started-box").slideUp();
        });

    })</script>';
    }
    if( $related ) {
        $orig_post = $post;
        $args=array(
            'post__not_in' => array($post->ID),
            'posts_per_page'=>4, // Number of related posts that will be displayed.
            'post_status'=>'publish',
           'meta_query' => array(
                          array(
                             'key' => '_cbaffmach_prodid',
                             'compare' => 'EXISTS'
                          )),
            'orderby'=>'rand' // Randomize the posts
        );
        $related_txt = '';
        $my_query = new wp_query( $args );
        if( $my_query->have_posts() ) {
        $related_txt = '<div id="affm_related_posts" class="clear"><h3>Related Products</h3><ul>';
        while( $my_query->have_posts() ) {
        $my_query->the_post();

        $related_txt .= '<li>
         <a href="'.get_permalink().'" rel="bookmark" title="'.get_the_title().'">
         '.get_the_post_thumbnail( get_the_id() ).'
         </a>
         <div class="related_content">
         <a href="'.get_permalink().'" rel="bookmark" title="'.get_the_title().'">'.get_the_title().'</a>
         </div>
        </li>';
        }
        $related_txt .= '</ul></div><div style="clear:both"></div>';
        }
        $post = $orig_post;
        wp_reset_query();
        $content = $content.$related_txt;
    }
    if( $enabled)
        $content = $content.$box.$js;


    return $content;
}

function cbaffmach_get_youtube_id_from_url($url)  {
     preg_match('/(http(s|):|)\/\/(www\.|)yout(.*?)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/i', $url, $results);
     if( isset( $results[6] ) )
        return $results[6];
    return false;
}

function cbaffmach_getVimeoVideoIdFromUrl($url = '') {
    $regs = array();
    $id = '';
    // if (preg_match('/(https://player.vimeo.com)/([0-9]+)/i', $url, $regs) ) {
    if (preg_match('#https?://(player\.)?vimeo\.com/video/(\d+)#', $url, $regs) ) {


    // if (preg_match('/(https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)/i', $url, $regs)) {
        $id = $regs[2];
    }
    // var_dump($regs);
    return $id;
}

function cbaffmach_scripts_and_styles() {
    wp_register_style( 'cbaffmach_front_css', CBAFFMACH_URL . '/css/cbaffmach-front.css', array(), CBAFFMACH_VERSION );
    wp_enqueue_style( 'cbaffmach_front_css' );

    wp_register_script( 'cbaffmach_frontjs', CBAFFMACH_URL . '/js/cbaffmach-front.js', array('jquery'), CBAFFMACH_VERSION);
    $script_vars = array(
        'ajax_url' => admin_url ( 'admin-ajax.php' )
    );
    wp_localize_script( 'cbaffmach_frontjs', 'cbaffmach_vars', $script_vars );
    wp_enqueue_script( 'cbaffmach_frontjs');

}

add_action( 'wp_enqueue_scripts', 'cbaffmach_scripts_and_styles' );
?>