jQuery( function( $ ) {

			jQuery(document).on('change', '.pcfme_file', function() {

				var file_input_value = jQuery(this).attr("nkey");

				if ( ! this.files.length ) {
					jQuery( '.pcfme_filelist_'+file_input_value+'' ).empty();
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
									//jQuery( '.pcfme_filelist_'+file_input_value+'' ).html( '<img src="' +  response.image_url + '">' );
									jQuery( '.pcme_hidden_file_'+file_input_value+'' ).val( response.image_url );
								}
							}
						}
					});
				}
			});
});