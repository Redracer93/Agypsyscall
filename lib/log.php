<?php
/* Log */

function cbaffmach_screen_log() {
    if (! current_user_can ( 'manage_options' ))
        wp_die ( 'You don\'t have access to this page.' );
    if (! user_can_access_admin_page ())
        wp_die ( __ ( 'You do not have sufficient permissions to access this page', 'wp-auto-content' ) );
        ?>
    <div class="wrap">
    	<?php cbaffmach_header();?>
        <form method="POST" action=""><button type="submit" class="button button-secondary" style="float:right;margin-top:8px">Force Import</button><input type="hidden" name="cbaffmach_import" value="1"></form><h1>Imported Reviews </h1>
        <br/>
        <?php cbaffmach_print_log(); ?>
    </div>
    <div id="amshare-modal" class="ammodal" style="display:none">
    	<h3>Share link on: </h3>
    	<div style="padding-left:18px;">
    	<?php cbaffmach_share_list(); ?>
    	</div>
    	<br/>
    	<button type="button" class="button button-primary" id="btn-share-aff-link"><i class="fas fa-share"></i> Share</button>
    </div>
    <div id="amlink-modal" class="ammodal" style="display:none">
    	<h3>Enter your custom affiliate link: </h3>
    	<input type="text" id="custom-aff-link" style="width:100%;display:block;margin-bottom:10px">
    	<button type="button" class="button button-primary" id="btn-save-aff-link">Save</button>
    </div>
    <div id="amvideo-modal" class="ammodal" style="display:none">
    	<h3>Create a new video for your review: </h3>
    	<p>Video creation can take between 5 and 30mins based on video length, so after you click on "Create Video" please come back to this page in a few minutes to download your video.</p>
    	<button type="button" class="button button-primary" id="btn-do-create-video"><i class="fas fa-check"></i> Create Video</button>
    </div>
        <?php
}

