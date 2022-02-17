<?php

function cbaffmach_traffic_ili( $post_id, $traffic, $settings ) {
	// if( empty( $settings ) )
	// 	return false;
	$res = cbaffmach_ili_submit_links_indexing( get_permalink( $post_id ) );
  if( $res )
    cbaffmach_log_traffic( $post_id, CBAFFMACH_TRAFFIC_ILI );
}

function cbaffmach_ili_submit_links_indexing( $url ) {

$ili_settings = cbaffmach_get_settings( array( 'traffic', 'ili' ) );

if ( ! isset( $ili_settings['apikey'] ) || empty( $ili_settings['apikey'] ) ) {
  cbaffmach_debug( 'Error with Instant Link Indexer Credentials', 'error' );
	return false;
}

  $campaign = strtoupper( str_replace( ' ', '', get_bloginfo('name') ) );
  // All Links to be sent are hold in an array for example
  // build the POST query string and join the URLs array with | (single pipe)
  $qstring='apikey='.$ili_settings['apikey'].'&cmd=submit&campaign='.$campaign.'&urls='.urlencode($url);

  // Do the API Request using CURL functions
  $ch = curl_init();
  curl_setopt($ch,CURLOPT_POST,1);
  curl_setopt($ch,CURLOPT_URL,'http://www.instantlinkindexer.com/api.php');
  curl_setopt($ch,CURLOPT_HEADER,0);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch,CURLOPT_TIMEOUT,40);
  curl_setopt($ch,CURLOPT_POSTFIELDS,$qstring);
  curl_exec($ch);
  curl_close($ch);
  cbaffmach_debug( 'Traffic Module: Submitted to Instant Link Indexer', 'notice' );
  return true;
}


function cbaffmach_traffic_bli( $post_id, $traffic, $settings ) {
  // if( empty( $settings ) )
  //  return false;
  $res = cbaffmach_bli_submit_links_indexing( get_permalink( $post_id ) );
  if( $res )
    cbaffmach_log_traffic( $post_id, CBAFFMACH_TRAFFIC_BLI );
}

function cbaffmach_bli_submit_links_indexing( $url ) {

$bli_settings = cbaffmach_get_settings( array( 'traffic', 'bli' ) );

if ( ! isset( $bli_settings['apikey'] ) || empty( $bli_settings['apikey'] ) ) {
  cbaffmach_debug( 'Error with Backlinks Indexer Credentials', 'error' );
  return false;
}

  $campaign = strtoupper( str_replace( ' ', '', get_bloginfo('name') ) );
  // All Links to be sent are hold in an array for example
  // build the POST query string and join the URLs array with | (single pipe)
  //$qstring='apikey='.$bli_settings['apikey'].'&cmd=submit&campaign='.$campaign.'&urls='.urlencode($url);

  $api_url = 'https://backlinksindexer.com/api.php?key=' . $bli_settings['apikey'] . '&urls=' . urlencode($url).'&allow_duplicate=yes'; 

  // Do the API Request using CURL functions
  $ch = curl_init();
  curl_setopt($ch,CURLOPT_POST,1);
  curl_setopt($ch,CURLOPT_URL,$api_url);
  curl_setopt($ch,CURLOPT_HEADER,0);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch,CURLOPT_TIMEOUT,40);
  // curl_setopt($ch,CURLOPT_POSTFIELDS,$qstring);
  curl_exec($ch);
  curl_close($ch);
  cbaffmach_debug( 'Traffic Module: Submitted to Backlinks Indexer', 'notice' );
  return true;
}
?>