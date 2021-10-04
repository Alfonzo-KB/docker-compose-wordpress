<?php
/**
 * Colors section in Customizer.
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

if ( ! function_exists( 'highend_customizer_options_colors' ) ) :
	function highend_customizer_options_colors( $options ) {

		// Section.
		$options['section']['highend_section_colors'] = array(
			'title'    => esc_html__( 'Accent Color', 'hbthemes' ),
			'panel'    => 'highend_panel_colors',
			// 'priority' => 20,
		);

		// Accent color.
		$options['setting']['highend_accent_color'] = array(
			'transport'         => 'postMessage',
			'sanitize_callback' => 'highend_sanitize_color',
			'control'           => array(
				'type'        => 'color',
				'label'       => esc_html__( 'Accent Color', 'hbthemes' ),
				'description' => esc_html__( 'The accent color is used subtly throughout your site, to call attention to key elements.', 'hbthemes' ),
				'section'     => 'highend_section_colors',
				'priority'    => 10,
				'opacity'     => false,
			),
		);

		return $options;
	}
endif;

add_filter( 'highend_customizer_options', 'highend_customizer_options_colors' );
