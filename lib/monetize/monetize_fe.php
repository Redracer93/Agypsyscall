<?php
add_filter( 'the_content', 'cbaffmach_content_filter' );
function cbaffmach_content_filter( $content ) {
	global $post;
	if ( !is_single() || !is_main_query() )
		return $content;
	$post_id = get_the_id();
	$disable_mon = get_post_meta( $post_id, '_dis_mon', true );
	if( $disable_mon )
		return $content;
	$keyword = $post_keyword = $cat_keyword = false;
	$my_post_id = $my_post_cat = 0;
	$post_cat = get_the_category( $post_id );
	if( $post_cat ) {
		if( is_array( $post_cat ) )
			$post_cat = $post_cat[0];
		$cat_keyword = get_term_meta( $post_cat->term_id, 'cbaffmach_apmon_kw', true );
	}
	$post_keyword = get_post_meta( $post_id, '_apmon_kw', true );
	$args = array('fields' => 'names');
	$post_tags = wp_get_post_tags( $post_id, $args );
	if( !empty( $post_keyword ) ) {
		$my_post_id = $post_id;
		$keyword = $post_keyword;
	}
	else if( !empty( $post_tags ) ) {
		$my_post_id = $post_id;
		$keyword = implode( ',', $post_tags );
	}
	else if( !empty( $cat_keyword ) ) {
		$my_post_cat = $post_cat->term_id;
		$keyword = $cat_keyword;
	}
	// var_dump($post_keyword);
	// var_dump($cat_keyword);
	// var_dump($keyword);
	// var_dump($my_post_cat);
	$content =  cbaffmach_monetize_links_replace( $content );
	$content =  cbaffmach_monetize_amazon( $content, $keyword, $my_post_id, $my_post_cat );
	$content =  cbaffmach_monetize_clickbank( $content, $keyword, $my_post_id, $my_post_cat  );
	$content =  cbaffmach_monetize_ebay( $content, $keyword, $my_post_id, $my_post_cat );
	$content =  cbaffmach_monetize_aliexpress( $content, $keyword, $my_post_id, $my_post_cat );
	$content =  cbaffmach_monetize_walmart( $content, $keyword, $my_post_id, $my_post_cat );
	$content =  cbaffmach_monetize_bestbuy( $content, $keyword, $my_post_id, $my_post_cat );
	$content =  cbaffmach_monetize_envato( $content, $keyword, $my_post_id, $my_post_cat );
	$content =  cbaffmach_monetize_adsense( $content );
	$content =  cbaffmach_monetize_bannerads( $content );
	return $content;
}

function cbaffmach_monetize_links_replace( $content ) {
    $settings = cbaffmach_get_plugin_settings();
    $links = isset( $settings['inline']['links'] ) ? $settings['inline']['links'] : false;
	if( empty( $links ) )
		return $content;
	// $urls = array();
	// $keywords = array();
	if( $links ) {
		foreach( $links as $link ) {
			$replacement = '<a href="'.$link['url'].'" target="_blank">${0}</a>';
			$regEx = '\'(?!((<.*?)|(<a.*?)))(\b'. $link['keyword'] . '\b)(?!(([^<>]*?)>)|([^>]*?</a>))\'si';
			$content = preg_replace( $regEx, $replacement, $content);
		}
	}
	return $content;
}

