<?php

function cbaffmach_settings_monetize() {
    if( isset( $_POST['cbaffmach_save_settings'] ) ) {
        cbaffmach_save_settings( );
    }

    $settings = cbaffmach_get_plugin_settings();
    ?>
	<div class="wrap">
    <?php cbaffmach_header();?>
    <h1>Settings</h1>
    <br/>
    <?php if( isset( $_POST['cbaffmach_save_settings'] ) ) {
    	echo '<div class="notice notice-success is-dismissible inline"><p><i class="fa fa-check"></i> Settings saved!</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
    } ?>
        <form action="" method="post" id="cbaffmach-settings-form">
            <div id="cbaffmach-tabs" >
                <h2 class="nav-tab-wrapper">
                    <a href="#cbaffmach-monetize-importing-tab" class="nav-tab nav-tab-active"><i class="fas fa-file-import"></i> Importing</a>
                    <a href="#cbaffmach-monetize-scrolling-tab" class="nav-tab"><i class="fas fa-window-maximize"></i> Visual Settings</a>
                    <a href="#cbaffmach-monetize-adsense-tab" class="nav-tab"><i class="fas fa-code"></i> HTML Code</a>
                    <a href="#cbaffmach-monetize-bannerads-tab" class="nav-tab"><i class="fa fa-image"></i> Banner Ads</a>
                    <a href="#cbaffmach-monetize-social-tab" class="nav-tab"><i class="fa fa-share-alt"></i> Social</a>
                    <?php if( cbaffmach_is_pro() ) { ?>
                        <a href="#cbaffmach-monetize-traffic-tab" class="nav-tab"><i class="fa fa-link"></i> Traffic</a>
                        <a href="#cbaffmach-monetize-spinners-tab" class="nav-tab"><i class="fas fa-random"></i> Spinners</a>
                        <a href="#cbaffmach-monetize-video-tab" class="nav-tab"><i class="fas fa-video"></i> Video</a>
                        <a href="#cbaffmach-monetize-optin-tab" class="nav-tab"><i class="fas fa-envelope"></i> Optin Form</a>
                        <a href="#cbaffmach-monetize-relatedads-tab" class="nav-tab"><i class="far fa-check-square"></i> Related Ads</a>
                    <?php } ?>
                </h2>
                <div style="clear:both"></div>
                <div id="cbaffmach-monetize-importing-tab" class="tab-inner" style="display:block"><?php cbaffmach_settings_tab_importing( $settings );?></div>
                <div id="cbaffmach-monetize-relatedads-tab" class="tab-inner" ><?php cbaffmach_settings_monetize_tab_relatedads( $settings );?></div>
                <div id="cbaffmach-monetize-traffic-tab" class="tab-inner"><?php cbaffmach_settings_tab_traffic( $settings );?></div>
                <div id="cbaffmach-monetize-spinners-tab" class="tab-inner"><?php cbaffmach_settings_tab_spinners( $settings );?></div>
                <div id="cbaffmach-monetize-video-tab" class="tab-inner"><?php cbaffmach_settings_tab_video( $settings );?></div>
                <div id="cbaffmach-monetize-optin-tab" class="tab-inner"><?php cbaffmach_settings_tab_optin( $settings );?></div>
                <div id="cbaffmach-monetize-social-tab" class="tab-inner"><?php cbaffmach_settings_tab_social( $settings );?></div>
                <div id="cbaffmach-monetize-adsense-tab" class="tab-inner"><?php cbaffmach_settings_monetize_tab_adsense( $settings );?></div>
                <div id="cbaffmach-monetize-bannerads-tab" class="tab-inner"><?php cbaffmach_settings_monetize_tab_bannerads( $settings );?></div>
                <div id="cbaffmach-monetize-scrolling-tab" class="tab-inner"><?php cbaffmach_settings_monetize_tab_scrolling( $settings );?></div>
            </div>
            <?php cbaffmach_field_hidden( 'cbaffmach_save_settings', 1 ); ?>
            <button type="submit" class="button button-primary"><i class="fa fa-save"></i> Save All Changes</button>
        </form>
       </div>
    <?php
}

