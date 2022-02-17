<?php

/**
 * Copyright 2012 SendReach.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */
 
// default output for api returned data
function output($data){
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}
 
// main api class
class SRapi {
    var $api_key;
    var $api_secret;
    var $user_id;

    function __construct/*SRapi*/($api_key, $api_secret, $user_id) {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
        $this->user_id = $user_id;
    }
	
	// create new list
	function list_create($list_name,$list_redirect,$list_from_name,$list_from_email,$list_optin = "single"){
		global $api_vars;
		$query = 'http://api.sendreach.com/index.php?key='.$this->api_key.'&secret='.$this->api_secret.'&action=list_create&user_id='.$this->user_id.'&list_name='.urlencode($list_name).'&list_redirect='.$list_redirect.'&list_from_name='.urlencode($list_from_name).'&list_from_email='.$list_from_email.'&list_optin='.$list_optin.'';
		$call = file_get_contents($query);
		return $call;
	}
	
	// get lists
	function lists_view(){
		global $api_vars;
		$query = 'http://api.sendreach.com/index.php?key='.$this->api_key.'&secret='.$this->api_secret.'&action=lists_view';
		$call = file_get_contents($query);
		return $call;
	}
	
	// get list details
	function list_details($lid){
		global $api_vars;
		$query = 'http://api.sendreach.com/index.php?key='.$this->api_key.'&secret='.$this->api_secret.'&action=list_details&user_id='.$this->user_id.'&list_id='.$lid.'';
		$call = file_get_contents($query);
		return $call;
	}
	
	// get list size | number of subscribers
	function list_size($lid){
		global $api_vars;
		$query = 'http://api.sendreach.com/index.php?key='.$this->api_key.'&secret='.$this->api_secret.'&action=list_size&user_id='.$this->user_id.'&list_id='.$lid.'';
		$call = file_get_contents($query);
		return $call;
	}
	
	// get list subscribers
	function list_subscribers($lid){
		global $api_vars;
		$query = 'http://api.sendreach.com/index.php?key='.$this->api_key.'&secret='.$this->api_secret.'&action=list_subscribers&user_id='.$this->user_id.'&list_id='.$lid.'';
		$call = file_get_contents($query);
		return $call;
	}
	
	// add subscriber
    function subscriber_add($lid,$first_name,$last_name,$email,$client_ip, $photo = "", $gender = "", $dob = "", $cell = "", $address_1 = "", $address_2 = "", $city = "", $state = "", $zip = "", $country = "", $custom_1 = "", $custom_2 = "", $custom_3 = "", $custom_4 = "", $custom_5 = "", $custom_6 = "", $custom_7 = "", $custom_8 = "", $custom_9 = "", $custom_10 = ""){
		global $api_vars;
		$query = 'http://api.sendreach.com/index.php?key='.$this->api_key.'&secret='.$this->api_secret.'&action=subscriber_add&user_id='.$this->user_id.'&list_id='.$lid.'&first_name='.urlencode($first_name).'&last_name='.urlencode($last_name).'&email='.$email.'&client_ip='.$client_ip.'&photo='.urlencode($photo).'&gender='.$gender.'&dob='.urlencode($dob).'&cell='.$cell.'&address_1='.urlencode($address_1).'&address_2='.urlencode($address_2).'&city='.urlencode($city).'&state='.urlencode($state).'&zip='.$zip.'&country='.urlencode($country).'&custom_1='.urlencode($custom_1).'&custom_2='.urlencode($custom_2).'&custom_3='.urlencode($custom_3).'&custom_4='.urlencode($custom_4).'&custom_5='.urlencode($custom_5).'&custom_6='.urlencode($custom_6).'&custom_7='.urlencode($custom_7).'&custom_8='.urlencode($custom_8).'&custom_9='.urlencode($custom_9).'&custom_10='.urlencode($custom_10).'';
        // var_dump($query);
        $call = file_get_contents($query);
		return $call;
	}
	
	// get subscriber details
	function subscriber_view($sid){
		global $api_vars;
		$query = 'http://api.sendreach.com/index.php?key='.$this->api_key.'&secret='.$this->api_secret.'&action=subscriber_view&user_id='.$this->user_id.'&subscriber_hash='.$sid.'';
		$call = file_get_contents($query);
		return $call;
	}
	
	// add broadcast
	function broadcast_add($name,$subject,$message,$sms_message){
		global $api_vars;
		$name = base64_encode($name);
		$subject = base64_encode($subject);
		$message = base64_encode($message);
		$sms_message = base64_encode($sms_message);		
		$query = 'http://api.sendreach.com/index.php?key='.$this->api_key.'&secret='.$this->api_secret.'&action=broadcast_add&user_id='.$this->user_id.'&name='.$name.'&subject='.$subject.'&message='.$message.'&sms_message='.$sms_message.'';
        $call = file_get_contents($query);
		return $call;
	}
	
	// get broadcast details
	function broadcast_view($bid){
		global $api_vars;
		$query = 'http://api.sendreach.com/index.php?key='.$this->api_key.'&secret='.$this->api_secret.'&action=broadcast_view&user_id='.$this->user_id.'&broadcast_id='.$bid.'';
		$call = file_get_contents($query);
		return $call;
	}
	
	// send a broadcast
	function broadcast_send($bid,$btype,$lid){
		global $api_vars;
		$query = 'http://api.sendreach.com/index.php?key='.$this->api_key.'&secret='.$this->api_secret.'&action=broadcast_send&user_id='.$this->user_id.'&broadcast_id='.$bid.'&broadcast_type='.$btype.'&list_id='.$lid.'';
		$call = file_get_contents($query);
		return $call;
	}

    // get broadcasts
    function broadcasts_view(){
    	global $api_vars;
    	$query = 'http://api.sendreach.com/index.php?key='.$this->api_key.'&secret='.$this->api_secret.'&action=broadcasts_view&user_id='.$this->user_id.'';
    	$call = file_get_contents($query);
    	return $call;
    }
}

?>