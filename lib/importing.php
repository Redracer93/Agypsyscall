<?php
// define( 'CBAFFMACH_FEED_URL', 'http://localhost/wordpress/feed/' );
define( 'CBAFFMACH_FEED_URL', 'https://reviews.cbautomator.com/feed/' );
// define( 'CBAFFMACH_REC_FEED_URL', 'https://reviews2.wpaffiliatemachine.com/feed/' );
define( 'CBAFFMACH_REC_FEED_URL', 'https://cbautomator.com/recurrings/feed.php' );
define( 'CBAFFMACH_MAX_RUNS', '1' ); //rmi353, dejar a 10


/*  CRON JOB */
add_action( 'cbaffmach_cron_job', 'cbaffmach_maybe_import' );
add_action( 'init', 'cbaffmach_maybe_import_check' );


function cbaffmach_update_feed_quickly( $seconds ) {
  return 5;
}

function cbaffmach_dont_strip_tags( &$rss ) {
	if( !is_wp_error( $rss ) )
		@$rss->strip_htmltags( false );
}

function cbaffmach_maybe_import_check() {
	if( isset( $_REQUEST['cbaffmach_import'] ) )
		cbaffmach_maybe_import( false, false );
	if( isset( $_REQUEST['cbaffmach_import_recurring'] ) )
		cbaffmach_maybe_import( true, false );
}

function cbaffmach_maybe_import( $recurring = false, $is_cron = true ) {
	$importing_settings =  cbaffmach_get_settings( );
	if( empty( $importing_settings ) )
		return;
// die('cron');
	if( $is_cron ) {
		// check if every x days
		$settings = isset( $settings['import'] ) ? $settings['import'] : false;
		$every = isset( $settings['every'] ) ? intval( $settings['every'] ) : 3;
		$last_imported = get_option( 'cbaffmach_last_import', 0 );
		if( $every && $last_imported ) {
			$now = date( 'Y-m-d' );
			$datetime1 = new DateTime( $last_imported );
			$datetime2 = new DateTime( $now );
			$difference = $datetime1->diff($datetime2);
			if( $difference->d < $every )
				return;
		}
	}
	$posts = cbaffmach_search_posts( $recurring );
	// var_dump($posts);die();
	$done_importing = 0;
	if( !empty( $posts ) ) {
		// return;
		cbaffmach_import_posts( $posts, $recurring );
		update_option( 'cbaffmach_last_import', date( 'Y-m-d' ) );
		$done_importing = 1;
	}

	if( $is_cron && cbaffmach_is_recurring() ) {
		$posts = cbaffmach_search_posts( true );
		if( !empty( $posts ) ) {
			// return;
			cbaffmach_import_posts( $posts, true );
			if( !$done_importing )
				update_option( 'cbaffmach_last_import', date( 'Y-m-d' ) );
		}
	}
}


function cbaffmach_save_iframe( $allowedposttags ) {
	// Here add tags and attributes you want to allow
	$allowedposttags['iframe']=array(
	'align' => true,
	'width' => true,
	'height' => true,
	'frameborder' => true,
	'name' => true,
	'src' => true,
	'id' => true,
	'class' => true,
	'style' => true,
	'scrolling' => true,
	'marginwidth' => true,
	'marginheight' => true,
	'allowfullscreen' => true,
	'mozallowfullscreen' => true,
	'webkitallowfullscreen' => true,
	);
	return $allowedposttags;
}

