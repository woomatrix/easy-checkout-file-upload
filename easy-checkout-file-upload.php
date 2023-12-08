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

		

        $field = '<p class="form-row ' . implode( ' ', $args['class'] ) .' " id="' . $key . '_field">
            <label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>
            <input type="file" class="'.$fees_class.' input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'  '. pcfmeinput_conditional_class($key) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . $args['maxlength'] . ' ' . $args['autocomplete'] . ' value="' . esc_attr( $value ) . '" />
        </p>' . $after;
         

        return $field;
}