function cbaffmach_settings_tab_importing( $settings = false ) {
    $settings = isset( $settings['import'] ) ? $settings['import'] : false;
    $post_status = isset( $settings['post_status'] ) ? trim( $settings['post_status'] ) : 'publish';
    $per_day = isset( $settings['per_day'] ) ? intval( $settings['per_day'] ) : 1;
    $every = isset( $settings['every'] ) ? intval( $settings['every'] ) : 3;
    $cat = isset( $settings['cat'] ) ? intval( $settings['cat'] ) : 0;
    $featured = isset( $settings['featured'] ) ? intval( $settings['featured'] ) : 1;
    $tags = isset( $settings['tags'] ) ? intval( $settings['tags'] ) : 0;
    $jvzooid = isset( $settings['jvzooid'] ) ? trim( $settings['jvzooid'] ) : '';
    $clickbankid = isset( $settings['clickbankid'] ) ? trim( $settings['clickbankid'] ) : '';
    $wplusid = isset( $settings['wplusid'] ) ? trim( $settings['wplusid'] ) : '';
    $shareauto = isset( $settings['shareauto'] ) ? intval( $settings['shareauto'] ) : 0;
    $bmachine = isset( $settings['bmachine'] ) ? intval( $settings['bmachine'] ) : 0;
    $bmachine_num = isset( $settings['bmachine_num'] ) ? intval( $settings['bmachine_num'] ) : 10;
    $blindexer = isset( $settings['blindexer'] ) ? intval( $settings['blindexer'] ) : 0;
    $ilindexer = isset( $settings['ilindexer'] ) ? intval( $settings['ilindexer'] ) : 0;
    $spin = isset( $settings['spin'] ) ? intval( $settings['spin'] ) : 0;
    $createvideo = isset( $settings['createvideo'] ) ? intval( $settings['createvideo'] ) : 0;
?>
    <h3><i class="fas fa-file-import"></i> Importing Settings</h3>
	<table class="form-table">
		<tr id="cbaffmach_activate_row" valign="top">
	        <th scope="row">Post Status</th>
	        <td>
	        	<select name="cbaffmach_poststatus" id="">
	        		<option value="publish" <?php selected( $post_status, 'publish' ); ?>>Published</option>
	        		<option value="draft" <?php selected( $post_status, 'draft' ); ?>>Draft</option>
	        	</select>
		        <br/>
	        	<span class="description">Post status for imported articles </span>
        	</td>
	    </tr>
		<tr id="cbaffmach_max_tags_row2" valign="top">
	        <th scope="row">Articles to Import</th>
	        <td>
		        <input class="small-text " name="cbaffmach_perday" id="cbaffmach_perday" placeholder="" value="<?php echo $per_day;?>" type="number" min="1" max="10"> article/s every <input class="small-text " name="cbaffmach_every" id="cbaffmach_every" placeholder="" value="<?php echo $every;?>" type="number" min="1" max="10"> day/s
		        <br/>
	        	<span class="description">Number of articles to be imported on autopilot</span>
        	</td>
	    </tr>
		<tr id="cbaffmach_max_tags_row3" valign="top">
	        <th scope="row">Article category</th>
	        <td>
	        	<?php echo cbaffmach_cats_dropdown( 'cbaffmach_category', $cat ); ?>
		        <br/>
	        	<span class="description">The category for the imported posts</span>
        	</td>
	    </tr>
		<tr id="cbaffmach_max_tags_row4" valign="top">
	        <th scope="row">Import Featured Image</th>
	        <td>
	        	<select name="cbaffmach_featured" id="">
	        		<option value="0" <?php selected( $featured, 0 ); ?>>No</option>
	        		<option value="1" <?php selected( $featured, 1 ); ?>>Yes</option>
	        	</select>
		        <br/>
	        	<span class="description">If you select "yes", it will import the featured image from the original post</span>
        	</td>
	    </tr>
        <?php if( cbaffmach_is_pro() ) { ?>
        <tr id="cbaffmach_max_tags_row41" valign="top">
            <th scope="row">Add post tags</th>
            <td>
                <select name="cbaffmach_tags" id="">
                    <option value="0" <?php selected( $tags, 0 ); ?>>No</option>
                    <option value="1" <?php selected( $tags, 1 ); ?>>Yes</option>
                </select>
                <br/>
                <span class="description">If you select "yes", it will automatically add WP tags to new articles to give them a SEO boost</span>
            </td>
        </tr>
        <?php } ?>
    </table>
    <h3>Affiliate Settings</h3>
	<table class="form-table">
		<tr id="cbaffmach_max_tags_row4" valign="top" style="display:none">
	        <th scope="row">JVZoo Affiliate ID</th>
	        <td>
		        <input class="" name="cbaffmach_jvzooid" id="cbaffmach_jvzooid" placeholder="" value="<?php echo $jvzooid;?>" type="text">
		        <br/>
	        	<span class="description">Your JVZoo affiliate ID - <a href="https://cbautomator.com/support/knowledgebase/how-to-get-your-jvzoo-affiliate-id/" target="_blank">Tutorial</a></span>
        	</td>
	    </tr>
		<tr id="cbaffmach_max_tags_row5" valign="top">
	        <th scope="row">Clickbank Affiliate ID</th>
	        <td>
		        <input class="" name="cbaffmach_clickbankid" id="clickbankid" placeholder="" value="<?php echo $clickbankid;?>" type="text">
		        <br/>
	        	<span class="description">Your Clickbank affiliate ID - <a href="https://cbautomator.com/support/knowledgebase/how-to-get-your-clickbank-id/" target="_blank">Tutorial</a></span>
        	</td>
	    </tr>
        <tr id="cbaffmach_max_tags_row6" valign="top" style="display:none">
            <th scope="row">Warrior Plus Affiliate ID</th>
            <td>
                <input class="" name="cbaffmach_wplusid" id="wplusid" placeholder="" value="<?php echo $wplusid;?>" type="text">
                <br/>
                <span class="description">Your Warrior+ affiliate ID - <a href="https://cbautomator.com/support/knowledgebase/how-to-get-your-warrior-plus-affiliate-id/" target="_blank">Tutorial</a></span>
            </td>
        </tr>
    </table>
<br/>
    <h3>Sharing Settings</h3>
    <table class="form-table">
        <tr id="cbaffmach_max_tags_row7" valign="top">
            <th scope="row">Share new posts</th>
            <td>
                <input name="cbaffmach_shareauto" id="cbaffmach_shareauto" value="1" type="checkbox" <?php checked( $shareauto );?>>
                <span class="description">Automatically share new reviews on your social networks</span>
                <br/>
                <div id="share-new-posts" style="<?php if( !$shareauto) echo 'display:none';?>">
                    <h4>Share on:</h4>
                    <?php
                    if( isset( $settings['sharingsites'] ) && !empty( $settings['sharingsites'] ) ) {
                        $sharingsites_settings = $settings['sharingsites'];
                        $sharingsites = array(
                           ( $sharingsites_settings['facebook'] ) ? 1 : 0,
                           ( $sharingsites_settings['twitter'] ) ? 1 : 0,
                           ( $sharingsites_settings['medium'] ) ? 1 : 0,
                           ( $sharingsites_settings['tumblr'] ) ? 1 : 0,
                           ( $sharingsites_settings['linkedin'] ) ? 1 : 0,
                           ( $sharingsites_settings['buffer'] ) ? 1 : 0,
                        );
                    // var_dump($sharingsites);
                    }
                    else {
                        $sharingsites = array( 0,0,0,0,0,0 );
                    }
                    cbaffmach_share_list( 'cbaffmach_share', $sharingsites );
                    ?>
                </div>
            </td>
        </tr>
    </table>
    <?php if( cbaffmach_is_pro() ) { ?>
    <br/>
    <h3>Traffic Settings</h3>
    <table class="form-table">
        <tr id="cbaffmach_max_tags_row71" valign="top">
            <th scope="row">Create backlinks for new reviews</th>
            <td>
                <input name="cbaffmach_bmachine" id="cbaffmach_bmachine" value="1" type="checkbox" <?php checked( $bmachine );?>>
                <span class="description">Automatically create backlinks to your new reviews using <a href="https://backlinkmachine.convertri.com/" target="_blank">Backlink Machine</a></span>
                <br/>
                <br/>
                <span class="description">If you check this, after each new review is imported, we will drip feed 100 backlinks for your post over the following 4 weeks so you will get links that look more natural to Google from our private blog network.</span>
            </td>
        </tr>
        <tr id="cbaffmach_bm_row" valign="top" <?php echo 'style="display:none"';?>>
            <th scope="row">Number of backlinks</th>
            <td>
                <input class="" name="cbaffmach_bmachine_num" id="cbaffmach_bmachine_num" placeholder="" value="<?php echo $bmachine_num;?>" type="text">
                <br/>
                <span class="description">Number of backlinks to add per new review article</span>
            </td>
        </tr>
        <tr id="cbaffmach_max_tags_row72" valign="top">
            <th scope="row">Link Indexers</th>
            <td>
                <input name="cbaffmach_blindexer" id="cbaffmach_blindexer" value="1" type="checkbox" <?php checked( $blindexer );?>>
                <span class="description">Automatically index new reviews using <a href="https://wpautocontent.com/support/backlinksindexer" target="_blank">Backlinks Indexer</a></span>
                <br/>
                <br/>
                <input name="cbaffmach_ilindexer" id="cbaffmach_ilindexer" value="1" type="checkbox" <?php checked( $ilindexer );?>>
                <span class="description">Automatically index new reviews using <a href="https://wpautocontent.com/support/instantlinksindexer" target="_blank">Instant Link Indexer</a></span>
            </td>
        </tr>
    </table>
    <br/>
    <h3>Video creation</h3>
    <table class="form-table">
        <tr id="cbaffmach_max_tags_row77" valign="top">
            <th scope="row">Create review videos</th>
            <td>
                <input name="cbaffmach_createvideo" id="cbaffmach_createvideo" value="1" type="checkbox" <?php checked( $createvideo );?>>
                <span class="description">Create videos using Azon Video Maker</span>
                <br/>
                <p>This will turn each new review article into a video, and post it to Youtube for maximum traffic and exposure</p>
                <p><small>NOTE: To be able to create new videos, you need a valid <a href="https://azonvideomaker.convertri.com/" target="_blank">Azon Video Maker Pro</a> account. Enter your settings  under Settings > Video</small></p>
            </td>
        </tr>
    </table>
    <br/>
    <h3>Article Spinning</h3>
    <table class="form-table">
        <tr id="cbaffmach_max_tags_row73" valign="top">
            <th scope="row">Spin new reviews</th>
            <td>
                <input name="cbaffmach_spin" id="cbaffmach_spin" value="1" type="checkbox" <?php checked( $spin );?>>
                <span class="description">Spin new articles</span>
                <br/>
                <p>If you check this, the content of each new review article will be spun before importing.</p>
                <p><small>NOTE: To be able to spin the content you need to enter your <a href="https://wpautocontent.com/support/spinrewriter" target="_blank">Spin Rewriter</a> / <a href="https://wpautocontent.com/support/bestspinner" target="_blank">The Best Spinner </a> / <a href="https://wpautocontent.com/support/spinnerchief" target="_blank">SpinnerChief </a> / <a href="https://wpautocontent.com/support/wordai" target="_blank">WordAI </a> / account settings under Settings > Spinners</small></p>
            </td>
        </tr>
    </table>
    <?php } ?>
<?php
}

