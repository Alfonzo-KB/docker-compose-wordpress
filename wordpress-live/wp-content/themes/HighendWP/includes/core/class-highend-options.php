<?php
/**
 * Highend Options Class.
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

if ( ! class_exists( 'Highend_Options' ) ) :

	/**
	 * Highend Options Class.
	 */
	class Highend_Options {

		/**
		 * Singleton instance of the class.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private static $instance;

		/**
		 * Options variable.
		 *
		 * @since 1.0.0
		 * @var mixed $options
		 */
		private static $options;

		/**
		 * Main Highend_Options Instance.
		 *
		 * @since 1.0.0
		 * @return Highend_Options
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Highend_Options ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// @todo: remove in v4.0
			add_action( 'after_setup_theme', array( $this, 'init_vp_options' ) );

			// Refresh options.
			add_action( 'after_setup_theme', array( $this, 'refresh' ) );
		}

		/**
		 * Set default option values.
		 *
		 * @since  1.0.0
		 * @return array Default values.
		 */
		public function get_defaults() {

			$defaults = array(
			);

			$defaults = apply_filters( 'highend_default_option_values', $defaults );

			return $defaults;
		}

		/**
		 * Get the options from static array()
		 *
		 * @since  1.0.0
		 * @return array    Return array of theme options.
		 */
		public function get_options() {
			return self::$options;
		}

		/**
		 * Get the options from static array()
		 *
		 * @since  1.0.0
		 * @return array    Return array of theme options.
		 */
		public function get( $id ) {
			$value = isset( self::$options[ $id ] ) ? self::$options[ $id ] : self::get_default( $id );
			$value = apply_filters( "theme_mod_{$id}", $value ); // phpcs:ignore
			return $value;
		}

		/**
		 * Set option.
		 *
		 * @since  1.0.0
		 */
		public function set( $id, $value ) {
			set_theme_mod( $id, $value );
			self::$options[ $id ] = $value;
		}

		/**
		 * Refresh options.
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function refresh() {
			self::$options = wp_parse_args(
				get_theme_mods(),
				self::get_defaults()
			);
		}

		/**
		 * Returns the default value for option.
		 *
		 * @since  1.0.0
		 * @param  string $id Option ID.
		 * @return mixed      Default option value.
		 */
		public function get_default( $id ) {
			$defaults = self::get_defaults();
			return isset( $defaults[ $id ] ) ? $defaults[ $id ] : false;
		}

		/**
		 * Init VafPress Options.
		 *
		 * @todo To be replaced with customizer options in v4.0.
		 */
		public function init_vp_options() {

			if ( class_exists( 'VP_Option' ) ) {

				global $themeoptions;
				$tmpl_opt     = HBTHEMES_ADMIN . '/theme-options.php';
				$themeoptions = new VP_Option(
					array(
						'is_dev_mode'           => false,
						'option_key'            => 'hb_highend_option',
						'page_slug'             => 'highend_options',
						'template'              => $tmpl_opt,
						'menu_page'             => 'hb_about',
						'use_auto_group_naming' => true,
						'use_exim_menu'         => false,
						'minimum_role'          => 'edit_theme_options',
						'layout'                => 'fixed',
						'page_title'            => __( 'Highend Options', 'hbthemes' ),
						'menu_label'            => '<span style="color:#00b9eb;border-bottom:solid 2px #00b9eb;">' . __( 'Highend Options', 'hbthemes' ) . '</span>',
					)
				);
			}
		}
	}

endif;
