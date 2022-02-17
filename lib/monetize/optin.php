<?php
function cbaffmach_monetize_optin( $content, $settings ) {
	if( empty( $settings ) )
		return $content;
	$post_id = get_the_id();
	$form_style = isset( $settings['style'] ) ? intval( $settings['style'] ) : 1;
	$show_name = isset( $settings['first_name'] ) ? intval( $settings['first_name'] ) : 0;
	$name_txt = ( isset( $settings['firstname_field'] ) && !empty( $settings['firstname_field']  ) ) ? trim( sanitize_text_field( $settings['firstname_field'] ) ) : 'Your Name';
	$email_txt = ( isset( $settings['email_field'] ) && !empty( $settings['email_field']  ) ) ? trim( sanitize_text_field( $settings['email_field'] ) ) : 'Your Email';
	$submit_txt = ( isset( $settings['submit_txt'] ) && !empty( $settings['submit_txt']  ) ) ? trim( sanitize_text_field( $settings['submit_txt'] ) ) : 'Submit';

	$intro_txt = ( isset( $settings['intro_txt'] ) && !empty( $settings['intro_txt']  ) ) ? trim( sanitize_textarea_field( $settings['intro_txt'] ) ) : false;

	$thankyou_txt = ( isset( $settings['thankyou_txt'] ) && !empty( $settings['thankyou_txt']  ) ) ? trim( sanitize_textarea_field( $settings['thankyou_txt'] ) ) : false;

	$redirect_url = ( isset( $settings['redirect_url'] ) && !empty( $settings['redirect_url']  ) ) ? trim( sanitize_text_field( $settings['redirect_url'] ) ) : '';

	$optin_el = '<div class="cbaffmach-optin-form2 cbaffmach-optin-form cbaffmach-style'.$form_style.'"><div class="cbaffmach-optin-formp">';
	if( !empty( $intro_txt) )
		$optin_el .= '<p class="cbaffmach_intro_txt">'.$intro_txt.'</p>';

	$optin_el .= '<div class="cbaffmach-optin-fields">';
	if( $show_name ) {
		$optin_el .= '<span class="cbaffmach-optin-label">'.$name_txt.'</span>';
		$optin_el .= '<input type="text" style="margin-top:10px" name="cbaffmach-name-to" value="" class="cbaffmach-optin-field cbaffmach-name">';
	}
	$optin_el .= "<span class='cbaffmach-optin-label'>".$email_txt."</span>";
	$optin_el .= '<input type="email" style="margin-top:10px" name="cbaffmach-email-to" value="" class="cbaffmach-optin-field cbaffmach-email"><input type="hidden" class="cbaffmach-post-id" value="'.$post_id.'">';
	$optin_el .= '<input type="hidden" class="cbaffmach-redirect-url" value="'.$redirect_url.'" />';
	$optin_el .= '<span class="cbaffmach-thankyou" style="display:none">'.$thankyou_txt.'</span>';
	$optin_el .= '</div><input class="cbaffmach_submit_optin cbaffmach-submit-button" type="submit" name="email_submit" value="'.$submit_txt.'">';
	$optin_el .= '</div></div>';
	return cbaffmach_add_element_in_content( $optin_el, $content, $settings );

	// TO-DO, cookie con e-mail?
}

function cbaffmach_add_optin_ajax() {
	$email = isset( $_POST['email_to'] ) ? trim( sanitize_text_field( $_POST['email_to'] ) ) : false;
	if( !$email ) {
		wp_send_json( array( 'INVALIDEMAIL' => true) );
		exit();
	}

	$name = isset( $_POST['name_to'] ) ? trim( sanitize_text_field( $_POST['name_to'] ) ) : '';
	$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;

	$valid = cbaffmach_insert_optin( $email, $name, $post_id );

	$optin_settings = cbaffmach_get_settings( array( 'optin' ) );
	// var_dump($optin_settings);
	// die();
	$ar_type = $optin_settings['autoresponder'];
	if( $ar_type ) {
	// var_dump($ar_type);
		$list = isset( $optin_settings['list'] ) ? $optin_settings['list'] : 0;
		cbaffmach_signup_customer_autoresponder( $ar_type, $list, $email, $name );
	}
	/*$settings = wplpdf_get_settings( );
	if( $settings['optin']['autoresponder'] && ( $settings['optin']['autoresponder'] != 1 ) && $email && !empty( $email )  ) {
		// echo "todo ok;";
		wplpdf_signup_customer_autoresponder( $settings['optin']['autoresponder'], $settings['optin']['list'], $email, $name );
	}*/

	if( $valid ) {
		wp_send_json( array( 'SAVED' => true ) );
	}
	else
		wp_send_json( array( 'INVALIDEMAIL' => true ) );
	exit();
}