function cbaffmach_settings_tab_social( $settings = false ) {
    $social_settings = isset( $settings['social'] ) ? $settings['social'] : false;
    // var_dump($social_settings);
    $twitter_key = isset( $social_settings['twitter']['key'] ) ? trim( $social_settings['twitter']['key'] ) : '';
    $twitter_secret = isset( $social_settings['twitter']['secret'] ) ? trim( $social_settings['twitter']['secret'] ) : '';
    $twitter_oauth_token = isset( $social_settings['twitter']['oauth_token'] ) ? trim( $social_settings['twitter']['oauth_token'] ) : '';
    $twitter_oauth_secret = isset( $social_settings['twitter']['oauth_secret'] ) ? trim( $social_settings['twitter']['oauth_secret'] ) : '';

    $facebook_app_id = isset( $social_settings['facebook']['app_id'] ) ? trim( $social_settings['facebook']['app_id'] ) : '';
    $facebook_app_secret = isset( $social_settings['facebook']['app_secret'] ) ? trim( $social_settings['facebook']['app_secret'] ) : '';

    $pinterest_email = isset( $social_settings['pinterest']['email'] ) ? trim( $social_settings['pinterest']['email'] ) : '';
    $pinterest_pass = isset( $social_settings['pinterest']['pass'] ) ? trim( $social_settings['pinterest']['pass'] ) : '';
    $pinterest_app_id = isset( $social_settings['pinterest']['app_id'] ) ? trim( $social_settings['pinterest']['app_id'] ) : '';
    $pinterest_app_secret = isset( $social_settings['pinterest']['app_secret'] ) ? trim( $social_settings['pinterest']['app_secret'] ) : '';

    // $stumbleupon_email = isset( $social_settings['stumbleupon']['email'] ) ? trim( $social_settings['stumbleupon']['email'] ) : '';
    // $stumbleupon_pass = isset( $social_settings['stumbleupon']['pass'] ) ? trim( $social_settings['stumbleupon']['pass'] ) : '';

    // $reddit_email = isset( $social_settings['reddit']['email'] ) ? trim( $social_settings['reddit']['email'] ) : '';
    // $reddit_pass = isset( $social_settings['reddit']['pass'] ) ? trim( $social_settings['reddit']['pass'] ) : '';

    // $instagram_email = isset( $social_settings['instagram']['email'] ) ? trim( $social_settings['instagram']['email'] ) : '';
    // $instagram_pass = isset( $social_settings['instagram']['pass'] ) ? trim( $social_settings['instagram']['pass'] ) : '';

    // $googleplus_email = isset( $social_settings['googleplus']['email'] ) ? trim( $social_settings['googleplus']['email'] ) : '';
    // $googleplus_pass = isset( $social_settings['googleplus']['pass'] ) ? trim( $social_settings['googleplus']['pass'] ) : '';
    // $googleplus_apikey = isset( $social_settings['googleplus']['key'] ) ? trim( $social_settings['googleplus']['key'] ) : '';

    $medium_token = isset( $social_settings['medium']['token'] ) ? trim( $social_settings['medium']['token'] ) : '';

    $tumblr_key = isset( $social_settings['tumblr']['key'] ) ? trim( $social_settings['tumblr']['key'] ) : '';
    $tumblr_secret = isset( $social_settings['tumblr']['secret'] ) ? trim( $social_settings['tumblr']['secret'] ) : '';
    $tumblr_oauth_token = isset( $social_settings['tumblr']['oauth_token'] ) ? trim( $social_settings['tumblr']['oauth_token'] ) : '';
    $tumblr_oauth_secret = isset( $social_settings['tumblr']['oauth_secret'] ) ? trim( $social_settings['tumblr']['oauth_secret'] ) : '';

    $linkedin_app_id = isset( $social_settings['linkedin']['app_id'] ) ? trim( $social_settings['linkedin']['app_id'] ) : '';
    $linkedin_app_secret = isset( $social_settings['linkedin']['app_secret'] ) ? trim( $social_settings['linkedin']['app_secret'] ) : '';

    $buffer_client_id = isset( $social_settings['buffer']['client_id'] ) ? trim( $social_settings['buffer']['client_id'] ) : '';
    $buffer_client_secret = isset( $social_settings['buffer']['client_secret'] ) ? trim( $social_settings['buffer']['client_secret'] ) : '';

    cbaffmach_try_linkedin_auth( $linkedin_app_id, $linkedin_app_secret );
    // var_dump($social_settings['buffer']);
?>
    <p>Enter your social settings if you would like to share your imported posts on social media to get extra traffic to your reviews</p>

    <h3><i class="fab fa-facebook-f"></i> Facebook</h3>
    <table class="form-table">
        <?php cbaffmach_field_text( 'App ID', 'cbaffmach_facebook_app_id', $facebook_app_id, false, '', '', 'Your Facebook App ID' ); ?>
        <?php cbaffmach_field_text( 'App Secret', 'cbaffmach_facebook_app_secret', $facebook_app_secret, false, '', '<a href="https://cbautomator.com/support/knowledgebase/how-to-get-a-facebook-app-id-and-secret/" target="_blank">How to get your free Facebook App ID and Secret</a>', 'Your Facebook App Secret' ); ?>
    </table>
    <?php
        $login_url = false;
        if ( !empty($facebook_app_id ) && !empty( $facebook_app_secret ) ) {
            require_once CBAFFMACH_DIR . 'lib/libs/facebook/Facebook/autoload.php';
            $fb = new Facebook\Facebook([
                'app_id' => $facebook_app_id,
                'app_secret' =>$facebook_app_secret,
            ]);

            $helper = $fb->getRedirectLoginHelper();
            $permissions = ['publish_actions,manage_pages,publish_pages']; // optional
            $login_url = $helper->getLoginUrl( CBAFFMACH_URL . '/lib/libs/facebook/facebookcallback.php?fbid=' . $facebook_app_id . '&secret=' . $facebook_app_secret . '' , $permissions);
        }
        if( $login_url ) {
            echo '<a href="'.$login_url.'" class="button button-secondary">Auth Facebook</a>';
        }
    ?>
    <br/>
    <br/>

    <h3><i class="fab fa-twitter"></i> Twitter</h3>
    <table class="form-table">
        <?php cbaffmach_field_text( 'Consumer Key', 'cbaffmach_twitter_key', $twitter_key, false, '', '', 'Your Twitter Consumer Key' ); ?>
        <?php cbaffmach_field_text( 'Consumer Secret', 'cbaffmach_twitter_secret', $twitter_secret, false, '', '', 'Your Twitter Consumer Secret' ); ?>
        <?php cbaffmach_field_text( 'Oauth Access Token', 'cbaffmach_twitter_token', $twitter_oauth_token, false, '', '', 'Your Twitter Oauth Token' ); ?>
        <?php cbaffmach_field_text( 'Oauth Token Secret', 'cbaffmach_twitter_oauth_secret', $twitter_oauth_secret, false, '', '<a href="https://cbautomator.com/support/knowledgebase/how-to-get-your-twitter-details/" target="_blank">How to get your free Twitter API Details</a>', 'Your Twitter Token Secret' ); ?>
    </table>
    <br/>

    <h3><i class="fab fa-medium"></i> Medium</h3>
    <table class="form-table">
        <?php cbaffmach_field_text( 'Token', 'cbaffmach_medium_token', $medium_token, false, '', '<a href="https://cbautomator.com/support/knowledgebase/how-to-get-your-medium-token/" target="_blank">How to get your free Medium Token</a>', 'Your Medium Token' ); ?>
    </table>
    <br/>

    <h3><i class="fab fa-tumblr"></i> Tumblr</h3>
    <table class="form-table">
        <?php cbaffmach_field_text( 'Consumer Key', 'cbaffmach_tumblr_key', $tumblr_key, false, '', '', 'Your Tumblr Consumer Key' ); ?>
        <?php cbaffmach_field_text( 'Consumer Secret', 'cbaffmach_tumblr_secret', $tumblr_secret, false, '', '', 'Your Tumblr Consumer Secret' ); ?>
        <?php cbaffmach_field_text( 'Oauth Access Token', 'cbaffmach_tumblr_token', $tumblr_oauth_token, false, '', '', 'Your Tumblr Oauth Token' ); ?>
        <?php cbaffmach_field_text( 'Oauth Token Secret', 'cbaffmach_tumblr_oauth_secret', $tumblr_oauth_secret, false, '', '<a href="https://cbautomator.com/support/knowledgebase/how-to-get-your-tumblr-api-details/" target="_blank">How to get your free Tumblr API Details</a>', 'Your Tumblr Token Secret'  ); ?>
    </table>
    <br/>
    <h3><i class="fab fa-linkedin"></i> Linkedin</h3>
    <table class="form-table">
        <?php cbaffmach_field_text( 'App ID/Api Key', 'cbaffmach_linkedin_app_id', $linkedin_app_id, false, '', '', 'Your Linkedin App ID' ); ?>
        <?php cbaffmach_field_text( 'App Secret', 'cbaffmach_linkedin_app_secret', $linkedin_app_secret, false, '', '<a href="https://cbautomator.com/support/knowledgebase/how-to-get-your-linkedin-app-details/" target="_blank">How to get your Linkedin API Details</a>', 'Your Linkedin App Secret' ); ?>
        <?php cbaffmach_field_static( 'Callback URL', admin_url('/admin.php?page=affiliate-machine-settings&lin_auth=true'), false, '', 'Enter this URL to your linkedin app in OAuth 2.0 Redirect URLs field.'  ); ?>
    </table>

    <?php
        $login_url = false;
        if ( !empty($linkedin_app_id ) && !empty( $linkedin_app_secret ) ) {
            require_once CBAFFMACH_DIR . 'lib/libs/linkedin/LinkedIn.php';
            $li = new LinkedIn(
              array(
                'api_key' => $linkedin_app_id,
                'api_secret' => $linkedin_app_secret,
                'callback_url' => admin_url('/admin.php?page=affiliate-machine-settings&lin_auth=true')
              )
            );

            $login_url = $li->getLoginUrl(
              array(
                LinkedIn::SCOPE_BASIC_PROFILE,
                LinkedIn::SCOPE_WRITE_SHARE
              )
            );
        }
        if( $login_url ) {
            echo '<a href="'.$login_url.'" class="button button-secondary">Auth Linkedin</a>';
        }
    ?>
    <br/>
    <br/>

    <h3><i class="fas fa-share-square"></i> BufferApp</h3>
    <table class="form-table">
        <?php cbaffmach_field_text( 'Client ID', 'cbaffmach_buffer_client_id', $buffer_client_id, false, '', '', 'Your Buffer Client ID' ); ?>
        <?php cbaffmach_field_text( 'Client Secret', 'cbaffmach_buffer_client_secret', $buffer_client_secret, false, '', '<a href="https://cbautomator.com/support/knowledgebase/how-to-get-your-buffer-client-id-and-secret/" target="_blank">How to get your Buffer API Details</a>', 'Your Buffer Client Secret' ); ?>
        <?php cbaffmach_field_static( 'Callback URL', admin_url('/admin.php?page=affiliate-machine-settings&buffer_auth=true'), false, '', 'Enter this URL when creating your Buffer APP.'  ); ?>
    </table>

    <?php
        $login_url = false;
        if ( !empty($buffer_client_id ) && !empty( $buffer_client_secret ) ) {
            require_once CBAFFMACH_DIR . 'lib/libs/buffer/buffer.php';
            $buffer = new CBBufferApp( $buffer_client_id, $buffer_client_secret, admin_url('/admin.php?page=affiliate-machine-settings&buffer_auth=true') );
            $login_url = $buffer->get_login_url();
            echo '<a href="'.$login_url.'" class="button button-secondary">Auth Buffer</a>';
        }
    ?>
    <br/>
    <br/>
<?php
}

