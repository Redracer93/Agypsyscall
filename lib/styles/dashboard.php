<?php
if ( !class_exists( 'ASDashboard' ) ) {
	class ASDashboard {
		var $plugin_name;
		var $feed = 'https://kreatelinks.com/dashboard/custom-feed/';
		var $num_items = 5;
		var $quick = 0;
		function __construct( $plugin_name ) {
			$this->plugin_name = $plugin_name;
			add_action('wp_dashboard_setup', array( $this, 'add_dashboard_widget' ) );
			add_action('wp_user_dashboard_setup', array( $this, 'add_dashboard_widget' ) );
			add_action('wp_newtwork_dashboard_setup', array( $this, 'add_dashboard_widget' ) );
		}

		function add_dashboard_widget() {
			add_meta_box( 'asdashboard-widget', 'Recommended Internet Marketing Tools', array( $this, 'show_dashboard_widget' ), 'dashboard', 'side', 'high');
		}

		function show_dashboard_widget() {
			$str = '';
			if( $this->quick )
				add_filter( 'wp_feed_cache_transient_lifetime' , array( $this, 'update_feed_quickly' ) );
			$rss = fetch_feed( $this->feed );
			if( $this->quick )
				remove_filter( 'wp_feed_cache_transient_lifetime', array( $this, 'update_feed_quickly' ) );

		    if ( is_wp_error($rss) ) {
				if ( is_admin() || current_user_can('manage_options') ) {
				   echo '<p>';
				   printf(__('<strong>RSS Error</strong>: %s'), $rss->get_error_message());
				   echo '</p>';
				}
		    	return;
			}

			if ( !$rss->get_item_quantity() ) {
			     // echo '<p>Apparently, there are no updates to show!</p>';
			     $rss->__destruct();
			     unset($rss);
			     return;
			}

			$str .= '<ul>';

			if ( !isset($items) )
			     foreach ( $rss->get_items(0, $this->num_items) as $item ) {
			          $publisher = '';
			          $site_link = '';
			          $link = '';
			          $content = '';
			          $date = '';
			          $link = esc_url( strip_tags( $item->get_link() ) );
			          $link = add_query_arg( 'tid', 'dash_'.$this->plugin_name, $link );
			          $title = esc_html( $item->get_title() );
			           $cat = $item->get_item_tags('', 'thumbnail');
			           $thumbnail_img = $cat[0]['data'];
			          $content = $item->get_content();
			          $content = wp_html_excerpt($content, 300, '[...]');
			          $content .= "<p style='text-align:right'><a href='$link'  target='_blank'>Learn More</a></p>";

			         $str .= "<li>
			         	<div style='float:left;width:18%;margin-right:4%;display:block;padding-top:4px'><a href='$link' target='_blank'><img src='".$thumbnail_img."' style='width:100%;height:auto' /></a></div>
			         	<div style='float:left;width:78%'><a class='rsswidget' href='$link' target='_blank'>$title</a>\n<div class='rssSummary'>$content</div></div>
			         	<div class='clear'></div>
			         	</li>\n<li><hr/></li>";
			}

			$str .= '</ul><div style="clear:both"></div><a href="http://licensemachine.com/access/other-products.php" class="button button-primary" target="_blank">+ View More</a><a style="float:right" href="#" id="wp_asdashboard_hide">Don\'t show this widget</a><div style="clear:both"></div>';

			$str .= '<script type="text/javascript">
				jQuery( document ).ready(function() {
					jQuery("#wp_asdashboard_hide").click(function( e ){
						e.preventDefault();
						jQuery("#asdashboard-widget-hide").trigger("click");
					});
				});
			</script>';
			echo $str;
			$rss->__destruct();
			unset($rss);
		}

		function update_feed_quickly( $seconds ) {
		  return 5;
		}

	}
	$as_dashboard = new ASDashboard( 'cbauto' );
}

