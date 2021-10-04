<?php
/**
 * Layer Slider Compatibility.
 *
 * @package Highend
 * @since   3.6.14
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Check if plugin is installed and activated.
if ( ! defined( 'LS_PLUGIN_VERSION' ) ) {
	return;
}

/**
 * Highend Layer Slider Compatibility.
 */
if ( ! class_exists( 'Highend_LayerSlider' ) ) :

	/**
	 * Class Highend_LayerSlider
	 */
	class Highend_LayerSlider {

		/**
		 * Singleton instance of the class.
		 *
		 * @since 3.6.14
		 * @var   object
		 */
		private static $instance;

		/**
		 * Main Highend_LayerSlider Instance.
		 *
		 * @since  3.6.14
		 * @return Highend_LayerSlider
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Highend_LayerSlider ) ) {
				self::$instance = new Highend_LayerSlider();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'admin_init' ) );
		}

		/**
		 * Init Layer Slider Compatibility.
		 * This adds required actions and filters only if Layer Slider is detected.
		 *
		 * @since 3.6.14
		 * @return void
		 */
		public function admin_init() {	

			// Remove license notification under the plugin row on the Plugins screen.
			remove_action( 'after_plugin_row_' . LS_PLUGIN_BASE, 'layerslider_plugins_screen_notice', 10, 3 );
		}

	}
endif;

/**
* Kicking this off by calling 'instance()' method
*/
Highend_LayerSlider::instance();