function cbaffmach_settings_tab_traffic( $settings = false ) {
    $traffic_settings = isset( $settings['traffic'] ) ? $settings['traffic'] : false;
    $bm_email = isset( $traffic_settings['bmachine']['email'] ) ? trim( $traffic_settings['bmachine']['email'] ) : '';
    $bm_apikey = isset( $traffic_settings['bmachine']['apikey'] ) ? trim( $traffic_settings['bmachine']['apikey'] ) : '';
    $ili_apikey = isset( $traffic_settings['ili']['apikey'] ) ? trim( $traffic_settings['ili']['apikey'] ) : '';
    $bli_apikey = isset( $traffic_settings['bli']['apikey'] ) ? trim( $traffic_settings['bli']['apikey'] ) : '';
?>
    <div style="display:none">
    <h3><i class="fas fa-link"></i> Backlink Machine - <a href="http://backlinkmachine.com" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php cbaffmach_field_text( 'Backlink Machine Email', 'cbaffmach_bm_email', $bm_email, false, '', '', 'Your Backlink Machine Email' ); ?>
        <?php cbaffmach_field_text( 'Backlink Machine Api Key', 'cbaffmach_bm_apikey', $bm_apikey, false, '', 'Get it <a target="_blank" href="http://backlinkmachine.com">here</a> - <a target="_blank" href="https://cbautomator.com/support/knowledgebase/how-to-get-your-backlink-machine-api-key/">Tutorial</a>', 'Your Backlink Machine API Key' ); ?>
    </table>
    <br/>
    </div>
    <p>If you already have an account with <a href="https://wpautocontent.com/support/backlinksindexer">Backlinks Indexer</a> or <a href="https://wpautocontent.com/support/instantlinksindexer">Instant Link Indexer</a>, enter them here so you can index the urls for the new imported review articles for a boost in SEO rankings.</p>

    <h3><i class="fas fa-external-link-alt"></i> Backlinks Indexer - <a href="https://wpautocontent.com/support/backlinksindexer" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php cbaffmach_field_text( 'Backlinks Indexer Api Key', 'cbaffmach_bli_apikey', $bli_apikey, false, '', 'Get it <a target="_blank" href="https://wpautocontent.com/support/backlinksindexer">here</a> - <a target="_blank" href="https://cbautomator.com/support/knowledgebase/how-to-get-your-backlinks-indexer-api-key/">Tutorial</a>', 'Your Backlinks Indexer API Key' ); ?>
    </table>
    <br/>
    <h3><i class="fas fa-external-link-alt"></i> Instant Link Indexer - <a href="https://wpautocontent.com/support/instantlinksindexer" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php cbaffmach_field_text( 'Instant Link Indexer Api Key', 'cbaffmach_ili_apikey', $ili_apikey, false, '', 'Get it <a target="_blank" href="https://wpautocontent.com/support/instantlinksindexer">here</a> - <a target="_blank" href="https://cbautomator.com/support/knowledgebase/how-to-get-your-instant-link-indexer-api-key/">Tutorial</a>', 'Your Instant Link Indexer API Key' ); ?>
    </table>
    <br/>
<?php
}

function cbaffmach_settings_tab_spinners( $settings = false ) {
    $spinners_settings = isset( $settings['spinners'] ) ? $settings['spinners'] : false;
    $srewriter_email = isset( $spinners_settings['spinrewriter']['email'] ) ? trim( $spinners_settings['spinrewriter']['email'] ) : '';
    $srewriter_apikey = isset( $spinners_settings['spinrewriter']['apikey'] ) ? trim( $spinners_settings['spinrewriter']['apikey'] ) : '';

    $schief_username = isset( $spinners_settings['schief']['username'] ) ? trim( $spinners_settings['schief']['username'] ) : '';
    $schief_password = isset( $spinners_settings['schief']['password'] ) ? trim( $spinners_settings['schief']['password'] ) : '';

    $tbs_username = isset( $spinners_settings['tbs']['username'] ) ? trim( $spinners_settings['tbs']['username'] ) : '';
    $tbs_password = isset( $spinners_settings['tbs']['password'] ) ? trim( $spinners_settings['tbs']['password'] ) : '';

    $wordai_username = isset( $spinners_settings['wordai']['username'] ) ? trim( $spinners_settings['wordai']['username'] ) : '';
    $wordai_password = isset( $spinners_settings['wordai']['password'] ) ? trim( $spinners_settings['wordai']['password'] ) : '';

    $spinner = isset( $spinners_settings['spinner'] ) ? trim( $spinners_settings['spinner'] ) : 1;
    // var_dump($spinners_settings);
?>
    <p><a href="https://cbautomator.com/support/knowledgebase/what-is-an-article-spinner-and-why-should-i-use-it/" target="_blank"><i class="fa fa-question"></i> What is an article spinner?</a></p>
    <h3><i class="fa fa-random"></i> Spin Rewriter - <a href="https://wpautocontent.com/support/spinrewriter" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php cbaffmach_field_text( 'Spin Rewriter Email', 'cbaffmach_srewriter_email', $srewriter_email, false, '', '', 'Your Spin Rewriter Email address' ); ?>
        <?php cbaffmach_field_text( 'Spin Rewriter Api Key', 'cbaffmach_srewriter_apikey', $srewriter_apikey, false, '', 'Get it <a target="_blank" href="https://wpautocontent.com/support/spinrewriter">here</a> - <a href="https://cbautomator.com/support/knowledgebase/how-to-get-your-spin-rewriter-api-key/" target="_blank">Tutorial</a>', 'Your Spin Rewriter API Key' ); ?>
    </table>
    <br/>
    <h3><i class="fa fa-random"></i> Spinner Chief - <a href="https://wpautocontent.com/support/spinnerchief" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php cbaffmach_field_text( 'Username', 'cbaffmach_schief_username', $schief_username, false, '', '', 'Your Email address for Spinner Chief' ); ?>
        <?php cbaffmach_field_password( 'Password', 'cbaffmach_schief_pass', $schief_password, false, '', 'Get it <a target="_blank" href="https://wpautocontent.com/support/spinnerchief">here</a> - <a href="https://cbautomator.com/support/knowledgebase/how-to-get-your-api-key-for-spinnerchief/" target="_blank">Tutorial</a>', 'Your Spinner Chief Password' ); ?>
    </table>
    <br/>
    <h3><i class="fa fa-random"></i> The Best Spinner - <a href="https://wpautocontent.com/support/bestspinner" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php cbaffmach_field_text( 'Username', 'cbaffmach_tbspinner_username', $tbs_username, false, '', '', 'Your Email address for TBS' ); ?>
        <?php cbaffmach_field_password( 'Password', 'cbaffmach_tbspinner_pass', $tbs_password, false, '', 'Get it <a target="_blank" href="https://wpautocontent.com/support/bestspinner">here</a> - <a href="https://cbautomator.com/support/knowledgebase/how-to-get-your-api-key-for-the-best-spinner/" target="_blank">Tutorial</a>', 'Your TBS Password' ); ?>
    </table>
    <br/>
    <?php 
    // echo cbaffmach_spin_text_schief( 'The cat sat on the mat, and this is very good because Spain is a nice country, and I like to go fishing on the weekend to visit my friends, who are very kind' );
    ?>
    <h3><i class="fa fa-random"></i> WordAI - <a href="https://wpautocontent.com/support/wordai" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php cbaffmach_field_text( 'Email', 'cbaffmach_wordai_username', $wordai_username, false, '', '', 'Your Email address for WordAI' ); ?>
        <?php cbaffmach_field_password( 'Password', 'cbaffmach_wordai_pass', $wordai_password, false, '', 'Get it <a target="_blank" href="https://wpautocontent.com/support/wordai">here</a> - <a href="https://cbautomator.com/support/knowledgebase/how-to-get-your-api-key-for-wordai/" target="_blank">Tutorial</a>', 'Your WordAI Password' ); ?>
    </table>

    <br/>
    <h3>Spinner to Use</h3>
    <table class="form-table">
    <?php
        $spinners = array( array( 'value' => 1, 'label' => 'Spin Rewriter' ), array( 'value' => 2, 'label' => 'The Best Spinner' ), array( 'value' => 3, 'label' => 'WordAI'  ), array( 'value' => 4, 'label' => 'Spinner Chief',  ) );
    ?>
    <?php cbaffmach_field_select( 'Spinner', 'cbaffmach_spinner', $spinner, $spinners, false, 'Select the spinner you want to use (you need to enter the details for that spinner above or it won\'t work)' ); ?>
    </table>
    <br/>

<?php
}

