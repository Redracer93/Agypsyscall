<?php

include CBAFFMACH_DIR.'/lib/monetize/amazon.php';
include CBAFFMACH_DIR.'/lib/monetize/ebay.php';
include CBAFFMACH_DIR.'/lib/monetize/clickbank.php';
include CBAFFMACH_DIR.'/lib/monetize/aliexpress.php';
include CBAFFMACH_DIR.'/lib/monetize/walmart.php';
include CBAFFMACH_DIR.'/lib/monetize/bestbuy.php';
include CBAFFMACH_DIR.'/lib/monetize/envato.php';
include CBAFFMACH_DIR.'/lib/monetize/gearbest.php';
include CBAFFMACH_DIR.'/lib/monetize/optin.php';

function cbaffmach_save_settings() {
    $settings = cbaffmach_get_plugin_settings();
    $autoresponders = isset( $settings['autoresponders'] ) ? $settings['autoresponders'] : array();
    $per_day = isset( $_POST['cbaffmach_perday'] ) ? intval( $_POST['cbaffmach_perday'] ) : 0;
    $every = isset( $_POST['cbaffmach_every'] ) ? intval( $_POST['cbaffmach_every'] ) : 0;
    $status = isset( $_POST['cbaffmach_poststatus'] ) ? trim( $_POST['cbaffmach_poststatus'] ) : 'publish';
    $cat = isset( $_POST['cbaffmach_category'] ) ? intval( $_POST['cbaffmach_category'] ) : 0;
    $featured = isset( $_POST['cbaffmach_featured'] ) ? intval( $_POST['cbaffmach_featured'] ) : 0;
    $tags = isset( $_POST['cbaffmach_tags'] ) ? intval( $_POST['cbaffmach_tags'] ) : 0;
    $jvzooid = isset( $_POST['cbaffmach_jvzooid'] ) ? trim( $_POST['cbaffmach_jvzooid'] ) : '';
    $clickbankid = isset( $_POST['cbaffmach_clickbankid'] ) ? trim( $_POST['cbaffmach_clickbankid'] ) : '';
    $wplusid = isset( $_POST['cbaffmach_wplusid'] ) ? trim( $_POST['cbaffmach_wplusid'] ) : '';
    $shareauto = isset( $_POST['cbaffmach_shareauto'] ) ? intval( $_POST['cbaffmach_shareauto'] ) : 0;
    $share_facebook = isset( $_POST['cbaffmach_share_facebook'] ) ? intval( $_POST['cbaffmach_share_facebook'] ) : 0;
    $share_twitter = isset( $_POST['cbaffmach_share_twitter'] ) ? intval( $_POST['cbaffmach_share_twitter'] ) : 0;
    $share_medium = isset( $_POST['cbaffmach_share_medium'] ) ? intval( $_POST['cbaffmach_share_medium'] ) : 0;
    $share_tumblr = isset( $_POST['cbaffmach_share_tumblr'] ) ? intval( $_POST['cbaffmach_share_tumblr'] ) : 0;
    $share_linkedin = isset( $_POST['cbaffmach_share_linkedin'] ) ? intval( $_POST['cbaffmach_share_linkedin'] ) : 0;
    $share_buffer = isset( $_POST['cbaffmach_share_buffer'] ) ? intval( $_POST['cbaffmach_share_buffer'] ) : 0;
    $bmachine = isset( $_POST['cbaffmach_bmachine'] ) ? intval( $_POST['cbaffmach_bmachine'] ) : 0;
    $bmachine_num = isset( $_POST['cbaffmach_bmachine_num'] ) ? intval( $_POST['cbaffmach_bmachine_num'] ) : 0;
    $blindexer = isset( $_POST['cbaffmach_blindexer'] ) ? intval( $_POST['cbaffmach_blindexer'] ) : 0;
    $ilindexer = isset( $_POST['cbaffmach_ilindexer'] ) ? intval( $_POST['cbaffmach_ilindexer'] ) : 0;
    $spin = isset( $_POST['cbaffmach_spin'] ) ? intval( $_POST['cbaffmach_spin'] ) : 0;
    $createvideo = isset( $_POST['cbaffmach_createvideo'] ) ? intval( $_POST['cbaffmach_createvideo'] ) : 0;

    $amazon_key = isset( $_POST['cbaffmach_amazon_key'] ) ? trim( $_POST['cbaffmach_amazon_key'] ) : '';
    $amazon_secret = isset( $_POST['cbaffmach_amazon_secret'] ) ? trim( $_POST['cbaffmach_amazon_secret'] ) : '';
    $amazon_tag = isset( $_POST['cbaffmach_amazon_tag'] ) ? trim( $_POST['cbaffmach_amazon_tag'] ) : '';
    $amazon_country = isset( $_POST['cbaffmach_amazon_country'] ) ? trim( $_POST['cbaffmach_amazon_country'] ) : 'com';
    $amazon_enabled = isset( $_POST['cbaffmach_amazon_enabled'] ) ? intval( $_POST['cbaffmach_amazon_enabled'] ) : 0;
    $amazon_keywords = isset( $_POST['cbaffmach_amazon_keywords'] ) ? trim( $_POST['cbaffmach_amazon_keywords'] ) : '';
    $amazon_category = isset( $_POST['cbaffmach_amazon_category'] ) ? trim( $_POST['cbaffmach_amazon_category'] ) : '';
    $amazon_display_price = isset( $_POST['cbaffmach_amazon_display_price'] ) ? intval( $_POST['cbaffmach_amazon_display_price'] ) : 0;
    $amazon_display_image = isset( $_POST['cbaffmach_amazon_display_image'] ) ? intval( $_POST['cbaffmach_amazon_display_image'] ) : 0;
    $amazon_num_ads = isset( $_POST['cbaffmach_amazon_num_ads'] ) ? intval( $_POST['cbaffmach_amazon_num_ads'] ) : 0;
    $amazon_per_row = isset( $_POST['cbaffmach_amazon_per_row'] ) ? intval( $_POST['cbaffmach_amazon_per_row'] ) : 0;
    $amazon_enabled = isset( $_POST['cbaffmach_amazon_enabled'] ) ? intval( $_POST['cbaffmach_amazon_enabled'] ) : 0;
    $amazon_txt = isset( $_POST['cbaffmach_amazon_txt'] ) ? trim( $_POST['cbaffmach_amazon_txt'] ) : '';
    $amazon_position = isset( $_POST['cbaffmach_amazon_position'] ) ? intval( $_POST['cbaffmach_amazon_position'] ) : 0;
    $amazon_buy_button = isset( $_POST['cbaffmach_amazon_buy_button'] ) ? trim( $_POST['cbaffmach_amazon_buy_button'] ) : '';

    $clickbank_id = isset( $_POST['cbaffmach_clickbank_id'] ) ? trim( $_POST['cbaffmach_clickbank_id'] ) : '';
    $clickbank_enabled = isset( $_POST['cbaffmach_clickbank_enabled'] ) ? intval( $_POST['cbaffmach_clickbank_enabled'] ) : 0;
    $clickbank_popular = isset( $_POST['cbaffmach_clickbank_popular'] ) ? intval( $_POST['cbaffmach_clickbank_popular'] ) : 0;
    $clickbank_keywords = isset( $_POST['cbaffmach_clickbank_keywords'] ) ? trim( $_POST['cbaffmach_clickbank_keywords'] ) : '';
    // $clickbank_category = isset( $_POST['cbaffmach_clickbank_category'] ) ? trim( $_POST['cbaffmach_clickbank_category'] ) : '';
    $clickbank_display_price = isset( $_POST['cbaffmach_clickbank_display_price'] ) ? intval( $_POST['cbaffmach_clickbank_display_price'] ) : 0;
    $clickbank_display_image = isset( $_POST['cbaffmach_clickbank_display_image'] ) ? intval( $_POST['cbaffmach_clickbank_display_image'] ) : 0;
    $clickbank_num_ads = isset( $_POST['cbaffmach_clickbank_num_ads'] ) ? intval( $_POST['cbaffmach_clickbank_num_ads'] ) : 0;
    $clickbank_per_row = isset( $_POST['cbaffmach_clickbank_per_row'] ) ? intval( $_POST['cbaffmach_clickbank_per_row'] ) : 0;
    $clickbank_enabled = isset( $_POST['cbaffmach_clickbank_enabled'] ) ? intval( $_POST['cbaffmach_clickbank_enabled'] ) : 0;
    $clickbank_txt = isset( $_POST['cbaffmach_clickbank_txt'] ) ? trim( $_POST['cbaffmach_clickbank_txt'] ) : '';
    $clickbank_position = isset( $_POST['cbaffmach_clickbank_position'] ) ? intval( $_POST['cbaffmach_clickbank_position'] ) : 0;
    $clickbank_buy_button = isset( $_POST['cbaffmach_clickbank_buy_button'] ) ? trim( $_POST['cbaffmach_clickbank_buy_button'] ) : '';

    $ebay_appid = isset( $_POST['cbaffmach_ebay_appid'] ) ? trim( $_POST['cbaffmach_ebay_appid'] ) : '';
    $ebay_campaignid = isset( $_POST['cbaffmach_ebay_cid'] ) ? trim( $_POST['cbaffmach_ebay_cid'] ) : '';
    $ebay_country = isset( $_POST['cbaffmach_ebay_country'] ) ? trim( $_POST['cbaffmach_ebay_country'] ) : 'com';
    $ebay_enabled = isset( $_POST['cbaffmach_ebay_enabled'] ) ? intval( $_POST['cbaffmach_ebay_enabled'] ) : 0;
    $ebay_keywords = isset( $_POST['cbaffmach_ebay_keywords'] ) ? trim( $_POST['cbaffmach_ebay_keywords'] ) : '';
    $ebay_category = isset( $_POST['cbaffmach_ebay_category'] ) ? trim( $_POST['cbaffmach_ebay_category'] ) : '';
    $ebay_display_price = isset( $_POST['cbaffmach_ebay_display_price'] ) ? intval( $_POST['cbaffmach_ebay_display_price'] ) : 0;
    $ebay_display_image = isset( $_POST['cbaffmach_ebay_display_image'] ) ? intval( $_POST['cbaffmach_ebay_display_image'] ) : 0;
    $ebay_num_ads = isset( $_POST['cbaffmach_ebay_num_ads'] ) ? intval( $_POST['cbaffmach_ebay_num_ads'] ) : 0;
    $ebay_per_row = isset( $_POST['cbaffmach_ebay_per_row'] ) ? intval( $_POST['cbaffmach_ebay_per_row'] ) : 0;
    $ebay_enabled = isset( $_POST['cbaffmach_ebay_enabled'] ) ? intval( $_POST['cbaffmach_ebay_enabled'] ) : 0;
    $ebay_txt = isset( $_POST['cbaffmach_ebay_txt'] ) ? trim( $_POST['cbaffmach_ebay_txt'] ) : '';
    $ebay_position = isset( $_POST['cbaffmach_ebay_position'] ) ? intval( $_POST['cbaffmach_ebay_position'] ) : 0;
    $ebay_buy_button = isset( $_POST['cbaffmach_ebay_buy_button'] ) ? trim( $_POST['cbaffmach_ebay_buy_button'] ) : '';

    $aliexpress_api = isset( $_POST['cbaffmach_aliexpress_api'] ) ? trim( $_POST['cbaffmach_aliexpress_api'] ) : '';
    $aliexpress_hash = isset( $_POST['cbaffmach_aliexpress_hash'] ) ? trim( $_POST['cbaffmach_aliexpress_hash'] ) : '';
    $aliexpress_enabled = isset( $_POST['cbaffmach_aliexpress_enabled'] ) ? intval( $_POST['cbaffmach_aliexpress_enabled'] ) : 0;
    $aliexpress_keywords = isset( $_POST['cbaffmach_aliexpress_keywords'] ) ? trim( $_POST['cbaffmach_aliexpress_keywords'] ) : '';
    $aliexpress_category = isset( $_POST['cbaffmach_aliexpress_category'] ) ? trim( $_POST['cbaffmach_aliexpress_category'] ) : '';
    $aliexpress_display_price = isset( $_POST['cbaffmach_aliexpress_display_price'] ) ? intval( $_POST['cbaffmach_aliexpress_display_price'] ) : 0;
    $aliexpress_display_image = isset( $_POST['cbaffmach_aliexpress_display_image'] ) ? intval( $_POST['cbaffmach_aliexpress_display_image'] ) : 0;
    $aliexpress_num_ads = isset( $_POST['cbaffmach_aliexpress_num_ads'] ) ? intval( $_POST['cbaffmach_aliexpress_num_ads'] ) : 0;
    $aliexpress_per_row = isset( $_POST['cbaffmach_aliexpress_per_row'] ) ? intval( $_POST['cbaffmach_aliexpress_per_row'] ) : 0;
    $aliexpress_enabled = isset( $_POST['cbaffmach_aliexpress_enabled'] ) ? intval( $_POST['cbaffmach_aliexpress_enabled'] ) : 0;
    $aliexpress_txt = isset( $_POST['cbaffmach_aliexpress_txt'] ) ? trim( $_POST['cbaffmach_aliexpress_txt'] ) : '';
    $aliexpress_position = isset( $_POST['cbaffmach_aliexpress_position'] ) ? intval( $_POST['cbaffmach_aliexpress_position'] ) : 0;
    $aliexpress_buy_button = isset( $_POST['cbaffmach_aliexpress_buy_button'] ) ? trim( $_POST['cbaffmach_aliexpress_buy_button'] ) : '';

    $walmart_api = isset( $_POST['cbaffmach_walmart_api'] ) ? trim( $_POST['cbaffmach_walmart_api'] ) : '';
    $walmart_aff_id = isset( $_POST['cbaffmach_walmart_aff_id'] ) ? trim( $_POST['cbaffmach_walmart_aff_id'] ) : '';
    $walmart_enabled = isset( $_POST['cbaffmach_walmart_enabled'] ) ? intval( $_POST['cbaffmach_walmart_enabled'] ) : 0;
    $walmart_keywords = isset( $_POST['cbaffmach_walmart_keywords'] ) ? trim( $_POST['cbaffmach_walmart_keywords'] ) : '';
    $walmart_category = isset( $_POST['cbaffmach_walmart_category'] ) ? trim( $_POST['cbaffmach_walmart_category'] ) : '';
    $walmart_display_price = isset( $_POST['cbaffmach_walmart_display_price'] ) ? intval( $_POST['cbaffmach_walmart_display_price'] ) : 0;
    $walmart_display_image = isset( $_POST['cbaffmach_walmart_display_image'] ) ? intval( $_POST['cbaffmach_walmart_display_image'] ) : 0;
    $walmart_num_ads = isset( $_POST['cbaffmach_walmart_num_ads'] ) ? intval( $_POST['cbaffmach_walmart_num_ads'] ) : 0;
    $walmart_per_row = isset( $_POST['cbaffmach_walmart_per_row'] ) ? intval( $_POST['cbaffmach_walmart_per_row'] ) : 0;
    $walmart_enabled = isset( $_POST['cbaffmach_walmart_enabled'] ) ? intval( $_POST['cbaffmach_walmart_enabled'] ) : 0;
    $walmart_txt = isset( $_POST['cbaffmach_walmart_txt'] ) ? trim( $_POST['cbaffmach_walmart_txt'] ) : '';
    $walmart_position = isset( $_POST['cbaffmach_walmart_position'] ) ? intval( $_POST['cbaffmach_walmart_position'] ) : 0;
    $walmart_buy_button = isset( $_POST['cbaffmach_walmart_buy_button'] ) ? trim( $_POST['cbaffmach_walmart_buy_button'] ) : '';

    $bestbuy_api = isset( $_POST['cbaffmach_bestbuy_api'] ) ? trim( $_POST['cbaffmach_bestbuy_api'] ) : '';
    // $bestbuy_aff_id = isset( $_POST['cbaffmach_bestbuy_aff_id'] ) ? trim( $_POST['cbaffmach_bestbuy_aff_id'] ) : '';
    $bestbuy_enabled = isset( $_POST['cbaffmach_bestbuy_enabled'] ) ? intval( $_POST['cbaffmach_bestbuy_enabled'] ) : 0;
    $bestbuy_keywords = isset( $_POST['cbaffmach_bestbuy_keywords'] ) ? trim( $_POST['cbaffmach_bestbuy_keywords'] ) : '';
    $bestbuy_category = isset( $_POST['cbaffmach_bestbuy_category'] ) ? trim( $_POST['cbaffmach_bestbuy_category'] ) : '';
    $bestbuy_display_price = isset( $_POST['cbaffmach_bestbuy_display_price'] ) ? intval( $_POST['cbaffmach_bestbuy_display_price'] ) : 0;
    $bestbuy_display_image = isset( $_POST['cbaffmach_bestbuy_display_image'] ) ? intval( $_POST['cbaffmach_bestbuy_display_image'] ) : 0;
    $bestbuy_num_ads = isset( $_POST['cbaffmach_bestbuy_num_ads'] ) ? intval( $_POST['cbaffmach_bestbuy_num_ads'] ) : 0;
    $bestbuy_per_row = isset( $_POST['cbaffmach_bestbuy_per_row'] ) ? intval( $_POST['cbaffmach_bestbuy_per_row'] ) : 0;
    $bestbuy_enabled = isset( $_POST['cbaffmach_bestbuy_enabled'] ) ? intval( $_POST['cbaffmach_bestbuy_enabled'] ) : 0;
    $bestbuy_txt = isset( $_POST['cbaffmach_bestbuy_txt'] ) ? trim( $_POST['cbaffmach_bestbuy_txt'] ) : '';
    $bestbuy_position = isset( $_POST['cbaffmach_bestbuy_position'] ) ? intval( $_POST['cbaffmach_bestbuy_position'] ) : 0;
    $bestbuy_buy_button = isset( $_POST['cbaffmach_bestbuy_buy_button'] ) ? trim( $_POST['cbaffmach_bestbuy_buy_button'] ) : '';


    $envato_api = isset( $_POST['cbaffmach_envato_api'] ) ? trim( $_POST['cbaffmach_envato_api'] ) : '';
    $envato_username = isset( $_POST['cbaffmach_envato_username'] ) ? trim( $_POST['cbaffmach_envato_username'] ) : '';
    $envato_enabled = isset( $_POST['cbaffmach_envato_enabled'] ) ? intval( $_POST['cbaffmach_envato_enabled'] ) : 0;
    $envato_keywords = isset( $_POST['cbaffmach_envato_keywords'] ) ? trim( $_POST['cbaffmach_envato_keywords'] ) : '';
    $envato_category = isset( $_POST['cbaffmach_envato_category'] ) ? trim( $_POST['cbaffmach_envato_category'] ) : '';
    $envato_display_price = isset( $_POST['cbaffmach_envato_display_price'] ) ? intval( $_POST['cbaffmach_envato_display_price'] ) : 0;
    $envato_display_image = isset( $_POST['cbaffmach_envato_display_image'] ) ? intval( $_POST['cbaffmach_envato_display_image'] ) : 0;
    $envato_num_ads = isset( $_POST['cbaffmach_envato_num_ads'] ) ? intval( $_POST['cbaffmach_envato_num_ads'] ) : 0;
    $envato_per_row = isset( $_POST['cbaffmach_envato_per_row'] ) ? intval( $_POST['cbaffmach_envato_per_row'] ) : 0;
    $envato_enabled = isset( $_POST['cbaffmach_envato_enabled'] ) ? intval( $_POST['cbaffmach_envato_enabled'] ) : 0;
    $envato_txt = isset( $_POST['cbaffmach_envato_txt'] ) ? trim( $_POST['cbaffmach_envato_txt'] ) : '';
    $envato_position = isset( $_POST['cbaffmach_envato_position'] ) ? intval( $_POST['cbaffmach_envato_position'] ) : 0;
    $envato_buy_button = isset( $_POST['cbaffmach_envato_buy_button'] ) ? trim( $_POST['cbaffmach_envato_buy_button'] ) : '';

    $gearbest_appid = isset( $_POST['cbaffmach_gearbest_appid'] ) ? trim( $_POST['cbaffmach_gearbest_appid'] ) : '';
    $gearbest_appsecret = isset( $_POST['cbaffmach_gearbest_appsecret'] ) ? trim( $_POST['cbaffmach_gearbest_appsecret'] ) : '';
    $gearbest_deeplink = isset( $_POST['cbaffmach_gearbest_deeplink'] ) ? trim( $_POST['cbaffmach_gearbest_deeplink'] ) : '';
    $gearbest_enabled = isset( $_POST['cbaffmach_gearbest_enabled'] ) ? intval( $_POST['cbaffmach_gearbest_enabled'] ) : 0;
    $gearbest_keywords = isset( $_POST['cbaffmach_gearbest_keywords'] ) ? trim( $_POST['cbaffmach_gearbest_keywords'] ) : '';
    $gearbest_category = isset( $_POST['cbaffmach_gearbest_category'] ) ? trim( $_POST['cbaffmach_gearbest_category'] ) : '';
    $gearbest_display_price = isset( $_POST['cbaffmach_gearbest_display_price'] ) ? intval( $_POST['cbaffmach_gearbest_display_price'] ) : 0;
    $gearbest_display_image = isset( $_POST['cbaffmach_gearbest_display_image'] ) ? intval( $_POST['cbaffmach_gearbest_display_image'] ) : 0;
    $gearbest_num_ads = isset( $_POST['cbaffmach_gearbest_num_ads'] ) ? intval( $_POST['cbaffmach_gearbest_num_ads'] ) : 0;
    $gearbest_per_row = isset( $_POST['cbaffmach_gearbest_per_row'] ) ? intval( $_POST['cbaffmach_gearbest_per_row'] ) : 0;
    $gearbest_enabled = isset( $_POST['cbaffmach_gearbest_enabled'] ) ? intval( $_POST['cbaffmach_gearbest_enabled'] ) : 0;
    $gearbest_txt = isset( $_POST['cbaffmach_gearbest_txt'] ) ? trim( $_POST['cbaffmach_gearbest_txt'] ) : '';
    $gearbest_position = isset( $_POST['cbaffmach_gearbest_position'] ) ? intval( $_POST['cbaffmach_gearbest_position'] ) : 0;
    $gearbest_buy_button = isset( $_POST['cbaffmach_gearbest_buy_button'] ) ? trim( $_POST['cbaffmach_gearbest_buy_button'] ) : '';

    $ad1_code = isset( $_POST['cbaffmach_adsense1_ad'] ) ? trim( $_POST['cbaffmach_adsense1_ad'] ) : '';
    $ad1_pos = isset( $_POST['cbaffmach_adsense1_pos'] ) ? intval( $_POST['cbaffmach_adsense1_pos'] ) : 0;

    $ad2_code = isset( $_POST['cbaffmach_adsense2_ad'] ) ? trim( $_POST['cbaffmach_adsense2_ad'] ) : '';
    $ad2_pos = isset( $_POST['cbaffmach_adsense2_pos'] ) ? intval( $_POST['cbaffmach_adsense2_pos'] ) : 0;

    $ad3_code = isset( $_POST['cbaffmach_adsense3_ad'] ) ? trim( $_POST['cbaffmach_adsense3_ad'] ) : '';
    $ad3_pos = isset( $_POST['cbaffmach_adsense3_pos'] ) ? intval( $_POST['cbaffmach_adsense3_pos'] ) : 0;

    $banner1_img = isset( $_POST['cbaffmach_banner1_img'] ) ? trim( $_POST['cbaffmach_banner1_img'] ) : '';
    $banner1_link = isset( $_POST['cbaffmach_banner1_link'] ) ? trim( $_POST['cbaffmach_banner1_link'] ) : '';
    $banner1_newwin = isset( $_POST['cbaffmach_banner1_newwin'] ) ? intval( $_POST['cbaffmach_banner1_newwin'] ) : 0;
    $banner1_pos = isset( $_POST['cbaffmach_banner1_pos'] ) ? intval( $_POST['cbaffmach_banner1_pos'] ) : 0;

    $banner2_img = isset( $_POST['cbaffmach_banner2_img'] ) ? trim( $_POST['cbaffmach_banner2_img'] ) : '';
    $banner2_link = isset( $_POST['cbaffmach_banner2_link'] ) ? trim( $_POST['cbaffmach_banner2_link'] ) : '';
    $banner2_newwin = isset( $_POST['cbaffmach_banner2_newwin'] ) ? intval( $_POST['cbaffmach_banner2_newwin'] ) : 0;
    $banner2_pos = isset( $_POST['cbaffmach_banner2_pos'] ) ? intval( $_POST['cbaffmach_banner2_pos'] ) : 0;

    $banner3_img = isset( $_POST['cbaffmach_banner3_img'] ) ? trim( $_POST['cbaffmach_banner3_img'] ) : '';
    $banner3_link = isset( $_POST['cbaffmach_banner3_link'] ) ? trim( $_POST['cbaffmach_banner3_link'] ) : '';
    $banner3_newwin = isset( $_POST['cbaffmach_banner3_newwin'] ) ? intval( $_POST['cbaffmach_banner3_newwin'] ) : 0;
    $banner3_pos = isset( $_POST['cbaffmach_banner3_pos'] ) ? intval( $_POST['cbaffmach_banner3_pos'] ) : 0;

    $scrolling_enabled = isset( $_POST['cbaffmach_scrolling_enable'] ) ? intval( $_POST['cbaffmach_scrolling_enable'] ) : 0;
    $scrolling_video = isset( $_POST['cbaffmach_scrolling_video'] ) ? intval( $_POST['cbaffmach_scrolling_video'] ) : 0;
    $scrolling_txt = isset( $_POST['cbaffmach_scrolling_txt'] ) ? trim( $_POST['cbaffmach_scrolling_txt'] ) : '';
    $scrolling_button_txt = isset( $_POST['cbaffmach_scrolling_button_txt'] ) ? trim( $_POST['cbaffmach_scrolling_button_txt'] ) : '';
    $related_enabled = isset( $_POST['cbaffmach_related_enable'] ) ? intval( $_POST['cbaffmach_related_enable'] ) : 0;

    $disclaimer_enabled = isset( $_POST['cbaffmach_disclaimer_enable'] ) ? intval( $_POST['cbaffmach_disclaimer_enable'] ) : 0;
    $disclaimer_txt = isset( $_POST['cbaffmach_disclaimer_txt'] ) ? trim( $_POST['cbaffmach_disclaimer_txt'] ) : '';
    $disclaimer_affid = isset( $_POST['cbaffmach_disclaimer_affid'] ) ? intval( $_POST['cbaffmach_disclaimer_affid'] ) : 0;

    // 5. Social
    $twitter_key = isset( $_POST['cbaffmach_twitter_key'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_twitter_key'] ) ) : '';
    $twitter_secret = isset( $_POST['cbaffmach_twitter_secret'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_twitter_secret'] ) ) : '';
    $oauth_token = isset( $_POST['cbaffmach_twitter_token'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_twitter_token'] ) ) : '';
    $oauth_secret = isset( $_POST['cbaffmach_twitter_oauth_secret'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_twitter_oauth_secret'] ) ) : '';

    $facebook_app_id = isset( $_POST['cbaffmach_facebook_app_id'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_facebook_app_id'] ) ) : '';
    $facebook_secret = isset( $_POST['cbaffmach_facebook_app_secret'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_facebook_app_secret'] ) ) : '';
    $facebook_token = isset( $settings['social']['facebook']['token'] ) ? $settings['social']['facebook']['token'] : false;
 /*   $pinterest_email = isset( $_POST['cbaffmach_pinterest_email'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_pinterest_email'] ) ) : '';
    $pinterest_pass = isset( $_POST['cbaffmach_pinterest_pass'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_pinterest_pass'] ) ) : '';
    $pinterest_app_id = isset( $_POST['cbaffmach_pinterest_app_id'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_pinterest_app_id'] ) ) : '';
    $pinterest_secret = isset( $_POST['cbaffmach_pinterest_app_secret'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_pinterest_app_secret'] ) ) : '';

    $stumbleupon_email = isset( $_POST['cbaffmach_stumbleupon_email'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_stumbleupon_email'] ) ) : '';
    $stumbleupon_pass = isset( $_POST['cbaffmach_stumbleupon_pass'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_stumbleupon_pass'] ) ) : '';

    $googleplus_email = isset( $_POST['cbaffmach_googleplus_email'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_googleplus_email'] ) ) : '';
    $googleplus_pass = isset( $_POST['cbaffmach_googleplus_pass'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_googleplus_pass'] ) ) : '';
    $googleplus_apikey = isset( $_POST['cbaffmach_googleplus_apikey'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_googleplus_apikey'] ) ) : '';

    $instagram_email = isset( $_POST['cbaffmach_instagram_email'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_instagram_email'] ) ) : '';
    $instagram_pass = isset( $_POST['cbaffmach_instagram_pass'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_instagram_pass'] ) ) : '';

    $reddit_email = isset( $_POST['cbaffmach_reddit_email'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_reddit_email'] ) ) : '';
    $reddit_pass = isset( $_POST['cbaffmach_reddit_pass'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_reddit_pass'] ) ) : '';
*/
    $medium_token = isset( $_POST['cbaffmach_medium_token'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_medium_token'] ) ) : '';

    $tumblr_key = isset( $_POST['cbaffmach_tumblr_key'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_tumblr_key'] ) ) : '';
    $tumblr_secret = isset( $_POST['cbaffmach_tumblr_secret'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_tumblr_secret'] ) ) : '';
    $tumblr_oauth_token = isset( $_POST['cbaffmach_tumblr_token'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_tumblr_token'] ) ) : '';
    $tumblr_oauth_secret = isset( $_POST['cbaffmach_tumblr_oauth_secret'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_tumblr_oauth_secret'] ) ) : '';

    $linkedin_app_id = isset( $_POST['cbaffmach_linkedin_app_id'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_linkedin_app_id'] ) ) : '';
    $linkedin_secret = isset( $_POST['cbaffmach_linkedin_app_secret'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_linkedin_app_secret'] ) ) : '';

    $buffer_client_id = isset( $_POST['cbaffmach_buffer_client_id'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_buffer_client_id'] ) ) : '';
    $buffer_client_secret = isset( $_POST['cbaffmach_buffer_client_secret'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_buffer_client_secret'] ) ) : '';

    $settings_social = array(
        'twitter' => array(
            'key' => $twitter_key,
            'secret' => $twitter_secret,
            'oauth_token' => $oauth_token,
            'oauth_secret' => $oauth_secret
        ),
        'facebook' => array(
            'app_id' => $facebook_app_id,
            'app_secret' => $facebook_secret,
            'token' => $facebook_token
        ),
  /*      'pinterest' => array(
            'email' => $pinterest_email,
            'pass' => $pinterest_pass,
            'app_id' => $pinterest_app_id,
            'app_secret' => $pinterest_secret
        ),
        'stumbleupon' => array(
            'email' => $stumbleupon_email,
            'pass' => $stumbleupon_pass
        ),
        'reddit' => array(
            'email' => $reddit_email,
            'pass' => $reddit_pass
        ),
        'instagram' => array(
            'email' => $instagram_email,
            'pass' => $instagram_pass
        ),
        'googleplus' => array(
            'email' => $googleplus_email,
            'pass' => $googleplus_pass,
            'key' => $googleplus_apikey
        ),*/
        'medium' => array(
            'token' => $medium_token
        ),
        'tumblr' => array(
            'key' => $tumblr_key,
            'secret' => $tumblr_secret,
            'oauth_token' => $tumblr_oauth_token,
            'oauth_secret' => $tumblr_oauth_secret
        ),
        'linkedin' => array(
            'app_id' => $linkedin_app_id,
            'app_secret' => $linkedin_secret
        ),
        'buffer' => array(
            'client_id' => $buffer_client_id,
            'client_secret' => $buffer_client_secret
        ),
    );



    $settings = array(
        'import' => array(
            'enable_importing' => 1,
            'per_day' => $per_day,
            'every' => $every,
            'cat' => $cat,
            'post_status' => $status,
            'featured' => $featured,
            'tags' => $tags,
            'jvzooid' => $jvzooid,
            'clickbankid' => $clickbankid,
            'wplusid' => $wplusid,
            'shareauto' => $shareauto,
            'sharingsites' => array(
                'facebook' => $share_facebook,
                'twitter' => $share_twitter,
                'medium' => $share_medium,
                'tumblr' => $share_tumblr,
                'linkedin' => $share_linkedin,
                'buffer' => $share_buffer
            ),
            'bmachine' => $bmachine,
            'bmachine_num' => $bmachine_num,
            'blindexer' => $blindexer,
            'ilindexer' => $ilindexer,
            'spin' => $spin,
            'createvideo' => $createvideo,
        ),
        'amazon' => array(
            'key' => $amazon_key,
            'secret' => $amazon_secret,
            'tag' => $amazon_tag,
            'country' => $amazon_country,
            'enabled' => $amazon_enabled,
            'keywords' => $amazon_keywords,
            'category' => $amazon_category,
            'display_price' => $amazon_display_price,
            'display_image' => $amazon_display_image,
            'num_ads' => $amazon_num_ads,
            'per_row' => $amazon_per_row,
            'enabled' => $amazon_enabled,
            'txt' => $amazon_txt,
            'position' => $amazon_position,
            'buy_button' => $amazon_buy_button
        ),
        'clickbank' => array(
            'id' => $clickbank_id,
            'enabled' => $clickbank_enabled,
            'popular' => $clickbank_popular,
            'keywords' => $clickbank_keywords,
            // 'category' => $clickbank_category,
            'display_price' => $clickbank_display_price,
            'display_image' => $clickbank_display_image,
            'num_ads' => $clickbank_num_ads,
            'per_row' => $clickbank_per_row,
            'enabled' => $clickbank_enabled,
            'txt' => $clickbank_txt,
            'position' => $clickbank_position,
            'buy_button' => $clickbank_buy_button
        ),
        'ebay' => array(
            'appid' => $ebay_appid,
            'campaignid' => $ebay_campaignid,
            'country' => $ebay_country,
            'enabled' => $ebay_enabled,
            'keywords' => $ebay_keywords,
            'category' => $ebay_category,
            'display_price' => $ebay_display_price,
            'display_image' => $ebay_display_image,
            'num_ads' => $ebay_num_ads,
            'per_row' => $ebay_per_row,
            'enabled' => $ebay_enabled,
            'txt' => $ebay_txt,
            'position' => $ebay_position,
            'buy_button' => $ebay_buy_button
        ),
        'aliexpress' => array(
            'apikey' => $aliexpress_api,
            'hash' => $aliexpress_hash,
            'enabled' => $aliexpress_enabled,
            'keywords' => $aliexpress_keywords,
            'category' => $aliexpress_category,
            'display_price' => $aliexpress_display_price,
            'display_image' => $aliexpress_display_image,
            'num_ads' => $aliexpress_num_ads,
            'per_row' => $aliexpress_per_row,
            'enabled' => $aliexpress_enabled,
            'txt' => $aliexpress_txt,
            'position' => $aliexpress_position,
            'buy_button' => $aliexpress_buy_button
        ),
        'walmart' => array(
            'apikey' => $walmart_api,
            'aff_id' => $walmart_aff_id,
            'enabled' => $walmart_enabled,
            'keywords' => $walmart_keywords,
            'category' => $walmart_category,
            'display_price' => $walmart_display_price,
            'display_image' => $walmart_display_image,
            'num_ads' => $walmart_num_ads,
            'per_row' => $walmart_per_row,
            'enabled' => $walmart_enabled,
            'txt' => $walmart_txt,
            'position' => $walmart_position,
            'buy_button' => $walmart_buy_button
        ),
        'bestbuy' => array(
            'apikey' => $bestbuy_api,
            'enabled' => $bestbuy_enabled,
            'keywords' => $bestbuy_keywords,
            'category' => $bestbuy_category,
            'display_price' => $bestbuy_display_price,
            'display_image' => $bestbuy_display_image,
            'num_ads' => $bestbuy_num_ads,
            'per_row' => $bestbuy_per_row,
            'enabled' => $bestbuy_enabled,
            'txt' => $bestbuy_txt,
            'position' => $bestbuy_position,
            'buy_button' => $bestbuy_buy_button
        ),
        'envato' => array(
            'apikey' => $envato_api,
            'username' => $envato_username,
            'enabled' => $envato_enabled,
            'keywords' => $envato_keywords,
            'category' => $envato_category,
            'display_price' => $envato_display_price,
            'display_image' => $envato_display_image,
            'num_ads' => $envato_num_ads,
            'per_row' => $envato_per_row,
            'enabled' => $envato_enabled,
            'txt' => $envato_txt,
            'position' => $envato_position,
            'buy_button' => $envato_buy_button
        ),
        'gearbest' => array(
            'appid' => $gearbest_appid,
            'appsecret' => $gearbest_appsecret,
            'deeplink' => $gearbest_deeplink,
            'enabled' => $gearbest_enabled,
            'keywords' => $gearbest_keywords,
            'category' => $gearbest_category,
            'display_price' => $gearbest_display_price,
            'display_image' => $gearbest_display_image,
            'num_ads' => $gearbest_num_ads,
            'per_row' => $gearbest_per_row,
            'enabled' => $gearbest_enabled,
            'txt' => $gearbest_txt,
            'position' => $gearbest_position,
            'buy_button' => $gearbest_buy_button
        ),
        'adsense' => array(
            'ad1' => array(
                'code' => $ad1_code,
                'pos' => $ad1_pos
            ),
            'ad2' => array(
                'code' => $ad2_code,
                'pos' => $ad2_pos
            ),
            'ad3' => array(
                'code' => $ad3_code,
                'pos' => $ad3_pos
            ),
        ),
        'bannerads' => array(
            'ad1' => array(
                'img' => $banner1_img,
                'link' => $banner1_link,
                'newwin' => $banner1_newwin,
                'pos' => $banner1_pos
            ),
            'ad2' => array(
                'img' => $banner2_img,
                'link' => $banner2_link,
                'newwin' => $banner2_newwin,
                'pos' => $banner2_pos
            ),
            'ad3' => array(
                'img' => $banner3_img,
                'link' => $banner3_link,
                'newwin' => $banner3_newwin,
                'pos' => $banner3_pos
            ),
        ),
        'social' => $settings_social,
        'scrolling' => array(
            'enabled' => $scrolling_enabled,
            'related' => $related_enabled,
            'video' => $scrolling_video,
            'scrolling_txt' => $scrolling_txt,
            'button_txt' => $scrolling_button_txt,
            'show_disclaimer' => $disclaimer_enabled,
            'disclaimer_txt' => $disclaimer_txt,
            'disclaimer_affid' => $disclaimer_affid,
        ),
        'autoresponders' => $autoresponders
    );


    // 4. Traffic
    $bm_email = isset( $_POST['cbaffmach_bm_email'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_bm_email'] ) ) : '';
    $bm_apikey = isset( $_POST['cbaffmach_bm_apikey'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_bm_apikey'] ) ) : '';
    $ili_apikey = isset( $_POST['cbaffmach_ili_apikey'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_ili_apikey'] ) ) : '';
    $bli_apikey = isset( $_POST['cbaffmach_bli_apikey'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_bli_apikey'] ) ) : '';

    $settings['traffic'] = array(
        'bmachine' => array(
            'email' => $bm_email,
            'apikey' => $bm_apikey,
        ),
        'ili' => array(
            'apikey' => $ili_apikey,
        ),
        'bli' => array(
            'apikey' => $bli_apikey,
        )
    );

    // 7. Text Spinners
    $srewriter_email = isset( $_POST['cbaffmach_srewriter_email'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_srewriter_email'] ) ) : '';
    $srewriter_apikey = isset( $_POST['cbaffmach_srewriter_apikey'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_srewriter_apikey'] ) ) : '';

    $schief_username = isset( $_POST['cbaffmach_schief_username'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_schief_username'] ) ) : '';
    $schief_password = isset( $_POST['cbaffmach_schief_pass'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_schief_pass'] ) ) : '';

    $tbs_username = isset( $_POST['cbaffmach_tbspinner_username'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_tbspinner_username'] ) ) : '';
    $tbs_password = isset( $_POST['cbaffmach_tbspinner_pass'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_tbspinner_pass'] ) ) : '';

    $wordai_username = isset( $_POST['cbaffmach_wordai_username'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_wordai_username'] ) ) : '';
    $wordai_password = isset( $_POST['cbaffmach_wordai_pass'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_wordai_pass'] ) ) : '';

    $spinner = isset( $_POST['cbaffmach_spinner'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_spinner'] ) ) : '';

    $settings['spinners'] = array(
        'spinrewriter' => array(
            'email' => $srewriter_email,
            'apikey' => $srewriter_apikey,
        ),
        'schief' => array(
            'username' => $schief_username,
            'password' => $schief_password,
        ),
        'tbs' => array(
            'username' => $tbs_username,
            'password' => $tbs_password,
        ),
        'wordai' => array(
            'username' => $wordai_username,
            'password' => $wordai_password,
        ),
        'spinner' => $spinner
    );

    // 8. Video
    $avmaker_email = isset( $_POST['cbaffmach_avmaker_email'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_avmaker_email'] ) ) : '';
    $avmaker_apikey = isset( $_POST['cbaffmach_avmaker_apikey'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_avmaker_apikey'] ) ) : '';

    $settings['video'] = array(
        'avmaker' => array(
            'email' => $avmaker_email,
            'apikey' => $avmaker_apikey,
        ),
    );

    // 9. Optin
    $optin_enabled = isset( $_POST['cbaffmach_optin_enabled'] ) ? intval( $_POST['cbaffmach_optin_enabled'] ) : 0;
    $autoresponder = isset( $_POST['cbaffmach_optin_ar_type'] ) ? intval( sanitize_text_field( $_POST['cbaffmach_optin_ar_type'] ) ) : 0;
    $list = isset( $_POST['cbaffmach_optin_list'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_optin_list'] ) ) : '';
    $email_field = isset( $_POST['cbaffmach_optin_email_txt'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_optin_email_txt'] ) ) : '';
    $first_name = isset( $_POST['cbaffmach_optin_show_name'] ) ? intval( sanitize_text_field( $_POST['cbaffmach_optin_show_name'] ) ) : 0;
    $firstname_field = isset( $_POST['cbaffmach_optin_name_txt'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_optin_name_txt'] ) ) : '';
    $submit_txt = isset( $_POST['cbaffmach_optin_submit_txt'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_optin_submit_txt'] ) ) : '';
    $intro_txt = isset( $_POST['cbaffmach_optin_intro_txt'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_optin_intro_txt'] ) ) : '';
    $thankyou_txt = isset( $_POST['cbaffmach_optin_thankyou_txt'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_optin_thankyou_txt'] ) ) : '';
    $redirect_url = isset( $_POST['cbaffmach_optin_redirect_url'] ) ? trim( sanitize_text_field( $_POST['cbaffmach_optin_redirect_url'] ) ) : '';
    $style = isset( $_POST['cbaffmach_optin_style'] ) ? intval( sanitize_text_field( $_POST['cbaffmach_optin_style'] ) ) : 0;
    $position = isset( $_POST['cbaffmach_optin_position'] ) ? intval( sanitize_text_field( $_POST['cbaffmach_optin_position'] ) ) : 0;
    $paragraph = isset( $_POST['cbaffmach_optin_paragraph'] ) ? intval( sanitize_text_field( $_POST['cbaffmach_optin_paragraph'] ) ) : 0;
    $margin = isset( $_POST['cbaffmach_optin_margin'] ) ? intval( sanitize_text_field( $_POST['cbaffmach_optin_margin'] ) ) : 0;

    $settings['optin'] = array(
        'enabled' => $optin_enabled,
        'autoresponder' => $autoresponder,
        'list' => $list,
        'email_field' => $email_field,
        'first_name' => $first_name,
        'firstname_field' => $firstname_field,
        'submit_txt' => $submit_txt,
        'intro_txt' => $intro_txt,
        'thankyou_txt' => $thankyou_txt,
        'redirect_url' => $redirect_url,
        'style' => $style,
        'position' => $position,
        'paragraph' => $paragraph,
        'margin' => $margin
    );

    delete_transient( 'cbaffmach_amazon' );
    delete_transient( 'cbaffmach_ebay' );
    delete_transient( 'cbaffmach_clickbank' );
    delete_transient( 'cbaffmach_aliexpress' );
    delete_transient( 'cbaffmach_envato' );
    delete_transient( 'cbaffmach_gearbest' );
    delete_transient( 'cbaffmach_bestbuy' );
    delete_transient( 'cbaffmach_walmart' );

    cbaffmach_save_plugin_settings( $settings );
    return true;
}

function cbaffmach_separator() {
    echo '<br/><hr/><br/>';
}

function cbaffmach_settings_monetize_tab_relatedads( $settings = false ) {
?>
<p>Enter your details for each one of the services you would like to display ads for.</p>
<p>For example, if you would like to display Clickbank ads related to weight loss at the end of each post, enter your CBProAds Account number, check "Show Clickbank Ads" and in keywords enter <em>weight loss</em></p>
<?php
    cbaffmach_settings_monetize_tab_amazon( $settings );
    cbaffmach_separator();
    cbaffmach_settings_monetize_tab_clickbank( $settings );
    cbaffmach_separator();
    cbaffmach_settings_monetize_tab_ebay( $settings );
    cbaffmach_separator();
    cbaffmach_settings_monetize_tab_aliexpress( $settings );
    cbaffmach_separator();
    cbaffmach_settings_monetize_tab_walmart( $settings );
    cbaffmach_separator();
    cbaffmach_settings_monetize_tab_bestbuy( $settings );
    cbaffmach_separator();
    cbaffmach_settings_monetize_tab_envato( $settings );
    cbaffmach_separator();
    cbaffmach_settings_monetize_tab_gearbest( $settings );
}

function cbaffmach_settings_monetize_tab_amazon( $settings = false ) {
    $settings = isset( $settings['amazon'] ) ? $settings['amazon'] : false;
	$amazon_key = isset( $settings['key'] ) ? trim( $settings['key'] ) : '';
	$amazon_secret = isset( $settings['secret'] ) ? trim( $settings['secret'] ) : '';
	$amazon_tag = isset( $settings['tag'] ) ? trim( $settings['tag'] ) : '';
	$amazon_country = isset( $settings['country'] ) ? trim( $settings['country'] ) : 'com';

	$amazon_enabled = isset( $settings['enabled'] ) ? intval( $settings['enabled'] ) : 0;
	$amazon_keywords = isset( $settings['keywords'] ) ? trim( $settings['keywords'] ) : '';
	$amazon_category = isset( $settings['category'] ) ? trim( $settings['category'] ) : 'All';
	$amazon_display_price = isset( $settings['display_price'] ) ? intval( $settings['display_price'] ) : 0;
	$amazon_display_image = isset( $settings['display_image'] ) ? intval( $settings['display_image'] ) : 1;
	$amazon_num_ads = isset( $settings['num_ads'] ) ? intval( $settings['num_ads'] ) : 3;
	$amazon_per_row = isset( $settings['per_row'] ) ? intval( $settings['per_row'] ) : 3;
	$amazon_txt = isset( $settings['txt'] ) ? trim( $settings['txt'] ) : 'Amazon Products';
	$amazon_position = isset( $settings['position'] ) ? intval( $settings['position'] ) : 0;
	$amazon_buy_button = isset( $settings['buy_button'] ) ? trim( $settings['buy_button'] ) : 'Buy Now';

?>
	<h3><i class="fab fa-amazon"></i> Amazon</h3>
	<h4 class="h4bigger">1. Amazon API Settings</h4>
	<table class="form-table">
	    <?php cbaffmach_field_text( 'Amazon Key', 'cbaffmach_amazon_key', $amazon_key, false, '', '', 'Your Amazon Key' ); ?>
	    <?php cbaffmach_field_text( 'Amazon Secret', 'cbaffmach_amazon_secret', $amazon_secret, false, '', '<a href="https://cbautomator.com/support/knowledgebase/how-to-create-an-amazon-key-and-secret/" target="_blank">How to get your free Key and Secret Key from Amazon</a>', 'Your Amazon Secret Key' ); ?>
	    <?php cbaffmach_field_text( 'Amazon Tag', 'cbaffmach_amazon_tag', $amazon_tag, false, '', 'usually ends with -20', 'Your Amazon Affiliate Tag ' ); ?>
	    <?php $amazon_countries = cbaffmach_get_amazon_countries(); ?>
	    <?php cbaffmach_field_select( 'Amazon Country', 'cbaffmach_amazon_country', $amazon_country, $amazon_countries, false, '' ); ?>
	</table>
	<br/>
	<h4 class="h4bigger">2. Ad Settings</h4>
	<table class="form-table">
    	<?php cbaffmach_field_checkbox( 'Show Amazon Ads', 'cbaffmach_amazon_enabled', $amazon_enabled, false, 'cbaffmach_amazon_enabled', 'If you enable this, it will add Amazon Affiliate links on autopilot to your posts', '' ); ?>
	    <?php cbaffmach_field_text( 'Keywords', 'cbaffmach_amazon_keywords', $amazon_keywords, false, '', '', 'Your Amazon Keywords', 'cbaffmach_mon_amazon_row', $amazon_enabled ); ?>
	    <?php $amazon_categories = cbaffmach_get_amazon_cats(); ?>
	    <?php cbaffmach_field_select( 'Category', 'cbaffmach_amazon_category', $amazon_category, $amazon_categories, false, '', '', '', 'cbaffmach_mon_amazon_row', !$amazon_enabled ); ?>
    	<?php cbaffmach_field_checkbox( 'Display Price', 'cbaffmach_amazon_display_price', $amazon_display_price, false, 'cbaffmach_amazon_display_price', 'If you enable this, it will show the product price in the ad', '', 'cbaffmach_mon_amazon_row', $amazon_enabled ); ?>
    	<?php cbaffmach_field_checkbox( 'Display Image', 'cbaffmach_amazon_display_image', $amazon_display_image, false, 'cbaffmach_amazon_display_image', 'If you enable this, it will show the product image in the ad', '', 'cbaffmach_mon_amazon_row', $amazon_enabled ); ?>
		<?php $numbers = cbaffmach_get_numbers( 1, 10 ); ?>
	    <?php cbaffmach_field_select( 'Number of Ads', 'cbaffmach_amazon_num_ads', $amazon_num_ads, $numbers, false, '', '', '', 'cbaffmach_mon_amazon_row', !$amazon_enabled  ); ?>
	    <?php cbaffmach_field_select( 'Ads per Row', 'cbaffmach_amazon_per_row', $amazon_per_row, $numbers, false, '', '', '', 'cbaffmach_mon_amazon_row', !$amazon_enabled  ); ?>
	    <?php cbaffmach_field_text( 'Header Text', 'cbaffmach_amazon_txt', $amazon_txt, false, '', '', 'Intro Text before the ads', 'cbaffmach_mon_amazon_row', $amazon_enabled ); ?>
		<?php $positions = cbaffmach_get_banner_positions(); ?>
	    <?php cbaffmach_field_select( 'Position', 'cbaffmach_amazon_position', $amazon_position, $positions, false, '', '', '', 'cbaffmach_mon_amazon_row', !$amazon_enabled  ); ?>
	    <?php cbaffmach_field_text( 'Button Text', 'cbaffmach_amazon_buy_button', $amazon_buy_button, false, '', '', 'The text for the buy button', 'cbaffmach_mon_amazon_row', $amazon_enabled ); ?>
	</table>
	<br/>
<?php
}

function cbaffmach_settings_monetize_tab_clickbank( $settings = false ) {
    $settings = isset( $settings['clickbank'] ) ? $settings['clickbank'] : false;
    $clickbank_id = isset( $settings['id'] ) ? trim( $settings['id'] ) : '';

    $clickbank_enabled = isset( $settings['enabled'] ) ? intval( $settings['enabled'] ) : 0;
    $clickbank_popular = isset( $settings['popular'] ) ? intval( $settings['popular'] ) : 0;
    $clickbank_keywords = isset( $settings['keywords'] ) ? trim( $settings['keywords'] ) : '';
    // $clickbank_category = isset( $settings['category'] ) ? trim( $settings['category'] ) : 'All';
    $clickbank_display_price = isset( $settings['display_price'] ) ? intval( $settings['display_price'] ) : 0;
    $clickbank_display_image = isset( $settings['display_image'] ) ? intval( $settings['display_image'] ) : 1;
    $clickbank_num_ads = isset( $settings['num_ads'] ) ? intval( $settings['num_ads'] ) : 3;
    $clickbank_per_row = isset( $settings['per_row'] ) ? intval( $settings['per_row'] ) : 3;
    $clickbank_txt = isset( $settings['txt'] ) ? trim( $settings['txt'] ) : 'Clickbank Products';
    $clickbank_position = isset( $settings['position'] ) ? intval( $settings['position'] ) : 0;
    $clickbank_buy_button = isset( $settings['buy_button'] ) ? trim( $settings['buy_button'] ) : 'Buy Now';
?>
<h3><i class="far fa-bell"></i> Clickbank</h3>
<h4 class="h4bigger">1. CBProAds Settings</h4>

<table class="form-table">
    <?php cbaffmach_field_text( 'CBPro Ads Account Number', 'cbaffmach_clickbank_id', $clickbank_id, false, '', 'Get it <a target="_blank" href="http://wptagmachine.com/v2/support/cbproads">here</a> - <a href="https://cbautomator.com/support/knowledgebase//how-to-get-your-cbpro-ads-account-number/" target="_blank">Tutorial</a>' ); ?>
</table>
	<br/>
	<h4 class="h4bigger">2. Ad Settings</h4>
	<table class="form-table">
    	<?php cbaffmach_field_checkbox( 'Show Clickbank Ads', 'cbaffmach_clickbank_enabled', $clickbank_enabled, false, 'cbaffmach_clickbank_enabled', 'If you enable this, it will add Clickbank Affiliate links on autopilot to your posts', '' ); ?>
	    <?php cbaffmach_field_text( 'Keywords', 'cbaffmach_clickbank_keywords', $clickbank_keywords, false, '', '', 'Your Clickbank Keywords', 'cbaffmach_mon_clickbank_row', $clickbank_enabled ); ?>
        <?php cbaffmach_field_checkbox( 'Show Popular Products', 'cbaffmach_clickbank_popular', $clickbank_popular, false, 'cbaffmach_clickbank_popular', 'If you enable this, it will show popular the top Clickbank products if there are no results for your keyword/s', '', 'cbaffmach_mon_clickbank_row', $clickbank_enabled ); ?>
    	<?php cbaffmach_field_checkbox( 'Display Price', 'cbaffmach_clickbank_display_price', $clickbank_display_price, false, 'cbaffmach_clickbank_display_price', 'If you enable this, it will show the product price in the ad', '', 'cbaffmach_mon_clickbank_row', $clickbank_enabled ); ?>
    	<?php cbaffmach_field_checkbox( 'Display Image', 'cbaffmach_clickbank_display_image', $clickbank_display_image, false, 'cbaffmach_clickbank_display_image', 'If you enable this, it will show the product image in the ad', '', 'cbaffmach_mon_clickbank_row', $clickbank_enabled ); ?>
		<?php $numbers = cbaffmach_get_numbers( 1, 10 ); ?>
	    <?php cbaffmach_field_select( 'Number of Ads', 'cbaffmach_clickbank_num_ads', $clickbank_num_ads, $numbers, false, '', '', '', 'cbaffmach_mon_clickbank_row', !$clickbank_enabled  ); ?>
	    <?php cbaffmach_field_select( 'Ads per Row', 'cbaffmach_clickbank_per_row', $clickbank_per_row, $numbers, false, '', '', '', 'cbaffmach_mon_clickbank_row', !$clickbank_enabled  ); ?>
	    <?php cbaffmach_field_text( 'Header Text', 'cbaffmach_clickbank_txt', $clickbank_txt, false, '', '', 'Intro Text before the ads', 'cbaffmach_mon_clickbank_row', $clickbank_enabled ); ?>
		<?php $positions = cbaffmach_get_banner_positions(); ?>
	    <?php cbaffmach_field_select( 'Position', 'cbaffmach_clickbank_position', $clickbank_position, $positions, false, '', '', '', 'cbaffmach_mon_clickbank_row', !$clickbank_enabled  ); ?>
	    <?php cbaffmach_field_text( 'Button Text', 'cbaffmach_clickbank_buy_button', $clickbank_buy_button, false, '', '', 'The text for the buy button', 'cbaffmach_mon_clickbank_row', $clickbank_enabled ); ?>
    </table>
<?php
}

function cbaffmach_settings_monetize_tab_ebay( $settings = false ) {
    $settings = isset( $settings['ebay'] ) ? $settings['ebay'] : false;
	$ebay_appid = isset( $settings['appid'] ) ? trim( $settings['appid'] ) : '';
	$ebay_campaignid = isset( $settings['campaignid'] ) ? trim( $settings['campaignid'] ) : '';
	$ebay_country = isset( $settings['country'] ) ? trim( $settings['country'] ) : 'US';

	$ebay_enabled = isset( $settings['enabled'] ) ? intval( $settings['enabled'] ) : 0;
	$ebay_keywords = isset( $settings['keywords'] ) ? trim( $settings['keywords'] ) : '';
	$ebay_category = isset( $settings['category'] ) ? trim( $settings['category'] ) : 'All';
	$ebay_display_price = isset( $settings['display_price'] ) ? intval( $settings['display_price'] ) : 0;
	$ebay_display_image = isset( $settings['display_image'] ) ? intval( $settings['display_image'] ) : 1;
	$ebay_num_ads = isset( $settings['num_ads'] ) ? intval( $settings['num_ads'] ) : 3;
	$ebay_per_row = isset( $settings['per_row'] ) ? intval( $settings['per_row'] ) : 3;
	$ebay_txt = isset( $settings['txt'] ) ? trim( $settings['txt'] ) : 'Ebay Products';
	$ebay_position = isset( $settings['position'] ) ? intval( $settings['position'] ) : 0;
	$ebay_buy_button = isset( $settings['buy_button'] ) ? trim( $settings['buy_button'] ) : 'Buy Now';
?>
	<h3><i class="fab fa-ebay"></i> eBay</h3>
    <h4 class="h4bigger">1. eBay Settings</h4>
	<table class="form-table">
	    <?php cbaffmach_field_text( 'App ID', 'cbaffmach_ebay_appid', $ebay_appid, false, '', '<a href="https://cbautomator.com/support/knowledgebase//how-to-create-an-ebay-app/" target="_blank">How to get your free ebay App ID</a>', 'Your ebay App ID' ); ?>
	    <?php cbaffmach_field_text( 'Default Campaign ID', 'cbaffmach_ebay_cid', $ebay_campaignid, false, '', 'Your campaign ID to use. Get it <a target="_blank" href="https://epn.ebay.com/login">here</a>' ); ?>
	    <?php $ebay_countries = cbaffmach_get_ebay_countries(); ?>
	    <?php cbaffmach_field_select( 'eBay Country', 'cbaffmach_ebay_country', $ebay_country, $ebay_countries, false, '' ); ?>
	</table>
		<br/>
	<h4 class="h4bigger">2. Ad Settings</h4>
	<table class="form-table">
    	<?php cbaffmach_field_checkbox( 'Show Ebay Ads', 'cbaffmach_ebay_enabled', $ebay_enabled, false, 'cbaffmach_ebay_enabled', 'If you enable this, it will add ebay Affiliate links on autopilot to your posts', '' ); ?>
		    <?php cbaffmach_field_text( 'Keywords', 'cbaffmach_ebay_keywords', $ebay_keywords, false, '', '', 'Your Ebay Keywords', 'cbaffmach_mon_ebay_row', $ebay_enabled ); ?>
		    <?php $ebay_categories = cbaffmach_get_ebay_cats(); ?>
		    <?php cbaffmach_field_select( 'Category', 'cbaffmach_ebay_category', $ebay_category, $ebay_categories, false, '', '', '', 'cbaffmach_mon_ebay_row', !$ebay_enabled ); ?>
	    	<?php cbaffmach_field_checkbox( 'Display Price', 'cbaffmach_ebay_display_price', $ebay_display_price, false, 'cbaffmach_ebay_display_price', 'If you enable this, it will show the product price in the ad', '', 'cbaffmach_mon_ebay_row', $ebay_enabled ); ?>
	    	<?php cbaffmach_field_checkbox( 'Display Image', 'cbaffmach_ebay_display_image', $ebay_display_image, false, 'cbaffmach_ebay_display_image', 'If you enable this, it will show the product image in the ad', '', 'cbaffmach_mon_ebay_row', $ebay_enabled ); ?>
			<?php $numbers = cbaffmach_get_numbers( 1, 10 ); ?>
		    <?php cbaffmach_field_select( 'Number of Ads', 'cbaffmach_ebay_num_ads', $ebay_num_ads, $numbers, false, '', '', '', 'cbaffmach_mon_ebay_row', !$ebay_enabled  ); ?>
		    <?php cbaffmach_field_select( 'Ads per Row', 'cbaffmach_ebay_per_row', $ebay_per_row, $numbers, false, '', '', '', 'cbaffmach_mon_ebay_row', !$ebay_enabled  ); ?>
		    <?php cbaffmach_field_text( 'Header Text', 'cbaffmach_ebay_txt', $ebay_txt, false, '', '', 'Intro Text before the ads', 'cbaffmach_mon_ebay_row', $ebay_enabled ); ?>
			<?php $positions = cbaffmach_get_banner_positions(); ?>
		    <?php cbaffmach_field_select( 'Position', 'cbaffmach_ebay_position', $ebay_position, $positions, false, '', '', '', 'cbaffmach_mon_ebay_row', !$ebay_enabled  ); ?>
		    <?php cbaffmach_field_text( 'Button Text', 'cbaffmach_ebay_buy_button', $ebay_buy_button, false, '', '', 'The text for the buy button', 'cbaffmach_mon_ebay_row', $ebay_enabled ); ?>
    </table>
<?php
}



/* Amazon */

function cbaffmach_get_amazon_cats() {
	return array(
		array( 'label' => 'All', 'value' => 'All'),
		array( 'label' => 'Apparel & Accessories', 'value' => 'Apparel'),
		// array( 'label' => 'Appstore for Android', 'value' => 'Appstore for Android'),
		array( 'label' => 'Arts, Crafts & Sewing', 'value' => 'ArtsAndCrafts'),
		array( 'label' => 'Automotive', 'value' => 'Automotive'),
		array( 'label' => 'Baby', 'value' => 'Baby'),
		array( 'label' => 'Beauty', 'value' => 'Beauty'),
		// array( 'label' => 'Black Friday Sales', 'value' => 'Black Friday Sales'),
		array( 'label' => 'Books', 'value' => 'Books'),
		array( 'label' => 'Camera & Photo', 'value' => 'Photo'),
		// array( 'label' => 'Car Toys', 'value' => 'Car Toys'),
		array( 'label' => 'Cell Phones & Accessories', 'value' => 'Wireless'),
		array( 'label' => 'Computer & Video Games', 'value' => 'VideoGames'),
		// array( 'label' => 'Computers', 'value' => 'Computers'),
		array( 'label' => 'Electronics', 'value' => 'Electronics'),
		array( 'label' => 'Grocery & Gourmet Food', 'value' => 'Grocery'),
		array( 'label' => 'Health & Personal Care', 'value' => 'HealthPersonalCare'),
		array( 'label' => 'Home & Garden', 'value' => 'Garden'),
		array( 'label' => 'Industrial & Scientific', 'value' => 'Industrial'),
		array( 'label' => 'Jewelry', 'value' => 'Jewelry'),
		array( 'label' => 'Kindle Store', 'value' => 'KindleStore'),
		array( 'label' => 'Kitchen & Housewares', 'value' => 'Kitchen'),
		array( 'label' => 'Magazine Subscriptions', 'value' => 'Magazines'),
		array( 'label' => 'Miscellaneous', 'value' => 'Miscellaneous'),
		array( 'label' => 'Movies & TV', 'value' => 'Movies'),
		array( 'label' => 'MP3 Downloads', 'value' => 'MP3Downloads'),
		array( 'label' => 'Music', 'value' => 'Music'),
		array( 'label' => 'Musical Instruments', 'value' => 'MusicalInstruments'),
		array( 'label' => 'Office Products', 'value' => 'OfficeProducts'),
		array( 'label' => 'Outdoor Living', 'value' => 'OutdoorLiving'),
		// array( 'label' => 'Pet Supplies', 'value' => 'PetSupplies'),
		array( 'label' => 'Pet Supplies', 'value' => 'PetSupplies'),
		array( 'label' => 'PC Hardware', 'value' => 'PCHardware'),
		array( 'label' => 'Shoes', 'value' => 'Shoes'),
		array( 'label' => 'Software', 'value' => 'Software'),
		// array( 'label' => 'Specialty Stores', 'value' => 'Specialty Stores'),
		array( 'label' => 'Sports & Outdoors', 'value' => 'SportingGoods'),
		array( 'label' => 'Tools & Hardware', 'value' => 'Tools'),
		array( 'label' => 'Toys and Games', 'value' => 'Toys'),
		array( 'label' => 'Wine', 'value' => 'Wine')
		// array( 'label' => 'Warehouse Deals', 'value' => 'Warehouse Deals')
	);
}

function cbaffmach_get_amazon_countries() {
	// private $LOCATIONS = array ('ca','com','cn', 'co.jp', 'co.uk','fr',
	//                             'it','cn');
	return array(
		array( 'label' => 'USA', 'value' => 'com'),
		array( 'label' => 'Canada', 'value' => 'ca'),
		array( 'label' => 'UK', 'value' => 'co.uk'),
		array( 'label' => 'Germany', 'value' => 'de'),
		array( 'label' => 'France', 'value' => 'fr'),
		array( 'label' => 'Italy', 'value' => 'it'),
		array( 'label' => 'Spain', 'value' => 'es'),
		array( 'label' => 'Japan', 'value' => 'co.jp'),
		array( 'label' => 'India', 'value' => 'in')
	);
}

/* Ebay */

function cbaffmach_get_ebay_cats() {
	return array(
		array( 'label' => 'Any', 'value' => 0),
		array( 'value' => '20081', 'label' => 'Antiques' ),
		array( 'value' => '550', 'label' => 'Art' ),
		array( 'value' => '2984', 'label' => 'Baby' ),
		array( 'value' => '267', 'label' => 'Books, Comics & Magazines' ),
		array( 'value' => '12576', 'label' => 'Business, Office & Industrial' ),
		array( 'value' => '625', 'label' => 'Cameras & Photography' ),
		array( 'value' => '9800', 'label' => 'Cars, Motorcycles & Vehicles' ),
		array( 'value' => '11450', 'label' => 'Clothes, Shoes & Accessories' ),
		array( 'value' => '11116', 'label' => 'Coins' ),
		array( 'value' => '1', 'label' => 'Collectables' ),
		array( 'value' => '58058', 'label' => 'Computers/Tablets & Networking' ),
		array( 'value' => '14339', 'label' => 'Crafts' ),
		array( 'value' => '237', 'label' => 'Dolls & Bears' ),
		array( 'value' => '11232', 'label' => 'DVDs, Films & TV' ),
		array( 'value' => '1305', 'label' => 'Events Tickets' ),
		array( 'value' => '159912', 'label' => 'Garden & Patio' ),
		array( 'value' => '26395', 'label' => 'Health & Beauty' ),
		array( 'value' => '3252', 'label' => 'Holidays & Travel' ),
		array( 'value' => '11700', 'label' => 'Home, Furniture & DIY' ),
		array( 'value' => '281', 'label' => 'Jewellery & Watches' ),
		array( 'value' => '15032', 'label' => 'Mobile Phones & Communication' ),
		array( 'value' => '11233', 'label' => 'Music' ),
		array( 'value' => '619', 'label' => 'Musical Instruments' ),
		array( 'value' => '1281', 'label' => 'Pet Supplies' ),
		array( 'value' => '870', 'label' => 'Pottery, Porcelain & Glass' ),
		array( 'value' => '10542', 'label' => 'Property' ),
		array( 'value' => '293', 'label' => 'Sound & Vision' ),
		array( 'value' => '888', 'label' => 'Sporting Goods' ),
		array( 'value' => '64482', 'label' => 'Sports Memorabilia' ),
		array( 'value' => '260', 'label' => 'Stamps' ),
		array( 'value' => '220', 'label' => 'Toys & Games' ),
		array( 'value' => '131090', 'label' => 'Vehicle Parts & Accessories' ),
		array( 'value' => '1249', 'label' => 'Video Games & Consoles' ),
		array( 'value' => '40005', 'label' => 'Wholesale & Job Lots' )
	);
}

function cbaffmach_get_ebay_countries() {
	return array(
		array( 'label' => 'USA', 'value' => 'US'),
		array( 'label' => 'Canada', 'value' => 'CA'),
		array( 'label' => 'UK', 'value' => 'UK'),
		array( 'label' => 'Australia', 'value' => 'AU'),
		array( 'label' => 'Germany', 'value' => 'DE'),
		array( 'label' => 'France', 'value' => 'FR'),
		array( 'label' => 'Italy', 'value' => 'IT'),
		array( 'label' => 'Spain', 'value' => 'ES'),
		array( 'label' => 'Netherlands', 'value' => 'NL'),
		array( 'label' => 'Belgium', 'value' => 'BE'),
		array( 'label' => 'Ireland', 'value' => 'IE'),
		array( 'label' => 'Austria', 'value' => 'AT'),
		array( 'label' => 'Switzerland', 'value' => 'CH'),
	);
}

function cbaffmach_pop_admin( $text ) {
    return '<a href="#" class="cbaffmachpop"><i class="fa fa-question"></i></a>
    <div class="webui-popover-content">
       <p>'.$text.'</p>
    </div>';
}

function cbaffmach_get_numbers( $start, $end, $extra = false ) {
	$arr = array();
	if( $extra )
		$arr[] = array( 'label' => $extra, 'value' => 0 );

	for( $i = $start; $i<= $end; $i++ )
		$arr[] = array( 'label' =>$i, 'value' => $i);
	return $arr;
}

function cbaffmach_get_banner_positions() {
	return array(
		array( 'label' => 'Beginning of Post', 'value' => '1'),
		array( 'label' => 'End of Post', 'value' => '2'),
		array( 'label' => 'Middle of Post', 'value' => '3')
		// array( 'label' => 'After paragraph x', 'value' => '4')
	);
}

function cbaffmach_get_banner_float() {
    return array(
        array( 'label' => 'None', 'value' => '1'),
        array( 'label' => 'Left', 'value' => '2'),
        array( 'label' => 'Right', 'value' => '3')
    );
}

function cbaffmach_cdata($data)
{
    if (substr($data, 0, 9) === '<![CDATA[' && substr($data, -3) === ']]>') {
        $data = substr($data, 9, -3);
    }
    return $data;
}


function cbaffmach_custom_meta_boxes( $post_type, $post ) {
    add_meta_box('cbaffmach-monetization', __('CB Automator'), 'cbaffmach_insta_box', 'post', 'side', 'default');
}

function cbaffmach_insta_box() {
    global $post;
    $disable_mon = get_post_meta( $post->ID, '_dis_mon', true );
    $keyword = get_post_meta( $post->ID, '_apmon_kw', true );
    if( empty( $keyword ) )
        $keyword = '';
?>
    <div class="wrap">
        <label style="padding-bottom:8px;display:block" for="cbaffmach_disable_mon"> <b>Disable monetiztion for this post</b></label> 
        <label for="cbaffmach_disable_mon"> <input type="checkbox" name="cbaffmach_disable_mon" value="1" id="cbaffmach_disable_mon" <?php echo $disable_mon ? 'checked' : ''; ?> /> Disabled</label>
        <p style="margin-top:2px;padding-top:0">If you check this, no CB Automator ads will appear on this <?php echo get_post_type();?></p>
        <label style="padding-bottom:8px;display:block"><b>Keywords</b></label>
        <input type="text" value="<?php echo $keyword;?>" name="cbaffmach_kw" style="width:100%">
        <input type="hidden" name="cbaffmach_update" value="1">
        <p style="margin-top:2px;padding-top:0">Enter a value here to use custom monetization keywords for this <?php echo get_post_type();?></p>
    </div>
<?php
}

function cbaffmach_save_post_meta( $post_id ) {
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return;

    $do_update = isset( $_POST['cbaffmach_update'] ) ? 1 : 0;
    if( !$do_update )
        return;
    $pm = isset( $_POST['cbaffmach_disable_mon'] ) ? intval( $_POST['cbaffmach_disable_mon'] ) : 0;
    update_post_meta( $post_id, '_dis_mon',  $pm );
    $cta = isset( $_POST['cbaffmach_kw'] ) ? trim( $_POST['cbaffmach_kw'] ) : 0;
    update_post_meta( $post_id, '_apmon_kw',  $cta );
    cbaffmach_monetize_update_post( $post_id );
}

add_action( 'add_meta_boxes', 'cbaffmach_custom_meta_boxes', 10, 2 );
add_action( 'save_post', 'cbaffmach_save_post_meta' );

// add_action('category_edit_form', 'category_edit_form');
add_action('category_edit_form_fields','cbaffmach_category_edit_form_fields', 10, 2 );
add_action('category_add_form_fields','cbaffmach_category_add_form_fields', 10, 2 );
// add_action('category_add_form','category_edit_form');


add_action( 'created_category', 'cbaffmach_save_cat_meta', 10, 2 );
add_action( 'edited_category', 'cbaffmach_save_cat_meta', 10, 2 );

function cbaffmach_save_cat_meta( $term_id, $tt_id ){
    if( isset( $_POST['cbaffmach_apmon_kw'] ) /*&& '' !== $_POST['cbaffmach_apmon_kw']*/ ){
        $cbaffmach_apmon_kw = trim( $_POST['cbaffmach_apmon_kw'] );
        update_term_meta( $term_id, 'cbaffmach_apmon_kw', $cbaffmach_apmon_kw );
        cbaffmach_monetize_update_cat( $term_id );
    }
}


function cbaffmach_category_edit_form_fields ( $term, $tt_id ) {
    $cbaffmach_apmon_kw = get_term_meta( $term->term_id, 'cbaffmach_apmon_kw', true );
    // var_dump($cbaffmach_apmon_kw);
?>
    <tr class="form-field">
            <th valign="top" scope="row">
                <label for="cbaffmach_apmon_kw">CB Automator Keyword</label>
            </th>
            <td>
                <input type="text" id="cbaffmach_apmon_kw" name="cbaffmach_apmon_kw" value="<?php echo $cbaffmach_apmon_kw;?>" />
                <p class="description">If you enter a value here, it will be used for all posts in this category</p>
            </td>
        </tr>
    <?php
}

function cbaffmach_category_add_form_fields ( $term_id, $tt_id = 0 ) {
?>
    <div class="form-field term-wpsc-wrap">
        <label for="cbaffmach_apmon_kw">CB Automator Keyword</label>
        <input name="cbaffmach_apmon_kw" id="cbaffmach_apmon_kw" value="" size="40" type="text">
        <p class="description">If you enter a value here, it will be used for all posts in this category</p>
    </div>
    <?php
}

function cbaffmach_monetize_update_post( $post_id ) {
    delete_transient( 'cbaffmach_amazon_pid'.$post_id );
    delete_transient( 'cbaffmach_ebay_pid'.$post_id );
    delete_transient( 'cbaffmach_cb_pid'.$post_id );
}

function cbaffmach_monetize_update_cat( $cat_id ) {
    delete_transient( 'cbaffmach_amazon_cid'.$cat_id );
    delete_transient( 'cbaffmach_ebay_cid'.$cat_id );
    delete_transient( 'cbaffmach_cb_cid'.$cat_id );
}

function cbaffmach_settings_monetize_tab_adsense( $settings ) {
    $adsense_settings = isset( $settings['adsense'] ) ? $settings['adsense'] : array();
    $ad1_code = isset( $adsense_settings['ad1']['code'] ) ? trim( $adsense_settings['ad1']['code'] ) : '';
    $ad1_pos = isset( $adsense_settings['ad1']['pos'] ) ? intval( $adsense_settings['ad1']['pos'] ) : 0;
    $ad2_code = isset( $adsense_settings['ad2']['code'] ) ? trim( $adsense_settings['ad2']['code'] ) : '';
    $ad2_pos = isset( $adsense_settings['ad2']['pos'] ) ? intval( $adsense_settings['ad2']['pos'] ) : 0;
    $ad3_code = isset( $adsense_settings['ad3']['code'] ) ? trim( $adsense_settings['ad3']['code'] ) : '';
    $ad3_pos = isset( $adsense_settings['ad3']['pos'] ) ? intval( $adsense_settings['ad3']['pos'] ) : 0;
?>
    <?php $positions = cbaffmach_get_banner_positions(); ?>
    <p>Add your own HTML Code (it can be optin forms, Adsense ads, or any custom HTML ) (you can choose them to appear at the top/middle/bottom of each post)</p>
    <h3>Ad 1</h3>
    <table class="form-table">
        <?php cbaffmach_field_textarea( 'Ad Code', 'cbaffmach_adsense1_ad', $ad1_code, false, 'tall-textarea', 'Adsense Ads or any HTML custom code', 'Your HTML Ad Code' ); ?>
        <?php cbaffmach_field_select( 'Position', 'cbaffmach_adsense1_pos', $ad1_pos, $positions, false, '', '', '' ); ?>
    </table>
    <h3>Ad 2</h3>
    <table class="form-table">
        <?php cbaffmach_field_textarea( 'Ad Code', 'cbaffmach_adsense2_ad', $ad2_code, false, 'tall-textarea', 'Adsense Ads or any HTML custom code', 'Your HTML Ad Code' ); ?>
        <?php cbaffmach_field_select( 'Position', 'cbaffmach_adsense2_pos', $ad2_pos, $positions, false, '', '', '' ); ?>
    </table>
    <h3>Ad 3</h3>
    <table class="form-table">
        <?php cbaffmach_field_textarea( 'Ad Code', 'cbaffmach_adsense3_ad', $ad3_code, false, 'tall-textarea', 'Adsense Ads or any HTML custom code', 'Your HTML Ad Code' ); ?>
        <?php cbaffmach_field_select( 'Position', 'cbaffmach_adsense3_pos', $ad3_pos, $positions, false, '', '', '' ); ?>
    </table>
<?php
}

function cbaffmach_settings_monetize_tab_bannerads( $settings ) {
    $bannerads_settings = isset( $settings['bannerads'] ) ? $settings['bannerads'] : array();
    $ad1_img = isset( $bannerads_settings['ad1']['img'] ) ? trim( $bannerads_settings['ad1']['img'] ) : '';
    $ad1_newwin = isset( $bannerads_settings['ad1']['newwin'] ) ? intval( $bannerads_settings['ad1']['newwin'] ) : 0;
    $ad1_link = isset( $bannerads_settings['ad1']['link'] ) ? trim( $bannerads_settings['ad1']['link'] ) : '';
    $ad1_pos = isset( $bannerads_settings['ad1']['pos'] ) ? intval( $bannerads_settings['ad1']['pos'] ) : 0;

    $ad2_img = isset( $bannerads_settings['ad2']['img'] ) ? trim( $bannerads_settings['ad2']['img'] ) : '';
    $ad2_newwin = isset( $bannerads_settings['ad2']['newwin'] ) ? intval( $bannerads_settings['ad2']['newwin'] ) : 0;
    $ad2_link = isset( $bannerads_settings['ad2']['link'] ) ? trim( $bannerads_settings['ad2']['link'] ) : '';
    $ad2_pos = isset( $bannerads_settings['ad2']['pos'] ) ? intval( $bannerads_settings['ad2']['pos'] ) : 0;

    $ad3_img = isset( $bannerads_settings['ad3']['img'] ) ? trim( $bannerads_settings['ad3']['img'] ) : '';
    $ad3_newwin = isset( $bannerads_settings['ad3']['newwin'] ) ? intval( $bannerads_settings['ad3']['newwin'] ) : 0;
    $ad3_link = isset( $bannerads_settings['ad3']['link'] ) ? trim( $bannerads_settings['ad3']['link'] ) : '';
    $ad3_pos = isset( $bannerads_settings['ad3']['pos'] ) ? intval( $bannerads_settings['ad3']['pos'] ) : 0;
?>
    <?php $positions = cbaffmach_get_banner_positions(); ?>
    <p>Add your own Banner Ads to your posts (you can choose them to appear at the top/middle/bottom of each post)</p>
    <h3>Banner 1</h3>
    <table class="form-table">
        <?php
            cbaffmach_field_image( 'Image', 'cbaffmach_banner1_img', $ad1_img, false, 'Banner Image', 'Select an image for the banner ad' );
            cbaffmach_field_text( 'Link', 'cbaffmach_banner1_link', $ad1_link, false, '', '', 'The URL for the banner destination' );
            cbaffmach_field_checkbox( 'Open in New Window', 'cbaffmach_banner1_newwin', $ad1_newwin, false, '', 'If checked, the link will load in a new tab/window', '' );
            cbaffmach_field_select( 'Position', 'cbaffmach_banner1_pos', $ad1_pos, $positions, false, '', '', '' );
        ?>
    </table>
    <h3>Banner 2</h3>
    <table class="form-table">
        <?php
            cbaffmach_field_image( 'Image', 'cbaffmach_banner2_img', $ad2_img, false, 'Banner Image', 'Select an image for the banner ad' );
            cbaffmach_field_text( 'Link', 'cbaffmach_banner2_link', $ad2_link, false, '', '', 'The URL for the banner destination' );
            cbaffmach_field_checkbox( 'Open in New Window', 'cbaffmach_banner2_newwin', $ad2_newwin, false, '', 'If checked, the link will load in a new tab/window', '' );
            cbaffmach_field_select( 'Position', 'cbaffmach_banner2_pos', $ad2_pos, $positions, false, '', '', '' );
        ?>
    </table>
    <h3>Banner 3</h3>
    <table class="form-table">
    <?php
        cbaffmach_field_image( 'Image', 'cbaffmach_banner3_img', $ad3_img, false, 'Banner Image', 'Select an image for the banner ad' );
        cbaffmach_field_text( 'Link', 'cbaffmach_banner3_link', $ad3_link, false, '', '', 'The URL for the banner destination' );
        cbaffmach_field_checkbox( 'Open in New Window', 'cbaffmach_banner3_newwin', $ad3_newwin, false, '', 'If checked, the link will load in a new tab/window', '' );
        cbaffmach_field_select( 'Position', 'cbaffmach_banner3_pos', $ad3_pos, $positions, false, '', '', '' );
    ?>
    </table>
<?php
}

function cbaffmach_settings_monetize_tab_scrolling( $settings ) {
    $scrolling_settings = isset( $settings['scrolling'] ) ? $settings['scrolling'] : array();
    $enabled = isset( $scrolling_settings['enabled'] ) ? intval( $scrolling_settings['enabled'] ) : 0;
    $related = isset( $scrolling_settings['related'] ) ? intval( $scrolling_settings['related'] ) : 0;
    $video = isset( $scrolling_settings['video'] ) ? intval( $scrolling_settings['video'] ) : 0;
    $scrolling_txt = isset( $scrolling_settings['scrolling_txt'] ) ? trim( $scrolling_settings['scrolling_txt'] ) : '';
    $button_txt = isset( $scrolling_settings['button_txt'] ) ? trim( $scrolling_settings['button_txt'] ) : '';
    $show_disclaimer = isset( $scrolling_settings['show_disclaimer'] ) ? intval( $scrolling_settings['show_disclaimer'] ) : 0;
    $disclaimer_txt = isset( $scrolling_settings['disclaimer_txt'] ) ? trim( $scrolling_settings['disclaimer_txt'] ) : 'Blog posts on this website may contain affiliate links.';
    $disclaimer_affid = isset( $scrolling_settings['disclaimer_affid'] ) ? intval( $scrolling_settings['disclaimer_affid'] ) : 0;

?>
    <p>Add scrolling based content to maximize clicks to your affiliate offers</p>
    <table class="form-table">
        <?php
            cbaffmach_field_checkbox( 'Enable scrolling box', 'cbaffmach_scrolling_enable', $enabled, false, 'cbaffmach_scrolling_enable', 'If checked, it will show a box to feature your affiliate link on all product reviews', '' );
            cbaffmach_field_checkbox( 'Display video', 'cbaffmach_scrolling_video', $video, false, '', 'If checked, it will show a video review inside the floating box (whenever available in the article)', '', 'cbaffmach_scrolling_row', $enabled );
            cbaffmach_field_textarea( 'Intro Text', 'cbaffmach_scrolling_txt', $scrolling_txt, false, 'regular-text ', 'The intro text that will appear on the scrolling box', 'Ex: Want to know more?', 'cbaffmach_scrolling_row', $enabled );
            cbaffmach_field_text( 'Button Text', 'cbaffmach_scrolling_button_txt', $button_txt, false, '', 'Text for the CTA button', 'Ex: click here', 'cbaffmach_scrolling_row', $enabled );
        ?>
    </table>
    <p>Add related products at the end of each article for maximum SEO benefit and exposure</p>
    <table class="form-table">
        <?php
            cbaffmach_field_checkbox( 'Enable related offers', 'cbaffmach_related_enable', $related, false, '', 'If checked, it will show related reviews/products at the bottom of each review article', '' );
        ?>
    </table>
    <p>Optionally, add an affiliate disclaimer at the end of each review</p>
    <table class="form-table">
        <?php
            cbaffmach_field_checkbox( 'Show affiliate disclaimer', 'cbaffmach_disclaimer_enable', $show_disclaimer, false, 'cbaffmach_disclaimer_enable', 'If checked, it will add a at the bottom of each review article', '' );
            cbaffmach_field_textarea( 'Disclaimer Text', 'cbaffmach_disclaimer_txt', $disclaimer_txt, false, 'regular-text ', 'The disclaimer text', 'This will text will show at the bottom of each review', 'cbaffmach_disclaimer_row', $show_disclaimer );
            cbaffmach_field_checkbox( 'Show affiliate id', 'cbaffmach_disclaimer_affid', $disclaimer_affid, false, '', 'If checked, it will add your cb affiliate id at the end of the disclaimer text', '', 'cbaffmach_disclaimer_row', $show_disclaimer );
        ?>
    </table>
<?php
}
?>