function cbaffmach_get_optin_styles() {
	return array(
		array( 'label' => 'Black', 'value' => 1),
		array( 'label' => 'Green board', 'value' => 2),
		array( 'label' => 'White Clean', 'value' => 3),
		array( 'label' => 'Black and Green', 'value' => 4),
		array( 'label' => 'Pink', 'value' => 5),
		array( 'label' => 'Postcard', 'value' => 6),
		array( 'label' => 'By airplane', 'value' => 7),
		array( 'label' => 'White stylish', 'value' => 8),
		array( 'label' => 'Black dotted', 'value' => 9),
		array( 'label' => 'Grey bg', 'value' => 10),
	);
}


function cbaffmach_leads_screen() {
	$search = isset( $_GET['stm'] ) ? $_GET['stm'] : '';
	$total_leads = cbaffmach_get_total_leads( $search );
?>
	<a href="<?php echo admin_url("admin.php?page=affiliate-machine-leads&dolexport=1");?>" class="button button-secondary" style="float:right"><i class="fa fa-download"></i> Export All</a>
	<div style="clear:both"></div>

	      <input type="text" name="stm" id="cbaffmach_stm" class="regular-text" placeholder="Search name or email..." value="<?php echo $search;?>">
	      <span class="input-group-btn">
	        <button class="button" type="button button-secondary" id="do-search-leads"><i class="fa fa-search"></i> Search</button>
	        <a class="button button-secondary" type="button" href="<?php echo admin_url('admin.php?page=affiliate-machine-leads');?>"><i class="fas fa-times"></i> Show all</a>
	      </span>


	  		<?php if( $total_leads ) { ?>
				<h4>Total Leads: <?php echo $total_leads;?></h4>
	  		<?php } ?>
			<?php cbaffmach_leads_list(); ?>

	  	<div id="cbaffmach-leads-modal" style="display:none" class="ammodal">
	  		<h3 style="text-align:center">Are you Sure?</h3>
	  		<p>This action cannot be undone.</p>
	  		<br/>
	  		<br/>
	  		<button type="button" id="cbaffmach-do-remove-lead" class="button button-primary">Remove Lead</button>
	  		<a class="button button-secondary" href="#close" rel="ammodal:close">Cancel</a>
	  	</div>

<?php
}


function cbaffmach_leads_list() {
	$page = isset( $_GET['pgl'] ) ? $_GET['pgl'] : 0;
	$search = isset( $_GET['stm'] ) ? $_GET['stm'] : false;
	$search_str = '';
	$results_per_page = 60;
	$leads = cbaffmach_get_leads( $page, $results_per_page, $search );
	$str_ret = '';
	if (!$leads) {
		$str_ret .= '<p>No Leads</p>';
	}
	else {
		$str_ret .= '<table class="widefat"><thead>';
		$str_ret .= '<tr>
			<th>#</th>
			<th scope="col">Email</th>
			<th scope="col">Name</th>
			<th scope="col">Date Added</th>
			<th scope="col">&nbsp;</th></tr></thead><tbody>';
			$i = ($page * $results_per_page) + 1;
		foreach ( $leads as $lead ) {
			$str_ret .= '<tr>
				<th scope="row" >'.$i++.'</th>
				<th scope="row" >'.$lead->email.'</th>
				<td >'.(empty( $lead->name ) ? '-' : $lead->name).'</td>
				<td >'.$lead->date_f.'</td>
				<td >
					<a class="button button-secondary cbaffmach-remove-lead" href="#" data-lead-id="'.$lead->id.'"><i class="fas fa-times"></i> Delete</a>
				</td>

			</tr>';
		}
		$str_ret .= '</tbody></table><br/><br/>';
	}

	if( $search )
		$search_str = '&stm='.$search;

	if( $page > 0 )
		$str_ret .= '<a class="button button-primary" href="'.admin_url('admin.php?page=affiliate-machine-leads'.$search_str.'&pgl='.($page -1)).'"><i class="fa fa-chevron-left"></i> Prev Page</a>  &nbsp; &nbsp;';

	if( count( $leads ) >= $results_per_page ) {
		$str_ret .= '<a class="button button-primary" href="'.admin_url('admin.php?page=affiliate-machine-leads'.$search_str.'&pgl='.++$page).'"><i class="fa fa-chevron-right"></i> Next Page</a>';
	}

	echo $str_ret;
}