function cbaffmach_monetize_adsense( $content ) {
    $settings = cbaffmach_get_plugin_settings();
	$adsense_settings = isset( $settings['adsense'] ) ? $settings['adsense'] : array();
	$ad1_code = isset( $adsense_settings['ad1']['code'] ) ? trim( $adsense_settings['ad1']['code'] ) : '';
	$ad1_pos = isset( $adsense_settings['ad1']['pos'] ) ? intval( $adsense_settings['ad1']['pos'] ) : 0;
	$ad2_code = isset( $adsense_settings['ad2']['code'] ) ? trim( $adsense_settings['ad2']['code'] ) : '';
	$ad2_pos = isset( $adsense_settings['ad2']['pos'] ) ? intval( $adsense_settings['ad2']['pos'] ) : 0;
	$ad3_code = isset( $adsense_settings['ad3']['code'] ) ? trim( $adsense_settings['ad3']['code'] ) : '';
	$ad3_pos = isset( $adsense_settings['ad3']['pos'] ) ? intval( $adsense_settings['ad3']['pos'] ) : 0;
	$settings = array();
	if( !empty( $ad1_code ) ) {
		$settings['position'] = $ad1_pos;
		$content = cbaffmach_add_element_in_content( stripslashes( $ad1_code ), $content, $settings );
	}
	if( !empty( $ad2_code ) ) {
		$settings['position'] = $ad2_pos;
		$content = cbaffmach_add_element_in_content( stripslashes($ad2_code), $content, $settings );
	}
	if( !empty( $ad3_code ) ) {
		$settings['position'] = $ad3_pos;
		$content = cbaffmach_add_element_in_content( stripslashes($ad3_code), $content, $settings );
	}
	return $content;
}

function cbaffmach_monetize_bannerads( $content ) {
    $settings = cbaffmach_get_plugin_settings();
	$bannerads_settings = isset( $settings['bannerads'] ) ? $settings['bannerads'] : array();
	$ad1_img = isset( $bannerads_settings['ad1']['img'] ) ? trim( $bannerads_settings['ad1']['img'] ) : '';
	$ad1_newwin = isset( $bannerads_settings['ad1']['newwin'] ) ? intval( $bannerads_settings['ad1']['newwin'] ) : 0;
	$ad1_link = isset( $bannerads_settings['ad1']['link'] ) ? trim( $bannerads_settings['ad1']['link'] ) : '';
	$ad1_pos = isset( $bannerads_settings['ad1']['pos'] ) ? intval( $bannerads_settings['ad1']['pos'] ) : 0;

	$ad2_img = isset( $bannerads_settings['ad2']['img'] ) ? trim( $bannerads_settings['ad2']['img'] ) : '';
	$ad2_newwin = isset( $bannerads_settings['ad2']['newwin'] ) ? intval( $bannerads_settings['ad2']['newwin'] ) : 0;
	$ad2_link = isset( $bannerads_settings['ad2']['link'] ) ? trim( $bannerads_settings['ad2']['link'] ) : '';
	$ad2_pos = isset( $bannerads_settings['ad2']['pos'] ) ? intval( $bannerads_settings['ad2']['pos'] ) : 0;

	$ad3_img = isset( $bannerads_settings['ad3']['img'] ) ? trim( $bannerads_settings['ad3']['img'] ) : '';
	$ad3_newwin = isset( $bannerads_settings['ad3']['newwin'] ) ? intval( $bannerads_settings['ad3']['newwin'] ) : 0;
	$ad3_link = isset( $bannerads_settings['ad3']['link'] ) ? trim( $bannerads_settings['ad3']['link'] ) : '';
	$ad3_pos = isset( $bannerads_settings['ad3']['pos'] ) ? intval( $bannerads_settings['ad3']['pos'] ) : 0;
	$settings = array();
	if( !empty( $ad1_img ) ) {
		$target = $ad1_newwin ? ' target="_blank" ':'';
		$banner_el = '<a href="'.$ad1_link.'"'.$target.'>
			<img style="max-width:100%;height:auto;margin-top:10px;margin-bottom:10px;display:block" src="'.$ad1_img.'"/>
		</a>';

		$settings['position'] = $ad1_pos;
		$content = cbaffmach_add_element_in_content( $banner_el, $content, $settings );
	}

	if( !empty( $ad2_img ) ) {
		$target = $ad2_newwin ? ' target="_blank" ':'';
		$banner_el = '<a href="'.$ad2_link.'"'.$target.'>
			<img style="max-width:100%;height:auto;margin-top:10px;margin-bottom:10px;display:block" src="'.$ad2_img.'"/>
		</a>';

		$settings['position'] = $ad2_pos;
		$content = cbaffmach_add_element_in_content( $banner_el, $content, $settings );
	}

	if( !empty( $ad3_img ) ) {
		$target = $ad3_newwin ? ' target="_blank" ':'';
		$banner_el = '<a href="'.$ad3_link.'"'.$target.'>
			<img style="max-width:100%;height:auto;margin-top:10px;margin-bottom:10px;display:block" src="'.$ad3_img.'"/>
		</a>';

		$settings['position'] = $ad3_pos;
		$content = cbaffmach_add_element_in_content( $banner_el, $content, $settings );
	}
	return $content;
}