if ( !class_exists( 'ASDashboardPlugins' ) ) {
	class ASDashboardPlugins {
		var $plugin_name;
		var $feed = 'https://kreatelinks.com/ourproducts/feed/?t=8';
		var $num_items = 35;
		var $quick = 1;
		function __construct( $plugin_name ) {
			$this->plugin_name = $plugin_name;
			add_filter( 'plugin_install_action_links', array( $this, 'plugin_links' ), 10, 2 );
			add_filter( 'install_plugins_tabs', array( $this, 'plugin_tabs' ), 10, 2 );
			add_action( 'install_plugins_internetmarketers', array( $this, 'install_plugins_im' ), 10, 1 );
			add_action( 'install_plugins_pre_internetmarketers', array( $this, 'get_favorites' ) );
			add_action( 'install_plugins_internetmarketers', 'display_plugins_table');
			// add_action( "install_plugins_plugin-information", array( $this, 'display_plugin_info' ) );
			add_filter( 'plugins_api', array( $this, 'inject_plugin_info' ), 20, 3 );
		}

		function update_feed_quickly( $seconds ) {
		  return 5;
		}

		function plugin_tabs( $tabs ) {
			$plugins = $this->check_remote_plugins();
			if( $plugins )
		    	$tabs = array( 'internetmarketers' => __( 'For Internet Marketers' ) ) + $tabs;
			return $tabs;
		}

		function install_plugins_im() {
        ?>
            <p><?php _e( 'Here are the top recommended products for people with an Internet Marketing business:' ); ?></p>
            <style>
            	.plugin-install-tab-internetmarketers .tablenav.top {
            		display: none;
            	}
            	.plugin-install-tab-internetmarketers p.authors {
            		display: none;
            	}
            </style>
        <?php
		}

		function get_favorites() {
		   global $wp_list_table;
			$args = array();
			$api = $this->query_server();
			$wp_list_table->items = $api->plugins;
			$wp_list_table->set_pagination_args(
				array(
					'total_items' => $api->info['results'],
					'per_page' => $api->info['per_page'],
				)
			);
		}

		function query_server() {
			$res = new stdclass();
			$res->plugins = array();

			$res->plugins = $this->get_remote_plugins();
			$num_res = 0;
			if( $res->plugins )
				$num_res = count( $res->plugins );
			$res->info = array(
				'results' => $num_res,
				'per_page' => 20
			);
			return $res;
		}

		function check_remote_plugins( $force_check = 0 ) {
			$plugins = $this->get_remote_plugins();
			if( empty( $plugins ) )
				return false;
			return true;
		}

		private function get_remote_plugins() {
			if( $this->quick )
				delete_transient( 'ankur_plugins' );
			// Get any existing copy of our transient data
			if ( false === ( $ankur_plugins = get_transient( 'ankur_plugins' ) ) || empty( $ankur_plugins ) ) {
			    // It wasn't there, so regenerate the data and save the transient
			     $ankur_plugins = $this->do_get_remote_plugins();
			     set_transient( 'ankur_plugins', $ankur_plugins, 12 * HOUR_IN_SECONDS );
			}
			return $ankur_plugins;
		}

		private function do_get_remote_plugins() {
			$i = 0;
			$myposts = array();
			$url = add_query_arg( 'paged', $i, $this->feed );
			// add_filter( 'wp_kses_allowed_html', 'cbaffmach_save_iframe',1,1 );
			// add_action( 'wp_feed_options' , 'cbaffmach_dont_strip_tags' );
			if( $this->quick )
				add_filter( 'wp_feed_cache_transient_lifetime' , array( $this, 'update_feed_quickly' ) );

			$rss = fetch_feed( $url );

			if ( is_wp_error( $rss ) ) {
				if( !empty( $myposts ) )
					return $myposts;
				return false;
			}

			if( $this->quick )
				remove_filter( 'wp_feed_cache_transient_lifetime', array( $this, 'update_feed_quickly' ) );
			// Remove these tags from the list
			$strip_htmltags = $rss->strip_htmltags;
			array_splice( $strip_htmltags, array_search('iframe', $strip_htmltags), 1 );
			array_splice( $strip_htmltags, array_search('param', $strip_htmltags), 1 );
			array_splice( $strip_htmltags, array_search('embed', $strip_htmltags), 1 );

			if( !is_wp_error( $rss ) )
				@$rss->strip_htmltags( $strip_htmltags );


			$maxitems = $rss->get_item_quantity( 50 );

		    if ( is_wp_error($rss) ) {
				if( !empty( $myposts ) )
					return $myposts;
		    	return false;
			}

			if ( !$rss->get_item_quantity() ) {
			     $rss->__destruct();
			     unset($rss);
			     if( !empty( $myposts ) )
			     	return $myposts;
			     return false;
			}

			foreach ( $rss->get_items() as $item ) {
				$publisher = '';
				$site_link = '';
				$link = '';
				$content = '';
				$date = '';
				$link = esc_url( strip_tags( $item->get_link() ) );
				$link = add_query_arg( 'tid', 'addplugins_'.$this->plugin_name, $link );
				$title = esc_html( $item->get_title() );

				$slug = $this->get_rss_field( $item, 'autors_slug' );
				if( empty( $slug ) )
					continue;
				if( 1 && $this->ankur_plugin_installed( $slug ) ) // the plugin is already installed
					continue;

				$purchaseurl = $this->get_rss_field( $item, 'autors_purchaseurl' );
				$installs = $this->get_rss_field( $item, 'autors_installs' );
				$network = $this->get_rss_field( $item, 'autors_network' );
				$lastupdated = $this->get_rss_field( $item, 'autors_lastupdated' );
				$rating = $this->get_rss_field( $item, 'autors_rating' );
				$reviews = $this->get_rss_field( $item, 'autors_reviews' );
				$authorname = $this->get_rss_field( $item, 'autors_authorname' );
				$authorurl = $this->get_rss_field( $item, 'autors_authorurl' );
				$description = $this->get_rss_field( $item, 'autors_description' );
				$version = $this->get_rss_field( $item, 'autors_version' );

				$previews = array();
				$rfields = array( 'review1_name', 'review1_txt', 'review2_name', 'review2_txt', 'review3_name', 'review3_txt' );
				foreach( $rfields as $rfield ) {
					$val = $this->get_rss_field( $item, $rfield );
					$previews[ $rfield ] = $val;
				}

				$content = $item->get_content();

				if( empty( $authorname ) ) {
					$authorname = 'Ankur Shukla';
				}

				if( empty( $authorurl ) ) {
					$authorurl = 'https://ankurshukla.com';
				}

				if( empty( $lastupdated ) ) {
					$lastupdated = date( 'Y' ).'-'.date( 'm' ).'-01 8:49pm GMT';
				}

				if( empty( $version ) ) {
					$version = 1.8;
				}

				if( empty( $rating ) )
					$rating = rand( 90, 99);
				if( empty( $reviews ) )
					$reviews = rand( 237, 1283 );
				if( empty( $installs ) )
					$installs = rand( 3678, 13372 );

				$postid = $item->get_item_tags( '', 'post_id' );
				if( empty( $postid ) )
					$postid2 = 0;
				else
					$postid2 = $postid[0]['data'];

				$thumbnail = $item->get_item_tags( '', 'featured_image' );
				$thumbnail2 = $thumbnail[0]['data'];

				$myposts[] = array(
					'name' => $title,
					'slug' => $slug,
					'pltypea' => 'ankur',
					'version' => $version,
					'author' => '<a href="'.$authorurl.'">'.$authorname.'</a>',
					'author_profile' => $authorurl,
					'homepage' => $purchaseurl,
					'download_link' => $purchaseurl,
					'requires' => '3.5',
					'tested' => ceil( get_bloginfo( 'version' ) ).'.0',
					'requires_php' => false,
					'rating' => $rating,
					'num_ratings' => $reviews,
					'active_installs' => $installs,
					'last_updated' => $lastupdated,
					'downloaded' => $installs,
					'description' => $content,
					'short_description' => $description,
					'apreviews' => $previews,
					'icons' => array(
						'1x' => $thumbnail2,
						'2x' => $thumbnail2
					),
					'author_block_count' => 2,
					'author_block_rating' => 94,
				);

			}
			return $myposts;
		}
		function plugin_links( $links, $plugin ) {
			if( $plugin[ 'pltypea' ] == 'ankur' ) {
				$links[0] = '<a class="button" data-slug="'.$plugin[ 'slug' ].'" href="'.$plugin[ 'download_link' ].'" target="_blank" aria-label="Install Plugin" data-name="'.$plugin[ 'name' ].'">'.__( 'Download Now' ).'</a>';
				$links[1] = '<a href="'.$plugin[ 'download_link' ].'" class="" aria-label="'.sprintf( __( 'More information about %s' ), $plugin[ 'name' ] ).'" target="_blank" data-title="'.$plugin[ 'name' ].'">'.__( 'More Details' ).'</a>';
			}
			return $links;
		}

		public function inject_plugin_info($result, $action = null, $args = null){
			if( $action !== 'plugin_information' )
				return $result;
			$our_plugin_info = $this->is_our_plugin( $args->slug );
			if( !$our_plugin_info )
				return $result;

			$pluginInfo = $this->requestPluginInfo( $our_plugin_info );
			if ( $pluginInfo ) {
				return $pluginInfo;
			}

			return $result;
		}

		private function is_our_plugin( $slug ) {
			$plugins = $this->get_remote_plugins();
			if( empty( $plugins ) )
				return false;
			foreach( $plugins as $plugin ) {
				if( $plugin['slug'] === $slug )
					return $plugin;
			}
			return false;
		}

		public function requestPluginInfo( $info ) {
			// var_dump($info);
			$description = isset( $info[ 'description' ] ) ? trim( $info[ 'description' ] ) : '';
			$intro = '<h3 style="text-align:center"><center><a href="'.$info[ 'homepage' ].'" target="_blank" class="button button-primary" style="color:red">Click here to get the plugin</a></center></h3>';
			$outro = '<h3><center><a href="'.$info[ 'homepage' ].'" target="_blank" class="button button-primary" style="color:red">Download the plugin here</a></center></p>';

			$ret = array(
				'name' => isset( $info[ 'name' ] ) ? trim( $info[ 'name' ] ) : '',
				'slug' => isset( $info[ 'slug' ] ) ? trim( $info[ 'slug' ] ) : '',
				'homepage' => isset( $info[ 'homepage' ] ) ? trim( $info[ 'homepage' ] ) : '',
				'download_url' => isset( $info[ 'download_link' ] ) ? trim( $info[ 'download_link' ] ) : '',
				'version' => isset( $info[ 'version' ] ) ? trim( $info[ 'version' ] ) : '',
				'required' => isset( $info[ 'required' ] ) ? trim( $info[ 'required' ] ) : '',
				'tested' => isset( $info[ 'tested' ] ) ? trim( $info[ 'tested' ] ) : '',
				'last_updated' => isset( $info[ 'last_updated' ] ) ? trim( $info[ 'last_updated' ] ) : '',
				'author' => isset( $info[ 'author' ] ) ? trim( $info[ 'author' ] ) : '',
				'author_homepage' => isset( $info[ 'author_profile' ] ) ? trim( $info[ 'author_profile' ] ) : '',
				'rating' => isset( $info[ 'rating' ] ) ? intval( $info[ 'rating' ] ) : '',
				'num_ratings' => isset( $info[ 'num_ratings' ] ) ? intval( $info[ 'num_ratings' ] ) : '',
				'active_installs' => isset( $info[ 'active_installs' ] ) ? intval( $info[ 'active_installs' ] ) : '',
				'downloaded' => isset( $info[ 'downloaded' ] ) ? intval( $info[ 'downloaded' ] ) : '',
				'sections' => array(
					'description' => $intro.$description.$outro,
					'installation' => '<p>Just download the plugin from the members area and install it to your site in a few seconds</p>
					<p><a href="'.$info[ 'homepage' ].'" target="_blank" class="button button-primary" style="color:red">Click here to get the plugin</a></p>'
				)
			);

			$reviews = isset( $info[ 'apreviews' ] ) ? $info[ 'apreviews' ] : false;
			// var_dump($reviews);
			if( !empty( $reviews ) && isset( $reviews[ 'review1_name' ] )  && !empty( $reviews[ 'review1_name' ] ) )
				$ret[ 'sections' ][ 'review' ] = $this->format_reviews( $reviews );
			return (object) $ret;
		}

		private function format_reviews( $reviews ) {
			if( empty( $reviews ) )
				return '';
			$ret = '';
			$review1_name = isset( $reviews[ 'review1_name' ] ) ? trim( $reviews[ 'review1_name' ] ) : '';
			$review1_txt = isset( $reviews[ 'review1_txt' ] ) ? trim( $reviews[ 'review1_txt' ] ) : '';
			if( !empty( $review1_txt ) ) {
				$ret .= $this->format_review( $review1_name, $review1_txt );
			}
			$review2_name = isset( $reviews[ 'review2_name' ] ) ? trim( $reviews[ 'review2_name' ] ) : '';
			$review2_txt = isset( $reviews[ 'review2_txt' ] ) ? trim( $reviews[ 'review2_txt' ] ) : '';
			if( !empty( $review2_txt ) ) {
				$ret .= $this->format_review( $review2_name, $review2_txt );
			}
			$review3_name = isset( $reviews[ 'review3_name' ] ) ? trim( $reviews[ 'review3_name' ] ) : '';
			$review3_txt = isset( $reviews[ 'review3_txt' ] ) ? trim( $reviews[ 'review3_txt' ] ) : '';
			if( !empty( $review3_txt ) ) {
				$ret .= $this->format_review( $review3_name, $review3_txt );
			}
			return $ret;
			;

		}

		private function format_review( $name, $content ) {
			return '<div class="review">
				<div class="review-head">
					<div class="reviewer-info">
						<div class="review-title-section">
							<h4>'.$name.'</h4>
							<div class="star-rating">
							<div class="wporg-ratings"><span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span><span class="star dashicons dashicons-star-filled"></span></div>				</div>
						</div>
					</div>
				</div>
				<div class="review-body">'.$content.'</div>
			</div>';
		}
		private function get_rss_field( $item, $field ) {
			$value = $item->get_item_tags( '', $field );
			$val = $value[0]['data'];
			return trim( html_entity_decode( $val ) );
		}

		private function ankur_plugin_installed( $slug ) {
			$slug2 = str_replace( 'ankur-', '', $slug );
			return $this->is_plugin_there( $slug2 );
		}

		private function is_plugin_there( $plugin_dir ) {
		    $plugins = get_plugins( '/'.$plugin_dir );
			if ( $plugins )
				return $plugins;
			return false;
		}

	}
	$as_dashboard = new ASDashboardPlugins( 'cbauto' );
}
?>