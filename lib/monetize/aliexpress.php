<?php
// include_once CBAFFMACH_DIR.'/lib/libs/aliexpress/AliexpressProductRequest.php';
define( 'CBAFFMACH_EPN_API_URL', 'http://api.epn.bz/json' );
define( 'CBAFFMACH_EPN_CLIENT_API_VERSION', 2 );

function cbaffmach_monetize_aliexpress( $content, $keyword = false, $post_id = false, $cat_id = false  ) {
    $settings = cbaffmach_get_plugin_settings();
    $settings = isset( $settings['aliexpress'] ) ? $settings['aliexpress'] : false;
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
	$txt = isset( $settings['txt'] ) ? trim( $settings['txt'] ) : 'Aliexpress Products';
	$position = isset( $settings['position'] ) ? intval( $settings['position'] ) : 0;
	$buy_button = isset( $settings['buy_button'] ) ? trim( $settings['buy_button'] ) : 'Buy Now';

$code = '<div>';
	// var_dump($post_id);
	$products = cbaffmach_get_aliexpress_products( $keywords, $category, $num_ads, $post_id, $cat_id );
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
			$code .= cbaffmach_monetize_prod( $product['title'], $product['url'], $product['image_url'], 'aliexpress', $buy_button, $price, $display_image );
	    }
	}
    $code .= '</div>';

	return cbaffmach_add_element_in_content( $code, $content, $settings );
}

