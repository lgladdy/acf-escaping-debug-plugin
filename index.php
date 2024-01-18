<?php
/**
 * Plugin Name:       ACF Detailed Escaping Debug Logger
 * Plugin URI:        https://github.com/lgladdy/acf-escaping-debug-plugin/
 * Description:       This plugin will add a detailed error log message whenever ACF has removed or modified (or will modify, in ACF 6.2.7) potentially unsafe HTML on output when using the ACF shortcode, the_field or the_sub_field.
 * Version:           1.0.0
 * Author:            Liam Gladdy
 */

add_action( 'acf/will_remove_unsafe_html', 'acf_enable_detailed_escape_logging_to_php_error_log', 10, 4 );
add_action( 'acf/removed_unsafe_html', 'acf_enable_detailed_escape_logging_to_php_error_log', 10, 4 );
function acf_enable_detailed_escape_logging_to_php_error_log( $function, $selector, $field_object, $post_id ) {
	if ( $function === 'the_sub_field' ) {
		$field = get_sub_field_object( $selector, true );
		$value = ( is_array( $field ) && isset( $field['value'] ) ) ? $field['value'] : false;
	} else {
		$value = get_field( $selector, $post_id );
	}
	if ( is_array( $value ) ) {
		$value = implode( ', ', $value );
	}

	$field_type              = is_array( $field_object ) && isset( $field_object['type'] ) ? $field_object['type'] : 'text';
	$field_type_escapes_html = acf_field_type_supports( $field_type, 'escaping_html' );

	if ( $field_type_escapes_html ) {
		if ( $function === 'the_sub_field' ) {
			$field     = get_sub_field_object( $selector, true, true, true );
			$new_value = ( is_array( $field ) && isset( $field['value'] ) ) ? $field['value'] : false;
		} else {
			$new_value = get_field( $selector, $post_id, true, true );
		}
		if ( is_array( $new_value ) ) {
			$new_value = implode( ', ', $new_value );
		}
	} else {
		$new_value = acf_esc_html( $value );
	}

	if ( empty( $post_id ) ) {
		$post_id = acf_get_valid_post_id( $post_id );
	}

	if ( $function === 'acf_shortcode' ) {
		$template = get_page_template() . ' (likely not relevant for shortcode)';
	} else {
		$template = get_page_template();
	}

	error_log(
		'***ACF HTML Escaping Debug***' . PHP_EOL .
		'HTML modification detected the value of ' . $selector . ' on post ID ' . $post_id . ' via ' . $function . PHP_EOL .
		'Raw Value: ' . var_export( $value, true ) . PHP_EOL .
		'Escaped Value: ' . var_export( $new_value, true ) . PHP_EOL .
		'Template: ' . $template
	);
}
