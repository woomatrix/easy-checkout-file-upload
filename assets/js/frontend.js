jQuery( function( $ ) {

			jQuery(document).on('change', '.pcfme_file', function() {

				var file_input_value = jQuery(this).attr("nkey");

				var max_allowed      = jQuery(this).attr("max_allowed");

				var max_allowed_text2 =  jQuery(this).attr("max_allowed");

				max_allowed          = (max_allowed * 1024) * 1024;

				if ( ! this.files.length ) {
					jQuery( '.pcfme_filelist_'+file_input_value+'' ).empty();
				} else {
					const file = this.files[0];
					const formData = new FormData();
					formData.append( 'pcfme_file', file );

					var upload_size = this.files[0].size;

					upload_size = (upload_size);

					upload_size = Math.round(upload_size);
 
                    if (upload_size > max_allowed) {

                    	alert(''+pcfme_file_upload.max_allowed_text+' '+max_allowed_text2+' MB');

                    	jQuery( '.pcme_hidden_file_'+file_input_value+'' ).val("");

                    	return false;

                    } else {

                    	var allowed_type = jQuery(this).attr("allowed_type");

                    	var extension  = this.files[0].type;

                    	extension = extension.split('/');

                    	extension = extension[1];

                    	if(allowed_type.indexOf(extension) != -1){

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

                    	} else {

                    		alert(''+pcfme_file_upload.type_allowed_text+' '+allowed_type+'');

                    	}

                    	

                    }					
				}
			});
});