function cbaffmach_settings_tab_video( $settings = false ) {
    $video_settings = isset( $settings['video'] ) ? $settings['video'] : false;
    $avmaker_email = isset( $video_settings['avmaker']['email'] ) ? trim( $video_settings['avmaker']['email'] ) : '';
    $avmaker_apikey = isset( $video_settings['avmaker']['apikey'] ) ? trim( $video_settings['avmaker']['apikey'] ) : '';

?>
    <p>If you already have an <a href="https://azonvideomaker.convertri.com/">Azon Video Maker Pro</a> Account, enter it here so you can create review videos and upload them to Youtube on autopilot.</p>
    <h3><i class="fa fa-video"></i> Azon Video Maker - <a href="https://azonvideomaker.convertri.com/" target="_blank" style="font-size: 0.9em">Get an account</a></h3>
    <table class="form-table">
        <?php cbaffmach_field_text( 'Azon Video Maker Email', 'cbaffmach_avmaker_email', $avmaker_email, false, '', '', 'The e-mail you used to sign-up for Azon Video Maker' ); ?>
        <?php cbaffmach_field_text( 'Azon Video Maker Api Key', 'cbaffmach_avmaker_apikey', $avmaker_apikey, false, '', 'Get it <a target="_blank" href="https://azonvideomaker.com/app">here</a>', 'Your Azon Video Maker API Key' ); ?>
    </table>

<?php
}

function cbaffmach_settings_tab_optin( $settings = false ) {
    $optin_settings = isset( $settings['optin'] ) ? $settings['optin'] : false;
    $enabled = isset( $optin_settings['enabled'] ) ? intval( $optin_settings['enabled'] ) : 0;
    $autoresponder = isset( $optin_settings['autoresponder'] ) ? intval( $optin_settings['autoresponder'] ) : '';
    $list = isset( $optin_settings['list'] ) ? trim( $optin_settings['list'] ) : '';
    $email_field = isset( $optin_settings['email_field'] ) ? trim( $optin_settings['email_field'] ) : '';
    $first_name = isset( $optin_settings['first_name'] ) ? intval( $optin_settings['first_name'] ) : 0;
    $firstname_field = isset( $optin_settings['firstname_field'] ) ? trim( $optin_settings['firstname_field'] ) : '';
    $submit_txt = isset( $optin_settings['submit_txt'] ) ? trim( $optin_settings['submit_txt'] ) : '';
    $intro_txt = isset( $optin_settings['intro_txt'] ) ? trim( $optin_settings['intro_txt'] ) : '';
    $thankyou_txt = isset( $optin_settings['thankyou_txt'] ) ? trim( $optin_settings['thankyou_txt'] ) : '';
    $redirect_url = isset( $optin_settings['redirect_url'] ) ? trim( $optin_settings['redirect_url'] ) : '';
    $style = isset( $optin_settings['style'] ) ? intval( $optin_settings['style'] ) : '';
    $position = isset( $optin_settings['position'] ) ? intval( $optin_settings['position'] ) : '';
    $paragraph = isset( $optin_settings['paragraph'] ) ? intval( $optin_settings['paragraph'] ) : '';
    $margin = isset( $optin_settings['margin'] ) ? intval( $optin_settings['margin'] ) : '';
    echo '<p>Here you can enable an optin form to capture e-mails from your visitors. The leads will be stored inside WordPress (CB Automator > Leads), but you can also connect them to your favourite Autoresponder (CB Automator > Autoresponders) </p>';
    echo '<h3>Optin box:</h3>';
    echo '<table class="form-table">';
    cbaffmach_field_checkbox( 'Display Optin Box', 'cbaffmach_optin_enabled', $enabled, false, 'cbaffmach_optin_enabled', 'If checked, the an optin box to capture leads will be displayed' );
    echo '</table>';
    echo '<div id="optin-box-settings" '.(  !$enabled ? 'style="display:none"' : '').'>';
    echo '<h3>Autoresponder:</h3>';
    echo '<table class="form-table">';

    $autoresponders = cbaffmach_get_autoresponder_types() ;

    cbaffmach_field_select( 'Autoresponder type', 'cbaffmach_optin_ar_type', $autoresponder, $autoresponders, false, 'The autoresponder company to store leads', '', 'cbaffmach_autoresponder' );
    $current_ar = $autoresponder;

    $lists_sel = array();
    if ( $current_ar ) {
        $lists = cbaffmach_get_autoresponder_lists( $current_ar );

        if ( $lists ) {
            foreach ($lists as $alist) {
                $lists_sel[] = array( 'value' => $alist['id'], 'label' => $alist['name']);
            }
        }
    }

    cbaffmach_field_select( 'List', 'cbaffmach_optin_list', $list, $lists_sel, false, 'The list to store the leads', '', 'cbaffmach_list', 'cbaffmach_list_row', (!$current_ar ||  ($current_ar ==12 )) );

    echo '</table>';

    echo '<h3>Optin Settings:</h3>';
    echo '<table class="form-table">';

// function cbaffmach_field_text( $label = '', $name = '', $value = '', $id = false, $class = '',  $help = '', $placeholder = '', $row_class = '', $row_visible = true ) {


    cbaffmach_field_text( 'Email Field Text', 'cbaffmach_optin_email_txt', $email_field,  false, '', 'Ex: Your Email', 'Email Field Text' );

// function cbaffmach_field_checkbox( $label = '', $name = '', $value = '', $id = false, $class = '',  $help = '', $placeholder = '', $row_class = '', $row_visible = true ) {

    cbaffmach_field_checkbox( 'Ask for First Name', 'cbaffmach_optin_show_name', $first_name, false, 'cbaffmach_optin_show_name', 'If checked, the optin form will display a field to ask for the visitor\'s name' );

    $display_name_field = ( isset( $first_name ) && ( $first_name ) );
// function cbaffmach_field_text( $label = '', $name = '', $value = '', $id = false, $class = '',  $help = '', $placeholder = '', $row_class = '', $row_visible = true ) {

    cbaffmach_field_text( 'Name Field Text', 'cbaffmach_optin_name_txt',  $firstname_field,  false, '', 'Name Field Text', 'Ex: Your Name', 'cbaffmach_optin_name_field_row', $display_name_field );

    cbaffmach_field_text( 'Submit Button Text', 'cbaffmach_optin_submit_txt', $submit_txt,  false, '', 'Ex: Submit', 'Submit Button Text' );

    cbaffmach_field_textarea( 'Intro text', 'cbaffmach_optin_intro_txt', $intro_txt,  false, 'regular-text', 'This text will be displayed on top of the optin form', 'Intro text' );

    cbaffmach_field_textarea( 'Thank you text', 'cbaffmach_optin_thankyou_txt', $thankyou_txt,  false, 'regular-text', 'This text will be displayed after optin', 'Thank you text' );

    cbaffmach_field_text( 'Redirect URL', 'cbaffmach_optin_redirect_url', $redirect_url,  false, '', 'Optional, the user will be redirected here after optin (leave blank for no redirect)', 'Ex: http://www.google.com' );


    echo '</table>';

    echo '<h3>Display:</h3>';
    echo '<table class="form-table">';

    $styles = cbaffmach_get_optin_styles();
    cbaffmach_field_select( 'Style', 'cbaffmach_optin_style', $style, $styles, false, 'Optin Style', '', '' );

    cbaffmach_field_bannerpos( 'Position', array( 'cbaffmach_optin_position', 'cbaffmach_optin_paragraph', 'cbaffmach_optin_float', 'cbaffmach_optin_margin' ) , array( $position, $paragraph, $margin ), false, 'Optin Form Position' );

    echo '</table>';
    echo '</div>';
?>
<?php
}
/*
function cbaffmach_save_settings( ) {
	// $enable_auto = isset( $_POST['cbaffmach_auto'] ) ? intval( $_POST['cbaffmach_auto'] ) : 0;
	$per_day = isset( $_POST['cbaffmach_perday'] ) ? intval( $_POST['cbaffmach_perday'] ) : 0;
	$status = isset( $_POST['cbaffmach_poststatus'] ) ? trim( $_POST['cbaffmach_poststatus'] ) : 'publish';
	$cat = isset( $_POST['cbaffmach_category'] ) ? intval( $_POST['cbaffmach_category'] ) : 0;
	$featured = isset( $_POST['cbaffmach_featured'] ) ? intval( $_POST['cbaffmach_featured'] ) : 0;
	$jvzooid = isset( $_POST['cbaffmach_jvzooid'] ) ? trim( $_POST['cbaffmach_jvzooid'] ) : '';
	$clickbankid = isset( $_POST['cbaffmach_clickbankid'] ) ? trim( $_POST['cbaffmach_clickbankid'] ) : '';
	$settings = array(
		'enable_importing' => 1,
		'per_day' => $per_day,
		'cat' => $cat,
		'post_status' => $status,
		'featured' => $featured,
		'jvzooid' => $jvzooid,
		'clickbankid' => $clickbankid
	);
	cbaffmach_update_settings( $settings );
}*/


