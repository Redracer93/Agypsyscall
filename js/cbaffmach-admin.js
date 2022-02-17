var cbaffmach_global_lead = 0;
var cbaffmach_global_postid = 0;
var global_aff_url = '';
jQuery(document).ready( function($) {
    $('#cbaffmach-tabs a.nav-tab').click(function(e){
        e.preventDefault();
        $(this).addClass("nav-tab-active");
        $(this).siblings().removeClass("nav-tab-active");
        var tab = $(this).attr("href");
        $(".tab-inner").not(tab).css("display", "none");
        $(tab).fadeIn();
    });

    $('#cbaffmach-tabs a.nav-tab-inner').click(function(e){
        e.preventDefault();
        var tab = $(this).attr("href");
        current_tab = $('body').find('a[href='+tab+']');
        current_tab.addClass("nav-tab-active");
        current_tab.siblings().removeClass("nav-tab-active");
        $(".tab-inner").not(tab).css("display", "none");
        $(tab).fadeIn();
    });

    new ClipboardJS( '.cbaffmach-copy' );
    $( "#cb-your-tracking-id" ).keyup(function() {
      // console.log( "Handler for .keypress() called." );
      var base = global_aff_url;
      // if( $('#cb-your-tracking-id'))
      if( !$.trim($('#cb-your-tracking-id').val()).length )
        $('#cb-your-aff-link' ).val( base );
    else
        $('#cb-your-aff-link' ).val( base+'?tid='+$.trim($('#cb-your-tracking-id').val() ));

    });

    $('.btn-share-post').click(function(e){
        e.preventDefault();
        var post_id = jQuery(this).attr( 'data-post-id' );
        cbaffmach_global_postid = post_id;
        $('#amshare-modal').ammodal();
    });

    $('.enter-aff-link').click(function(e){
        e.preventDefault();
        var post_id = jQuery(this).attr( 'data-post-id' );
        cbaffmach_global_postid = post_id;
        $('#amlink-modal').ammodal();
    });

    $('#btn-save-aff-link').click(function(e){
        e.preventDefault();
        var link = jQuery('#custom-aff-link').val();
        var data = {
            action: 'cbaffmach_save_aff_link',
            post_id : cbaffmach_global_postid,
            link : link
        };

        jQuery.post(ajaxurl, data, function(response) {
            $.ammodal.close();
        });
    });

    $('#btn-share-aff-link').click(function(e){
        e.preventDefault();
       // var link = jQuery('#custom-aff-link').val();
       var networks = new Array();
       $( ".cbaffmach_share_nw" ).each(function( index ) {
        var nw = jQuery(this);
        if ( nw.is(':checked') )
            networks.push( 1 );
        else
            networks.push( 0 );
         // console.log( index + ": " + $( this ).text() );
       });
       // console.log(networks)
       var data = {
           action: 'cbaffmach_share_link',
           post_id : cbaffmach_global_postid,
           networks : networks
       };

       jQuery.post(ajaxurl, data, function(response) {
           $.ammodal.close();
       });
    });

    $('#cbaffmach_shareauto').change(function(e){
        e.preventDefault();
        val = $(this).is(':checked')
       if( val )
        $('#share-new-posts').show();
         else
        $('#share-new-posts').hide();
    });

    $('.btn-create-vid').click(function(e){
        e.preventDefault();
        var post_id = jQuery(this).attr( 'data-post-id' );
        cbaffmach_global_postid = post_id;
        $('#amvideo-modal').ammodal();
    });

    $('#btn-do-create-video').click(function(e){
        e.preventDefault();
        jQuery( '#row-wpam-'+cbaffmach_global_postid+' .btn-create-vid' ).hide();
        var data = {
            action: 'cbaffmach_create_video',
            post_id : cbaffmach_global_postid
        };

        jQuery.post(ajaxurl, data, function(response) {
            $.ammodal.close();
        });
    });
    /*$('#cbaffmach_bmachine').change(function(e){
        e.preventDefault();
        val = $(this).is(':checked')
       if( val )
        $('#cbaffmach_bm_row').show();
         else
        $('#cbaffmach_bm_row').hide();
    });*/
    
    jQuery('.cbaffmach-color-picker').wpColorPicker();
    jQuery('a.cbaffmachpop').webuiPopover({trigger:'hover'});

    /* 1.2. WP Image Selector */
    var _custom_media = true,
    _orig_send_attachment = wp.media.editor.send.attachment;

    jQuery(document).on('click', '.cbaffmach_img_upload', function(e) {
        e.preventDefault();
        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = jQuery(this);
        _custom_media = true;

        wp.media.editor.send.attachment = function(props, attachment){
            // console.log('a')
            if ( _custom_media ) {
            // console.log('b')

                // console.log(attachment)
                parent = button.closest('.file-upload-parent');
                parent.find('.file-upload-url').val(attachment.url)
                jQuery('.file-upload-url').trigger('change');
                parent.find('.file-upload-img').attr('src', attachment.url)
                parent.find('.file-upload-img').show();
            } else {
                return _orig_send_attachment.apply( this, [props, attachment] );
            };
        }

        wp.media.editor.open(button);
        return false;
    });

    /* Clickbank Only */

    $('.btn-aff-link').click(function(e){
        e.preventDefault();
        var product_id = jQuery(this).attr( 'data-product-id' );
        var data = {
            action: 'cbaffmach_get_cb_link',
            product_id : product_id
        };

        jQuery.post(ajaxurl, data, function(response) {
            // console.log(response)
            // document.location = document.location;
            // this_button.next().remove();
            global_aff_url = response;
            jQuery( '#cb-your-aff-link' ).val( response );
        });
        $('#cblink-modal').ammodal();
    });
    /* Custom Stuff */

    /* MOnetize*/

    jQuery(document).on('click', '.cbaffmach-add-inline-link', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = jQuery('#cbaffmach-inline-links-table');
        // var num = parent.find('.cbaffmach_num').val();
        num_rows = parent.find( '.cbaffmach_links_row' ).length + 1;
        field1_name = 'cbaffmach_monetize_links['+num_rows+'][keyword]';
        field2_name = 'cbaffmach_monetize_links['+num_rows+'][url]';
        var row = '<tr class="cbaffmach_links_row"><td><input type="text" style="width:100%" name="'+field1_name+'" placeholder ="Keyword, for ex weight loss" /></td><td><input type="text" style="width:100%" name="'+field2_name+'"  placeholder ="URL, for ex http://youroffer.com" /></td>'+
        '<td><button type="button" class="button button-secondary cbaffmach_remove_link_row"><i class="fas fa-times"></i></button></td></tr>';
        parent.append(row);
    });

    jQuery(document).on('click', '.cbaffmach_remove_link_row', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( 'tr' )
        parent.remove();
    });

    jQuery( 'input.cbaffmach_amazon_enabled').change( function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var checked = $this.is( ':checked' );
        if( checked )
            jQuery( '.cbaffmach_mon_amazon_row' ).show();
        else
            jQuery( '.cbaffmach_mon_amazon_row' ).hide();
    });

    jQuery( 'input.cbaffmach_ebay_enabled').change( function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var checked = $this.is( ':checked' );
        if( checked )
            jQuery( '.cbaffmach_mon_ebay_row' ).show();
        else
            jQuery( '.cbaffmach_mon_ebay_row' ).hide();
    });

    jQuery( 'input.cbaffmach_clickbank_enabled').change( function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var checked = $this.is( ':checked' );
        if( checked )
            jQuery( '.cbaffmach_mon_clickbank_row' ).show();
        else
            jQuery( '.cbaffmach_mon_clickbank_row' ).hide();
    });

    jQuery( 'input.cbaffmach_aliexpress_enabled').change( function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var checked = $this.is( ':checked' );
        if( checked )
            jQuery( '.cbaffmach_mon_aliexpress_row' ).show();
        else
            jQuery( '.cbaffmach_mon_aliexpress_row' ).hide();
    });

    jQuery( 'input.cbaffmach_walmart_enabled').change( function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var checked = $this.is( ':checked' );
        if( checked )
            jQuery( '.cbaffmach_mon_walmart_row' ).show();
        else
            jQuery( '.cbaffmach_mon_walmart_row' ).hide();
    });

    jQuery( 'input.cbaffmach_bestbuy_enabled').change( function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var checked = $this.is( ':checked' );
        if( checked )
            jQuery( '.cbaffmach_mon_bestbuy_row' ).show();
        else
            jQuery( '.cbaffmach_mon_bestbuy_row' ).hide();
    });

    jQuery( 'input.cbaffmach_envato_enabled').change( function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var checked = $this.is( ':checked' );
        if( checked )
            jQuery( '.cbaffmach_mon_envato_row' ).show();
        else
            jQuery( '.cbaffmach_mon_envato_row' ).hide();
    });

    jQuery( 'input.cbaffmach_gearbest_enabled').change( function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var checked = $this.is( ':checked' );
        if( checked )
            jQuery( '.cbaffmach_mon_gearbest_row' ).show();
        else
            jQuery( '.cbaffmach_mon_gearbest_row' ).hide();
    });

    jQuery(document).on('change', '.cbaffmach_banner_pos', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( '.tab-inner' )
        if( $this.val() == 4 )
            parent.find( '.cbaffmach_paragraph_row' ).show();
        else
            parent.find( '.cbaffmach_paragraph_row' ).hide();
    });

        /* Autoresponders */

    jQuery('#cbaffmach_mailit_type').change(function(e) {
        $this = jQuery(this);
        if( $this.val() == 2 ) {
            jQuery('#mailit-local-settings').hide();
            jQuery('#mailit-remote-settings').show();
        }
        else {
            jQuery('#mailit-remote-settings').hide();
            jQuery('#mailit-local-settings').show();
        }
    });

    jQuery('#do-activate-mailit').click(function(e) {
        e.preventDefault();
        jQuery('#activate-mailit-plg').val(1);
        jQuery('#mailit-vp-form').submit();
    });

    jQuery('.aweber_refresh_lists').click(function(e) {
        e.preventDefault();
        cbaffmach_autoresponder_refresh(1, jQuery(this));
    });

    jQuery('.getresponse_refresh_lists').click(function(e) {
        e.preventDefault();
        cbaffmach_autoresponder_refresh(2, jQuery(this));
    });

    jQuery('.mailchimp_refresh_lists').click(function(e) {
        e.preventDefault();
        cbaffmach_autoresponder_refresh(3, jQuery(this));
    });

    jQuery('.icontact_refresh_lists').click(function(e) {
        e.preventDefault();
        cbaffmach_autoresponder_refresh(4, jQuery(this));
    });

    jQuery('.constantcontact_refresh_lists').click(function(e) {
        e.preventDefault();
        cbaffmach_autoresponder_refresh(5, jQuery(this));
    });

    jQuery('.sendreach_refresh_lists').click(function(e) {
        e.preventDefault();
        cbaffmach_autoresponder_refresh(6, jQuery(this));
    });

    jQuery('.sendy_refresh_lists').click(function(e) {
        e.preventDefault();
        cbaffmach_autoresponder_refresh(7, jQuery(this));
    });

    jQuery('.activecampaign_refresh_lists').click(function(e) {
        e.preventDefault();
        // alert('x');
        cbaffmach_autoresponder_refresh(8, jQuery(this));
    });

    jQuery('.mailit_refresh_lists').click(function(e) {
        e.preventDefault();
        cbaffmach_autoresponder_refresh(9, jQuery(this));
    });

    jQuery('.sendlane_refresh_lists').click(function(e) {
        e.preventDefault();
        cbaffmach_autoresponder_refresh(11, jQuery(this));
    });

    jQuery('.gtw_refresh_webinars').click(function(e) {
        e.preventDefault();
        cbaffmach_autoresponder_refresh(101, jQuery(this));
    });

    jQuery(document).on('change', '.cbaffmach_autoresponder', function(e) {
        e.preventDefault();
        /* Act on the event */
        $this = jQuery(this);
        var parent = $this.closest( '.tab-inner' )
// console.log($this.val())
        // $parent = $this.closest('.cbaffmach-action')
        if ( $this.val() == 0 || $this.val() == 12 ) {
            parent.find( '.cbaffmach_list_row' ).hide()
            // $parent.find('.cbaffmach-ar-html-code').show()
        }
        else {
            type = $this.val();
            cbaffmach_update_ar_list_by_type( type, true, parent.find( '.cbaffmach_list' ) );
            // $parent.find('.cbaffmach-ar-html-code').hide()
            parent.find( '.cbaffmach_list_row' ).show()
        }
    });

    jQuery(document).on('change', '.cbaffmach_optin_show_name', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( '.tab-inner' );
        var checked = $this.is( ':checked' );
        if( checked )
            parent.find( '.cbaffmach_optin_name_field_row' ).show();
        else
            parent.find( '.cbaffmach_optin_name_field_row' ).hide();
    });

    jQuery(document).on('change', '.cbaffmach_optin_enabled', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( '.tab-inner' );
        var checked = $this.is( ':checked' );
        if( checked )
            jQuery( '#optin-box-settings' ).show();
        else
            jQuery( '#optin-box-settings' ).hide();
    });
    
    jQuery ('.cbaffmach-remove-lead').click(function( e ){
        e.preventDefault();
        cbaffmach_global_lead = jQuery(this).attr('data-lead-id')
        jQuery( '#cbaffmach-leads-modal' ).ammodal();
    });

    jQuery ('#cbaffmach-do-remove-lead').click(function( e ){
        e.preventDefault();
        cbaffmach_delete_lead( cbaffmach_global_lead );
    });

    jQuery ('#do-search-leads').click(function( e ){
        e.preventDefault();
        // cbaffmach_delete_lead( global_lead );
        document.location = document.location + '&stm='+jQuery('#cbaffmach_stm').val();
    });

    jQuery ('#do-clear-leads').click(function( e ){
        e.preventDefault();
        // cbaffmach_delete_lead( global_lead );
        document.location = cbaffmach_vars.admin_url+'admin.php?page=affiliate-machine-leads';
    });

    jQuery(document).on('change', '.cbaffmach_scrolling_enable', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( '.tab-inner' );
        var checked = $this.is( ':checked' );
        if( checked )
            jQuery('.cbaffmach_scrolling_row').show();
        else
            jQuery('.cbaffmach_scrolling_row').hide();
    });

    jQuery(document).on('change', '.cbaffmach_disclaimer_enable', function( e ) {
        e.preventDefault();
        var $this = jQuery(this)
        var parent = $this.closest( '.tab-inner' );
        var checked = $this.is( ':checked' );
        if( checked )
            jQuery('.cbaffmach_disclaimer_row').show();
        else
            jQuery('.cbaffmach_disclaimer_row').hide();
    });
});

