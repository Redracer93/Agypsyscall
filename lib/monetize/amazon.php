<?php
include_once CBAFFMACH_DIR.'/lib/libs/amazon/AMAmazonProductRequest.php';

function cbaffmach_monetize_amazon( $content, $keyword = false, $post_id = false, $cat_id = false ) {
    $settings = cbaffmach_get_plugin_settings();
    $settings = isset( $settings['amazon'] ) ? $settings['amazon'] : false;
    if( empty( $settings ) )
    	return $content;

	$enabled = isset( $settings['enabled'] ) ? intval( $settings['enabled'] ) : 0;
	if( !$enabled )
		return $content;
	if( $keyword && !empty( $keyword ) )
		$keywords = $keyword;
	else
		$keywords = isset( $settings['keywords'] ) ? trim( $settings['keywords'] ) : '';

	$category = isset( $settings['category'] ) ? trim( $settings['category'] ) : 'All';
	$display_price = isset( $settings['display_price'] ) ? intval( $settings['display_price'] ) : -1;
	$display_image = isset( $settings['display_image'] ) ? intval( $settings['display_image'] ) : 0;
	$num_ads = isset( $settings['num_ads'] ) ? intval( $settings['num_ads'] ) : 3;
	$per_row = isset( $settings['per_row'] ) ? intval( $settings['per_row'] ) : 3;
	$txt = isset( $settings['txt'] ) ? trim( $settings['txt'] ) : 'Amazon Products';
	$position = isset( $settings['position'] ) ? intval( $settings['position'] ) : 0;
	$buy_button = isset( $settings['buy_button'] ) ? trim( $settings['buy_button'] ) : 'Buy Now';

	$code = empty( $txt ) ? '' : '<h3>'.$txt.'</h3>';
	$code .= '<div class="cbaffmach_am_ads per_row_'.$per_row.'">';
	// var_dump($products);
	$products = cbaffmach_get_amazon_products( $keywords, $category, $num_ads, $post_id, $cat_id );
	if( $products && is_array( $products ) )
		shuffle( $products );
	if( $num_ads && $products ) {
	    for( $i=0; $i < $num_ads; $i++) {
	    	$product = $products[$i];
	    	if( !$display_price || $display_price == -1 )
	    	    $price = -1;
	    	else
	    	    $price = isset( $product['price'] ) ? $product['price'] : -1;
			$code .= cbaffmach_monetize_prod( $product['name'], $product['url'], $product['image'], 'amazon', $buy_button, $price, $display_image );
	    }
	}
    $code .= '</div>';

	return cbaffmach_add_element_in_content( $code, $content, $settings );
}

function cbaffmach_get_amazon_products( $keyword, $category, $num_ads, $post_id = false, $cat_id = false  ) {
         // $amazon_products = cbaffmach_do_get_amazon_products( $keyword, $category, $num_ads );

    $transient_name = 'cbaffmach_amazon';
    if( $post_id )
    	$transient_name .= '_pid'.$post_id;
    else if( $cat_id )
    	$transient_name .= '_cid'.$cat_id;
    // delete_transient( $transient_name );
    if ( false === ( $amazon_products = get_transient( $transient_name ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
         $amazon_products = cbaffmach_do_get_amazon_products( $keyword, $category, $num_ads );
         set_transient( $transient_name, $amazon_products, 6 * HOUR_IN_SECONDS );
    }
    // var_dump($amazon_products);
    return $amazon_products;
}

function cbaffmach_do_get_amazon_products( $keyword, $category = 'All', $num_ads ) {
		try
		{
			// $plugin_options = cbaffmach_get_plugin_options();
			$settings = cbaffmach_get_settings( );
			$amazon_settings = $settings['amazon'];
			$country = isset( $amazon_settings['country'] ) && !empty( $amazon_settings['country'] ) ? $amazon_settings['country'] : 'com';

		    $request = new AMAmazonProductRequest($amazon_settings['key'], $amazon_settings['tag'], 
		                                        $amazon_settings['secret'], $country);

// var_dump($amazon_settings);
// var_dump($category);
		    /* Set our response to array format. */
		    $request->setConfigResponseFormat('array');
		    $request->setConfigDelay(true);
		   // $keyword = str_replace( ',', ' ', $keyword );
		    $keyword = explode( ',', $keyword );
		    $keyword = trim( $keyword[0] );
// var_dump($keyword);
		    $response = $request->resetParams()
		        ->setResponseGroup('Medium')
		        ->setItemPage(1)
		        ->setSearchIndex($category)
		        ->itemSearch( $keyword );
		        // var_dump($response);
		    $products = cbaffmach_extract_products( $response );
		    // var_dump($products);
		    return $products;
	    }
	    catch (Exception $e)
	    {
	        // print $e->getMessage();
	        return false;
	        die();
	    }
}

function cbaffmach_extract_products( $response ) {
	if( !$response ) return '';
	$array_ret = array();
	if( isset( $response['Items']['Item'] ) ) {
		foreach( $response['Items']['Item'] as $item ) {
			// var_dump($item['ItemAttributes']);
			$price = isset( $item['ItemAttributes']['ListPrice']['FormattedPrice'] ) ? $item['ItemAttributes']['ListPrice']['FormattedPrice'] : '';
			$discount_price = isset( $item['OfferSummary']['LowestNewPrice']['FormattedPrice'] ) ? $item['OfferSummary']['LowestNewPrice']['FormattedPrice'] : '';
			// var_dump($item['ItemLinks']);
			$array_ret[] = array(
				'name' => cbaffmach_escape_input_txt( $item['ItemAttributes']['Title'] ),
				'price' =>  $price,
				'discount_price' =>  $discount_price,
				'image' => isset( $item['MediumImage']['URL'] ) ? $item['MediumImage']['URL'] : ( isset( $item['LargeImage']['URL'] ) ? $item['LargeImage']['URL'] : false ),
				'url' => ( isset( $item['DetailPageURL'] ) && !empty( $item['DetailPageURL'] ) ) ? /*rawurlencode(*/ $item['DetailPageURL'] /*)*/ : ''
				);
		}
	}
	return $array_ret;
}
?>