function cbaffmach_monetize_prod( $title, $url, $image_url, $source = 'amazon', $button_txt = 'Buy Now', $price = -1, $image = 0 ) {
	$code = '<div class="cbaffmach_am">
		<a class="nounder" href="'.$url.'" target="_blank">';
	if( $image ) {
		$code .= '<div class="cbaffmach_thumb">
					<img class="cbaffmach_am_i" src="'.$image_url.'"/>
				</div>';
			}
		$code .= '<div class="cbaffmach_am_t">'.$title.'</div>';
			// var_dump($price);
	if( ( $price != -1 ) && ( !empty( $price ) ) )
		$code .= '<div class="cbaffmach_am_p">'.$price.' $</div>';
			$code .= '
				<div class="cbaffmach_am_src"><img src="'.CBAFFMACH_URL.'/img/logo-'.$source.'.png" class="cbaffmach_am_il" /></div>
			</a>
			<div class="cbaffmach_buybtn">
    			<a class="cbaffmach_am_l" href="'.$url.'" target="_blank">'.$button_txt.'</a>
    		</div>
    	</div>
	';
	return $code;
}

function cbaffmach_add_element_in_content( $element, $content, $settings ) {
	$float =  1;
	$margin =  isset( $settings['margin'] ) ? intval( $settings['margin'] ) : 0 ;
	$float_str = '';
	if( $float == 2 )
		$float_str = 'float:left';
	else if( $float == 3 )
		$float_str = 'float:right';
	$wrapper_open = '<div style="'.$float_str.';margin-top: '.$margin.'px!important;margin-bottom: '.$margin.'px!important">';
	if( $float == 1 )
		$wrapper_close = '</div><div style="clear:both"></div>';
	else
		$wrapper_close = '</div>';

	if( !isset( $settings['position'] ) || ( empty( $settings['position'] ) ) || ( $settings['position'] == 1 ) ) {
		// beginning of post
		return $wrapper_open.$element.$wrapper_close.$content;
	}
	else if( $settings['position'] == 2 ) {
		// end of post
		return $content.$wrapper_open.$element.$wrapper_close.'<div style="clear:both"></div>';
	}
	else if( $settings['position'] == 3 ) {
		// middle
	    $content = explode( '</p>', $content );
	    $new_content = '';
	    $num_paragraphs = count( $content );
	    $paragraphAfter = round( $num_paragraphs / 2 );
	    for ( $i = 0; $i < $num_paragraphs; $i++ ) {
	        if ( $i == $paragraphAfter ) {
	        	$new_content .= $wrapper_open.$element.$wrapper_close;
	        }

	        $new_content .= $content[$i] . '</p>';
	    }
	    return $new_content;
	}
	else if( $settings->position == 4 ) {
		// after x paragraphs
		$paragraphAfter = isset( $settings->paragraph ) ? intval( $settings->paragraph ) : 0; //Enter number of paragraphs to display ad after.
		if( !$paragraphAfter )
			return $content;
	    $content = explode( '</p>', $content );
	    $new_content = '';
	    $num_paragraphs = count( $content );
	    for ( $i = 0; $i < $num_paragraphs; $i++ ) {
	        if ( $i == $paragraphAfter ) {
	        	$new_content .= $wrapper_open.$element.$wrapper_close;
	        }

	        $new_content .= $content[$i] . '</p>';
	    }
	    return $new_content;
	}
}
/*function cbaffmach_escape_input_txt( $text ) {
	return htmlentities( $text, ENT_QUOTES, 'UTF-8' );
}*/


?>