function cbaffmach_search_posts( $recurring = false ) {
	if( $recurring && !cbaffmach_is_recurring() )
		return false;
	$importing_settings =  cbaffmach_get_import_settings( );
	$max_posts = isset( $importing_settings['per_day'] ) && !empty( $importing_settings['per_day'] ) ? $importing_settings['per_day'] : 1;

	// $importing_settings =  cbaffmach_get_settings( );
	// $max_posts = isset( $importing_settings['per_day'] ) && !empty( $importing_settings['per_day'] ) ? $importing_settings['per_day'] : 1;
	// if( $this->quick )
	if( $recurring ) {
		$email = cbaffmach_settings_recurring_email();
		if( empty( $email ) )
		    return false;
		$feed_url = CBAFFMACH_REC_FEED_URL.'?email='.$email;
	}
	else
		$feed_url = CBAFFMACH_FEED_URL;

	$myposts = array();
	for( $i = 1; $i <= CBAFFMACH_MAX_RUNS; $i++) {
		$url = add_query_arg( 'paged', $i, $feed_url );
		add_filter( 'wp_kses_allowed_html', 'cbaffmach_save_iframe',1,1 );
		add_action( 'wp_feed_options' , 'cbaffmach_dont_strip_tags' );

		add_filter( 'wp_feed_cache_transient_lifetime' , 'cbaffmach_update_feed_quickly' );
// var_dump($url);

		$rss = fetch_feed( $url );
		// var_dump($rss);
		if ( is_wp_error( $rss ) ) {
			if( !empty( $myposts ) )
				return $myposts;
			return false;
		}
		// var_dump($rss);
		remove_filter( 'wp_feed_cache_transient_lifetime', 'cbaffmach_update_feed_quickly' );
		// Remove these tags from the list
		$strip_htmltags = $rss->strip_htmltags;
		array_splice($strip_htmltags, array_search('iframe', $strip_htmltags), 1);
		array_splice($strip_htmltags, array_search('param', $strip_htmltags), 1);
		array_splice($strip_htmltags, array_search('embed', $strip_htmltags), 1);
		// var_dump($strip_htmltags);die();
		if( !is_wp_error( $rss ) )
			@$rss->strip_htmltags( $strip_htmltags );


		$maxitems = $rss->get_item_quantity( 50 );
		// if( $this->quick )

	    if ( is_wp_error($rss) ) {
			/*if ( is_admin() || current_user_can('manage_options') ) {
			   echo '<p>';
			   printf(__('<strong>RSS Error</strong>: %s'), $rss->get_error_message());
			   echo '</p>';
			}*/
			if( !empty( $myposts ) )
				return $myposts;
	    	return false;
		}

		if ( !$rss->get_item_quantity() ) {
		     // echo '<p>Apparently, there are no updates to show!</p>';
		     $rss->__destruct();
		     unset($rss);
		     if( !empty( $myposts ) )
		     	return $myposts;
		     return false;
		}

		// if ( !isset($items) )
			foreach ( $rss->get_items() as $item ) {
				$publisher = '';
				$site_link = '';
				$link = '';
				$content = '';
				$date = '';
				$link = esc_url( strip_tags( $item->get_link() ) );
				// $link = add_query_arg( 'tid', 'dash_'.$this->plugin_name, $link );
				$title = esc_html( $item->get_title() );
				// var_dump($item);
				// $approval = $item->get_item_tags( '', 'autors_approval' );
				// $approval2 = $approval[0]['data'];

				$prodname = $item->get_item_tags( '', 'autors_prodname' );
				$prodname2 = $prodname[0]['data'];

				$productid = $item->get_item_tags( '', 'autors_productid' );
				$productid2 = $productid[0]['data'];
				if( empty( $productid2 ) )
					continue;

				$network = $item->get_item_tags( '', 'autors_network' );
				$network2 = $network[0]['data'];

				$afflink = $item->get_item_tags( '', 'autors_afflink' );
				$afflink2 = $afflink[0]['data'];

				$postid = $item->get_item_tags( '', 'post_id' );
				$postid2 = $postid[0]['data'];

				$thumbnail = $item->get_item_tags( '', 'featured_image' );
				$thumbnail2 = $thumbnail[0]['data'];

				$ptags = $item->get_item_tags( '', 'post_tags' );
				$ptags2 = $ptags[0]['data'];
// var_dump($tags2);die();
				$content = $item->get_content();

				// if( strpos( $title, 'TrafficZion' ) !== false ) {
				// 	print_r($content);die();
				// }
				if( empty( $prodname2 ) || empty( $content ) ) {
					// echo "error";
					continue;
				}

				if( cbaffmach_already_imported( $productid2, $myposts ) )
					continue;
				// else {
				// 	echo "aa";
				// }
				$myposts[] = array(
					'title' => $title,
					'content' => $content,
					'product_name' => $prodname2,
					// 'approval_link' => $approval2,
					'prodid' => $postid2,
					'productid' => $productid2,
					'network' => $network2,
					'afflink' => $afflink2,
					'tags' => $ptags2,
					'thumbnail' => $thumbnail2
				);
				// var_dump($title);
				// var_dump($postid2);
				if( count( $myposts ) >= $max_posts )
					return $myposts;
			}
	}
	return $myposts;
}



