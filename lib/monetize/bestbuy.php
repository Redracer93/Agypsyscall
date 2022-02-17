<?php
// include_once CBAFFMACH_DIR.'/lib/libs/bestbuy/BestbuyProductRequest.php';

function cbaffmach_monetize_bestbuy( $content, $keyword = false, $post_id = false, $cat_id = false  ) {
    $settings = cbaffmach_get_plugin_settings();
    $settings = isset( $settings['bestbuy'] ) ? $settings['bestbuy'] : false;
    if( empty( $settings ) )
    	return $content;

    $enabled = isset( $settings['enabled'] ) ? intval( $settings['enabled'] ) : 0;
    if( !$enabled )
    	return $content;

    if( $keyword && !empty( $keyword ) )
    	$keywords = $keyword;
    else
    	$keywords = isset( $settings['keywords'] ) ? trim( $settings['keywords'] ) : '';
// var_dump($keyword);
	// $keywords = isset( $settings['keywords'] ) ? trim( $settings['keywords'] ) : '';
	$category = isset( $settings['category'] ) ? trim( $settings['category'] ) : 'All';
	$display_price = isset( $settings['display_price'] ) ? intval( $settings['display_price'] ) : -1;
	$display_image = isset( $settings['display_image'] ) ? intval( $settings['display_image'] ) : 0;
	$num_ads = isset( $settings['num_ads'] ) ? intval( $settings['num_ads'] ) : 3;
	$per_row = isset( $settings['per_row'] ) ? intval( $settings['per_row'] ) : 3;
	$txt = isset( $settings['txt'] ) ? trim( $settings['txt'] ) : 'Bestbuy Products';
	$position = isset( $settings['position'] ) ? intval( $settings['position'] ) : 0;
	$buy_button = isset( $settings['buy_button'] ) ? trim( $settings['buy_button'] ) : 'Buy Now';

$code = '<div>';
	// var_dump($post_id);
	$products = cbaffmach_get_bestbuy_products( $keywords, $category, $num_ads, $post_id, $cat_id );
	if( $products && is_array( $products ) )
		shuffle( $products );
	// var_dump($products);
	if( $num_ads && $products ) {
		$code = empty( $txt ) ? '' : '<h3>'.$txt.'</h3>';
		$code .= '<div class="cbaffmach_am_ads per_row_'.$per_row.'">';
	    for( $i=0; $i < $num_ads; $i++) {
	    	$product = $products[$i];
	    	// var_dump($product);
	    	if( !$display_price || $display_price == -1 )
	    	    $price = -1;
	    	else
	    	    $price = isset( $product['price'] ) ? $product['price'] : -1;
			$code .= cbaffmach_monetize_prod( $product['title'], $product['url'], $product['image_url'], 'bestbuy', $buy_button, $price, $display_image );
	    }
	}
    $code .= '</div>';

	return cbaffmach_add_element_in_content( $code, $content, $settings );
}

