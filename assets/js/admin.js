jQuery( function( $ ) {

			jQuery(document).on('change', '.pcfme_file', function() {

				var file_input_value = jQuery(this).attr("nkey");

				if ( ! this.files.length ) {
					jQuery( '.pcfme_filelist_'+file_input_value+'' ).empty();
				} else {
					const file = this.files[0];
					const formData = new FormData();
					formData.append( 'pcfme_file', file );

					var upload_size = this.files[0].size;

					upload_size = (upload_size/1024);

					upload_size = Math.round(upload_size);

					

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

			jQuery(document).on('change', '.checkout_field_type', function() {
                
                var new_val = jQuery(this).val();

                if (new_val == "file_upload") {
                	jQuery('tr.visible_only_if_field_type_file_upload_custom').show();
                } else {
                	jQuery('tr.visible_only_if_field_type_file_upload_custom').hide();
                }

			});
});