function cbaffmach_import_posts( $posts, $recurring = false ) {
	if( !empty( $posts ) ) {
		foreach( $posts as $post ) {
			cbaffmach_import_post( $post, $recurring );
		}
	}
}

function cbaffmach_import_post( $post, $recurring = false ) {
	$importing_settings =  cbaffmach_get_import_settings( );
	$max_posts = isset( $importing_settings['per_day'] ) && !empty( $importing_settings['per_day'] ) ? $importing_settings['per_day'] : 1;
	$post_status = isset( $importing_settings['post_status'] ) && !empty( $importing_settings['post_status'] ) ? $importing_settings['post_status'] : 'publish';
	$spin = isset( $importing_settings['spin'] ) && !empty( $importing_settings['spin'] ) ? $importing_settings['spin'] : 0;
	if( $spin )
		$post['content'] = cbaffmach_spin_text( $post['content'] );
// var_dump($max_posts);die();

	// $post_content = cbaffmach_jvzoo_replace ( cbaffmach_clickbank_replace ( $post['content'], $importing_settings ), $importing_settings );
	$post_content = cbaffmach_clickbank_replace ( $post['content'], $importing_settings );
	$my_post = array(
	  'post_title'    => wp_strip_all_tags( $post['title'] ),
	  'post_content'  => $post_content,
	  'post_status'   => $post_status
	);

	if( isset( $importing_settings['cat'] ) && !empty( $importing_settings['cat'] ) ) {
		$my_post['post_category'] = array( $importing_settings['cat'] );
	}

	$post_id =  wp_insert_post( $my_post );
	if( $post_id ) {
		add_post_meta( $post_id, '_cbaffmach_post', 1, true ); //  rmi353, campaign id!
		add_post_meta( $post_id, '_cbaffmach_prodid', $post['prodid'], true ); //  rmi353, campaign id!
		add_post_meta( $post_id, '_cbaffmach_prodname', $post['product_name'], true ); //  rmi353, campaign id!
		// add_post_meta( $post_id, '_cbaffmach_approval', $post['approval_link'], true ); //  rmi353, campaign id!
		add_post_meta( $post_id, '_cbaffmach_productid', $post['productid'], true ); //  rmi353, campaign id!
		add_post_meta( $post_id, '_cbaffmach_network', $post['network'], true ); //  rmi353, campaign id!
		add_post_meta( $post_id, '_cbaffmach_afflink', $post['afflink'], true ); //  rmi353, campaign id!
		if( $recurring )
			add_post_meta( $post_id, '_cbaffmach_rec', 1, true ); //  rmi353, campaign id!
		if( isset($importing_settings['featured']) && $importing_settings['featured'] && !empty( $post['thumbnail'] ) )
			cbaffmach_import_media( $post['thumbnail'], $post_id );
		if( isset( $importing_settings['tags']) && $importing_settings['tags'] )
			cbaffmach_import_tags( $post_id, $post['tags'] );
		if( $post_status == 'publish' )
			cbaffmach_post_traffic( $post_id, $post['tags'] );
    	/*if ( defined( 'CBAFFMACH_CRON_DEBUG' ) ) {
    		// print stuff
    		echo '<p>Creating post: <b><a href="'.get_permalink( $post_id ).'">'.wp_strip_all_tags( $title ).'</a></b> from <b>'.$source_name.'</b><p>';
    	}*/

	}
}

function cbaffmach_import_media( $file_url, $post_id ) {
    if( !$post_id ) {
        return false;
    }
    $filename = basename( $file_url );
    $pos = strpos( $filename, '?' );
    if( $pos !== false )
    	$filename = substr( $filename, 0, $pos );

    $upload_file = wp_upload_bits( $filename, null, file_get_contents( $file_url ) );
// die();
    if (!$upload_file['error']) {
        $wp_filetype = wp_check_filetype( $filename, null );
        // var_dump($wp_filetype);
        $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_parent' => $post_id,
                'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );
        $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $post_id );

        if (!is_wp_error($attachment_id)) {
            require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
            $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
            wp_update_attachment_metadata( $attachment_id, $attachment_data );
            update_post_meta( $post_id, '_thumbnail_id', $attachment_id );
        } else {
            return false;
        }
    } else {
        return false;
    }
    return true;
}

