<?php
/**
 * Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * You can override functions wrapped with function_exists() call by defining
 * them first in your child theme's functions.php file.
 *
 * @package Highend
 * @since   1.0.0
 */

/**
 * Main Highend class.
 *
 * @since 3.7
 */
final class Highend {

	/**
	 * Singleton instance of the class.
	 *
	 * @since 3.7
	 * @var object
	 */
	private static $instance;

	/**
	 * Theme version.
	 *
	 * @since 3.7
	 * @var string
	 */
	public $version = '3.7.3';

	/**
	 * Main Highend Instance.
	 *
	 * Insures that only one instance of Highend exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 3.7
	 * @return Highend
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Highend ) ) {
			self::$instance = new Highend();

			self::$instance->constants();
			self::$instance->includes();
			self::$instance->objects();

			// Hook now that all of the Highend stuff is loaded.
			do_action( 'highend_loaded' );
		}
		return self::$instance;
	}

	/**
	 * Primary class constructor.
	 *
	 * @since 3.7
	 * @return void
	 */
	public function __construct() {
	}

	/**
	 * Setup constants.
	 *
	 * @since 3.7
	 * @return void
	 */
	private function constants() {

		if ( ! defined( 'HBTHEMES_ROOT' ) ) {
			define( 'HBTHEMES_ROOT', get_template_directory() );
		}

		if ( ! defined( 'HBTHEMES_URI' ) ) {
			define( 'HBTHEMES_URI', get_template_directory_uri() );
		}

		if ( ! defined( 'HBTHEMES_INCLUDES' ) ) {
			define( 'HBTHEMES_INCLUDES', HBTHEMES_ROOT . '/includes' );
		}

		if ( ! defined( 'HBTHEMES_ADMIN' ) ) {
			define( 'HBTHEMES_ADMIN', HBTHEMES_ROOT . '/admin' );
		}
		
		if ( ! defined( 'HBTHEMES_FUNCTIONS' ) ) {
			define( 'HBTHEMES_FUNCTIONS', HBTHEMES_ROOT . '/functions' );
		}
		
		if ( ! defined( 'HBTHEMES_ADMIN_URI' ) ) {
			define( 'HBTHEMES_ADMIN_URI', HBTHEMES_URI . '/admin' );
		}

		if ( ! defined( 'HB_THEME_VERSION' ) ) {
			define( 'HB_THEME_VERSION', $this->version );
		}

		// Constants since 3.7.
		if ( ! defined( 'HIGHEND_VERSION' ) ) {
			define( 'HIGHEND_VERSION', $this->version );
		}

		if ( ! defined( 'HIGHEND_THEME_PATH' ) ) {
			define( 'HIGHEND_THEME_PATH', get_parent_theme_file_path() );
		}

		if ( ! defined( 'HIGHEND_THEME_URI' ) ) {
			define( 'HIGHEND_THEME_URI', get_parent_theme_file_uri() );
		}
	}

	/**
	 * Include files.
	 *
	 * @since  3.7
	 * @return void
	 */
	public function includes() {

		require_once HIGHEND_THEME_PATH . '/functions/helpers.php';
		require_once HIGHEND_THEME_PATH . '/functions/common.php';
		require_once HIGHEND_THEME_PATH . '/functions/deprecated.php';
		require_once HIGHEND_THEME_PATH . '/functions/template-parts.php';
		require_once HIGHEND_THEME_PATH . '/functions/template-functions.php';
		require_once HIGHEND_THEME_PATH . '/functions/dynamic-styles.php';
		require_once HIGHEND_THEME_PATH . '/functions/breadcrumbs.php';
		require_once HIGHEND_THEME_PATH . '/functions/theme-likes.php';
		require_once HIGHEND_THEME_PATH . '/functions/theme-thumbnails-resize.php';

		// Core.
		require_once HIGHEND_THEME_PATH . '/hbframework/hbframework.php';
		require_once HIGHEND_THEME_PATH . '/includes/core/class-highend-options.php';
		require_once HIGHEND_THEME_PATH . '/includes/core/class-highend-theme-setup.php';
		require_once HIGHEND_THEME_PATH . '/includes/core/class-highend-enqueue-scripts.php';
		require_once HIGHEND_THEME_PATH . '/includes/core/class-highend-install.php';
		require_once HIGHEND_THEME_PATH . '/options-framework/bootstrap.php';

		// Compatibility.
		require_once HIGHEND_THEME_PATH . '/includes/compatibility/class-highend-layerslider.php';
		require_once HIGHEND_THEME_PATH . '/includes/compatibility/class-highend-revslider.php';

		// Modules.
		require_once HIGHEND_THEME_PATH . '/includes/portfolio/class-highend-portfolio.php';
		require_once HIGHEND_THEME_PATH . '/includes/gallery/class-highend-gallery.php';

		require_once HIGHEND_THEME_PATH . '/includes/shortcodes.php';

		// Admin.
		if ( is_admin() ) {
			require_once HIGHEND_THEME_PATH . '/admin/class-highend-admin.php';
		}

		// Customizer.
		require_once HIGHEND_THEME_PATH . '/includes/customizer/class-highend-customizer.php';

		// @todo: refactor.
		require_once HIGHEND_THEME_PATH . '/admin/metabox/class-metabox.php';
		require_once HIGHEND_THEME_PATH . '/admin/theme-options-dependency.php';
		require_once HIGHEND_THEME_PATH . '/admin/metaboxes/metabox-dependency.php';
		require_once HIGHEND_THEME_PATH . '/admin/metaboxes/gallery-multiupload.php';

	}

	/**
	 * Setup objects to be used throughout the theme.
	 *
	 * @since  3.7
	 * @return void
	 */
	public function objects() {

		// Options.
		highend()->options = new Highend_Options();

		// Customizer.
		highend()->customizer = new Highend_Customizer();
		
		if ( is_admin() ) {

			highend()->admin = new Highend_Admin();

			// Updates.
			if ( class_exists( 'ThemeUpdateChecker' ) ) {
				highend()->updates = new ThemeUpdateChecker(
					'HighendWP',
					'http://hb-themes.com/update/?action=get_metadata&slug=HighendWP'
				);
			}
		}
	}
}

/**
 * The function which returns the one Highend instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $highend = highend(); ?>
 *
 * @since 3.7
 * @return object
 */
function highend() {
	return Highend::instance();
}

highend();
