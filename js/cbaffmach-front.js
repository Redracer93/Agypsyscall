jQuery(document).ready( function($) {
	// try {
	// 	jQuery('a.autoc-popup').webuiPopover({trigger:"hover",animation:"pop"});
	// 	}
	// 	catch(err) {
	// 	}
	jQuery( document ).on( 'click', '.cbaffmach_submit_optin', function(e) {
		e.preventDefault();
		// console.log('click')
		var $this = jQuery(this);
		var parent = $this.closest( '.cbaffmach-optin-form' );
		var email_to = parent.find( '.cbaffmach-email' ).val();
		var name_to = parent.find( '.cbaffmach-name' ).val();
		var postid = parent.find( '.cbaffmach-post-id' ).val();
		var monetize_id = parent.find( '.cbaffmach-monetize-id' ).val();

		var redirect = parent.find( '.cbaffmach-redirect-url' ).val();
		var thankyou = parent.find( '.cbaffmach-thankyou' ).html();

		form_el = $this.closest('.wplpdf-optin-formp')
		$this.attr('disabled', 'disabled').val('Sending...');

		var data = {
			action: 'cbaffmach_optin_submit',
			email_to: email_to,
			name_to: name_to,
			post_id: postid,
			monetize_id: monetize_id
		};

		jQuery.post( cbaffmach_vars.ajax_url, data, function(response) {
			if( response.INVALIDEMAIL ) {
				var htmlq = '<div style="color:red"><span style="padding-top:5px">Please enter a valid e-mail address.</span></div>';
				$this.removeAttr( 'disabled' ).val( 'Submit' );
				$this.after( htmlq );
			} else if( response.SAVED ) {
				if ( redirect != 0 && redirect != '') {
					parent.html('<span>'+thankyou+'</span>');
					setTimeout( function(){ location.href = redirect; }, 2000 );
				}
				else {
					parent.html('<span>'+thankyou+'</span>');
				}
			}
		});
	});

});