// Autoresponders
function cbaffmach_admin_autoresponders( ) {
    cbaffmach_check_authorize_ar_api();
    $settings = cbaffmach_get_plugin_settings();
    $ar_data = isset( $settings['autoresponders'] ) ? $settings['autoresponders'] : false;
?>

<div id="cbaffmach-tabs" >
    <h2 class="nav-tab-wrapper">
        <a href="#aweber-tab" class="nav-tab nav-tab-active">Aweber</a>
        <a href="#getresponse-tab" class="nav-tab" >Getresponse</a>
        <a href="#icontact-tab" class="nav-tab">Icontact</a>
        <a href="#mailchimp-tab" class="nav-tab">Mailchimp</a>
        <a href="#ccontact-tab" class="nav-tab">Constant Contact</a>
        <a href="#sendreach-tab" class="nav-tab">Sendreach</a>
        <a href="#activecampaign-tab" class="nav-tab">Active Campaign</a>
        <a href="#sendlane-tab" class="nav-tab">Sendlane</a>
        <a href="#mailit-tab" class="nav-tab">Mailit Plugin</a>
        <a href="#mymailit-tab" class="nav-tab">MyMailit</a>
    </h2>

            <div style="clear:both"></div>
            <div id="mailit-tab" class="tab-inner" style="padding: 20px">

                            <h2><img src="<?php echo CBAFFMACH_URL;?>/img/icons/icon_mailit.png" style="vertical-align: top" /> Mailit Plugin   - <a href="https://wpautocontent.com/support/mailit" target="_blank" style="font-size: 0.9em">Get it here</a></h2>
                            <form action="" method="post" class="form-horizontal" id="mailit-vp-form">
                            <?php
                                $mailit_type = isset( $plugin_options['autoresponders']['mailit']['install_type'] ) ? $plugin_options['autoresponders']['mailit']['install_type'] : 1;
                            ?>

                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row">Install Type</th>
                                    <td>
                                        <select name="cbaffmach_mailit_type" id="cbaffmach_mailit_type"  class="form-control">
                                            <option value="1" <?php selected( $mailit_type, '1' );?>>Local</option>
                                            <option value="2" <?php selected( $mailit_type, '2' );?>>Remote</option>
                                        </select>
                                        <span class="description">If you have Mailit installed on the same WordPress install, select "Local". Otherwise, select "Remote"</span>
                                    </td>
                                </tr>
                            </table>
                            <div class="form-group" id="mailit-local-settings" <?php if($mailit_type == 2) echo 'style="display:none"';?>>
                                <?php
                                $plugin_name = '/mailit';
                                if ( cbaffmach_is_plugin_there( $plugin_name ) ) {
                                    if ( cbaffmach_is_mailit_plugin_active() ) {
                                        echo '<p class="text-success">Plugin Active</p>';
                                    }
                                    else
                                        echo '<p class="text-warning">Plugin installed, but not active &nbsp;&nbsp;&nbsp; <input type="submit" class="button button-secondary" value="Activate" id="do-activate-mailit"><input type="hidden" id="activate-mailit-plg" name="activate-mailit" value="0"><br/></p>';
                                }
                                else
                                    echo '<p class="text-error">Plugin not installed &nbsp; </p>';
                            ?>
                            </div>

                                    <button type="submit" class="button button-primary" style="margin-right:20px"><i class="fa fa-sign-in"></i> Update changes </button>
                                    <?php if (isset($ar_data['mailit']['cache_expires']) && !empty($ar_data['mailit']['cache_expires'])
                                        && cbaffmach_is_plugin_there( $plugin_name ) && cbaffmach_is_mailit_plugin_active()
                                    ) { ?>
                                        <button type="button" class="mailit_refresh_lists button button-secondary"><i class="fa fa-refresh"></i> Refresh lists</button>
                                    <?php } ?>

                            <div class="form-group" id="mailit-remote-settings" <?php if($mailit_type == 1) echo 'style="display:none"';?>>

                                <table class="form-table">
                                    <tr valign="top">
                                        <th scope="row">Mailit URL</th>
                                        <td>
                                         <input type="text" class="regular-text" name="cbaffmach_mailit_url" placeholder="Enter your Mailt URL" value="<?php echo empty( $ar_data['mailit']['list_url'] ) ? '' : $ar_data['mailit']['list_url']; ?>">
                                            <span class="description">You can find it under Mailit > Lists > Remote Submit URL</span>
                                        </td>
                                    </tr>
                                </table>

                                    <input type="hidden" name="cbaffmach_mailit_authorize" value="1">
                                    <button type="submit" class="button button-secondary" ><i class="fa fa-sign-in"></i> Update changes </button>
                            </div>

                        <!-- </fieldset> -->
                        <p><?php
                           // var_dump( cbaffmach_get_mailit_mailing_lists()); //nvrC
                          // cbaffmach_ar_mailit_subscribe_user(1, 'rrxxrrr@gmail.com', 'xxxxx');
                        // cbaffmach_ar_getresponse_move_subscriber('nvrC', 0, 'raulmellado@gmail.com', 0);
                        ?></p>
                </form>

            </div>

            <div id="mymailit-tab" class="tab-inner" style="padding: 20px">

                            <h2><img src="<?php echo CBAFFMACH_URL;?>/img/icons/icon_mymailit.jpg" style="vertical-align: top" /> My Mailit   - <a href="https://wpautocontent.com/support/mymailit" target="_blank" style="font-size: 0.9em">Get it here</a></h2>
                            <form action="" method="post" class="form-horizontal" id="mymailit-vp-form">

                            <div class="form-group" id="my-mailit-remote-settings" >

                                <table class="form-table">
                                    <tr valign="top">
                                        <th scope="row">My Mailit List URL</th>
                                        <td>
                                         <input type="text" class="regular-text" name="cbaffmach_mymailit_url" placeholder="Enter your Mailt List URL" value="<?php echo empty( $ar_data['mymailit']['list_url'] ) ? '' : $ar_data['mymailit']['list_url']; ?>">
                                            <span class="description">It will be something like https://mymailit.com/members/remotesubmit.php?l=12345</span>
                                        </td>
                                    </tr>
                                </table>

                                    <input type="hidden" name="cbaffmach_mymailit_authorize" value="1">
                                    <button type="submit" class="button button-primary" ><i class="fa fa-sign-in"></i> Update changes </button>
                            </div>

                        <!-- </fieldset> -->
                        <p><?php
                           // var_dump( cbaffmach_get_mailit_mailing_lists()); //nvrC
                          // cbaffmach_ar_mailit_subscribe_user(1, 'rrxxrrr@gmail.com', 'xxxxx');
                        // cbaffmach_ar_getresponse_move_subscriber('nvrC', 0, 'raulmellado@gmail.com', 0);
                        ?></p>
                </form>

            </div>

            <div id="aweber-tab" class="tab-inner" style="padding: 20px;display:block">

    <h2><img src="<?php echo CBAFFMACH_URL;?>/img/icons/icon_aweber.png" style="vertical-align: top" /> Aweber - <a href="https://wpautocontent.com/support/aweber" target="_blank" style="font-size: 0.9em">Get an account</a></h2>

    <?php
    if ( isset($aweber_data['token']) && isset( $aweber_data['token'] ) && $aweber_data['token_secret'] && $aweber_data['token_secret'] ) :?>
        <p class="text-success">You have successfully connected your Aweber account!</p>
        <form action="" method="post">
            <input type="hidden" name="cbaffmach_aweber_unauthorize" value="1">
            <button type="submit" class="button button-primary" ><i class="fa icon-ban-circle"></i> Disconnect</button>
            <button style="margin-left:20px;" type="button" class="aweber_refresh_lists button button-secondary"><i class="fa fa-refresh"></i> Refresh lists</button>
        </form>
    <?php  else: ?>
        <h4 >Connect To Aweber</h4>
            <p>You need to connect your Aweber account first</p>
            <form action="" method="post">
                <input type="hidden" name="cbaffmach_aweber_authorize" value="1">
                <button type="submit" class="button button-primary" ><i class="fa fa-sign-in"></i> Connect </button>
            </form>
    <?php endif; ?>

    <br/>
    </div>
            <div id="getresponse-tab" class="tab-inner" style="padding: 20px">
    <h2><img src="<?php echo CBAFFMACH_URL;?>/img/icons/icon_getresponse.png" style="vertical-align: top" /> Getresponse  - <a href="https://wpautocontent.com/support/getresponse" target="_blank" style="font-size: 0.9em">Get an account</a></h2>

    <form action="" method="post" class="form-horizontal">

    <table class="form-table">
        <tr valign="top">
            <th scope="row">API Key</th>
            <td>
             <input type="text" class="form-control regular-text" name="cbaffmach_getresponse_api" placeholder="Enter your API key" value="<?php echo empty( $ar_data['getresponse']['apikey'] ) ? '' : $ar_data['getresponse']['apikey']; ?>">
                <span class="description">Get your API key <a target="_blank" href="http://support.getresponse.com/faq/where-i-find-api-key">here</a></span>
            </td>
        </tr>
    </table>

    <input type="hidden" name="cbaffmach_getresponse_authorize" value="1">
    <button type="submit" class="button button-primary" ><i class="fa fa-sign-in"></i> Authorize </button>
    <?php if (isset($ar_data['getresponse']['cache_expires']) && !empty($ar_data['getresponse']['cache_expires'])) { ?>
        <button style="margin-left:20px;" type="button" class="getresponse_refresh_lists button button-secondary"><i class="fa fa-refresh"></i> Refresh lists</button>
    <?php } ?>
    </form>

    <br/>
    </div>
    <div id="icontact-tab" class="tab-inner" style="padding: 20px">

    <h2><img src="<?php echo CBAFFMACH_URL;?>/img/icons/icon_icontact.png" style="vertical-align: top" /> iContact  - <a href="https://wpautocontent.com/support/icontact" target="_blank" style="font-size: 0.9em">Get an account</a></h2>

    <form action="" method="post" class="form-horizontal">

        <table class="form-table">
            <tr valign="top">
                <th scope="row">App Id</th>
                <td>
                 <input type="text" class="regular-text form-control" name="cbaffmach_icontact_app_id" placeholder="Enter your APP Id" value="<?php echo empty( $ar_data['icontact']['app_id'] ) ? '' : $ar_data['icontact']['app_id']; ?>">
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">Api Username</th>
                <td>
                 <input type="text" class="regular-text form-control" name="cbaffmach_icontact_user" placeholder="Enter your Api Username" value="<?php echo empty( $ar_data['icontact']['user'] ) ? '' : $ar_data['icontact']['user']; ?>">
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">Api Password</th>
                <td>
                 <input type="password" class="regular-text form-control" name="cbaffmach_icontact_pass" placeholder="Enter your Api Password" value="<?php echo empty( $ar_data['icontact']['pass'] ) ? '' : $ar_data['icontact']['pass']; ?>">
                    <span class="description">Get your API details <a target="_blank" href="http://developer.icontact.com/documentation/register-your-app/">here</a></span>
                </td>
            </tr>

        </table>

        <input type="hidden" name="cbaffmach_icontact_authorize" value="1">
        <button type="submit" class="button button-primary" ><i class="fa fa-sign-in"></i> Authorize </button>
        <?php if (isset($ar_data['icontact']['cache_expires']) && !empty($ar_data['icontact']['cache_expires'])) { ?>
            <button style="margin-left:20px;" type="button" class="icontact_refresh_lists button button-secondary"><i class="fa fa-refresh"></i> Refresh lists</button>
        <?php } ?>
    </form>

    <br/>
    </div>
    <div id="mailchimp-tab" class="tab-inner" style="padding: 20px">
    <h2><img src="<?php echo CBAFFMACH_URL;?>/img/icons/icon_mailchimp.png" style="vertical-align: top" /> Mailchimp  - <a href="https://wpautocontent.com/support/mailchimp" target="_blank" style="font-size: 0.9em">Get an account</a></h2>
    <form action="" method="post" class="form-horizontal">

        <table class="form-table">
            <tr valign="top">
                <th scope="row">API Key</th>
                <td>
                 <input type="text" class="form-control regular-text" name="cbaffmach_mailchimp_api" placeholder="Enter your Api Key" value="<?php echo empty( $ar_data['mailchimp']['apikey'] ) ? '' : $ar_data['mailchimp']['apikey']; ?>">
                 <span class="description">Get your API key <a target="_blank" href="http://kb.mailchimp.com/article/where-can-i-find-my-api-key">here</a></span>
                </td>
            </tr>
       </table>

                <input type="hidden" name="cbaffmach_mailchimp_authorize" value="1">
            <button type="submit" class="button button-primary" ><i class="fa fa-sign-in"></i> Authorize </button>
            <?php if (isset($ar_data['mailchimp']['cache_expires']) && !empty($ar_data['mailchimp']['cache_expires'])) { ?>
                <button style="margin-left:20px;" type="button" class="mailchimp_refresh_lists button button-secondary"><i class="fa fa-refresh"></i> Refresh lists</button>
            <?php } ?>

            <p><?php
                 // var_dump( cbaffmach_get_mailchimp_mailing_lists());
                  // cbaffmach_ar_constcontact_subscribe_user(2, 'raulmellado@gmail.com', 'Raul Mellado');
            // cbaffmach_ar_constcontact_move_subscriber(2, 1, 'raulmellado@gmail.com', 0);
            ?></p>
    </form>

    <br/>
    </div>
        <div id="ccontact-tab" class="tab-inner" style="padding: 20px">
        <h2><img src="<?php echo CBAFFMACH_URL;?>/img/icons/icon_constantcontact.png" style="vertical-align: top" /> Constant Contact  - <a href="https://wpautocontent.com/support/constantcontact" target="_blank" style="font-size: 0.9em">Get an account</a></h2>
        <form action="" method="post" class="form-horizontal">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">API Username</th>
                    <td>
                     <input type="text" class="form-control regular-text" name="cbaffmach_ccontact_user" placeholder="Enter your Api Username" value="<?php echo empty( $ar_data['ccontact']['user'] ) ? '' : $ar_data['ccontact']['user']; ?>">
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">API Password</th>
                    <td>
                        <input type="password" class="form-control regular-text" name="cbaffmach_ccontact_pass" placeholder="Enter your Api Password" value="<?php echo empty( $ar_data['ccontact']['pass'] ) ? '' : $ar_data['ccontact']['pass']; ?>">
                        <span class="description">Get your API details <a target="_blank" href="http://developer.constantcontact.com/">here</a></span>
                    </td>
                </tr>

           </table>

            <input type="hidden" name="cbaffmach_ccontact_authorize" value="1">
            <button type="submit" class="button button-primary" ><i class="fa fa-sign-in"></i> Authorize </button>
            <?php if (isset($ar_data['constantcontact']['cache_expires']) && !empty($ar_data['constantcontact']['cache_expires'])) { ?>
                <button style="margin-left:20px;" type="button" class="constantcontact_refresh_lists button button-secondary"><i class="fa fa-refresh"></i> Refresh lists</button>
            <?php } ?>

        </form>
                                <p><?php
                                     // var_dump( cbaffmach_get_constantcontact_mailing_lists());
                                      // cbaffmach_ar_constcontact_subscribe_user(2, 'raulmellado@gmail.com', 'Raul Mellado');
                                // cbaffmach_ar_constcontact_move_subscriber(2, 1, 'raulmellado@gmail.com', 0);
                                ?></p>
    <br/>
    </div>
        <div id="sendreach-tab" class="tab-inner" style="padding: 20px">
            <h2><img src="<?php echo CBAFFMACH_URL;?>/img/icons/icon_sendreach.png" style="vertical-align: top" /> SendReach  - <a href="https://wpautocontent.com/support/sendreach" target="_blank" style="font-size: 0.9em">Get an account</a></h2>

            <form action="" method="post" class="form-horizontal">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">User Id</th>
                        <td>
                         <input type="text" class="form-control regular-text" name="cbaffmach_sendreach_user_id" placeholder="Enter your User Id" value="<?php echo empty( $ar_data['sendreach']['user'] ) ? '' : $ar_data['sendreach']['user']; ?>">
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">Api KEY</th>
                        <td>
                         <input type="text" class="form-control regular-text" name="cbaffmach_sendreach_apikey" placeholder="Enter your Api Key" value="<?php echo empty( $ar_data['sendreach']['apikey'] ) ? '' : $ar_data['sendreach']['apikey']; ?>">
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">API Secret</th>
                        <td>
                            <input type="text" class="form-control regular-text" name="cbaffmach_sendreach_secret" placeholder="Enter your Api Secret" value="<?php echo empty( $ar_data['sendreach']['apisecret'] ) ? '' : $ar_data['sendreach']['apisecret']; ?>">
                            <span class="description">Get your API details <a target="_blank" href="http://developer.sendreach.com/documentation/register-your-app/">here</a></span>
                        </td>
                    </tr>
                 </table>

                <input type="hidden" name="cbaffmach_sendreach_authorize" value="1">
                <button type="submit" class="button button-primary" ><i class="fa fa-sign-in"></i> Authorize </button>
                <?php if (isset($ar_data['sendreach']['cache_expires']) && !empty($ar_data['sendreach']['cache_expires'])) { ?>
                    <button style="margin-left:20px;" type="button" class="sendreach_refresh_lists button button-secondary"><i class="fa fa-refresh"></i> Refresh lists</button>
                <?php } ?>

                <p><?php

                // var_dump( cbaffmach_get_sendreach_mailing_lists(1));
                 // cbaffmach_ar_sendreach_subscribe_user(14592, 'pepe2@pepito2.com', 'Pepito 2', 'Perez 2');
                // cbaffmach_ar_sendreach_move_subscriber();
                ?></p>
            </form>

            <br/>
            </div>
                <div id="activecampaign-tab" class="tab-inner" style="padding: 20px">
                <h2><img src="<?php echo CBAFFMACH_URL;?>/img/icons/icon_activecampaign.png" style="vertical-align: top" /> Active Campaign  - <a href="https://wpautocontent.com/support/activecampaign" target="_blank" style="font-size: 0.9em">Get an account</a></h2>
                <form action="" method="post" class="form-horizontal">

                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">URL</th>
                            <td>
                             <input type="text" class="form-control regular-text" name="cbaffmach_activecampaign_url" placeholder="Enter your Active Campaign URL" value="<?php echo empty( $ar_data['activecampaign']['url'] ) ? '' : $ar_data['activecampaign']['url']; ?>">
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Api KEY</th>
                            <td>
                             <input type="text" class="form-control regular-text" name="cbaffmach_activecampaign_apikey" placeholder="Enter your Api Key" value="<?php echo empty( $ar_data['activecampaign']['apikey'] ) ? '' : $ar_data['activecampaign']['apikey']; ?>">
                            </td>
                        </tr>
                     </table>

                    <input type="hidden" name="cbaffmach_activecampaign_authorize" value="1">
                    <button type="submit" class="button button-primary" ><i class="fa fa-sign-in"></i> Authorize </button>
                    <?php if (isset($ar_data['activecampaign']['cache_expires']) && !empty($ar_data['activecampaign']['cache_expires'])) { ?>
                        <button style="margin-left:20px;" type="button" class="activecampaign_refresh_lists button button-secondary"><i class="fa fa-refresh"></i> Refresh lists</button>
                    <?php } ?>

                    <p><?php

                     // var_dump( cbaffmach_get_activecampaign_mailing_lists(1));
                     // cbaffmach_ar_activecampaign_subscribe_user(2, 'pepe3@pepito3.com', 'Pepito 2', 'Perez 2');
                    // cbaffmach_ar_sendreach_move_subscriber();
                    ?></p>
                </form>
                <br/>
                </div>
                    <div id="sendlane-tab" class="tab-inner" style="padding: 20px">
                    <h2><img src="<?php echo CBAFFMACH_URL;?>/img/icons/icon_sendlane.png" style="vertical-align: top" /> Sendlane   - <a href="https://wpautocontent.com/support/sendlane" target="_blank" style="font-size: 0.9em">Get an account</a></h2>



                                            <form action="" method="post" class="form-horizontal">

                                                            <table class="form-table">
                                                                <tr valign="top">
                                                                    <th scope="row">Subdomain</th>
                                                                    <td>
                                                                     <input type="text" class="form-control regular-text" name="cbaffmach_sendlane_url" placeholder="Enter your User Id" value="<?php echo empty( $ar_data['sendlane']['url'] ) ? '' : $ar_data['sendlane']['url']; ?>">
                                                                     <span class="description">Ex, if your url is http://yoursite.sendlane.com, enter yoursite</span>
                                                                    </td>
                                                                </tr>

                                                                <tr valign="top">
                                                                    <th scope="row">Api KEY</th>
                                                                    <td>
                                                                     <input type="text" class="form-control regular-text" name="cbaffmach_sendlane_apikey" placeholder="Enter your Api Key" value="<?php echo empty( $ar_data['sendlane']['apikey'] ) ? '' : $ar_data['sendlane']['apikey']; ?>">
                                                                    </td>
                                                                </tr>

                                                                <tr valign="top">
                                                                    <th scope="row">Hash KEY</th>
                                                                    <td>
                                                                     <input type="text" class="form-control regular-text" name="cbaffmach_sendlane_hashkey" placeholder="Enter your Hash Key" value="<?php echo empty( $ar_data['sendlane']['hashkey'] ) ? '' : $ar_data['sendlane']['hashkey']; ?>">
                                                                    </td>
                                                                </tr>
                                                             </table>
                                                                <input type="hidden" name="cbaffmach_sendlane_authorize" value="1">

                                                                <button type="submit" class="button button-primary" ><i class="fa fa-sign-in"></i> Authorize </button>
                                                                <?php if (isset($ar_data['sendlane']['cache_expires']) && !empty($ar_data['sendlane']['cache_expires'])) { ?>
                                                                    <button style="margin-left:20px;" type="button" class="sendlane_refresh_lists button button-secondary"><i class="fa fa-refresh"></i> Refresh lists</button>
                                                                <?php } ?>

                                                <p><?php

                                                 // var_dump( cbaffmach_get_sendlane_mailing_lists(1));
                                                 // cbaffmach_ar_sendlane_subscribe_user(1, 'pepe3@pepito3.com', 'Pepito 2', 'Perez 2');
                                                // cbaffmach_ar_sendreach_move_subscriber();
                                                ?></p>
                                            </form>
                                    </div>
                                    </div>
                                    </div>
<?php
}
?>