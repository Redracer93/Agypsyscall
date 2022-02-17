<?php
define ('VIDPROFITS_WEBINAR_GOTOWEBINAR', 1);
define ('VIDPROFITS_WEBINAR_HANGOUT', 2);
define ('VIDPROFITS_WEBINAR_WEBINAREXPRESS', 3);
define ('VIDPROFITS_WEBINAR_WEBINARIGINITION', 4);
define ('VIDPROFITS_WEBINAR_WEBINARJAM', 5);
define ('VIDPROFITS_WEBINAR_WEBINARJEO', 6);

define('VIDPROFITS_WEBINARJEO_URL', 'https://app.webinarjeo.com/api/web/v1/');
// echo vidprofits_WEBINAREXPRESS_DIR.'/../webinarexpress';
// exit(0);
// define( 'vidprofits_WEBINAREXPRESS_URL', plugins_url( vidprofits_PLUGIN_NAME ) );

/* Gotowebinar */
function vidprofits_check_authorize_gtw() {
	if (isset($_POST['vidprofits_gtw_api'])) {
		vidprofits_check_authorize_gtw_api();
	}
	else if (isset($_GET['vidprofits_gtw_authorize'])) {
		vidprofits_check_save_gtw_creds();
	}
}
add_action('admin_init', 'vidprofits_check_authorize_gtw');

define('REDIRECT_URL_AFTER_AUTHENTICATION', admin_url('admin.php?page=vidprofits-settings&vidprofits_gtw_authorize=1'));

function vidprofits_check_authorize_gtw_api() {
	include_once VIDPROFITS_DIR.'/lib/autoresponders/gotowebinar/gotoWebinarClass.php';

	$plugin_options = vidprofits_get_plugin_options();
	$plugin_options['webinars']['gotowebinar']['apikey'] = trim($_POST['vidprofits_gtw_api']);
	vidprofits_update_plugin_options($plugin_options);

	$obj = new OAuth_En();
	$oauth = new OAuth($obj);

	if(!isset($_GET['code'])){
	    vidprofits_goForAuthorization($oauth);
	}

}

function vidprofits_check_save_gtw_creds() {
	include_once VIDPROFITS_DIR.'/lib/autoresponders/gotowebinar/gotoWebinarClass.php';
	$obj = new OAuth_En();
	$oauth = new OAuth($obj);
    $oauth->authorizeUsingResponseKey($_GET['code']);
    if(!$oauth->hasApiError()){
        $objOAuthEn = $oauth->getOAuthEntityClone();
        $plugin_options = vidprofits_get_plugin_options();
        $plugin_options['webinars']['gotowebinar']['organizer_key'] = $objOAuthEn->getOrganizerKey();
        $plugin_options['webinars']['gotowebinar']['access_token'] = $objOAuthEn->getAccessToken();
        vidprofits_update_plugin_options($plugin_options);
        vidprofits_get_gotowebinar_webinars(1);
        wp_redirect(admin_url('admin.php?page=survey-snatcher-settings'));
        exit(0);
	}
}

/*
	Register somebody for a webinar
$registrantInfo = array(
    "firstName"=>"ashish",
    "lastName"=>"mehta",
    "email"=>"test@test.com",
);

$oauth->setWebinarId(525120321);
$oauth->setRegistrantInfo($registrantInfo);

$res = $oauth->createRegistrant();
echo $oauth->getApiErrorCode();
if(!$oauth->hasApiError()){
    print_r($res);
}else{
    echo 'error';
    print_r($oauth->getApiError());
}
*/
//this function has been used for getting the key using which we can get the access token and organizer key
function vidprofits_goForAuthorization($oauth){
    $oauth->setRedirectUrl(REDIRECT_URL_AFTER_AUTHENTICATION);
    $url = $oauth->getApiAuthorizationUrl();
    header('Location: '.$url);
}


function vidprofits_gtw_subscribe_user($webinar_id, $user_email, $first_name = '', $last_name = '') {
	$registrantInfo = array(
	    "firstName"=> empty($first_name) ? ' ' : $first_name,
	    "lastName"=> empty($last_name) ? ' ' : $last_name,
	    "email"=> $user_email
	);

	try {
		$oauth = vidprofits_webinars_gtw_authenticate();
		$oauth->setWebinarId($webinar_id);
		$oauth->setRegistrantInfo($registrantInfo);
		$res = $oauth->createRegistrant();
	} catch (Exception $oException) { return 0; }
}

