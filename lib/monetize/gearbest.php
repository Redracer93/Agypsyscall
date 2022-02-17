<?php
// include_once CBAFFMACH_DIR.'/lib/libs/gearbest/GearbestProductRequest.php';

function cbaffmach_monetize_gearbest( $content, $keyword = false, $post_id = false, $cat_id = false  ) {
	return '';
    $settings = cbaffmach_get_plugin_settings();
    $settings = isset( $settings['gearbest'] ) ? $settings['gearbest'] : false;
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
	$txt = isset( $settings['txt'] ) ? trim( $settings['txt'] ) : 'Gearbest Products';
	$position = isset( $settings['position'] ) ? intval( $settings['position'] ) : 0;
	$buy_button = isset( $settings['buy_button'] ) ? trim( $settings['buy_button'] ) : 'Buy Now';

$code = '<div>';
	// var_dump($post_id);
	$products = cbaffmach_get_gearbest_products( $keywords, $category, $num_ads, $post_id, $cat_id );
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
			$code .= cbaffmach_monetize_prod( $product['title'], $product['url'], $product['image_url'], 'gearbest', $buy_button, $price, $display_image );
	    }
	}
    $code .= '</div>';

	return cbaffmach_add_element_in_content( $code, $content, $settings );
}

function cbaffmach_get_gearbest_products( $keyword, $category, $num_ads, $post_id = false, $cat_id = false  ) {
	$transient_name = 'cbaffmach_gearbest';
	if( $post_id )
		$transient_name .= '_pid'.$post_id;
	if( $cat_id )
		$transient_name .= '_cid'.$cat_id;
	// var_dump($transient_name);
	// delete_transient( $transient_name );
    if ( false === ( $gearbest_products = get_transient( $transient_name ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
         $gearbest_products = cbaffmach_do_get_gearbest_products( $keyword, $category, $num_ads );
         set_transient( $transient_name, $gearbest_products, 6 * HOUR_IN_SECONDS );
    }
    // var_dump($gearbest_products);
    return $gearbest_products;
}

function cbaffmach_do_get_gearbest_products( $keyword, $category = 0, $num_ads = 6 ) {
	$settings = cbaffmach_get_settings( );
	$gearbest_settings = $settings['gearbest'];

	$apikey = isset( $gearbest_settings['apikey'] ) && !empty( $gearbest_settings['apikey'] ) ? $gearbest_settings['apikey'] : '';
	$username = isset( $gearbest_settings['username'] ) && !empty( $gearbest_settings['username'] ) ? $gearbest_settings['username'] : 'raulmellado';

	if( empty( $apikey ) )
		return false;
// echo "b";

	$args = array(
		'timeout' => 30,
		'redirection' => 5,
		'headers' => array("Authorization" => "Bearer ".$apikey)
    );

	$res = wp_remote_get( 'https://api.gearbest.com/v1/discovery/search/search/item?term='.urlencode( $keyword ), $args );

	if( !$res )
		return false;
	$res = wp_remote_retrieve_body( $res );
	$res = json_decode( $res );
	$results = array();
	if( isset( $res->matches ) && !empty( $res->matches ) ) {
	    foreach($res->matches as $item) {
	    	// var_dump($item->image_urls);
	    	// var_dump($item);
			$res = array();
	        $res['title']   = $item->name;
	        // $res['description']   = empty( $item->description ) ? $item->name : $item->description;
	        if( isset( $item->previews->icon_with_landscape_preview->landscape_url ) )
	        	$thumbnail = $item->previews->icon_with_landscape_preview->landscape_url;
	        else if( isset( $item->previews->landscape_preview->landscape_url ) )
	        	$thumbnail = $item->previews->landscape_preview->landscape_url;
	        else
	        	$thumbnail = $item->author_image;
	        $res['image_url']  = $thumbnail;
	        $res['url']  = add_query_arg( 'ref', $username, $item->url );
			$res['price'] = round( $item->price_cents / 100 ) ;
			$results[] = $res;
		}
	}
	else
		return false;
	return $results;
// var_dump($resp);
}


/* Admin */

function cbaffmach_settings_monetize_tab_gearbest( $settings = false ) {
    $settings = isset( $settings['gearbest'] ) ? $settings['gearbest'] : false;
    $gearbest_deeplink = isset( $settings['deeplink'] ) ? trim( $settings['deeplink'] ) : '';
    $gearbest_appid = isset( $settings['appid'] ) ? trim( $settings['appid'] ) : '';
    $gearbest_appsecret = isset( $settings['appsecret'] ) ? trim( $settings['appsecret'] ) : '';

    $gearbest_enabled = isset( $settings['enabled'] ) ? intval( $settings['enabled'] ) : 0;
    $gearbest_keywords = isset( $settings['keywords'] ) ? trim( $settings['keywords'] ) : '';
    $gearbest_category = isset( $settings['category'] ) ? trim( $settings['category'] ) : 'All';
    $gearbest_display_price = isset( $settings['display_price'] ) ? intval( $settings['display_price'] ) : 0;
    $gearbest_display_image = isset( $settings['display_image'] ) ? intval( $settings['display_image'] ) : 1;
    $gearbest_num_ads = isset( $settings['num_ads'] ) ? intval( $settings['num_ads'] ) : 3;
    $gearbest_per_row = isset( $settings['per_row'] ) ? intval( $settings['per_row'] ) : 3;
    $gearbest_txt = isset( $settings['txt'] ) ? trim( $settings['txt'] ) : 'Gearbest Products';
    $gearbest_position = isset( $settings['position'] ) ? intval( $settings['position'] ) : 0;
    $gearbest_buy_button = isset( $settings['buy_button'] ) ? trim( $settings['buy_button'] ) : 'Buy Now';
?>
    <h3><i class="fa fa-shopping-cart"></i> Gearbest</h3>
    <h4 class="h4bigger">1. Gearbest API Settings</h4>
    <table class="form-table">
    	<?php cbaffmach_field_text( 'Gearbest DeepLink ID', 'cbaffmach_gearbest_deeplink', $gearbest_deeplink, false, '', 'Get it <a target="_blank" href="https://affiliate.gearbest.com/">here</a> - <a href="https://cbautomator.com/support/knowledgebase/how-to-get-your-gearbest-app-id-and-secret/" target="_blank">Tutorial</a>' ); ?>
    	<?php cbaffmach_field_text( 'Gearbest App ID', 'cbaffmach_gearbest_appid', $gearbest_appid, false, '', 'Your Gearbest App ID' ); ?>
    	<?php cbaffmach_field_text( 'Gearbest App Secret', 'cbaffmach_gearbest_appsecret', $gearbest_appsecret, false, '', 'Your Gearbest App Secret' ); ?>
    </table>
        <br/>
    <h4 class="h4bigger">2. Ad Settings</h4>
    <table class="form-table">
        <?php cbaffmach_field_checkbox( 'Show Gearbest Ads', 'cbaffmach_gearbest_enabled', $gearbest_enabled, false, 'cbaffmach_gearbest_enabled', 'If you enable this, it will add gearbest Affiliate links on autopilot to your posts', '' ); ?>
            <?php cbaffmach_field_text( 'Keywords', 'cbaffmach_gearbest_keywords', $gearbest_keywords, false, '', '', 'Your Gearbest Keywords', 'cbaffmach_mon_gearbest_row', $gearbest_enabled ); ?>
            <?php cbaffmach_field_checkbox( 'Display Price', 'cbaffmach_gearbest_display_price', $gearbest_display_price, false, 'cbaffmach_gearbest_display_price', 'If you enable this, it will show the product price in the ad', '', 'cbaffmach_mon_gearbest_row', $gearbest_enabled ); ?>
            <?php cbaffmach_field_checkbox( 'Display Image', 'cbaffmach_gearbest_display_image', $gearbest_display_image, false, 'cbaffmach_gearbest_display_image', 'If you enable this, it will show the product image in the ad', '', 'cbaffmach_mon_gearbest_row', $gearbest_enabled ); ?>
            <?php $numbers = cbaffmach_get_numbers( 1, 10 ); ?>
            <?php cbaffmach_field_select( 'Number of Ads', 'cbaffmach_gearbest_num_ads', $gearbest_num_ads, $numbers, false, '', '', '', 'cbaffmach_mon_gearbest_row', !$gearbest_enabled  ); ?>
            <?php cbaffmach_field_select( 'Ads per Row', 'cbaffmach_gearbest_per_row', $gearbest_per_row, $numbers, false, '', '', '', 'cbaffmach_mon_gearbest_row', !$gearbest_enabled  ); ?>
            <?php cbaffmach_field_text( 'Header Text', 'cbaffmach_gearbest_txt', $gearbest_txt, false, '', '', 'Intro Text before the ads', 'cbaffmach_mon_gearbest_row', $gearbest_enabled ); ?>
            <?php $positions = cbaffmach_get_banner_positions(); ?>
            <?php cbaffmach_field_select( 'Position', 'cbaffmach_gearbest_position', $gearbest_position, $positions, false, '', '', '', 'cbaffmach_mon_gearbest_row', !$gearbest_enabled  ); ?>
            <?php cbaffmach_field_text( 'Button Text', 'cbaffmach_gearbest_buy_button', $gearbest_buy_button, false, '', '', 'The text for the buy button', 'cbaffmach_mon_gearbest_row', $gearbest_enabled ); ?>
    </table>
<?php
}
?>