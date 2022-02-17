<?php
// include_once CBAFFMACH_DIR.'/lib/libs/envato/EnvatoProductRequest.php';

function cbaffmach_monetize_envato( $content, $keyword = false, $post_id = false, $cat_id = false  ) {
    $settings = cbaffmach_get_plugin_settings();
    $settings = isset( $settings['envato'] ) ? $settings['envato'] : false;
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
	$txt = isset( $settings['txt'] ) ? trim( $settings['txt'] ) : 'Envato Products';
	$position = isset( $settings['position'] ) ? intval( $settings['position'] ) : 0;
	$buy_button = isset( $settings['buy_button'] ) ? trim( $settings['buy_button'] ) : 'Buy Now';

$code = '<div>';
	// var_dump($post_id);
	$products = cbaffmach_get_envato_products( $keywords, $category, $num_ads, $post_id, $cat_id );
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
			$code .= cbaffmach_monetize_prod( $product['title'], $product['url'], $product['image_url'], 'envato', $buy_button, $price, $display_image );
	    }
	}
    $code .= '</div>';

	return cbaffmach_add_element_in_content( $code, $content, $settings );
}

function cbaffmach_get_envato_products( $keyword, $category, $num_ads, $post_id = false, $cat_id = false  ) {
	$transient_name = 'cbaffmach_envato';
	if( $post_id )
		$transient_name .= '_pid'.$post_id;
	if( $cat_id )
		$transient_name .= '_cid'.$cat_id;
	// var_dump($transient_name);
	// delete_transient( $transient_name );
    if ( false === ( $envato_products = get_transient( $transient_name ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
         $envato_products = cbaffmach_do_get_envato_products( $keyword, $category, $num_ads );
         set_transient( $transient_name, $envato_products, 6 * HOUR_IN_SECONDS );
    }
    // var_dump($envato_products);
    return $envato_products;
}

function cbaffmach_do_get_envato_products( $keyword, $category = 0, $num_ads = 6 ) {
	$settings = cbaffmach_get_settings( );
	$envato_settings = $settings['envato'];

	$apikey = isset( $envato_settings['apikey'] ) && !empty( $envato_settings['apikey'] ) ? $envato_settings['apikey'] : '';
	$username = isset( $envato_settings['username'] ) && !empty( $envato_settings['username'] ) ? $envato_settings['username'] : 'raulmellado';

	if( empty( $apikey ) )
		return false;
// echo "b";

	$args = array(
		'timeout' => 30,
		'redirection' => 5,
		'headers' => array("Authorization" => "Bearer ".$apikey)
    );

	$res = wp_remote_get( 'https://api.envato.com/v1/discovery/search/search/item?term='.urlencode( $keyword ), $args );

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

function cbaffmach_settings_monetize_tab_envato( $settings = false ) {
    $settings = isset( $settings['envato'] ) ? $settings['envato'] : false;
    $envato_api = isset( $settings['apikey'] ) ? trim( $settings['apikey'] ) : '';
    $envato_username = isset( $settings['username'] ) ? trim( $settings['username'] ) : '';

    $envato_enabled = isset( $settings['enabled'] ) ? intval( $settings['enabled'] ) : 0;
    $envato_keywords = isset( $settings['keywords'] ) ? trim( $settings['keywords'] ) : '';
    $envato_category = isset( $settings['category'] ) ? trim( $settings['category'] ) : 'All';
    $envato_display_price = isset( $settings['display_price'] ) ? intval( $settings['display_price'] ) : 0;
    $envato_display_image = isset( $settings['display_image'] ) ? intval( $settings['display_image'] ) : 1;
    $envato_num_ads = isset( $settings['num_ads'] ) ? intval( $settings['num_ads'] ) : 3;
    $envato_per_row = isset( $settings['per_row'] ) ? intval( $settings['per_row'] ) : 3;
    $envato_txt = isset( $settings['txt'] ) ? trim( $settings['txt'] ) : 'Envato Products';
    $envato_position = isset( $settings['position'] ) ? intval( $settings['position'] ) : 0;
    $envato_buy_button = isset( $settings['buy_button'] ) ? trim( $settings['buy_button'] ) : 'Buy Now';
?>
    <h3><i class="fa fa-leaf"></i> Envato</h3>
    <h4 class="h4bigger">1. Envato API Settings</h4>
    <table class="form-table">
    	<?php cbaffmach_field_text( 'Envato API Key', 'cbaffmach_envato_api', $envato_api, false, '', 'Get it <a target="_blank" href="https://build.envato.com/my-apps/#tokens">here</a> - <a target="_blank" href="https://cbautomator.com/support/knowledgebase/how-to-get-your-envato-api-key/">Tutorial</a>', 'Your Envato API Key' ); ?>
    	<?php cbaffmach_field_text( 'Envato username', 'cbaffmach_envato_username', $envato_username, false, '', 'So you can get affiliate commissions from Envato sites' ); ?>
    </table>
        <br/>
    <h4 class="h4bigger">2. Ad Settings</h4>
    <table class="form-table">
        <?php cbaffmach_field_checkbox( 'Show Envato Ads', 'cbaffmach_envato_enabled', $envato_enabled, false, 'cbaffmach_envato_enabled', 'If you enable this, it will add envato Affiliate links on autopilot to your posts', '' ); ?>
            <?php cbaffmach_field_text( 'Keywords', 'cbaffmach_envato_keywords', $envato_keywords, false, '', '', 'Your Envato Keywords', 'cbaffmach_mon_envato_row', $envato_enabled ); ?>
            <?php cbaffmach_field_checkbox( 'Display Price', 'cbaffmach_envato_display_price', $envato_display_price, false, 'cbaffmach_envato_display_price', 'If you enable this, it will show the product price in the ad', '', 'cbaffmach_mon_envato_row', $envato_enabled ); ?>
            <?php cbaffmach_field_checkbox( 'Display Image', 'cbaffmach_envato_display_image', $envato_display_image, false, 'cbaffmach_envato_display_image', 'If you enable this, it will show the product image in the ad', '', 'cbaffmach_mon_envato_row', $envato_enabled ); ?>
            <?php $numbers = cbaffmach_get_numbers( 1, 10 ); ?>
            <?php cbaffmach_field_select( 'Number of Ads', 'cbaffmach_envato_num_ads', $envato_num_ads, $numbers, false, '', '', '', 'cbaffmach_mon_envato_row', !$envato_enabled  ); ?>
            <?php cbaffmach_field_select( 'Ads per Row', 'cbaffmach_envato_per_row', $envato_per_row, $numbers, false, '', '', '', 'cbaffmach_mon_envato_row', !$envato_enabled  ); ?>
            <?php cbaffmach_field_text( 'Header Text', 'cbaffmach_envato_txt', $envato_txt, false, '', '', 'Intro Text before the ads', 'cbaffmach_mon_envato_row', $envato_enabled ); ?>
            <?php $positions = cbaffmach_get_banner_positions(); ?>
            <?php cbaffmach_field_select( 'Position', 'cbaffmach_envato_position', $envato_position, $positions, false, '', '', '', 'cbaffmach_mon_envato_row', !$envato_enabled  ); ?>
            <?php cbaffmach_field_text( 'Button Text', 'cbaffmach_envato_buy_button', $envato_buy_button, false, '', '', 'The text for the buy button', 'cbaffmach_mon_envato_row', $envato_enabled ); ?>
    </table>
<?php
}
?>