function cbaffmach_print_log( $recurring = false ) {
	$campaign_id = isset( $_GET['cid'] ) ? intval( $_GET['cid'] ) : 0;
	$source_id = isset( $_GET['sid'] ) ? intval( $_GET['sid'] ) : 0;
	$page = isset( $_GET['pagenum'] ) ? intval( $_GET['pagenum'] ) : 1;
	$per_page = isset( $_GET['per_page'] ) ? intval( $_GET['per_page'] ) : 50;

	$args = array(
		'post_type' => 'post',
		'post_status' => 'any',
		'meta_query' => array(
			array(
				'key' => '_cbaffmach_post'
			),
		),
		'posts_per_page' => -1
	);

	if( $recurring ) {
		$meta_query = array(
			'relation' => 'AND',
			array(
				'key'     => '_cbaffmach_post',
				// 'value'   => 'Value I am looking for 2',
				// 'compare' => '='
			),
			array(
				'key'     => '_cbaffmach_rec',
				// 'compare' => 'EXISTS',
				// 'value'   => '',
			)
		);
	}
	else {
		$meta_query = array(
			'relation' => 'AND',
			array(
				'key'     => '_cbaffmach_post',
				// 'compare' => '='
			),
			array(
				'key'     => '_cbaffmach_rec',
				'compare' => 'NOT EXISTS',
				'value'   => '',
			)
		);
	}
// var_dump($meta_query);
	$args['meta_query'] = $meta_query;
// var_dump($args);
	$total_query = new WP_Query( $args );
	$total = $total_query->found_posts;
	$num_of_pages = ceil( $total / $per_page );

	$args['posts_per_page'] = $per_page;
	if( !$page )
		$args['offset'] = 0;
	else
		$args['offset'] = ( $page-1 ) * $per_page;
	$args['orderby'] = 'ID';

	$str = '<div class=""><span style="float:right;margin-right:10px;margin-bottom:8px">Total: <b>'.$total.'</b></span><div style="clear:both"></div></div>';
	$str .= '<table class="widefat"><thead>
		<tr><th></th><th scope="col">Post</th><th scope="col">Product</th><th scope="col">Date</th><th scope="col">&nbsp;</th><th scope="col">&nbsp;</th></tr>
	</thead><tbody>';
	$posts = get_posts( $args );
	$i = $args['offset'] + 1;
	if( $posts ) {
		$current_time = time();
		foreach( $posts as $post ) {
			$product_name = get_post_meta( $post->ID, '_cbaffmach_prodname', true );
			// $approval_link = get_post_meta( $post->ID, '_cbaffmach_approval', true );
			$network = get_post_meta( $post->ID, '_cbaffmach_network', true );
			$affurl = get_post_meta( $post->ID, '_cbaffmach_affurl', true );
			$source_sdname = '';
			$source = get_post_meta( $post->ID, '_wpac_cnttype', true );
			$video = get_post_meta( $post->ID, '_wpac_video', true );
			$video_job = get_post_meta( $post->ID, '_wpaffm_avm_job_id', true );
			$video_job_time = get_post_meta( $post->ID, '_wpaffm_avm_job_request', true );

			$aff_link_enter = '';
			// if( $network == 1 )
			// 	$aff_link_enter = '<a class="button button-primary enter-aff-link" href="#" data-post-id="'.$post->ID.'"><i class="fas fa-link"></i> Enter affiliate link</a>';
			$share_btn = '<a class="button button-primary btn-share-post" href="#" data-post-id="'.$post->ID.'"><i class="fas fa-share-alt"></i> Share</a>';
			// $approval_btn = '';
			// if( $network == 0 || $network == 1 )
			// 	$approval_btn = '<a class="button button-secondary" href="'.$approval_link.'" target="_blank"><i class="fas fa-external-link-alt"></i> Affiliate Approval</a>';
			$pdf_btn = '';
			if( cbaffmach_is_pro() ) {
				$vid_btn = ' <a class="button button-secondary btn-create-vid" href="#" data-post-id="'.$post->ID.'"><i class="fas fa-video"></i> Create Video</a>';
				$pdf_btn = ' <a class="button button-secondary" href="'.admin_url('admin.php?page=affiliate-machine&ddpdf=true&post_id='.$post->ID).'" data-post-id="'.$post->ID.'"><i class="far fa-file-pdf"></i> PDF</a>';
				if( !empty( $video_job ) ) {
					if( $video_job_time && ( ( $current_time - $video_job_time ) > 1000 ) )
						$pdf_btn = $pdf_btn .' <a href="https://azonvideomaker.com/app/dl-video.php?jobid='.$video_job.'" class="button button-secondary"><i class="fas fa-download"></i> Download video</a>';
				}
				else {
					$pdf_btn = $pdf_btn .' '.$vid_btn;
				}
			}
			$str .= '<tr id="row-wpam-'.$post->ID.'"><th>'.$i.'</th><th scope="col"><a href="'.get_permalink( $post->ID) .'" target="_blank">'.get_the_title( $post->ID) .'</a></th><td>'.$product_name.'</td><td>'.get_the_date( 'd-m-Y H:m', $post->ID) .'</td><td><a class="button button-secondary" href="'.get_permalink( $post->ID) .'" target="_blank"><i class="far fa-file-alt"></i> View Post</a></td><td>'.$share_btn.' '.$aff_link_enter.$pdf_btn.'</td></tr>';
			$i++;
		}
	}
	$str .= '</tbody></table>';

	$page_links = paginate_links( array(
		'base' => add_query_arg( array(
			    'pagenum' => '%#%',
			    'cid' => $campaign_id,
			    'sid' => $source_id
			)
		),
		'format' => '',
		'end_size' => 4,
		'mid_size' => 3,
		'prev_text' => __( '«', 'text-domain' ),
		'next_text' => __( '»', 'text-domain' ),
		'total' => $num_of_pages,
		'current' => $page
	) );
	if ( $page_links ) {
		$str .= '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0;">' . $page_links . '</div></div>';
	}

	echo $str;
}