function cbaffmach_get_aliexpress_products( $keyword, $category, $num_ads, $post_id = false, $cat_id = false  ) {
	$transient_name = 'cbaffmach_aliexpress';
	if( $post_id )
		$transient_name .= '_pid'.$post_id;
	if( $cat_id )
		$transient_name .= '_cid'.$cat_id;
	// var_dump($transient_name);
	// delete_transient( $transient_name );
    if ( false === ( $aliexpress_products = get_transient( $transient_name ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
         $aliexpress_products = cbaffmach_do_get_aliexpress_products( $keyword, $category, $num_ads );
         set_transient( $transient_name, $aliexpress_products, 6 * HOUR_IN_SECONDS );
    }
    // var_dump($aliexpress_products);
    return $aliexpress_products;
}

function cbaffmach_do_get_aliexpress_products( $keyword, $category = 0, $num_ads = 6 ) {
	$settings = cbaffmach_get_settings( );
	$aliexpress_settings = $settings['aliexpress'];

	$apikey = isset( $aliexpress_settings['apikey'] ) && !empty( $aliexpress_settings['apikey'] ) ? $aliexpress_settings['apikey'] : '0fabfb08cb1146181b566df18a055f5a';
	$hash = isset( $aliexpress_settings['hash'] ) && !empty( $aliexpress_settings['hash'] ) ? $aliexpress_settings['hash'] : 'p1n2wjs55jg8t38o1r3aqhetb9k8m0o3';

	$data = array(
		'user_api_key' => $apikey,
		'user_hash' => $hash,
		'api_version' => CBAFFMACH_EPN_CLIENT_API_VERSION,
		'requests' => array( 'search_1' => array( 'query' => urlencode( $keyword ), 'action' => 'search', 'limit' => $num_ads, 'offset' => 0 ) )
	);

	$args = array(
		'method' => 'POST',
		'timeout' => 30,
		'redirection' => 5,
		'headers' => array("Content-Type" => "text/plain"),
		'body' => json_encode( $data ),
    );

	$res = wp_remote_post( CBAFFMACH_EPN_API_URL, $args );
	if( !$res )
		return false;
	$res = wp_remote_retrieve_body( $res );
	$res = json_decode( $res );
	// var_dump($keyword);
	// var_dump($res);

	$results = array();
	if( isset( $res->results->search_1->offers ) && !empty( $res->results->search_1->offers ) ) {
	    foreach($res->results->search_1->offers as $item) {
	    	// var_dump($item);
			$res = array();
	        $res['title']   = $item->name;
	        // $res['description']   = $item->description;
	        $res['image_url']   = $item->picture;
	        $res['url']  = $item->url;
			$res['price'] = $item->sale_price;
			$results[] = $res;
		}
	}
	else
		return false;

	return $results;
	// var_dump($resp);
}


/* Admin */

function cbaffmach_settings_monetize_tab_aliexpress( $settings = false ) {
    $settings = isset( $settings['aliexpress'] ) ? $settings['aliexpress'] : false;
    $aliexpress_api = isset( $settings['apikey'] ) ? trim( $settings['apikey'] ) : '';
    $aliexpress_hash = isset( $settings['hash'] ) ? trim( $settings['hash'] ) : '';

    $aliexpress_enabled = isset( $settings['enabled'] ) ? intval( $settings['enabled'] ) : 0;
    $aliexpress_keywords = isset( $settings['keywords'] ) ? trim( $settings['keywords'] ) : '';
    $aliexpress_category = isset( $settings['category'] ) ? trim( $settings['category'] ) : 'All';
    $aliexpress_display_price = isset( $settings['display_price'] ) ? intval( $settings['display_price'] ) : 0;
    $aliexpress_display_image = isset( $settings['display_image'] ) ? intval( $settings['display_image'] ) : 1;
    $aliexpress_num_ads = isset( $settings['num_ads'] ) ? intval( $settings['num_ads'] ) : 3;
    $aliexpress_per_row = isset( $settings['per_row'] ) ? intval( $settings['per_row'] ) : 3;
    $aliexpress_txt = isset( $settings['txt'] ) ? trim( $settings['txt'] ) : 'Aliexpress Products';
    $aliexpress_position = isset( $settings['position'] ) ? intval( $settings['position'] ) : 0;
    $aliexpress_buy_button = isset( $settings['buy_button'] ) ? trim( $settings['buy_button'] ) : 'Buy Now';
?>
    <h3><i class="fa fa-shopping-cart"></i> Aliexpress</h3>
    <h4 class="h4bigger">1. Aliexpress Settings</h4>
    <table class="form-table">
    	<?php cbaffmach_field_text( 'EPN API Key', 'cbaffmach_aliexpress_api', $aliexpress_api, false, '', 'Get it <a target="_blank" href="https://epn.bz/en/cabinet#/epn-api">here</a> - <a href="https://cbautomator.com/support/knowledgebase/how-to-get-your-epn-api-and-hash-for-aliexpress/" target="_blank">Tutorial</a>' ); ?>
    	<?php cbaffmach_field_text( 'EPN Deeplink Hash', 'cbaffmach_aliexpress_hash', $aliexpress_hash, false, '', 'Your EPN Hash. Get it <a target="_blank" href="https://epn.bz/en/cabinet#/creative/create/type/4">here</a>' ); ?>
    </table>
        <br/>
    <h4 class="h4bigger">2. Ad Settings</h4>
    <table class="form-table">
        <?php cbaffmach_field_checkbox( 'Show Aliexpress Ads', 'cbaffmach_aliexpress_enabled', $aliexpress_enabled, false, 'cbaffmach_aliexpress_enabled', 'If you enable this, it will add aliexpress Affiliate links on autopilot to your posts', '' ); ?>
            <?php cbaffmach_field_text( 'Keywords', 'cbaffmach_aliexpress_keywords', $aliexpress_keywords, false, '', '', 'Your Aliexpress Keywords', 'cbaffmach_mon_aliexpress_row', $aliexpress_enabled ); ?>
            <?php cbaffmach_field_checkbox( 'Display Price', 'cbaffmach_aliexpress_display_price', $aliexpress_display_price, false, 'cbaffmach_aliexpress_display_price', 'If you enable this, it will show the product price in the ad', '', 'cbaffmach_mon_aliexpress_row', $aliexpress_enabled ); ?>
            <?php cbaffmach_field_checkbox( 'Display Image', 'cbaffmach_aliexpress_display_image', $aliexpress_display_image, false, 'cbaffmach_aliexpress_display_image', 'If you enable this, it will show the product image in the ad', '', 'cbaffmach_mon_aliexpress_row', $aliexpress_enabled ); ?>
            <?php $numbers = cbaffmach_get_numbers( 1, 10 ); ?>
            <?php cbaffmach_field_select( 'Number of Ads', 'cbaffmach_aliexpress_num_ads', $aliexpress_num_ads, $numbers, false, '', '', '', 'cbaffmach_mon_aliexpress_row', !$aliexpress_enabled  ); ?>
            <?php cbaffmach_field_select( 'Ads per Row', 'cbaffmach_aliexpress_per_row', $aliexpress_per_row, $numbers, false, '', '', '', 'cbaffmach_mon_aliexpress_row', !$aliexpress_enabled  ); ?>
            <?php cbaffmach_field_text( 'Header Text', 'cbaffmach_aliexpress_txt', $aliexpress_txt, false, '', '', 'Intro Text before the ads', 'cbaffmach_mon_aliexpress_row', $aliexpress_enabled ); ?>
            <?php $positions = cbaffmach_get_banner_positions(); ?>
            <?php cbaffmach_field_select( 'Position', 'cbaffmach_aliexpress_position', $aliexpress_position, $positions, false, '', '', '', 'cbaffmach_mon_aliexpress_row', !$aliexpress_enabled  ); ?>
            <?php cbaffmach_field_text( 'Button Text', 'cbaffmach_aliexpress_buy_button', $aliexpress_buy_button, false, '', '', 'The text for the buy button', 'cbaffmach_mon_aliexpress_row', $aliexpress_enabled ); ?>
    </table>
<?php
}
?>