/*
	$webinar_type:
		1: gotowebinar
		2: hangout
		3: webinarexpress
*/

function vidprofits_get_webinars( $webinar_type ) {
	if ($webinar_type == 1)
		$webinars = vidprofits_get_gotowebinar_webinars(0);
	else if ($webinar_type == 2)
		$webinars = vidprofits_get_hangouts_webinars(0);
	else if ($webinar_type == 3)
		$webinars = vidprofits_get_webinarexpress_webinars(0);
	else if ($webinar_type == 4)
		$webinars = vidprofits_get_webinarignition_webinars(0);
	else if ($webinar_type == 5)
		$webinars = vidprofits_get_webinarjam_webinars(0);
	else if ($webinar_type == 6)
		$webinars = vidprofits_get_webinarjeo_webinars(0);
	else
		$webinars = 0;
	return $webinars;
}

function vidprofits_webinar_gtw_webinars_select($webinar_type, $id = '', $name = '', $val=0) {
	$output = '';

	if ($webinar_type == 1)
		$webinars = vidprofits_get_gotowebinar_webinars(0);
	else if ($webinar_type == 2)
		$webinars = vidprofits_get_hangouts_webinars(0);
	else if ($webinar_type == 3)
		$webinars = vidprofits_get_webinarexpress_webinars(0);
	else if ($webinar_type == 4)
		$webinars = vidprofits_get_webinarignition_webinars(0);
	else if ($webinar_type == 5)
		$webinars = vidprofits_get_webinarjam_webinars(0);
	else
		$webinars = 0;
// var_dump($webinars);
// var_dump($val);
	if (!empty($name) && !empty($id))
		$output .= '<select name="'.$name.'" id="'.$id.'" class="webinar_list">';
	else
		$output .= '<select class="webinar_list">';

	$output .= '<option value="0">-</option>';
	if ($webinars) {
		foreach($webinars as $offset => $webinar) {
			// var_dump($webinar);
				$output.= '<option '.selected($webinar['id'], $val, false).' name="mc_'.$webinar['id'].'" value="'.$webinar['id'].'">'.$webinar['name'].'</option>';
		}
	}
	$output.= '</select>';
	echo $output;
	return 0;
}

function vidprofits_webinars_gtw_authenticate() {
	$plugin_options = vidprofits_get_plugin_options();
	if (!isset($plugin_options['webinars']['gotowebinar'])) return 0;
	$gtw_data = $plugin_options['webinars']['gotowebinar'];
	if (isset($gtw_data['organizer_key'])  && isset($gtw_data['access_token']) ) {
		include_once VIDPROFITS_DIR.'/lib/autoresponders/gotowebinar/gotoWebinarClass.php';
		$obj = new OAuth_En();
		$obj->setAccessToken($gtw_data['access_token']/*get_site_option('vidprofits_gtw_access_token')*/);
		$obj->setOrganizerKey($gtw_data['organizer_key']/*get_site_option('vidprofits_gtw_organizer_key')*/);
		$oauth = new OAuth($obj);
		return $oauth;
	}
	return 0;
}

/*
	$use_cache:
		0 : always get new online version
		1 : if date of last check < now - 1 day, re-read
		2 : force read online, ignore date

*/
function vidprofits_get_gotowebinar_webinars($force_read = 0) {
	$plugin_options = vidprofits_get_plugin_options();

	if (isset($plugin_options['webinars']['gotowebinar']['cache_expires']) && ($expiration = $plugin_options['webinars']['gotowebinar']['cache_expires'])) {
		if ($expiration >= time() && (!$force_read))
			return ($plugin_options['webinars']['gotowebinar']['webinars']);
	}

	$webinars = vidprofits_query_gotowebinar_webinars();
	$plugin_options['webinars']['gotowebinar']['cache_expires'] = time() + (24 * 3600 * 3);
	$plugin_options['webinars']['gotowebinar']['webinars'] = $webinars;
	vidprofits_update_plugin_options($plugin_options);
	return $webinars;
}

