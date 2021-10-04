<?php
/**
 * Highend Customizer sanitization callback functions.
 *
 * @package Highend
 * @since   3.7
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * No sanitization. Used for controls that only output HTML.
 *
 * @since 3.7
 * @param mixed $val Value.
 */
function highend_no_sanitize( $val ) {
	return $val;
}

/**
 * Color field sanitization callback
 *
 * @since 3.7
 * @param string $color Color code.
 */
function highend_sanitize_color( $color ) {

	if ( empty( $color ) || is_array( $color ) ) {
		return '';
	}

	if ( false === strpos( $color, 'rgba' ) ) {
		return highend_sanitize_hex_color( $color );
	}

	return highend_sanitize_alpha_color( $color );
}

/**
 * Sanitize HEX color.
 *
 * @since 3.7
 * @param string $color Color code in HEX.
 */
function highend_sanitize_hex_color( $color ) {

	if ( '' === $color ) {
		return '';
	}

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return $color;
	}

	return '';
}

/**
 * Sanitize Alpha color.
 *
 * @since 3.7
 * @param string $color Color code in RGBA.
 */
function highend_sanitize_alpha_color( $color ) {

	if ( '' === $color ) {
		return '';
	}

	if ( false === strpos( $color, 'rgba' ) ) {
		/* Hex sanitize */
		return highend_sanitize_hex_color( $color );
	}

	/* rgba sanitize */
	$color = str_replace( ' ', '', $color );
	sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
	return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
}