function cbaffmach_share_list( $name = 'cbaffmach_share', $values = array( 0, 0, 0, 0, 0, 0 )  ) {
	$i = 0;
?>
<label class="shr-link"><input type="checkbox" class="cbaffmach_share_nw" value="1" name="<?php echo $name.'_facebook';?>" <?php checked( $values[$i++]);?> /> <i class="fab fa-facebook-f"></i> Facebook</label>
<label class="shr-link"><input type="checkbox" class="cbaffmach_share_nw" value="1" name="<?php echo $name.'_twitter';?>" <?php checked( $values[$i++]);?> /> <i class="fab fa-twitter"></i> Twitter</label>
<label class="shr-link"><input type="checkbox" class="cbaffmach_share_nw" value="1" name="<?php echo $name.'_medium';?>" <?php checked( $values[$i++]);?> /> <i class="fab fa-medium"></i> Medium</label>
<label class="shr-link"><input type="checkbox" class="cbaffmach_share_nw" value="1" name="<?php echo $name.'_tumblr';?>" <?php checked( $values[$i++]);?> /> <i class="fab fa-tumblr"></i> Tumblr</label>
<label class="shr-link"><input type="checkbox" class="cbaffmach_share_nw" value="1" name="<?php echo $name.'_linkedin';?>" <?php checked( $values[$i++]);?> /> <i class="fab fa-linkedin"></i> Linkedin</label>
<label class="shr-link"><input type="checkbox" class="cbaffmach_share_nw" value="1" name="<?php echo $name.'_buffer';?>" <?php checked( $values[$i++]);?> /> <i class="fas fa-share-alt"></i> Buffer</label>
<?php
}

function cbaffmach_save_aff_link_ajax() {
	$post_id = intval( $_POST['post_id'] );
	$link = trim( $_POST['link'] );
	if( $post_id && !empty( $link ) )
		update_post_meta( $post_id, '_cbaffmach_affurl', $link );
	echo "1";
	exit();
}

function cbaffmach_share_link_ajax() {
	$post_id = intval( $_POST['post_id'] );
	if( !$post_id )
		exit();
	$networks =  $_POST['networks'];
	// var_dump($networks);
	foreach( $networks as $key => $network ) {
		if( ($key == 0 ) && $network )
			cbaffmach_traffic_facebook( $post_id );
		if( ($key == 1 ) && $network )
			cbaffmach_traffic_twitter( $post_id );
		if( ($key == 2 ) && $network )
			cbaffmach_traffic_medium( $post_id );
		if( ($key == 3 ) && $network )
			cbaffmach_traffic_tumblr( $post_id );
		if( ($key == 4 ) && $network )
			cbaffmach_traffic_linkedin( $post_id );
		if( ($key == 5 ) && $network )
			cbaffmach_traffic_buffer( $post_id );
	}
	// if( $post_id && !empty( $networks ) )
		// update_post_meta( $post_id, '_cbaffmach_affurl', $link );
	echo "1";
	exit();
}

add_action( 'init', 'cbaffmach_maybe_pdf' );

function cbaffmach_maybe_pdf() {
	$is_pdf = ( isset( $_GET['ddpdf'] ) && $_GET['ddpdf'] ) ? 1 : 0;
	if( !$is_pdf )
		return;
	$post_id = ( isset( $_GET['post_id'] ) && $_GET['post_id'] ) ? intval( $_GET['post_id'] ) : 0;
	cbaffmach_output_pdf( $post_id );
	exit();
}

function cbaffmach_create_video_ajax() {
	$post_id = intval( $_POST['post_id'] );
	if( !$post_id )
		exit();
	$title = cbaffmach_shorten_text( get_the_title( $post_id ), 88 );
	// if( function_exists( 'iconv') )
	    // $title = iconv('UTF-8', 'windows-1252', html_entity_decode($title));

	$content = cbaffmach_shorten_text( cbaffmach_get_content_by_id( $post_id ), 1900 );
	$content = html_entity_decode( strip_tags( $content ) );
	// if( function_exists( 'iconv') )
	    // $content = iconv('UTF-8', 'windows-1252//IGNORE', html_entity_decode( $content ) );
	$images = array( );
	$thumb = cbaffmach_get_thumbnail( $post_id );
	if( $thumb )
	    $images = array( $thumb );
	$youtube_description = 'More info at '.get_permalink( $post_id );
	$doc = new DOMDocument();
	@$doc->loadHTML(cbaffmach_get_content_by_id( $post_id ));
	$tags = $doc->getElementsByTagName('img');
	foreach ($tags as $tag) {
	       $src = $tag->getAttribute('src');
	       $images[] = $src;
	}
	$job = cbaffmach_wpavmaker_submit_job( $title, $content, $images, 'en-us', 0, $title, $youtube_description );
	// var_dump($job);
	if( $job[0] ) {
		$job_id = $job[1];
		add_post_meta( $post_id, '_wpaffm_avm_job_id', $job_id );
		add_post_meta( $post_id, '_wpaffm_avm_job_request', time() );
	}
	echo "1";
	exit();
}
?>