function cbaffmach_remove_lead_ajax() {
	if( isset( $_POST['lead_id'] ) )
		cbaffmach_delete_lead( intval( $_POST['lead_id'] ) );
	echo 1;
	exit();
}

function cbaffmach_export_leads ( $filename = "leads.csv", $delimiter=";") {
	$leads = cbaffmach_get_all_leads();
    // open raw memory as file so no temp files needed, you might run out of memory though
    $f = fopen('php://memory', 'w');
    // loop over the input array
    foreach ($leads as $line) {
        // generate csv lines from the inner arrays
        // var_dump($line);
        fputcsv($f, $line, $delimiter);
    }
    // reset the file pointer to the start of the file
    fseek($f, 0);
    // tell the browser it's going to be a csv file
    header('Content-Type: application/csv');
    // tell the browser we want to save it instead of displaying it
    header('Content-Disposition: attachment; filename="'.$filename.'";');
    // make php send the generated csv lines to the browser
    fpassthru($f);
    exit();
}

add_action('admin_init', 'cbaffmach_check_dlleads');

function cbaffmach_check_dlleads() {
	if( isset( $_GET['dolexport'] ) && $_GET['dolexport'] ) {
		cbaffmach_export_leads( );
	}
}

/* Leads / Optins */
function cbaffmach_insert_optin( $email, $name, $post_id ) {
	global $wpdb;
	// $created_at = date( 'Y-m-d' );
	if( empty( $name ) )
		$name = '';

	$res = $wpdb->insert(
	    $wpdb->prefix.'cbaffmach_optins',
	    array(
	        'email' => $email,
	        'name' => $name,
	        'post_id' => $post_id,
	        'created_at' => current_time( 'mysql' )
	    ),
	    array(
	        '%s',
	        '%s',
	        '%d',
	        '%s'
	    )
	);
	if ( $res )
	    return $wpdb->insert_id;
	else
		return 0;
}

function cbaffmach_get_leads( $page = 0, $results_per_page = 30, $search = false ) {
	global $wpdb;
	if ($page) $page--;
	$start = $page*$results_per_page;
	if( $search ) {
		return $wpdb->get_results( "SELECT *, DATE_FORMAT(created_at,'%d %b %Y %T') as date_f FROM {$wpdb->prefix}cbaffmach_optins WHERE name like '%$search%' OR email like '%$search%' LIMIT $start,$results_per_page" );
	}

	return $wpdb->get_results( "SELECT *, DATE_FORMAT(created_at,'%d %b %Y %T') as date_f FROM {$wpdb->prefix}cbaffmach_optins LIMIT $start,$results_per_page" );
}

function cbaffmach_get_total_leads( $search = false ) {
	global $wpdb;
	if( $search )
		return $wpdb->get_var( "SELECT count(*) FROM {$wpdb->prefix}cbaffmach_optins WHERE name like '%$search%' OR email like '%$search%'" );
	return $wpdb->get_var( "SELECT count(*) FROM {$wpdb->prefix}cbaffmach_optins" );
}

function cbaffmach_get_all_leads( ) {
	global $wpdb;
	return $wpdb->get_results( "SELECT email, name, DATE_FORMAT(created_at,'%d %b %Y %T') as date_f FROM {$wpdb->prefix}cbaffmach_optins", 'ARRAY_A' );
}

function cbaffmach_delete_lead( $lead_id ) {
	global $wpdb;
	if( $lead_id )
		$wpdb->query( "DELETE FROM {$wpdb->prefix}cbaffmach_optins WHERE id = ".intval( $lead_id ) );
}

?>