function vidprofits_query_gotowebinar_webinars() {
	$oauth = vidprofits_webinars_gtw_authenticate();
	if (!$oauth) return 0;
	$webinars = $oauth->getWebinars();
	$all_webinars = array();

	if(!$oauth->hasApiError()){
	    if ($webinars) {
	    	foreach ($webinars as $webinar) {
	    		$single_webinar = array('id' => $webinar->webinarKey, 'name' => $webinar->subject, 'date' => date('d-M-Y ',strtotime ($webinar->times[0]->startTime)));
	    		$all_webinars[] = $single_webinar;
	    	}
	    }
	    return $all_webinars;
	}else{
		return 0;
	}
}


/* Jebinar Jam */

function vidprofits_webinars_webinarjam_authenticate() {
	$plugin_options = vidprofits_get_plugin_options();
	if (!isset($plugin_options['webinars']['webinarjam'])) return 0;
	$wjam_data = $plugin_options['webinars']['webinarjam'];
	if ( isset( $wjam_data['apikey'] ) ) {
		include_once VIDPROFITS_DIR.'/lib/autoresponders/webinarjam/WebinarJam.php';
		$obj = new WebinarJam( $wjam_data['apikey'] );
		return $obj;
	}
	return 0;
}
function vidprofits_get_webinarjam_webinars($force_read = 0) {
	$plugin_options = vidprofits_get_plugin_options();

	if (isset($plugin_options['webinars']['webinarjam']['cache_expires']) && ($expiration = $plugin_options['webinars']['webinarjam']['cache_expires'])) {
		if ($expiration >= time() && (!$force_read))
			return ($plugin_options['webinars']['webinarjam']['webinars']);
	}

	$webinars = vidprofits_query_webinarjam_webinars();
	$plugin_options['webinars']['webinarjam']['cache_expires'] = time() + (24 * 3600 * 3);
	$plugin_options['webinars']['webinarjam']['webinars'] = $webinars;
	vidprofits_update_plugin_options($plugin_options);
	return $webinars;
}

function vidprofits_query_webinarjam_webinars() {
	$oauth = vidprofits_webinars_webinarjam_authenticate();
	if (!$oauth) return 0;
	$webinars = $oauth->getWebinars();
	$all_webinars = array();

// var_dump($webinars['webinars']);
    if ($webinars && ($webinars['status'] == 'success' ) && !empty($webinars['webinars']) ) {
    	foreach ($webinars['webinars'] as $webinar) {
    		$webinar_date = '';
    		if( isset( $webinar['schedules'] ) && !empty( $webinar['schedules'] ) && isset( $webinar['schedules'][0] ) && !empty( $webinar['schedules'][0] ))
    			$webinar_date = date('d-M-Y ',strtotime ($webinar['schedules'][0] ) );
    		$single_webinar = array('id' => $webinar['webinar_id'], 'name' => $webinar['name'], 'date' => $webinar_date );
    		$all_webinars[] = $single_webinar;
    	}
    	return $all_webinars;
	}else{
		return 0;
	}
}


function vidprofits_webinarjam_subscribe_user($webinar_id, $user_email, $first_name = '', $last_name = '') {

	try {
		$oauth = vidprofits_webinars_webinarjam_authenticate();
		$webinar = $oauth->getWebinar( $webinar_id );
		// var_dump($webinar['webinar']['schedules'][0]);
		if(  ($webinar['status'] == 'success') && isset( $webinar['webinar'] ) && isset( $webinar['webinar']['schedules'][0]) ) {
			$schedule = $webinar['webinar']['schedules'][0];
			$schedule = $schedule['schedule'];
			$name = empty( $last_name ) ? $first_name : $first_name.' '.$last_name;
			$oauth->addToWebinar( $webinar_id, $name, $user_email, $schedule, vidprofits_get_visitor_ip() );
		}
	} catch (Exception $oException) { return 0; }
}


/* Jebinar JEO */

