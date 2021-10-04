<?php
/**
 * Revolution Slider Compatibility.
 *
 * @package Highend
 * @since   3.6.14
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Check if plugin is installed and activated.
if ( ! defined( 'RS_PLUGIN_PATH' ) ) {
	return;
}

/**
 * Highend Revolution Slider Compatibility.
 */
if ( ! class_exists( 'Highend_RevSlider' ) ) :

	/**
	 * Class Highend_RevSlider
	 */
	class Highend_RevSlider {

		/**
		 * Singleton instance of the class.
		 *
		 * @since 3.6.14
		 * @var   object
		 */
		private static $instance;

		/**
		 * Main Highend_RevSlider Instance.
		 *
		 * @since  3.6.14
		 * @return Highend_RevSlider
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Highend_RevSlider ) ) {
				self::$instance = new Highend_RevSlider();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		}

		/**
		 * Init Revolution Slider Compatibility.
		 * This adds required actions and filters only if Revolution Slider is detected.
		 *
		 * @since 3.6.14
		 * @return void
		 */
		public function admin_notices() {	

			// Remove license notification under the plugin row on the Plugins screen.
			$plugins = get_plugins();

			foreach ( $plugins as $plugin_id => $plugin ) {

				$slug = dirname( $plugin_id );

				if ( empty ( $slug ) || $slug !== 'revslider'){
					continue;
				}

				remove_action( 'after_plugin_row_' . $plugin_id, array( 'RevSliderAdmin', 'add_notice_wrap_pre' ), 10, 3);
				remove_action( 'after_plugin_row_' . $plugin_id, array( 'RevSliderAdmin', 'show_purchase_notice' ), 10, 3 );
				remove_action( 'after_plugin_row_' . $plugin_id, array( 'RevSliderAdmin', 'add_notice_wrap_post' ), 10, 3 );
			}
		}

	}
endif;

/**
* Kicking this off by calling 'instance()' method
*/
Highend_RevSlider::instance();
