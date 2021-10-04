<?php
/**
 * Template part for displaying post format image entry.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package     Highend
 * @since       3.5.1
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) { 
	return;
}

if ( ! has_post_thumbnail() ) {
	return;
}

$crop = highend_option( 'hb_blog_enable_image_cropping' );

$width  = 900;
$height = false;

if ( $crop ) {
	$height = is_single() ? highend_option( 'hb_blog_image_height', 500 ) : 500;
}

$args = array(
	'width'    => $width,
	'height'   => $height,
	'crop'     => $crop,
	'lightbox' => is_single(),
);

highend_featured_image_thumb( $args );