function cbaffmach_import_tags( $post_id, $tags ) {
	if( !$post_id ) {
	    return false;
	}
	wp_set_post_tags( $post_id, $tags );
	// var_dump($tags);die();
}

function cbaffmach_already_imported( $content_id, $myposts = array() ) {
	$rd_args = array(
		'post_type' => 'post',
		'post_status' => 'any',
		'meta_query' => array(
			array(
				'key' => '_cbaffmach_productid',
				'value' => $content_id
			),
		)
	);
	$rd_query = new WP_Query( $rd_args );
	return $rd_query->found_posts ? 1 : 0 ;
}

// replacements

function cbaffmach_jvzoo_replace( $content, $importing_settings ) {
    // $affiliate_id = wpstore_jvzoo_get_user_aff_id();
	$affiliate_id = $importing_settings['jvzooid'];
    if( $affiliate_id ) {
        $content = preg_replace('#\/\/jvz(\d+).com\/c\/(\d+)\/(\d+)#', '//jvz$1.com/c/'.$affiliate_id.'/$3', $content );
    }
    return $content;
}

function cbaffmach_clickbank_replace( $content, $importing_settings ) {
	$affiliate_id = $importing_settings['clickbankid'];
    if( $affiliate_id ) {
        // $content = preg_replace('#\/\/(.*?)\.(.*?).hop.clickbank.net#', '//'.$affiliate_id.'.$2.hop.clickbank.net', $content );
	    $content = preg_replace('#href="http:\/\/(.*?)\.(.*?).hop.clickbank.net#', 'href="http://'.$affiliate_id.'.$2.hop.clickbank.net', $content );
	    $content = preg_replace('#href="https:\/\/(.*?)\.(.*?).hop.clickbank.net#', 'href="https://'.$affiliate_id.'.$2.hop.clickbank.net', $content );
	}

    return $content;
}

function cbaffmach_wplus_replace( $content, $importing_settings ) {
	return $content;
	$affiliate_id = $importing_settings['wplusid'];
    if( $affiliate_id )
        $content = preg_replace('#\/\/(.*?)\.(.*?).hop.clickbank.net#', '//'.$affiliate_id.'.$2.hop.clickbank.net', $content );
    return $content;
}

add_action( 'the_content', 'cbaffmach_replace_content' );

function cbaffmach_replace_content( $content ) {
	global $post;
	$post_id = isset( $post->ID ) ? $post->ID : 0 ;
	if( empty( $post_id ) )
		return $content;
	$prod_id = get_post_meta( $post_id, '_cbaffmach_prodid', true );
	if( empty( $prod_id ) )
		return $content;
	$content = cbaffmach_replace_link( $content );
	return $content;
}


function cbaffmach_replace_link( $content ) {
	$importing_settings =  cbaffmach_get_import_settings( );
	// $content = cbaffmach_jvzoo_replace( $content, $importing_settings );
	$content = cbaffmach_clickbank_replace( $content, $importing_settings );
	// $content = cbaffmach_wplus_replace( $content, $importing_settings );
	return $content;
}

/* Spinners */

function cbaffmach_spin_text( $text, $spinner = 1 ) {
	$spinners_settings = cbaffmach_get_settings( array( 'spinners' ) );
	// $spinners_settings = isset( $settings['spinners'] ) ? $settings['spinners'] : false;
	$spinner = isset( $spinners_settings['spinner'] ) ? trim( $spinners_settings['spinner'] ) : 1;
// die('sss');
	if( $spinner == 2 )
		return cbaffmach_spin_text_tbs( $text );
	else if( $spinner == 3 )
		return cbaffmach_spin_text_wordai( $text );
	else if( $spinner == 4 )
		return cbaffmach_spin_text_schief( $text );
	return cbaffmach_spin_text_srewiter( $text );
}

