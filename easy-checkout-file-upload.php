<?php

/**
 * File Upload module easy checkout field editor
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

 if( !defined( 'pcfme_PLUGIN_URL_file_upload' ) )
define( 'pcfme_PLUGIN_URL_file_upload', plugin_dir_url( __FILE__ ) );


add_filter('pcfme_override_field_types','pcfme_add_file_type_function',10,1);

function pcfme_add_file_type_function($field_types) {
	$field_types[] = array(
	 		    'type'=>'file_upload',
	 		    'text'=> __('File Upload','pcfme'),
	 		    'icon'=> 'fa fa-upload'

	 	    );
	return $field_types;
}

add_filter( 'woocommerce_form_field_file_upload', 'pcfmefile_upload_form_field', 10, 4 );

function pcfmefile_upload_form_field($field, $key, $args, $value) {
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


	$input_html =  '<div class="form-row form-row-wide"><input nkey="'.$key.'" type="file" class="pcfme_file" id="pcfme_file_'.$key.'" name="pcfme_file_'.$key.'" /><input class="pcme_hidden_file_'.$key.'" type="hidden" name="' . $key . '" /><div class="pcfme_filelist pcfme_filelist_' . $key . '"></div>
	</div>';
	

        $field = '<p class="form-row ' . implode( ' ', $args['class'] ) .' " id="' . $key . '_field">
            <label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>
            ' . $input_html . '
        </p>' . $after;
         

        return $field;
}

add_action( 'wp_ajax_pcfme_checkout_file_upload', 'pcfme_file_upload' );
add_action( 'wp_ajax_nopriv_pcfme_checkout_file_upload', 'pcfme_file_upload' );
function pcfme_file_upload(){

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

add_filter( 'wp_enqueue_scripts', 'pcfme_add_checkout_frountend_scripts' );

function pcfme_add_checkout_frountend_scripts() {
	if ( is_checkout() || is_account_page() ) {
       wp_enqueue_script( 'pcfme_file_upload', ''.pcfme_PLUGIN_URL_file_upload.'assets/js/frontend.js',array('jquery') );
        wp_enqueue_style( 'pcfme_file_upload', ''.pcfme_PLUGIN_URL_file_upload.'assets/css/frontend.css' );
	}
}

add_action( 'woocommerce_checkout_update_order_meta', 'pcfme_file_field_save_added' );
function pcfme_file_field_save_added( $order_id ){

	if( ! empty( $_POST[ 'pcfme_file_field' ] ) ) {
		update_post_meta( $order_id, 'pcfme_file_field', sanitize_text_field( $_POST[ 'pcfme_file_field' ] ) );
	}

}



add_action( 'woocommerce_admin_order_data_after_order_details', 'pcfme_order_meta_general' );
function pcfme_order_meta_general( $order ){

	$file = get_post_meta( $order->get_id(), 'pcfme_file_field', true );
	if( $file ) {
		echo '<img class="cxc-order-img" style="max-width: 400px;width: 100%;height: auto; margin-top: 10px;" src="'. esc_url( $file ) .'" />';
	}

}