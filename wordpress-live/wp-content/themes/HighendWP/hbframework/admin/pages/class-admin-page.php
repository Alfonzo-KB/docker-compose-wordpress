<?php if ( !defined( 'ABSPATH' ) ) { die( 'Direct access forbidden.' ); }

/**
 * HB Admin Screens. Base class for all Admin pages.
 * 
 * @since 3.4.1
 */
class HB_Admin_Screens {

	protected $page_slug;
	protected $theme_name;
	protected $theme_slug;
	protected $config;
	protected $prefix;

	/**
	 * Primary class constructor.
	 *
	 * @since 3.4.1
	 */
	public function __construct() {

		$this->config 			= require get_theme_file_path( 'config/config.php' );
		$this->prefix 			= $this->config['prefix'];
		$this->theme_slug 		= $this->config['theme_slug'];
		$this->theme_name 		= $this->config['theme_name'];

		add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );
	}


	/**
	 * Load required assets on the admin page(s).
	 *
	 * @since 3.4.1
	 */
	public function load_assets( $hook ) {
		
		if ( $hook != 'toplevel_page_hb_about' && $hook != 'highend_page_' . $this->page_slug ) {
			return;
		}

		// Don't enqueue on VafPress options framework page.
		if ( 'highend_page_highend_options' === $hook ) {
			return;
		}

		wp_enqueue_style( 'hb-framework-style', get_parent_theme_file_uri( 'hbframework/assets/css/hb-framework.css' ), false, $this->config['theme_version'] );
		wp_enqueue_script( 'hb-framework-script', get_parent_theme_file_uri( '/hbframework/assets/js/hb-framework.js' ),array( 'jquery' ), $this->config['theme_version'], true );
	}
}