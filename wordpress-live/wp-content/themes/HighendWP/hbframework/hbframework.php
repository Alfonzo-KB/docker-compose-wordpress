<?php
/**
 * HB Framework
 *
 * @package    Highend
 * @author     HB-Themes
 * @since      3.4.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Don't allow multiple versions to be active
if ( ! class_exists( 'HB_Framework' ) ) {

	/**
	 * Main HBFramework class.
	 *
	 * @since 3.4.1
	 * @package Highen
	 */
	final class HB_Framework {

		/**
		 * Singleton instance of the class.
		 *
		 * @since 3.4.1
		 * @var object
		 */
		private static $instance;

		/**
		 * Main HB_Framework Instance.
		 *
		 * Insures that only one instance of HB_Framework exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 3.4.1
		 * @return Highen
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof HB_Framework ) ) {

				self::$instance = new HB_Framework;
				self::$instance->includes();
			}
			return self::$instance;
		}

		/**
		 * Include files.
		 *
		 * @since 1.0.0
		 */
		private function includes() {

			// Utilities
			require get_parent_theme_file_path( 'hbframework/admin/utilities/class-sidebar-manager.php' );
			require get_parent_theme_file_path( 'hbframework/admin/utilities/class-icon-manager.php' );
			require get_parent_theme_file_path( 'hbframework/admin/utilities/class-install-plugins.php' );
				

			// Pages
			require get_parent_theme_file_path( 'hbframework/admin/pages/class-admin-page.php' );

			if ( is_admin() && current_user_can( 'install_themes' ) ) {

				// Updater
				require get_parent_theme_file_path( 'hbframework/vendor/class-theme-update-checker.php' );

				// Utilities
				require get_parent_theme_file_path( 'hbframework/admin/utilities/class-demo-importer.php' );

				// Pages
				require get_parent_theme_file_path( 'hbframework/admin/pages/class-about-page.php' );
				require get_parent_theme_file_path( 'hbframework/admin/pages/class-install-plugins-page.php' );
				require get_parent_theme_file_path( 'hbframework/admin/pages/class-demo-importer-page.php' );
			}

			require get_parent_theme_file_path( 'hbframework/admin/pages/class-sidebar-manager-page.php' );

			// Custom menu walker
			require get_parent_theme_file_path( 'hbframework/menu-walker/class-hb-custom-walker-edit.php' );
			require get_parent_theme_file_path( 'hbframework/menu-walker/class-hb-custom-walker.php' );

			if ( ( is_admin() || is_customize_preview() ) && current_user_can( 'install_themes' ) ) {
				if ( ! class_exists( 'HB_Customizer_Helper' ) ) {
					require get_parent_theme_file_path ( 'hbframework/admin/utilities/class-customizer-helper.php' );
				}
			}

			require get_parent_theme_file_path( 'hbframework/hooks.php' );
			require get_parent_theme_file_path( 'hbframework/integrations.php' );
		}
	}

	/**
	 * The function which returns the one HB Framework instance.
	 *
	 * Use this function like you would a global variable, except without needing
	 * to declare the global.
	 *
	 * Example: <?php $hbframework = hbframework(); ?>
	 *
	 * @since 1.0.0
	 * @return object
	 */
	function hbframework() {
		return HB_Framework::instance();
	}

	hbframework();
}