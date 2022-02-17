<?php
// include_once CBAFFMACH_DIR.'/lib/libs/ebay/AmazonProductRequest.php';

function cbaffmach_monetize_ebay( $content, $keyword = false, $post_id = false, $cat_id = false  ) {
    $settings = cbaffmach_get_plugin_settings();
    $settings = isset( $settings['ebay'] ) ? $settings['ebay'] : false;
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
	$txt = isset( $settings['txt'] ) ? trim( $settings['txt'] ) : 'Amazon Products';
	$position = isset( $settings['position'] ) ? intval( $settings['position'] ) : 0;
	$buy_button = isset( $settings['buy_button'] ) ? trim( $settings['buy_button'] ) : 'Buy Now';

	$code = empty( $txt ) ? '' : '<h3>'.$txt.'</h3>';
	$code .= '<div class="cbaffmach_am_ads per_row_'.$per_row.'">';
	// var_dump($post_id);
	$products = cbaffmach_get_ebay_products( $keywords, $category, $num_ads, $post_id, $cat_id );
	if( $products && is_array( $products ) )
		shuffle( $products );
	// var_dump($products);
	if( $num_ads && $products ) {
	    for( $i=0; $i < $num_ads; $i++) {
	    	$product = $products[$i];
	    	// var_dump($product);
	    	if( !$display_price || $display_price == -1 )
	    	    $price = -1;
	    	else
	    	    $price = isset( $product['price'] ) ? $product['price'] : -1;
			$code .= cbaffmach_monetize_prod( $product['title'], $product['url'], $product['image_url'], 'ebay', $buy_button, $price, $display_image );
	    }
	}
    $code .= '</div>';

	return cbaffmach_add_element_in_content( $code, $content, $settings );
}

function cbaffmach_get_ebay_products( $keyword, $category, $num_ads, $post_id = false, $cat_id = false  ) {
	$transient_name = 'cbaffmach_ebay';
	if( $post_id )
		$transient_name .= '_pid'.$post_id;
	if( $cat_id )
		$transient_name .= '_cid'.$cat_id;
	// var_dump($transient_name);
    if ( false === ( $ebay_products = get_transient( $transient_name ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
         $ebay_products = cbaffmach_do_get_ebay_products( $keyword, $category, $num_ads );
         set_transient( $transient_name, $ebay_products, 6 * HOUR_IN_SECONDS );
    }
    // var_dump($ebay_products);
    return $ebay_products;
}

function cbaffmach_do_get_ebay_products( $keyword, $category = 0, $num_ads = 6 ) {
	// API request variables
	$endpoint = 'http://svcs.ebay.com/services/search/FindingService/v1';  // URL to call
	$version = '1.0.0';  // API version supported by your application
	$myappid = 'RaulMell-EPNWpFee-PRD-b5d8a3c47-6f4553c5';  // Replace with your own AppID

	$search = $keyword;
	$ebay_settings = cbaffmach_get_settings( array( 'monetize', 'ebay' ) );

	$appid = isset( $ebay_settings['appid'] ) && !empty( $ebay_settings['appid'] ) ? $ebay_settings['appid'] : $myappid;
	$campid = isset( $ebay_settings['campaignid'] ) && !empty( $ebay_settings['campaignid'] ) ? $ebay_settings['campaignid'] : '5338235125';
	$country = isset( $ebay_settings['country'] ) && !empty( $ebay_settings['country'] ) ? $ebay_settings['country'] : 'US';
	$globalid = 'EBAY-'.$country;

	$num_ads += 4;
	$SafeQuery = urlencode($keyword);  // Make the query URL-friendly
	// Construct the findItemsAdvanced call 
	$apicall = "$endpoint?";
	$apicall .= "OPERATION-NAME=findItemsAdvanced";
	$apicall .= "&SERVICE-VERSION=$version";
	$apicall .= "&SECURITY-APPNAME=$appid";
	$apicall .= "&GLOBAL-ID=$globalid";
	$apicall .= "&keywords=$SafeQuery";
	if( $category != 0 ){
		$apicall .= "&categoryId=".$category;
	}
	$apicall .= "&affiliate.networkId=9";
	$apicall .= "&affiliate.trackingId=$campid";
	// if($sort_by!=""){
	// $apicall .= "&sortOrder=$sort_by";
	// }
	$apicall .= "&descriptionSearch=false";
	$apicall .= "&paginationInput.entriesPerPage=$num_ads";
	// $apicall .= $urlfilter;
	$resp = simplexml_load_file($apicall);
	$results = array();
	if ( $resp->searchResult->item ) {
    foreach($resp->searchResult->item as $item) {
		$res = array();
        $res['image_url']   = (string) $item->galleryURL;
        $res['url']  = (string) $item->viewItemURL;
        $res['title'] = (string) $item->title;
		$res['price'] = (string) $item->sellingStatus->currentPrice;
		$currencyid = $item->sellingStatus->currentPrice['currencyId'];
		if($currencyid == "GBP"){
			$currencysign = "&pound;";
		} else if($currencyid == "USD") {
			$currencysign = "$";
		} else {
			$currencysign = "";
			$cidtext = $currencyid;
		}
		$results[] = $res;
	}
	}
	else
		return false;

	return $results;
	// var_dump($resp);
}

?>