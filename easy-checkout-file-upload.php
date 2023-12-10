<?php

/**
 * Custom File Upload module easy checkout field editor
 *
 * @author            SysBasics
 * @copyright         2019 Your SysBasics
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       File Upload module easy checkout field editor
 * Plugin URI:        https://sysbasics.com
 * Description:       Description of the plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            SysBasics
 * Author URI:        https://sysbasics.com
 * Text Domain:       plugin-slug
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://example.com/my-plugin/
 */

if( !defined( 'pcfme_PLUGIN_URL_file_upload_custom' ) )

define( 'pcfme_PLUGIN_URL_file_upload_custom', plugin_dir_url( __FILE__ ) );


add_filter('pcfme_override_field_types','pcfme_add_file_type_function_custom',10,1);

function pcfme_add_file_type_function_custom($field_types) {
	$field_types[] = array(
	 		    'type'=>'file_upload_custom',
	 		    'text'=> __('Custom Upload','pcfme'),
	 		    'icon'=> 'fa fa-upload'

	 	    );
	return $field_types;
}

add_filter( 'woocommerce_form_field_file_upload', 'pcfmefile_upload_form_field_custom', 10, 4 );

function pcfmefile_upload_form_field_custom($field, $key, $args, $value) {
	$key = isset($args['field_key']) ? $args['field_key'] : $key;

         if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';
	  
	     if ( $args['required'] ) {
			  $args['class'][] = 'validate-required';
			  $required = ' <abbr class="required" title="' . esc_attr__( 'required', 'pcfme'  ) . '">*</abbr>';
		  } else {
			$required = '';
		  }
		     


		$fees_class       = '';

		$fees_class       = pcfme_get_fees_class($key);

		
		if ($value == "empty") {
			$value = "";
		}
    $max_allowed = isset($args['max_file_size']) ? $args['max_file_size'] : 2;

    $allowed_file_types = isset($args['allowed_file_types']) ? $args['allowed_file_types'] : "png,jpeg,pdf";

	$input_html =  '<div class="form-row form-row-wide"><input nkey="'.$key.'" type="file" class="pcfme_file" allowed_type="'.$allowed_file_types.'" max_allowed="'.$max_allowed.'" id="pcfme_file_'.$key.'" name="pcfme_file_'.$key.'" /><input class="pcme_hidden_file_'.$key.'" type="hidden" name="' . $key . '" /><div class="pcfme_filelist pcfme_filelist_' . $key . '"></div>
	</div>';
	

        $field = '<p class="form-row ' . implode( ' ', $args['class'] ) .' " id="' . $key . '_field">
            <label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>
            ' . $input_html . '
        </p>' . $after;
         

    return $field;
}

add_action( 'wp_ajax_pcfme_checkout_file_upload', 'pcfme_file_upload_custom' );
add_action( 'wp_ajax_nopriv_pcfme_checkout_file_upload', 'pcfme_file_upload_custom' );

function pcfme_file_upload_custom(){

	$upload_dir = wp_upload_dir();
	$image_url = '';
	if ( isset( $_FILES[ 'pcfme_file' ] ) ) {
		$path = $upload_dir[ 'path' ] . '/' . basename( $_FILES[ 'pcfme_file' ][ 'name' ] );

		if( move_uploaded_file( $_FILES[ 'pcfme_file' ][ 'tmp_name' ], $path ) ) {
			$image_url = $upload_dir[ 'url' ] . '/' . basename( $_FILES[ 'pcfme_file' ][ 'name' ] );
		}
	}

	wp_send_json( array( 'type' => 'success', 'image_url' => $image_url ) );
}

add_filter( 'wp_enqueue_scripts', 'pcfme_add_checkout_frountend_scripts_custom' );

