<?php
// include_once CBAFFMACH_DIR.'/lib/libs/clickbank/AmazonProductRequest.php';

function cbaffmach_monetize_clickbank( $content, $keyword = false, $post_id = false, $cat_id = false  ) {
    $settings = cbaffmach_get_plugin_settings();
    $settings = isset( $settings['clickbank'] ) ? $settings['clickbank'] : false;
    if( empty( $settings ) )
    	return $content;

    $enabled = isset( $settings['enabled'] ) ? intval( $settings['enabled'] ) : 0;
    if( !$enabled )
        return $content;

    // if( $keyword && !empty( $keyword ) )
    //     $keywords = $keyword;
    // else
    $keywords = isset( $settings['keywords'] ) ? trim( $settings['keywords'] ) : '';
    if( empty( $keywords ) )
        $keywords = $keyword;
    // var_dump($settings);
	// $keyword = isset( $settings['keywords'] ) ? trim( $settings['keywords'] ) : '';
	$category = isset( $settings['category'] ) ? trim( $settings['category'] ) : 'All';
	$display_price = isset( $settings['display_price'] ) ? intval( $settings['display_price'] ) : 0;
	$popular = isset( $settings['popular'] ) ? intval( $settings['popular'] ) : 0;
	$display_image = isset( $settings['display_image'] ) ? intval( $settings['display_image'] ) : 0;
	$num_ads = isset( $settings['num_ads'] ) ? intval( $settings['num_ads'] ) : 3;
	$per_row = isset( $settings['per_row'] ) ? intval( $settings['per_row'] ) : 3;
	$txt = isset( $settings['txt'] ) ? trim( $settings['txt'] ) : 'Amazon Products';
	$position = isset( $settings['position'] ) ? intval( $settings['position'] ) : 0;
	$buy_button = isset( $settings['buy_button'] ) ? trim( $settings['buy_button'] ) : 'Buy Now';

// var_dump($keywords);
// var_dump($category);
    $products =  cbaffmach_get_clickbank_products( $keywords, $category, $popular, $num_ads, $post_id, $cat_id );
    // var_dump($products);
    if( $products && is_array( $products ) )
        shuffle( $products );
	// var_dump($products);
    $code = '<div>';
	if( $num_ads && $products ) {
        $code = empty( $txt ) ? '' : '<h3>'.$txt.'</h3>';
        $code .= '<div class="cbaffmach_am_ads per_row_'.$per_row.'">';
	    for( $i=0; $i < $num_ads; $i++ ) {
            if( !isset( $products[$i] ) )
                continue;
	    	$product = $products[$i];
            // var_dump($product);
	    	// var_dump($products[$i]);
	    	$url = urldecode( $product['url'] );
	    	$image_url = $product['image'];
	    	$product_name = $product['title'];
	    	$product_desc = $product['description'];
	    	$sales_url = $product['sales_link'];
            if( !$display_price || $display_price == -1 )
                $price = -1;
            else
                $price = isset( $product['price'] ) ? $product['price'] : -1;
            // var_dump($product);
            // var_dump($price);
			$code .= cbaffmach_viralad( $product_name, $product_desc, $url, $image_url, $price, $display_image );


	    }
	}
    $code .= '</div>';

	return cbaffmach_add_element_in_content( $code, $content, $settings );
}

function cbaffmach_get_clickbank_products( $keyword, $category, $popular = false, $num_ads, $post_id = false, $cat_id = false  ) {
        $transient_name = 'cbaffmach_cb';
        if( $post_id )
            $transient_name .= '_pid'.$post_id;
        else if( $cat_id )
            $transient_name .= '_cid'.$cat_id;
        // var_dump($transient_name);
        // delete_transient($transient_name);
        if ( /*1 || (*/ false === ( $cb_products = get_transient( $transient_name ) ) ) /*)*/ {
            // It wasn't there, so regenerate the data and save the transient
             $cb_products = cbaffmach_do_get_cb_products( $keyword, $category, $popular, $num_ads );
             set_transient( $transient_name, $cb_products, 6 * HOUR_IN_SECONDS );
        }
        // var_dump($cb_products);
        return $cb_products;
}