function cbaffmach_tmce_setContent(content, editor_id, textarea_id) {
  if ( typeof editor_id == 'undefined' ) editor_id = wpActiveEditor;
  if ( typeof textarea_id == 'undefined' ) textarea_id = editor_id;
  if ( jQuery('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
    return tinyMCE.get(editor_id).setContent(content);
  }else{
    return jQuery('#'+textarea_id).val(content);
  }
}

/* Autoresponders */

function cbaffmach_autoresponder_refresh(ar_type, this_button) {
    this_button.after('<img style="margin-left:20px" src="'+cbaffmach_vars.plugin_url+'/img/ajax-loader-small.gif" >')

    var data = {
        action: 'cbaffmach_refresh_autoresponder',
        ar_type : ar_type
    };

    jQuery.post(ajaxurl, data, function(response) {
        // document.location = document.location;
        this_button.next().remove();
    });
}

function cbaffmach_update_ar_list_by_type(type, update, element) {
    // console.log(type);
    switch (type) {
        case '1':
            lists = cbaffmach_vars.aweber_lists;
            break;
        case '2':
            lists = cbaffmach_vars.getresponse_lists;
            break;
        case '3':
            lists = cbaffmach_vars.mailchimp_lists;
            break;
        case '4':
            lists = cbaffmach_vars.icontact_lists;
            break;
        case '5':
            lists = cbaffmach_vars.ccontact_lists;
            break;
        case '6':
            lists = cbaffmach_vars.sendreach_lists;
            break;
        case '7':
            lists = cbaffmach_vars.sendy_lists;
            break;
        case '8':
            lists = cbaffmach_vars.activecampaign_lists;
            break;
        case '9':
            lists = cbaffmach_vars.mailit_lists;
            break;
        case '11':
            lists = cbaffmach_vars.sendlane_lists;
            break;
        case '10':
        default:
            lists = new Array();
            break;

    }
// console.log(lists);
    str_ret = '<option value="0">Select list...</option>';
    if (lists)
        n_lists = lists.length;
    else
        n_lists = 0;
    if (n_lists) {
        for (i=0;i<n_lists;i++) {
            str_ret += '<option value="'+lists[i].id+'">'+lists[i].name+'</option>';
        }
    }
    if ( update ) {
        // jQuery('.ar_list').html(str_ret);
        // parent.find('.cbaffmach-ar-list-el').html(str_ret);
        element.html(str_ret);
        // parent.find('.cbaffmach-ar-list-el').html(str_ret);
    }
    else
        return str_ret;
    return;
    // return str_ret;
}

function cbaffmach_delete_lead( lead_id ) {

    var data = {
        action: 'cbaffmach_remove_lead',
        lead_id: lead_id
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(ajaxurl, data, function(response) {
        if ( response ) {
            // document.location = document.location;
            console.log('deleted')
            document.location = cbaffmach_vars.admin_url+'admin.php?page=affiliate-machine-leads';
            return;
        }
    });
}