function pcfme_add_checkout_frountend_scripts_custom() {
	if ( is_checkout() || is_account_page() ) {
       wp_enqueue_script( 'pcfme_file_upload', ''.pcfme_PLUGIN_URL_file_upload.'assets/js/frontend.js',array('jquery') );
        wp_enqueue_style( 'pcfme_file_upload', ''.pcfme_PLUGIN_URL_file_upload.'assets/css/frontend.css' );

        $translation_array = array( 
		        'max_allowed_text'               => esc_html__( 'Maximum size allowed for this upload is ' ,'pcfme'),

		        'type_allowed_text'               => esc_html__( 'File type allowed' ,'pcfme'),
		        
		);
         
        wp_localize_script( 'pcfme_file_upload', 'pcfme_file_upload', $translation_array );
	}
}

add_action( 'woocommerce_checkout_update_order_meta', 'pcfme_file_field_save_added_custom' );
function pcfme_file_field_save_added_custom( $order_id ){

	if( ! empty( $_POST[ 'pcfme_file_field' ] ) ) {
		update_post_meta( $order_id, 'pcfme_file_field', sanitize_text_field( $_POST[ 'pcfme_file_field' ] ) );
	}

}



add_action( 'woocommerce_admin_order_data_after_order_details', 'pcfme_order_meta_general_custom' );

function pcfme_order_meta_general_custom( $order ){

	$file = get_post_meta( $order->get_id(), 'pcfme_file_field', true );
	if( $file ) {
		echo '<img class="cxc-order-img" style="max-width: 400px;width: 100%;height: auto; margin-top: 10px;" src="'. esc_url( $file ) .'" />';
	}

}

add_action('admin_enqueue_scripts','pcfme_register_admin_scripts_file_upload_custom');

/*
 * registers admin scripts via admin enqueue scripts
 */
function pcfme_register_admin_scripts_file_upload_custom($hook) {
	    global $billing_pcfmesettings_page;
			
		if ( $hook == $billing_pcfmesettings_page ) {
		     
 
		 
		 
		 
		    
		    wp_enqueue_script( 'pcfmeadmin-file_upload', ''.pcfme_PLUGIN_URL_file_upload.'assets/js/admin.js' , array('jquery'));
		 
         
		    //wp_enqueue_style( 'pcfmeadmin', ''.pcfme_PLUGIN_URL.'assets/css/pcfmeadmin.css' );
		    
		 

        
		
		 
		    $translation_array = array( 
		        //'removealert'               => esc_html__( 'Are you sure you want to delete?' ,'pcfme'),
		        
		    );
         
            wp_localize_script( 'pcfmeadmin-file_upload', 'pcfmeadmin-file_upload', $translation_array );
        }
	

}

add_action('pcfme_after_visibility_content_tr','pcfme_after_visibility_content_tr_function_custom',10,3);


function pcfme_after_visibility_content_tr_function_custom($slug,$key,$field) {
	?>

	<tr class="visible_only_if_field_type_file_upload" style="<?php if (isset($field['type']) && ($field['type'] == "file_upload")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
		<td width="25%">
			<label for="<?php echo $key; ?>_charlimit"><?php echo esc_html__('Max file size allowed','pcfme'); ?></label>
		</td>
		<td width="75%">
			<?php $max_allowed = isset($field['max_file_size']) ? $field['max_file_size'] : 2; ?>
			<input type="number" name="<?php echo $slug; ?>[<?php echo $key; ?>][max_file_size]" value="<?php echo $max_allowed; ?>">
			<?php echo esc_html__('MB','pcfme'); ?>
		</td>
	</tr>

	<tr class="visible_only_if_field_type_file_upload" style="<?php if (isset($field['type']) && ($field['type'] == "file_upload")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
		<td width="25%">
			<label for="<?php echo $key; ?>_charlimit"><?php echo esc_html__('Allowed file types','pcfme'); ?></label>
		</td>
		<td width="75%">
			<?php $allowed_file_types = isset($field['allowed_file_types']) ? $field['allowed_file_types'] : "png,jpeg,pdf"; ?>
			<input type="text" name="<?php echo $slug; ?>[<?php echo $key; ?>][allowed_file_types]" value="<?php echo $allowed_file_types; ?>">
		
		</td>
	</tr>

	<?php
}