function cbaffmach_do_get_cb_products( $keyword, $category = 0, $popular = false, $num_ads = 10 ) {
    $keyword = explode( ',', $keyword );
    $keyword = trim( $keyword[0] );
    // var_dump($keyword);
    $clickbank_settings = cbaffmach_get_settings( array( 'clickbank' ) );
    $user_id = isset( $clickbank_settings['id'] ) && !empty( $clickbank_settings['id'] ) ? $clickbank_settings['id'] : '4147758';
    if( $keyword == 'popularcb' ) {
        $url = 'http://clickbankproads.com/xmlfeed/wp/main/cb_gravity.asp'
            . '?id='.$user_id
            . '&no_of_products=12';
    }
    else {
	$url = 'http://clickbankproads.com/xmlfeed/wp/main/cb_search.asp'
            . '?id='.$user_id
            . '&keywords='.rawurlencode($keyword)
            . '&start=0'
            . '&end=12';
    if( $category )
        $url .= '&cs_category='.$category;
    }
    // var_dump($clickbank_settings);
    // echo $url;
    $empty_answer = array();
    $rss = fetch_feed($url);
    if (is_wp_error($rss))
        return $empty_answer;
// echo "aa";
    if ( 0 == $rss->get_item_quantity(400) ) {
        // No products
        if( $popular ) {
            return cbaffmach_do_get_cb_products( 'popularcb', true );
        }
        return $empty_answer;
    }
// echo "bb";

    $tmp = $rss->get_item()->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, 'totalp');
    $totalp = cbaffmach_cdata($tmp[0]['data']);
    // $cat_link = cbaffmach_get_products_page('cs_category', '');
    $count = 0;
    $item_list = array();
    $items = $rss->get_items(0, 400);
    if( !$items ) {
        if( $popular ) {
            return cbaffmach_do_get_cb_products( 'popularcb', true );
        }
    }
    foreach ($items as $item) {
        // var_dump($item);
        // Title
        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "title");
        $title = htmlspecialchars(cbaffmach_cdata($paths[0]['data']));
        // URL
        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "affiliate");
        $mem = cbaffmach_cdata($paths[0]['data']);
        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "ids");
        $tar = cbaffmach_cdata($paths[0]['data']);
        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "niche");
        $niche = cbaffmach_cdata($paths[0]['data']);
        $link = htmlspecialchars('http://clickbankproads.com/xmlfeed/wp/main/tracksf.asp'
            . '?memnumber='.$user_id
            . '&mem='.$mem
            . '&tar='.$tar
            . '&niche='.$niche);

        // Link
        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "link");
        $sales_link = htmlspecialchars(cbaffmach_cdata($paths[0]['data']));
        // Descriptions
        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "description");
        $description = htmlspecialchars(cbaffmach_cdata($paths[0]['data']));
        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "mdescr");
        $mdescr = htmlspecialchars(cbaffmach_cdata($paths[0]['data']));

        // Images
        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "images");
        $imageFilename = cbaffmach_cdata($paths[0]['data']);
        if ($imageFilename != '' && $imageFilename != 'no') {
            $image = 'http://cbproads.com/clickbankstorefront/v4/send_binary.asp'
                . '?Path=D:/hshome/cbproads/cbproads.com/cbbanners/'
                . $imageFilename.'&resize=240&show_border=No';
            $image = htmlspecialchars($image);
            $imageFull = 'http://cbproads.com/clickbankstorefront/v4/send_binary.asp'
                . '?Path=D:/hshome/cbproads/cbproads.com/cbbanners/'.$imageFilename.'&resize=default&show_border=No';
            $imageFull = htmlspecialchars($imageFull);
        } else {
            unset($image, $imageFull);
        }
        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "altimage");
        $altimageFilename = cbaffmach_cdata($paths[0]['data']);
        if ($altimageFilename != '' && $altimageFilename != 'no') {
            $altimage = 'http://cbproads.com/clickbankstorefront/v4/send_binary.asp'
                . '?Path=D:/hshome/cbproads/cbproads.com/cbbanners/alter/'
                . $altimageFilename.'&resize=default&show_border=No';
            $altimage = htmlspecialchars($altimage);
            $altimageFull = 'http://cbproads.com/clickbankstorefront/v4/send_binary.asp'
                . '?Path=D:/hshome/cbproads/cbproads.com/cbbanners/alter/'
                . $altimageFilename.'&resize=240&show_border=No';
            $altimageFull = htmlspecialchars($altimageFull);
        } else {
            unset($altimage, $altimageFull);
        }

        // Price
        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "price");
        $price = htmlspecialchars(cbaffmach_cdata($paths[0]['data']));

        // Rank & Gravity
        /*$paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "rank");
        $rank = cbaffmach_cdata($paths[0]['data']);
        $paths = $item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, "gravity");
        $gravity = cbaffmach_cdata($paths[0]['data']);*/
        $list_item = array();
        $list_item['title'] = html_entity_decode( $title );
        $list_item['description'] = $description;
        $list_item['url'] = $link;
        $list_item['image'] = $image;
        $list_item['price'] = $price;
        $list_item['sales_link'] = $sales_link;
        $item_list[] = $list_item;
        $count++;
    }
    return $item_list;
}

function cbaffmach_viralad( $title, $description, $url, $image_url, $price = -1, $image = 0 ) {
	$code = '<div class="cbaffmach_am">
			<a class="nounder" href="'.$url.'" target="_blank">';
			if ( $image ) {
				$code .= '<div class="cbaffmach_thumb">
					<img class="cbaffmach_am_i" src="'.$image_url.'"/>
				</div>';
			}
			$code .= '<div class="cbaffmach_am_desc">'.$description.'</div>
			<div class="cbaffmach_am_t">'.$title.'</div>';
			// var_dump($price);
	if( ( $price != -1 ) && ( !empty( $price ) ) )
		$code .= '<div class="cbaffmach_am_p">'.$price.' $</div>';
			$code .= '
			</a>
    	</div>
	';
	return $code;
}

function cbaffmach_textad( $title, $description, $url, $display_url = false ) {
	if( empty( $display_url ) )
		$display_url = $url;
	$code = '<div class="cbaffmach_am">
				<div class="cbaffmach_am_t"><a class="nounder" href="'.$url.'" target="_blank">'.$title.'</a></div>
				<div class="cbaffmach_am_desc">'.$description.'</div>
				<div class="cbaffmach_am_url"><a class="nounder" href="'.$url.'" target="_blank">'.$display_url.'</a></div>
    	</div>
	';
	return $code;
}
?>