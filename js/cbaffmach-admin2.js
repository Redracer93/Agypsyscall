jQuery(document).ready( function($) {
    jQuery(document).on( 'click', '.cbaffmach-anotice .notice-dismiss', function() {
        jQuery.ajax({
            url: ajaxurl,
            data: {
                action: 'cbaffmach_dismiss_notice'
            }
        })
    });
});