<?php

/*
   SIMPLIFIED EXAMPLE BY : ANDYWITHPHP

*/

/*
STEPS..........................................
1. Crate an account on : https://sendlane.com/ and log in.
2. After sucessfull account creation, you will get
    API key And Hash Kay. (Keys will be available on Account Settings menu.)
3. Create the list by clicking on Lists menu.  
4. Now you can go through the code to get the list and add subscibers to list.
   
   - WE HAVE TO INTERACT WITH API USING CLIENT URL ONLY.   

   You should refer "http://help.sendlane.com/api-documentation/" to interact with Sendlane API.   
*/

	        // TO GET THE LISTS INTO THE DROPDOWN...

function vprofits_sendlane_get_lists( $api ) {

	               			$integrations_api_key = $api[1];   // you will get this key when account created on sendlane
	               			$integrations_hash_key = $api[2]; // you will get this key when account created on sendlane

	               			
		                    $ch = curl_init();

							curl_setopt($ch, CURLOPT_URL, "https://".$api[0].".sendlane.com/api/v1/lists" ); 
							/*
									For URL mentioned above,
									- Here you need to add you website address, which is unique.
									- You have created this address at the time of registration. See the link ( https://sendlane.com/users/signup/plan/14DAYSTRIAL ) and last field.
									- You will remember the website name.
									- So URL will be , https://yourwebsitename.sendlane.com/
									- then refer the link ( http://help.sendlane.com/api-documentation/ ), you will find that 'api/v1/lists' is appended because we need to get all the lists.
									- this URL will be changed according to the interaction with API like Create List, Get List , Create Subscriber etc.
	
							*/

							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($ch, CURLOPT_POST,           true);

							curl_setopt($ch, CURLOPT_POSTFIELDS,     "api=$integrations_api_key&hash=$integrations_hash_key"); 
							/*
								You will need pass the API key and Hash Key as a Query String for URL we mentioned.
								So that we will get Lists details in JSON.
							*/
							//curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 

							$result=curl_exec ($ch);


							$result=  json_decode($result);
							return $result;
							//var_dump($result);

							// $list =NULL;
							// foreach($result as $obj)
							// {
							//  	$name = $obj->list_name;
							//  	$list_id = $obj->list_id;
							//  	$list .="<option value='".$list_id."'>".$name."</option>";  // listed list in option os select tag

	      //                   }
	      //                   echo $list;
	    }

        // TO ADD THE SUBSCRIBER TO THE LIST.......
        

	    function vprofits_sendlane_api_subscribe( $api, $list_id, $email, $first_name, $last_name = '' ) {
	    	$integrations_api_key = $api[1];   // you will get this key when account created on sendlane
	    	$integrations_hash_key = $api[2]; // you will get this key when account created on sendlane

						$list_id        = $list_id; # put the List ID here

						// $collect_leads_username = explode(" ", $collect_leads_username); // username which has to be sent to the list EX : andy ideologi
						$collect_leads_fname    = $first_name; // andy
						$collect_leads_lname    = $last_name; // ideologi
						$collect_leads_email    = $email;  // email to be inserted to list
                        $subscriber= "$collect_leads_fname $collect_leads_lname<$collect_leads_email>";
                        

                        

						$ch = curl_init();

							// curl_setopt($ch, CURLOPT_URL, "https://".$api[0].".sendlane.com/api/v1/lists" ); 

						curl_setopt($ch, CURLOPT_URL,            "https://".$api[0].".sendlane.com/api/v1/list-subscribers-add" );  // use url of your app / account created
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_POST,           true);
						curl_setopt($ch, CURLOPT_POSTFIELDS,     "api=$integrations_api_key&hash=$integrations_hash_key&email=$subscriber&list_id=$list_id"); // Query string to be passed to the URL
						//curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 

						$result=curl_exec ($ch);


						$result=  json_decode($result);
						return $result;
	}


		/*

		You can also refer PDF "http://help.sendlane.com/wp-content/uploads/2015/11/SendlaneAPIDocument.pdf"
		
		*/

		/*
				___ HOPE THIS FILE HELPED YOU ____

				- I have implemented only two things like listing and adding subscriber.
				- Similarly you can do other stuffs by refering these examples and link ("http://help.sendlane.com/api-documentation/").
				- Still if you need any help , you can mail me on : andy.ideologi@gmail.com or can comment.
				- I will surely help you... 

				__ THANK YOU _____________________	
		*/
?>		