function vidprofits_webinars_webinarjeo_authenticate() {
	$plugin_options = vidprofits_get_plugin_options();
	if (!isset($plugin_options['webinars']['webinarjeo'])) return 0;
	$wjeo_data = $plugin_options['webinars']['webinarjeo'];
	if ( isset( $wjeo_data['user'] ) && $wjeo_data['user'] && isset( $wjeo_data['pass'] ) && $wjeo_data['pass'] ) {
		// include_once VIDPROFITS_DIR.'/lib/autoresponders/webinarjeo/Webinarjeo.php';
		// $obj = new Webinarjeo( $wjeo_data['apikey'] );
		// return $obj;
		return array( base64_encode($wjeo_data['user'].':'.$wjeo_data['pass'] ), $wjeo_data['user'], $wjeo_data['pass'] );
	}
	return 0;
}
function vidprofits_get_webinarjeo_webinars($force_read = 0) {
	$plugin_options = vidprofits_get_plugin_options();

	if (isset($plugin_options['webinars']['webinarjeo']['cache_expires']) && ($expiration = $plugin_options['webinars']['webinarjeo']['cache_expires'])) {
		if ($expiration >= time() && (!$force_read))
			return ($plugin_options['webinars']['webinarjeo']['webinars']);
	}

	$webinars = vidprofits_query_webinarjeo_webinars();
	$plugin_options['webinars']['webinarjeo']['cache_expires'] = time() + (24 * 3600 * 3);
	$plugin_options['webinars']['webinarjeo']['webinars'] = $webinars;
	vidprofits_update_plugin_options($plugin_options);
	return $webinars;
}

function vidprofits_query_webinarjeo_webinars() {
	$oauth = vidprofits_webinars_webinarjeo_authenticate();
	if (!$oauth) return 0;
	// $webinars = $oauth->getWebinars();
	$all_webinars = array();

	$url = VIDPROFITS_WEBINARJEO_URL.'users/self/webinars';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPGET, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURL_HTTP_VERSION_1_1 , true);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, $oauth[1] . ":" . $oauth[2]);
	$res = curl_exec($ch);
	$webinars= json_decode($res,true);
// var_dump($webinars[0]);
    if ($webinars && count( $webinars ) /*&& ($webinars['status'] == 'success' ) && !empty($webinars['webinars'])*/ ) {
    	foreach ($webinars as $webinar) {
    		$webinar_date = '';
    			$webinar_date = date('d-M-Y ', $webinar['start_at']  );
    		$single_webinar = array('id' => $webinar['node_id'], 'name' => $webinar['name'], 'date' => $webinar_date );
    		$all_webinars[] = $single_webinar;
    	}
    	return $all_webinars;
	}else{
		return 0;
	}
}


function vidprofits_webinarjeo_subscribe_user($webinar_id, $user_email, $first_name = '', $last_name = '') {
	// echo html_entity_decode( 'Participant%5B0%5D%5Bname%5D=name&Participant%5B0%5D%5Bemail%5D=name%40email.loc');
// return;
	try {
		$oauth = vidprofits_webinars_webinarjeo_authenticate();
		if (!$oauth) return 0;

		if( empty( $first_name ) )
			$first_name = 'User';
		
		$fields = array(
			'Participant' => array(
				array("name" => $first_name, "email" => $user_email)
				)
			);
			$data_string = http_build_query($fields);
			$url = VIDPROFITS_WEBINARJEO_URL.'webinars/'.$webinar_id.'/participants';
			$ch = curl_init();
			// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
			// curl_setopt($ch, CURLOPT_HTTPGET, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			// curl_setopt($ch, CURL_HTTP_VERSION_1_1 , true);
			curl_setopt($ch, CURLOPT_USERPWD, $oauth[1] . ":" . $oauth[2]);
			$ret = curl_exec($ch);
			$resp= json_decode($ret,true);
			// var_dump($ret);
			// var_dump($resp);
			/*

curl 'https://dev.webinarjeo.com/api/web/v1/webinars/772/participants' -H 'Accept: application/json' -H 'Authorization: Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ==' -H 'Content-Type: application/x-www-form-urlencoded' -H 'Host: dev.webinarjeo.com' --data 'Participant%5B0%5D%5Bname%5D=name&Participant%5B0%5D%5Bemail%5D=name%40email.loc'

			*/
			/*$schedule = $webinar['webinar']['schedules'][0];
			$schedule = $schedule['schedule'];
			$name = empty( $last_name ) ? $first_name : $first_name.' '.$last_name;
			$oauth->addToWebinar( $webinar_id, $name, $user_email, $schedule, vidprofits_get_visitor_ip() );*/
	} catch (Exception $oException) { return 0; }
}


