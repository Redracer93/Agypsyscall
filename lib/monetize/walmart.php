<?php
// include_once CBAFFMACH_DIR.'/lib/libs/walmart/AmazonProductRequest.php';

function cbaffmach_monetize_walmart( $content, $keyword = false, $post_id = false, $cat_id = false  ) {
    $settings = cbaffmach_get_plugin_settings();
    $settings = isset( $settings['walmart'] ) ? $settings['walmart'] : false;
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
	$txt = isset( $settings['txt'] ) ? trim( $settings['txt'] ) : 'Walmart Products';
	$position = isset( $settings['position'] ) ? intval( $settings['position'] ) : 0;
	$buy_button = isset( $settings['buy_button'] ) ? trim( $settings['buy_button'] ) : 'Buy Now';


	// var_dump($post_id);
	$products = cbaffmach_get_walmart_products( $keywords, $category, $num_ads, $post_id, $cat_id );
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
			$code .= cbaffmach_monetize_prod( $product['title'], $product['url'], $product['image_url'], 'walmart', $buy_button, $price, $display_image );
	    }
	}
    $code .= '</div>';

	return cbaffmach_add_element_in_content( $code, $content, $settings );
}

function cbaffmach_get_walmart_products( $keyword, $category, $num_ads, $post_id = false, $cat_id = false  ) {
	$transient_name = 'cbaffmach_walmart';
	if( $post_id )
		$transient_name .= '_pid'.$post_id;
	if( $cat_id )
		$transient_name .= '_cid'.$cat_id;
	// var_dump($transient_name);
	// delete_transient( $transient_name );
    if ( false === ( $walmart_products = get_transient( $transient_name ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
         $walmart_products = cbaffmach_do_get_walmart_products( $keyword, $category, $num_ads );
         set_transient( $transient_name, $walmart_products, 6 * HOUR_IN_SECONDS );
    }
    // var_dump($walmart_products);
    return $walmart_products;
}

function cbaffmach_do_get_walmart_products( $keyword, $category = 0, $num_ads = 6 ) {
	$settings = cbaffmach_get_settings( );
	$walmart_settings = $settings['walmart'];

	$api_key = isset( $walmart_settings['apikey'] ) && !empty( $walmart_settings['apikey'] ) ? $walmart_settings['apikey'] : 'nd4cev46pk9xf9vr8cfxuqt4';
	$aff_id = isset( $walmart_settings['aff_id'] ) && !empty( $walmart_settings['aff_id'] ) ? $walmart_settings['aff_id'] : '2512587';

	$url = 'http://api.walmartlabs.com/v1/search?query='.urlencode( $keyword ).'&format=json&apiKey='.$api_key.'&lsPublisherId='.$aff_id;

	$response = wp_remote_get( $url );
	if ( is_array( $response ) ) {
	  $header = $response['headers']; // array of http header lines
	  $body = $response['body']; // use the content
	  $prods = json_decode( $body );
	  $products = array();
	  if( $prods->numItems > 0 ) {
	  	foreach( $prods->items as $item ) {
	  		// var_dump($item);
	  		$prod = array();
			$prod['title'] = (string) $item->name;
			$prod['description'] = (string) $item->shortDescription;
			$prod['url']  = (string) $item->productUrl;
			// rmi353, probablemente es productTrackingUrl
			$prod['image_url']   = (string) $item->mediumImage;
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

function cbaffmach_settings_monetize_tab_walmart( $settings = false ) {
    $settings = isset( $settings['walmart'] ) ? $settings['walmart'] : false;
    $walmart_api = isset( $settings['apikey'] ) ? trim( $settings['apikey'] ) : '';
    $walmart_aff_id = isset( $settings['aff_id'] ) ? trim( $settings['aff_id'] ) : '';

    $walmart_enabled = isset( $settings['enabled'] ) ? intval( $settings['enabled'] ) : 0;
    $walmart_keywords = isset( $settings['keywords'] ) ? trim( $settings['keywords'] ) : '';
    $walmart_category = isset( $settings['category'] ) ? trim( $settings['category'] ) : 'All';
    $walmart_display_price = isset( $settings['display_price'] ) ? intval( $settings['display_price'] ) : 0;
    $walmart_display_image = isset( $settings['display_image'] ) ? intval( $settings['display_image'] ) : 1;
    $walmart_num_ads = isset( $settings['num_ads'] ) ? intval( $settings['num_ads'] ) : 3;
    $walmart_per_row = isset( $settings['per_row'] ) ? intval( $settings['per_row'] ) : 3;
    $walmart_txt = isset( $settings['txt'] ) ? trim( $settings['txt'] ) : 'Walmart Products';
    $walmart_position = isset( $settings['position'] ) ? intval( $settings['position'] ) : 0;
    $walmart_buy_button = isset( $settings['buy_button'] ) ? trim( $settings['buy_button'] ) : 'Buy Now';
?>
    <h3><i class="fa fa-shopping-cart"></i> Walmart</h3>
    <h4 class="h4bigger">1. Walmart Settings</h4>
    <table class="form-table">
    	<?php cbaffmach_field_text( 'Walmart API Key', 'cbaffmach_walmart_api', $walmart_api, false, '', 'Get it <a target="_blank" href="https://developer.walmartlabs.com/">here</a> - <a target="_blank" href="https://cbautomator.com/support/knowledgebase/how-to-create-a-walmart-api-key-and-get-your-affiliate-id/">Tutorial</a>' ); ?>
    	<?php cbaffmach_field_text( 'Walmart Affiliate ID', 'cbaffmach_walmart_aff_id', $walmart_aff_id, false, '', 'Your Linkshare Id. Get it <a target="_blank" href="http://cli.linksynergy.com/cli/publisher/home.php">here</a>' ); ?>
    </table>
        <br/>
    <h4 class="h4bigger">2. Ad Settings</h4>
    <table class="form-table">
        <?php cbaffmach_field_checkbox( 'Show Walmart Ads', 'cbaffmach_walmart_enabled', $walmart_enabled, false, 'cbaffmach_walmart_enabled', 'If you enable this, it will add walmart Affiliate links on autopilot to your posts', '' ); ?>
            <?php cbaffmach_field_text( 'Keywords', 'cbaffmach_walmart_keywords', $walmart_keywords, false, '', '', 'Your Walmart Keywords', 'cbaffmach_mon_walmart_row', $walmart_enabled ); ?>
            <?php cbaffmach_field_checkbox( 'Display Price', 'cbaffmach_walmart_display_price', $walmart_display_price, false, 'cbaffmach_walmart_display_price', 'If you enable this, it will show the product price in the ad', '', 'cbaffmach_mon_walmart_row', $walmart_enabled ); ?>
            <?php cbaffmach_field_checkbox( 'Display Image', 'cbaffmach_walmart_display_image', $walmart_display_image, false, 'cbaffmach_walmart_display_image', 'If you enable this, it will show the product image in the ad', '', 'cbaffmach_mon_walmart_row', $walmart_enabled ); ?>
            <?php $numbers = cbaffmach_get_numbers( 1, 10 ); ?>
            <?php cbaffmach_field_select( 'Number of Ads', 'cbaffmach_walmart_num_ads', $walmart_num_ads, $numbers, false, '', '', '', 'cbaffmach_mon_walmart_row', !$walmart_enabled  ); ?>
            <?php cbaffmach_field_select( 'Ads per Row', 'cbaffmach_walmart_per_row', $walmart_per_row, $numbers, false, '', '', '', 'cbaffmach_mon_walmart_row', !$walmart_enabled  ); ?>
            <?php cbaffmach_field_text( 'Header Text', 'cbaffmach_walmart_txt', $walmart_txt, false, '', '', 'Intro Text before the ads', 'cbaffmach_mon_walmart_row', $walmart_enabled ); ?>
            <?php $positions = cbaffmach_get_banner_positions(); ?>
            <?php cbaffmach_field_select( 'Position', 'cbaffmach_walmart_position', $walmart_position, $positions, false, '', '', '', 'cbaffmach_mon_walmart_row', !$walmart_enabled  ); ?>
            <?php cbaffmach_field_text( 'Button Text', 'cbaffmach_walmart_buy_button', $walmart_buy_button, false, '', '', 'The text for the buy button', 'cbaffmach_mon_walmart_row', $walmart_enabled ); ?>
    </table>
<?php
}
?>