function cbaffmach_spin_text_srewiter( $text ) {
	require_once CBAFFMACH_DIR.'/lib/libs/spinners/SpinRewriterAPI.php';

	$sr_settings = cbaffmach_get_settings( array( 'spinners', 'spinrewriter' ) );

	// var_dump($sr_settings);
	if ( ! isset( $sr_settings['email'] ) || empty( $sr_settings['email'] ) || ! isset( $sr_settings['apikey'] ) || empty( $sr_settings['apikey'] ) )
		return $text;
// die('aa');
/*	$email_address = "raulmellado@gmail.com";			// your Spin Rewriter email address goes here
	$api_key = "e53de7e#d903feb_2d3542b?e5acb71";	// your unique Spin Rewriter API key goes here
*/
	// Include the Spin Rewriter API SDK.

	// Authenticate yourself.
	$spinrewriter_api = new SpinRewriterAPI( $sr_settings['email'], $sr_settings['apikey'] );

	/*
	 * (optional) Set a list of protected terms.
	 * You can use one of the following formats:
	 * - protected terms are separated by the '\n' (newline) character
	 * - protected terms are separated by commas (comma-separated list)
	 * - protected terms are stored in a PHP array
	 */
	// $protected_terms = "John, Douglas Adams, then";
	// $spinrewriter_api->setProtectedTerms($protected_terms);

	// (optional) Set whether the One-Click Rewrite process automatically protects Capitalized Words outside the article's title.
	$spinrewriter_api->setAutoProtectedTerms(false);

	// (optional) Set the confidence level of the One-Click Rewrite process.
	$spinrewriter_api->setConfidenceLevel("medium");

	// (optional) Set whether the One-Click Rewrite process uses nested spinning syntax (multi-level spinning) or not.
	$spinrewriter_api->setNestedSpintax(false);

	// (optional) Set whether Spin Rewriter rewrites complete sentences on its own.
	$spinrewriter_api->setAutoSentences(false);

	// (optional) Set whether Spin Rewriter rewrites entire paragraphs on its own.
	$spinrewriter_api->setAutoParagraphs(false);

	// (optional) Set whether Spin Rewriter writes additional paragraphs on its own.
	$spinrewriter_api->setAutoNewParagraphs(false);

	// (optional) Set whether Spin Rewriter changes the entire structure of phrases and sentences.
	$spinrewriter_api->setAutoSentenceTrees(false);

	// (optional) Sets whether Spin Rewriter should only use synonyms (where available) when generating spun text.
	$spinrewriter_api->setUseOnlySynonyms(false);

	// (optional) Sets whether Spin Rewriter should intelligently randomize the order of paragraphs and lists when generating spun text.
	$spinrewriter_api->setReorderParagraphs(false);

	// Make the actual API request and save the response as a native PHP array.
	// $text = "John will book a room. Then he will read a book by Douglas Adams.";
	// print_r($text);
	$text = str_replace( array( '<p>', '</p>'), '', $text );
	$text = str_replace( "\n", "\n\n", $text );
	// print_r($text);
	$text = str_replace(array("< img ", "< a ", "/ >", "=\" "), array("<img ", "<a ", "/>", "=\""), $text);
	$text = trim( $text );
	$api_response = $spinrewriter_api->getUniqueVariation($text);
// var_dump($api_response);
// die();
	$ret = $api_response['response'];
	// print_r($text);
	// echo "<br/>";
	// echo "<br/>";
	// print_r($ret);

	// $ret = nl2br( $ret );
	$ret = str_replace( '< img', '<img', $ret );
	$ret = str_replace( '< a', '<a', $ret );
	// print_r($ret);
// die();
	if( isset( $api_response ['status'] ) && ( $api_response ['status'] == 'OK' ) )
		return $api_response['response'];
	else
		return $text;
}

function cbaffmach_spin_text_wordai( $text ) {
	$sr_settings = cbaffmach_get_settings( array( 'spinners', 'wordai' ) );

	// var_dump($sr_settings);
	if ( ! isset( $sr_settings['username'] ) || empty( $sr_settings['username'] ) || ! isset( $sr_settings['password'] ) || empty( $sr_settings['password'] ) )
		return $text;

	//'Regular', 'Unique', 'Very Unique', 'Readable', or 'Very Readable

	$quality = 'Readable';
	$email = $sr_settings['username'];
	$pass = $sr_settings['password'];
	$text = urlencode($text);

     $ch = curl_init('http://wordai.com/users/turing-api.php');

     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt ($ch, CURLOPT_POST, 1);
     curl_setopt ($ch, CURLOPT_POSTFIELDS, "s=$text&quality=$quality&email=$email&pass=$pass&output=json&returnspin=true&sentence=on&paragraph=on");
     curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds
     $result = curl_exec($ch);

     curl_close ($ch);
	// var_dump($result);
	$res = json_decode( $result );
	// var_dump($res);
	if( isset( $res->status ) && $res->status == 'Success' )
		return $res->text;
	return $text;
}