/* Hangouts Plugin */
function vidprofits_get_hangouts_webinars($use_cache=0) {
	$plugin_options = vidprofits_get_plugin_options();

	if ($use_cache) {
		return ($plugin_options['webinars']['hangouts']['webinars']);
	}

	$args = array('post_type' => 'ghangout');
	$posts_array = get_posts($args);
	$arr_ret = array();
	if ($posts_array) {
		foreach ($posts_array as $post) {
			$arr_ret[] = array('id' => $post->ID, 'name' => $post->post_title, 'date' => '0000');
		}
	}
	$plugin_options['webinars']['hangouts']['webinars'] = $arr_ret;
	vidprofits_update_plugin_options($plugin_options);
	return $arr_ret;
}

function vidprofits_hangouts_subscribe_user($webinar_id, $user_email, $user_name = '') {
	$_POST['g_hangout_id'] = $webinar_id;
	$_POST['event_reg_name'] = $user_name;
	$_POST['event_reg_email'] = $user_email;

	// $reg_reminder = $_POST['event_reg_reminder'];
	// $reminder_time = $_POST['reminder_time'];
	// $add_email = $_POST['event_reg_email_add'];
	if (vidprofits_is_hangouts_plugin_active())
		include(VIDPROFITS_DIR.'../HangoutPlugin/ajax.php');
	return 0;

}

function vidprofits_is_hangouts_plugin_active() {
	return is_plugin_active( 'HangoutPlugin/google_hangout_plugin.php' );
}

/* Webinar Express */
function vidprofits_get_webinarexpress_webinars($use_cache = 0) {
	if (!vidprofits_is_webexpr_plugin_active()) return;
	$plugin_options = vidprofits_get_plugin_options();

	if ($use_cache) {
		return ($plugin_options['webinars']['webinarexpress']['webinars']);
	}

    global $wpdb;
    $webinars = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}webinarexpress" );
    $arr_webinars = array();
    if ($webinars) {
    	foreach ($webinars as $webinar) {
    		$arr_webinars[] = array('id' => $webinar->ID, 'name' => $webinar->appname, 'date' => '0000');
    	}
    }

	$plugin_options['webinars']['webinarexpress']['webinars'] = $arr_webinars;
	vidprofits_update_plugin_options($plugin_options);
    return $arr_webinars;
}

function vidprofits_webexpr_subscribe_user($webinar_id, $user_email, $user_name = '') {
	return vidprofits_webinarexpress_add_lead_callback($webinar_id, $user_email, $user_name);
}

function vidprofits_is_webexpr_plugin_active() {
	return is_plugin_active( 'webinarexpress/functions.php' );
}


function vidprofits_webinarexpress_add_lead_callback($webinar_id, $email, $name ) {

		 $_POST['id'] = $webinar_id;
		 $_POST['name'] = $name;
		 $_POST['email'] = $email;
		 $_POST['phone'] = '';
		 $_POST['skype'] = '';
		 if (function_exists('webinarexpress_add_lead_callback'))
			webinarexpress_add_lead_callback();
}


/* Webinar Ignition */
function vidprofits_get_webinarignition_webinars($use_cache = 0) {
	if (!vidprofits_is_webignition_plugin_active()) return;
	$plugin_options = vidprofits_get_plugin_options();

	if ($use_cache) {
		return ($plugin_options['webinars']['webinarignition']['webinars']);
	}

    global $wpdb;
    $webinars = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}webinarignition" );
    $arr_webinars = array();
    if ($webinars) {
    	foreach ($webinars as $webinar) {
    		$arr_webinars[] = array('id' => $webinar->ID, 'name' => $webinar->appname, 'date' => '0000');
    	}
    }

	$plugin_options['webinars']['webinarignition']['webinars'] = $arr_webinars;
	vidprofits_update_plugin_options($plugin_options);
    return $arr_webinars;
}

function vidprofits_webignition_subscribe_user($webinar_id, $user_email, $user_name = '') {
	return vidprofits_webinarignition_add_lead_callback($webinar_id, $user_email, $user_name);
}

function vidprofits_is_webignition_plugin_active() {
	return is_plugin_active( 'webinarignition/functions.php' );
}

function vidprofits_webinarignition_add_lead_callback($webinar_id, $email, $name ) {
		 $_POST['id'] = $webinar_id;
		 $_POST['name'] = $name;
		 $_POST['email'] = $email;
		 $_POST['phone'] = '';
		 $_POST['skype'] = '';
		 if (function_exists('webinarignition_add_lead_callback'))
			webinarignition_add_lead_callback();
}
?>