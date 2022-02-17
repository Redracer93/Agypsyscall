<?php
// require_once CBAFFMACH_DIR.'/lib/libs/pinterest/nxs-api/nxs-api.php';
// require_once CBAFFMACH_DIR.'/lib/libs/pinterest/nxs-api/nxs-http.php';
// require_once CBAFFMACH_DIR.'/lib/libs/pinterest/inc/nxs-functions.php';

function cbaffmach_traffic_pinterest( $post_id, $traffic, $settings ) {
	$board_id = $settings->board;
	if( !$board_id ) {
		cbaffmach_debug( 'Error with Pinterest: no Bord Selected', 'error' );
		return false;
	}
	$res = cbaffmach_share_pinterest( $board_id, cbaffmach_get_the_excerpt( $post_id ), get_permalink( $post_id ), cbaffmach_get_thumbnail( $post_id ) );
	if( $res )
		cbaffmach_log_traffic( $post_id, CBAFFMACH_TRAFFIC_PINTEREST );
}

function cbaffmach_share_pinterest( $board_id, $text, $post_url, $image_url = false ) {

	$pinterest_settings = cbaffmach_get_settings( array( 'social', 'pinterest' ) );

	if ( ! isset( $pinterest_settings['email'] ) || empty( $pinterest_settings['email'] ) || ! isset( $pinterest_settings['pass'] ) || empty( $pinterest_settings['pass'] ) ) {
		cbaffmach_debug( 'Error with Pinterest Credentials', 'error' );
		return false;
	}
	cbaffmach_include_nextgen();

	// var_dump($pinterest_settings);
	$email = $pinterest_settings['email'];
	$password = $pinterest_settings['pass'];

	$nxs_gCookiesArr = cbaffmach_get_saved_pinterest_login();

	$nt         = new nxsAPI_PN();
	$nt->ck     = $nxs_gCookiesArr;
	$loginError = $nt->connect($email, $password);

	if ( !$loginError ) {
	  cbaffmach_save_pinterest_login($nt->ck);
	  $return = $nt->post($text, $image_url, $post_url, $board_id );
	  if( $return ) {
		cbaffmach_debug( 'Shared on Pinterest', 'notice' );
		return true;
		}
	}
	else
		cbaffmach_debug( 'Error connecting with Pinterest', 'error' );
	return false;
}

function cbaffmach_get_pinterest_boards( $for_select = false ) {
	// require_once CBAFFMACH_DIR.'/lib/libs/pinterest/PinnerException.php';
	// require_once CBAFFMACH_DIR.'/lib/libs/pinterest/Pinner.php';
	if ( false === ( $boards = get_transient( 'cbaffmach_pinterest_boards' ) ) ) {
	$pinterest_settings = cbaffmach_get_settings( array( 'social', 'pinterest' ) );

	// var_dump($settings);
	if ( ! isset( $pinterest_settings['email'] ) || empty( $pinterest_settings['email'] ) || ! isset( $pinterest_settings['pass'] ) || empty( $pinterest_settings['pass'] ) )
		return false;
// function cbaffmach_GetPinterestBoard( $email, $password ) {

	$email = $pinterest_settings['email'];
	$password = $pinterest_settings['pass'];
	  $nxs_gCookiesArr = cbaffmach_get_saved_pinterest_login();

	  $nt         = new nxsAPI_PN();
	  $nt->ck     = $nxs_gCookiesArr;
	  $loginError = $nt->connect($email, $password);

	  if (!$loginError) {
	    cbaffmach_save_pinterest_login($nt->ck);
	    $boards = cbaffmach_ParseBoards( $nt->getBoards(), $for_select );
	    set_transient( 'cbaffmach_pinterest_boards', $boards, 12 * HOUR_IN_SECONDS );
	    return $boards;
	  }
	  else {
	    return $loginError;
	  }
	}
	return $boards;
}

function cbaffmach_get_saved_pinterest_login() {
  $pnTempLogin = get_option( 'cbaffmach_pint_temp_login' );
  // $pnTempLogin = get_post_meta('111111113', 'cbaffmach_pint_temp_login', true);
  if (empty($pnTempLogin)) return null;
  return unserialize($pnTempLogin);
}

function cbaffmach_save_pinterest_login($obj) {
	update_option( 'cbaffmach_pint_temp_login', serialize($obj) );
  // update_post_meta('111111113', 'cbaffmach_pint_temp_login', serialize($obj));
}

function cbaffmach_ParseBoards( $str, $for_select ) {
  $matches = array();
  preg_match_all("/<option[^>]*value=\"(\d+)\">([^<]*)<\/option>/", $str, $matches);

  $out = array();

  if (!empty($matches)) {
    foreach($matches[1] as $key => $val) {
    	if( $for_select )
    		$out[] = array( 'value' => $val, 'label' => $matches[2][$key]);
    	else
      		$out[$val] = $matches[2][$key];
    }
  }

  return $out;
}

?>