jQuery( function( $ ) {

			jQuery(document).on('change', '#pcfme_file', function() {

				if ( ! this.files.length ) {
					jQuery( '#pcfme_filelist' ).empty();
				} else {
					const file = this.files[0];
					const formData = new FormData();
					formData.append( 'pcfme_file', file );

					jQuery.ajax({
						url: wc_checkout_params.ajax_url + '?action=pcfme_checkout_file_upload',
						type: 'POST',
						data: formData,
						contentType: false,
						enctype: 'multipart/form-data',
						processData: false,
						success: function ( response ) {
							if( response ){
								if( response.type == 'success' ){
									jQuery( '#pcfme_filelist' ).html( '<img src="' +  response.image_url + '">' );
									jQuery( 'input[name="pcfme_file_field"]' ).val( response.image_url );
								}
							}
						}
					});
				}
			} );
});