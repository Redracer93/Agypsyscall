<?php

function cbaffmach_traffic_facebook( $post_id ) {
	// echo "entro bmachine";
	/*var_dump($settings);*/
	if( empty( $settings ) )
		return false;
		// echo "fb";
	$fb_page = isset( $settings->fb_page ) ? $settings->fb_page : 0;
	if( empty( $fb_page ) ) {
		cbaffmach_debug( 'Error with Facebook - No Page Selected', 'error' );
		return;
	}
	// var_dump($post_id);
	// var_dump(get_permalink( $post_id ));
	// var_dump(get_the_excerpt( $post_id ));
// return;
	$res = cbaffmach_share_facebook( $fb_page, get_the_title( $post_id ), /*get_the_excerpt( $post_id )*/'', get_permalink( $post_id ) );
	if( $res )
		cbaffmach_log_traffic( $post_id, CBAFFMACH_TRAFFIC_FACEBOOK );
}

function cbaffmach_share_facebook( $fb_page, $title, $text, $post_url, $image_url = false ) {
	require_once CBAFFMACH_DIR . 'lib/libs/facebook/Facebook/autoload.php';
	$facebook_settings = cbaffmach_get_settings( array( 'social', 'facebook' ) );
// var_dump($facebook_settings);
	// var_dump($settings);
	if ( ! isset( $facebook_settings['app_id'] ) || empty( $facebook_settings['app_id'] ) || ! isset( $facebook_settings['app_secret'] ) || empty( $facebook_settings['app_secret'] ) ) {
		cbaffmach_debug( 'Error with Facebook Credentials', 'error' );
		return false;
	}
    $pageAccessToken = cbaffmach_page_access_token( $fb_page );

	// $fbpageid             = $fb_page;
	$data['link']         = $post_url;
	$data['message']      = html_entity_decode( $title );

	cbaffmach_facebook_post( $fb_page . '/feed', $data, $pageAccessToken );
	// var_dump($post_url);
	// trfInsertHistory('facebook', json_encode($return), $link, $title, $post_id);
	return true;
}

function cbaffmach_get_facebook_pages() {
	require_once CBAFFMACH_DIR . 'lib/libs/facebook/Facebook/autoload.php';

	$response = cbaffmach_facebook_query( "me/accounts", "" );
	$pages_array = array();
	if( !isset( $response['data'] ) )
		return array();
	foreach ($response['data'] as $page) {
	    $pagename = $page['name'];
	    $id = trim($page['id']);
	    $token = $page['access_token'];

	    // if ($id == $selected_facebook_page) {
	    //     echo "<option value = '".$id."'  selected = 'selected'>".$pagename."</option>";
	    // } else {
	    //     echo "<option value = '".$id."'>".$pagename."</option>";
	    // }

	    array_push($pages_array, array(
	        'label' => $pagename,
	        'value' => $id,
	        'token' => $token
	    ));
	}
	return $pages_array;
	// echo '</select></td></tr>';
	// update_post_meta(111111113, 'trfTempFBPages', json_encode($pagesArray));
}

function cbaffmach_facebook_query( $query, $params ) {
    $post_url = 'https://graph.facebook.com/'.$query;
	$facebook_settings = cbaffmach_get_settings( array( 'social', 'facebook' ) );
    $accesstoken = $facebook_settings['token'];

    $post_url = $post_url . '?access_token=' . $accesstoken . $params;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $post_url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($result, TRUE);

    return $result;
}

function cbaffmach_facebook_post ( $query, $params, $pagetoken = false ) {
    $post_url = 'https://graph.facebook.com/'.$query;

	$accesstoken = $pagetoken;

    $params['access_token'] = $accesstoken;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $post_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $return = curl_exec($ch);
    curl_close($ch);

    $return = json_decode( $return );

    return $return;
}

function cbaffmach_page_access_token( $pageid ) {
  $response = cbaffmach_facebook_query('me/accounts', '');

  foreach ($response ['data'] as $page){
    $pagename = $page['name'];
    $id = $page['id'];
    $id = trim($id);

    $token = $page['access_token'];

    if ($id == $pageid){
        $accesstoken = $token;
    }
  }

  return $accesstoken;
}
?>