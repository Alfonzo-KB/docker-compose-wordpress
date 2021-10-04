<?php
/**
 * Highend Customizer Class.
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

if ( ! class_exists( 'Highend_Customizer' ) ) :
	/**
	 * Highend Customizer class
	 */
	class Highend_Customizer {

		/**
		 * Singleton instance of the class.
		 *
		 * @since 3.7
		 * @var object
		 */
		private static $instance;

		/**
		 * Customizer options.
		 *
		 * @since 3.7
		 * @var Array
		 */
		private static $options;

		/**
		 * Main Highend_Customizer Instance.
		 *
		 * @since 3.7
		 * @return Highend_Customizer
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Highend_Customizer ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Primary class constructor.
		 *
		 * @since 3.7
		 */
		public function __construct() {

			// Registers our custom panels in Customizer.
			add_filter( 'highend_customizer_options', array( $this, 'register_panel' ) );

			// Loads our Customizer helper functions.
			add_action( 'customize_register', array( $this, 'load_customizer_helpers' ) );

			// Load our Customizer options.
			add_action( 'customize_register', array( $this, 'load_options' ) );

			// Registers our Customizer options.
			add_action( 'customize_register', array( $this, 'register_options' ) );
		}

		/**
		 * Loads Customizer helper functions and sanitization callbacks.
		 *
		 * @since 1.0.0
		 */
		public function load_customizer_helpers() {
			require HIGHEND_THEME_PATH . '/includes/customizer/customizer-callbacks.php'; // phpcs:ignore
		}

		/**
		 * Registers our Customizer options.
		 *
		 * @since 1.0.0
		 */
		public function load_options() {

			// Directory where each individual section is located.
			$path = HIGHEND_THEME_PATH . '/includes/customizer/settings/';

			/**
			 * Customizer sections.
			 */
			$sections = array(
				// 'sections',
				// 'colors',
				// 'typography',
				// 'layout',
				// 'top-bar',
				// 'main-header',
				// 'main-navigation',
				// 'hero',
				// 'page-header',
				// 'logo',
				// 'single-post',
				// 'blog-page',
				// 'main-footer',
				// 'copyright-settings',
				// 'pre-footer',
				// 'buttons',
				// 'misc',
				// 'transparent-header',
				// 'sticky-header',
				// 'sidebar',
				// 'breadcrumbs',
			);

			foreach ( $sections as $section ) {
				if ( file_exists( $path . $section . '.php' ) ) {
					require_once $path . $section . '.php'; // phpcs:ignore
				}
			}
		}

		/**
		 * Registers our Customizer options.
		 *
		 * @since 3.7
		 *
		 * @param WP_Customize_Manager $customizer instance of WP_Customize_Manager.
		 *
		 * @return void
		 */
		public function register_options( $customizer ) {

			$options = $this->get_customizer_options();

			if ( isset( $options['panel'] ) && ! empty( $options['panel'] ) ) {
				foreach ( $options['panel'] as $id => $args ) {
					$this->add_panel( $id, $args, $customizer );
				}
			}

			if ( isset( $options['section'] ) && ! empty( $options['section'] ) ) {
				foreach ( $options['section'] as $id => $args ) {
					$this->add_section( $id, $args, $customizer );
				}
			}

			if ( isset( $options['setting'] ) && ! empty( $options['setting'] ) ) {
				foreach ( $options['setting'] as $id => $args ) {
					$this->add_setting( $id, $args, $customizer );
					$this->add_control( $id, $args['control'], $customizer );
				}
			}
		}

		/**
		 * Register Customizer Panel.
		 *
		 * @since 1.0.0
		 *
		 * @param Array                $panel Panel settings.
		 * @param WP_Customize_Manager $customizer instance of WP_Customize_Manager.
		 *
		 * @return void
		 */
		private function add_panel( $id, $args, $customizer ) {
			$class = highend_get_prop( $args, 'class', 'WP_Customize_Panel' );

			$customizer->add_panel( new $class( $customizer, $id, $args ) );
		}

		/**
		 * Register Customizer Section.
		 *
		 * @since 1.0.0
		 *
		 * @param Array                $section Section settings.
		 * @param WP_Customize_Manager $customizer instance of WP_Customize_Manager.
		 *
		 * @return void
		 */
		private function add_section( $id, $args, $customizer ) {
			$class = highend_get_prop( $args, 'class', 'WP_Customize_Section' );

			$customizer->add_section( new $class( $customizer, $id, $args ) );
		}

		/**
		 * Register Customizer Control.
		 *
		 * @since 1.0.0
		 *
		 * @param Array                $control Control settings.
		 * @param WP_Customize_Manager $customizer instance of WP_Customize_Manager.
		 *
		 * @return void
		 */
		private function add_control( $id, $args, $customizer ) {
			$class = $this->get_control_class( highend_get_prop( $args, 'type' ) );

			$args['setting'] = $id;

			if ( false !== $class && class_exists( $class ) ) {
				$customizer->add_control( new $class( $customizer, $id, $args ) );
			} else {
				$customizer->add_control( $id, $args );
			}
		}

		/**
		 * Register Customizer Setting.
		 *
		 * @since 1.0.0
		 *
		 * @param Array                $setting Settings.
		 * @param WP_Customize_Manager $customizer instance of WP_Customize_Manager.
		 *
		 * @return void
		 */
		private function add_setting( $id, $setting, $customizer ) {
			$setting = wp_parse_args( $setting, $this->get_customizer_defaults( 'setting' ) );

			$customizer->add_setting(
				$id,
				array(
					'default'           => highend()->options->get_default( $id ),
					'type'              => highend_get_prop( $setting, 'type' ),
					'transport'         => highend_get_prop( $setting, 'transport' ),
					'sanitize_callback' => highend_get_prop( $setting, 'sanitize_callback', 'highend_no_sanitize' ),
				)
			);

			$partial = highend_get_prop( $setting, 'partial', false );

			if ( $partial && isset( $customizer->selective_refresh ) ) {

				$customizer->selective_refresh->add_partial(
					$id,
					array(
						'selector'            => highend_get_prop( $partial, 'selector' ),
						'container_inclusive' => highend_get_prop( $partial, 'container_inclusive' ),
						'render_callback'     => highend_get_prop( $partial, 'render_callback' ),
						'fallback_refresh'    => highend_get_prop( $partial, 'fallback_refresh' ),
					)
				);
			}
		}

		/**
		 * Filter and return Customizer options.
		 *
		 * @since 3.7
		 *
		 * @return Array Customizer options for registering Sections/Panels/Controls.
		 */
		public function get_customizer_options() {
			if ( ! is_null( self::$options ) ) {
				return self::$options;
			}

			return apply_filters( 'highend_customizer_options', array() );
		}

		/**
		 * Return default values for customizer parts.
		 *
		 * @since 1.0.0
		 *
		 * @return Array default values for the Customizer Configurations.
		 */
		private function get_customizer_defaults( $type ) {

			$defaults = array();

			switch ( $type ) {
				case 'setting':
					$defaults = array(
						'type'      => 'theme_mod',
						'transport' => 'refresh',
					);
					break;

				case 'control':
					$defaults = array();
					break;

				default:
					break;
			}

			return apply_filters(
				'highend_customizer_configuration_defaults',
				$defaults,
				$type
			);
		}

		/**
		 * Get custom control classname.
		 *
		 * @since 1.0.0
		 *
		 * @param string $control Control ID.
		 *
		 * @return string Control classname.
		 */
		private function get_control_class( $type ) {

			if ( false !== strpos( $type, 'highend-' ) ) {

				$controls = $this->get_custom_controls();
				$type     = trim( str_replace( 'highend-', '', $type ) );

				if ( isset( $controls[ $type ] ) ) {
					return $controls[ $type ];
				}
			}

			return $type;
		}

		/**
		 * Return custom controls.
		 *
		 * @since 1.0.0
		 *
		 * @return Array custom control slugs & classnames.
		 */
		private function get_custom_controls() {
			return apply_filters(
				'highend_custom_customizer_controls',
				array(
				)
			);
		}

		/**
		 * Registers our custom options in Customizer.
		 *
		 * @since 1.0.0
		 * @param array $options Array of customizer options.
		 */
		public function register_panel( $options ) {

			// General panel.
			$options['panel']['highend_panel_colors'] = array(
				'title'    => esc_html__( 'Colors', 'sinatra' ),
				'priority' => 1,
			);

			return $options;
		}
	}
endif;