function cbaffmach_get_bestbuy_products( $keyword, $category, $num_ads, $post_id = false, $cat_id = false  ) {
	$transient_name = 'cbaffmach_bestbuy';
	if( $post_id )
		$transient_name .= '_pid'.$post_id;
	if( $cat_id )
		$transient_name .= '_cid'.$cat_id;
	// var_dump($transient_name);
	// delete_transient( $transient_name );
    if ( false === ( $bestbuy_products = get_transient( $transient_name ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
         $bestbuy_products = cbaffmach_do_get_bestbuy_products( $keyword, $category, $num_ads );
         set_transient( $transient_name, $bestbuy_products, 6 * HOUR_IN_SECONDS );
    }
    // var_dump($bestbuy_products);
    return $bestbuy_products;
}

function cbaffmach_do_get_bestbuy_products( $keyword, $category = 0, $num_ads = 6 ) {
	$settings = cbaffmach_get_settings( );
	$bestbuy_settings = $settings['bestbuy'];
// var_dump($keyword);
	$api_key = isset( $bestbuy_settings['apikey'] ) && !empty( $bestbuy_settings['apikey'] ) ? $bestbuy_settings['apikey'] : '0ckoDt0yU9C8Vb8Ca4x9RyJH';

	$url = 'https://api.bestbuy.com/v1/products((search='.( $keyword ).'))?apiKey='.$api_key.'&sort=bestSellingRank.asc&show=description,image,name,shortDescription,url,salePrice,bestSellingRank&format=json';
	$response = wp_remote_get( $url );
	if ( is_array( $response ) ) {
	  $header = $response['headers']; // array of http header lines
	  $body = $response['body']; // use the content
	  $prods = json_decode( $body );
	  // var_dump($prods);
	  $products = array();
	  if( isset( $prods->total ) && $prods->total > 0 ) {
	  	foreach( $prods->products as $item ) {
	  		// var_dump($item);
	  		$prod = array();
			$prod['title'] = (string) $item->name;
			$prod['description'] = (string) $item->shortDescription;
			$prod['url']  = (string) $item->url;
			// rmi353, probablemente es productTrackingUrl
			$prod['image_url']   = (string) $item->image;
			$prod['price'] = (string) $item->salePrice;
			$products[] = $prod;
	  	}
	  }
	}
	else
		return false;

	return $products;
	// var_dump($resp);
}


/* Admin */

function cbaffmach_settings_monetize_tab_bestbuy( $settings = false ) {
    $settings = isset( $settings['bestbuy'] ) ? $settings['bestbuy'] : false;
    $bestbuy_api = isset( $settings['apikey'] ) ? trim( $settings['apikey'] ) : '';

    $bestbuy_enabled = isset( $settings['enabled'] ) ? intval( $settings['enabled'] ) : 0;
    $bestbuy_keywords = isset( $settings['keywords'] ) ? trim( $settings['keywords'] ) : '';
    $bestbuy_category = isset( $settings['category'] ) ? trim( $settings['category'] ) : 'All';
    $bestbuy_display_price = isset( $settings['display_price'] ) ? intval( $settings['display_price'] ) : 0;
    $bestbuy_display_image = isset( $settings['display_image'] ) ? intval( $settings['display_image'] ) : 1;
    $bestbuy_num_ads = isset( $settings['num_ads'] ) ? intval( $settings['num_ads'] ) : 3;
    $bestbuy_per_row = isset( $settings['per_row'] ) ? intval( $settings['per_row'] ) : 3;
    $bestbuy_txt = isset( $settings['txt'] ) ? trim( $settings['txt'] ) : 'Bestbuy Products';
    $bestbuy_position = isset( $settings['position'] ) ? intval( $settings['position'] ) : 0;
    $bestbuy_buy_button = isset( $settings['buy_button'] ) ? trim( $settings['buy_button'] ) : 'Buy Now';
?>
    <h3><i class="fa fa-shopping-cart"></i> Bestbuy</h3>
    <h4 class="h4bigger">1. Bestbuy API Settings</h4>
    <table class="form-table">
    	<?php cbaffmach_field_text( 'BestBuy API Key', 'cbaffmach_bestbuy_api', $bestbuy_api, false, '', 'Get it <a target="_blank" href="https://developer.bestbuy.com">here</a> - <a target="_blank" href="https://cbautomator.com/support/knowledgebase/how-to-create-a-best-buy-api-key/">Tutorial</a>', 'Your BestBuy API Key' ); ?>
    </table>
        <br/>
    <h4 class="h4bigger">2. Ad Settings</h4>
    <table class="form-table">
        <?php cbaffmach_field_checkbox( 'Show Bestbuy Ads', 'cbaffmach_bestbuy_enabled', $bestbuy_enabled, false, 'cbaffmach_bestbuy_enabled', 'If you enable this, it will add bestbuy Affiliate links on autopilot to your posts', '' ); ?>
            <?php cbaffmach_field_text( 'Keywords', 'cbaffmach_bestbuy_keywords', $bestbuy_keywords, false, '', '', 'Your Bestbuy Keywords', 'cbaffmach_mon_bestbuy_row', $bestbuy_enabled ); ?>
            <?php cbaffmach_field_checkbox( 'Display Price', 'cbaffmach_bestbuy_display_price', $bestbuy_display_price, false, 'cbaffmach_bestbuy_display_price', 'If you enable this, it will show the product price in the ad', '', 'cbaffmach_mon_bestbuy_row', $bestbuy_enabled ); ?>
            <?php cbaffmach_field_checkbox( 'Display Image', 'cbaffmach_bestbuy_display_image', $bestbuy_display_image, false, 'cbaffmach_bestbuy_display_image', 'If you enable this, it will show the product image in the ad', '', 'cbaffmach_mon_bestbuy_row', $bestbuy_enabled ); ?>
            <?php $numbers = cbaffmach_get_numbers( 1, 10 ); ?>
            <?php cbaffmach_field_select( 'Number of Ads', 'cbaffmach_bestbuy_num_ads', $bestbuy_num_ads, $numbers, false, '', '', '', 'cbaffmach_mon_bestbuy_row', !$bestbuy_enabled  ); ?>
            <?php cbaffmach_field_select( 'Ads per Row', 'cbaffmach_bestbuy_per_row', $bestbuy_per_row, $numbers, false, '', '', '', 'cbaffmach_mon_bestbuy_row', !$bestbuy_enabled  ); ?>
            <?php cbaffmach_field_text( 'Header Text', 'cbaffmach_bestbuy_txt', $bestbuy_txt, false, '', '', 'Intro Text before the ads', 'cbaffmach_mon_bestbuy_row', $bestbuy_enabled ); ?>
            <?php $positions = cbaffmach_get_banner_positions(); ?>
            <?php cbaffmach_field_select( 'Position', 'cbaffmach_bestbuy_position', $bestbuy_position, $positions, false, '', '', '', 'cbaffmach_mon_bestbuy_row', !$bestbuy_enabled  ); ?>
            <?php cbaffmach_field_text( 'Button Text', 'cbaffmach_bestbuy_buy_button', $bestbuy_buy_button, false, '', '', 'The text for the buy button', 'cbaffmach_mon_bestbuy_row', $bestbuy_enabled ); ?>
    </table>
<?php
}
?>