function cbaffmach_spin_text_schief( $text ) {
	$sr_settings = cbaffmach_get_settings( array( 'spinners', 'schief' ) );

	// var_dump($sr_settings);
	if ( ! isset( $sr_settings['username'] ) || empty( $sr_settings['username'] ) || ! isset( $sr_settings['password'] ) || empty( $sr_settings['password'] ) )
		return $text;

	$quality = 'Readable';
	$email = $sr_settings['username'];
	$pass = $sr_settings['password'];
	// $text = urlencode($text);
	$url = "http://api.spinnerchief.com:8000/apikey=72f539e6f62b411a9&spintype=1&username=$email&password=$pass&protecthtml=1&original=0";
     $ch = curl_init( $url );
     // var_dump($url);
$text = base64_encode( $text );
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt ($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $text);
     curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds
     $result = curl_exec($ch);

     curl_close ($ch);
     // var_dump( $result );
     $res = base64_decode( $result );
     var_dump( $res );
     die();
     if( $res && !empty( $res ) )
     	return $res;
	// var_dump($result);
	// $res = base64_decode( $result );
	// var_dump($res);
	/*if( isset( $res->status ) && $res->status == 'Success' )
		return $res->text;*/
	return $text;
}

function cbaffmach_tbs_curl_post($url, $data, &$info){

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, curl_postData($data));
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_REFERER, $url);
  $html = trim(curl_exec($ch));
  curl_close($ch);

  return $html;
}

function cbaffmach_tbs_curl_postData($data){

  $fdata = "";
  foreach($data as $key => $val){
    $fdata .= "$key=" . urlencode($val) . "&";
  }

  return $fdata;

}

function cbaffmach_spin_text_tbs( $text ) {

	$tbs_settings = cbaffmach_get_settings( array( 'spinners', 'tbs' ) );

	// var_dump($settings);
	if ( ! isset( $tbs_settings['username'] ) || empty( $tbs_settings['username'] ) || ! isset( $tbs_settings['password'] ) || empty( $tbs_settings['password'] ) )
		return $text;

	if( empty( $email_address ) || empty( $api_key ) )
		return $text;

	$url = 'http://thebestspinner.com/api.php';

	#$testmethod = 'identifySynonyms';
	$testmethod = 'replaceEveryonesFavorites';


	# Build the data array for authenticating.

	$data = array();
	$data['action'] = 'authenticate';
	$data['format'] = 'php'; # You can also specify 'xml' as the format.

	# The user credentials should change for each UAW user with a TBS account.

	$data['username'] = $tbs_settings['username'];
	$data['password'] = $tbs_settings['password'];

	# Authenticate and get back the session id.
	# You only need to authenticate once per session.
	# A session is good for 24 hours.
	$output = unserialize(cbaffmach_tbs_curl_post($url, $data, $info));

	if($output['success']=='true'){
	  # Success.
	  $session = $output['session'];
	  
	  # Build the data array for the example.
	  $data = array();
	  $data['session'] = $session;
	  $data['format'] = 'php'; # You can also specify 'xml' as the format.
	  $data['text'] = $text;
	  $data['action'] = /*$testmethod*/'rewriteText';
	  $data['maxsyns'] = '3'; # The number of synonyms per term.
	  
	  /*if($testmethod=='replaceEveryonesFavorites'){
	    # Add a quality score for this method.
	    $data['quality'] = '1';
	  }*/

	  # Post to API and get back results.
	  $output = cbaffmach_tbs_curl_post($url, $data, $info);
	  $output = unserialize($output);
	  if($output['success']=='true')
	  	return $output['output'];
  		return $text;
	}
	else{
	  # There were errors.
		return $text;
	}
}

?>