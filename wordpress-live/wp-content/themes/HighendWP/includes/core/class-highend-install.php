<?php
/**
 * Installation related functions and actions.
 *
 * @package Highend
 * @since   3.6.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Highend_Install Class.
 */
class Highend_Install {

	/**
	 * Database updates and callbacks that need to be run per version.
	 *
	 * @var array
	 */
	private static $database_updates = array(
		'3.6.0' => array(
			'highend_migrate_page_templates'
		),
	);

	/**
	 * Init theme.
	 *
	 * @since 3.6.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'check_version' ), 5 );
	}

	/**
	 * Check theme version and run the updater is required.
	 *
	 * This check is done on all requests and runs if the versions do not match.
	 *
	 * @since 3.6.0
	 * 
	 * @return void
	 */
	public static function check_version() {
		if ( ! defined( 'IFRAME_REQUEST' ) && version_compare( get_option( 'highend_theme_version' ), HB_THEME_VERSION, '<' ) ) {
			self::install();
			do_action( 'highend_theme_updated' );
		}
	}

	/**
	 * Install theme.
	 *
	 * @since 3.6.0
	 * 
	 * @return void
	 */
	public static function install() {

		if ( ! is_blog_installed() ) {
			return;
		}

		// Check if we are not already running this routine.
		if ( 'yes' === get_transient( 'highend_theme_installing' ) ) {
			return;
		}

		// If we made it till here nothing is running yet, lets set the transient now.
		set_transient( 'highend_theme_installing', 'yes', MINUTE_IN_SECONDS * 10 );

		self::update_theme_version();
		self::update_database();

		delete_transient( 'highend_theme_installing' );

		do_action( 'highend_theme_installed' );
	}

	/**
	 * Update theme version.
	 *
	 * @since 3.6.0
	 * 
	 * @return void
	 */
	private static function update_theme_version() {
		delete_option( 'highend_theme_version' );
		add_option( 'highend_theme_version', HB_THEME_VERSION );
	}

	/**
	 * See if we need to show or run database updates during install.
	 *
	 * @since 3.6.0
	 */
	private static function update_database() {
		if ( self::needs_database_update() ) {
			self::update();
		} else {
			self::update_database_version();
		}
	}

	/**
	 * Is a database update needed?
	 *
	 * @since 3.6.0
	 * 
	 * @return boolean
	 */
	public static function needs_database_update() {
		$current_db_version = get_option( 'highend_database_version', '1.0.0' );
		$updates            = self::get_database_update_callbacks();
		$update_versions    = array_keys( $updates );
		usort( $update_versions, 'version_compare' );

		return ! is_null( $current_db_version ) && version_compare( $current_db_version, end( $update_versions ), '<' );
	}

	/**
	 * Update database version to current.
	 *
	 * @since 3.6.0
	 * @param string|null $version New database version or null.
	 * 
	 * @return void
	 */
	public static function update_database_version( $version = null ) {
		delete_option( 'highend_database_version' );
		add_option( 'highend_database_version', is_null( $version ) ? HB_THEME_VERSION : $version );
	}

	/**
	 * Push all needed DB updates to the queue for processing.
	 *
	 * @since 3.6.0
	 */
	private static function update() {

		$current_db_version = get_option( 'highend_database_version' );
		$loop               = 0;
		$result             = true;

		foreach ( self::get_database_update_callbacks() as $version => $update_callbacks ) {

			if ( version_compare( $current_db_version, $version, '<' ) ) {
				
				foreach ( $update_callbacks as $update_callback ) {
					if ( is_callable( $update_callback ) ) {
						$result &= (bool) call_user_func( $update_callback );
					}
				}

				if ( $result ) {
					self::update_database_version( $version );
				}
			}
		}
	}

	/**
	 * Get list of DB update callbacks.
	 *
	 * @since  3.6.0
	 * @return array
	 */
	public static function get_database_update_callbacks() {
		return self::$database_updates;
	}
}

Highend_Install::init();

/**
 * Migrate and rename page template files.
 *
 * @since  3.6.0
 * @return void
 */
function highend_migrate_page_templates() {

	$page_templates = array(
		'page-blank.php'                  => 'page-templates/blank.php',
		'page-blog-fullwidth.php'         => 'page-templates/blog-grid-fullwidth.php',
		'page-blog-grid.php'              => 'page-templates/blog-grid.php',
		'page-blog-minimal.php'           => 'page-templates/blog-minimal.php',
		'page-blog.php'                   => 'page-templates/blog.php',
		'page-contact.php'                => 'page-templates/contact.php',
		'page-gallery-fullwidth.php'      => 'page-templates/gallery-fullwidth.php',
		'page-gallery-standard.php'       => 'page-templates/gallery-standard.php',
		'page-login.php'                  => 'page-templates/login.php',
		'page-portfolio-simple.php'       => 'page-templates/portfolio-simple.php',
		'page-portfolio-standard.php'     => 'page-templates/portfolio-standard.php',
		'page-presentation-fullwidth.php' => 'page-templates/presentation-fullwidth.php',
	);

	global $wpdb;

	set_time_limit( 300 );

	foreach ( $page_templates as $old => $new ) {
		
		if ( ! locate_template( $old ) && locate_template( $new ) ) {
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE $wpdb->postmeta
					SET meta_value = %s
					WHERE meta_key IN ( '_wp_page_template' )
					AND meta_value = %s",
					$new,
					$old
				)
			);
		}

		unset( $page_templates[ $old ] );
	}

	if ( ! empty( $page_templates ) ) {
		return false;
	}

	return true;
}
