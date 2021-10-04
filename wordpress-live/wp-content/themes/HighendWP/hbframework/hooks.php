<?php

if ( is_admin() && current_user_can( 'install_themes' ) ) {

	// Enqueues styles and scripts for the WP admin. 
	add_action( 'admin_enqueue_scripts', 'hb_admin_enqueue' );
}


/**
 * Enqueues styles and scripts for the WP admin.
 *
 * @since 3.4.1
 */
if ( ! function_exists( 'hb_admin_enqueue' ) ) {
	function hb_admin_enqueue() {

		$screen = get_current_screen();

		// If we are on Menus page load correct assets.
		if ( $screen->base == 'nav-menus' ) {
			wp_enqueue_script( 'hb-menus-js', get_parent_theme_file_uri( 'hbframework/assets/js/hb-menus.js' ), array( 'jquery' ), false, true );
			wp_enqueue_style( 'hb-menus-css', get_parent_theme_file_uri( 'hbframework/assets/css/hb-menus.css' ), false, HB_THEME_VERSION, 'all' );
			wp_enqueue_media();
		}

		// Load the Icon Picker assets on Highend Options and Menus page.
		//if ( $screen->base == 'highend_page_hb_theme_options' || $screen->base == 'nav-menus' ) {
		if ( $screen->base == 'nav-menus' ) {
			wp_enqueue_script( 'hb-icon-picker-js', get_parent_theme_file_uri( 'hbframework/assets/js/hb-icon-picker.js', array( 'jquery' ), false, true ) );
			wp_enqueue_style( 'hb-icon-picker-css', get_parent_theme_file_uri( 'hbframework/assets/css/hb-icon-picker.css' ), false, HB_THEME_VERSION, 'all' );
			wp_enqueue_media();
		}
	}
}


/**
 * HBFramework Icon Picker HTML
 *
 * @since 4.0
 */
if ( ! function_exists( 'hb_icon_picker' ) ) {
	function hb_icon_picker() {

		$screen = get_current_screen();
		
		// If we are not on Highend Options or Menus page, exit.
		if ( $screen->base != 'highend_page_hb_theme_options' && $screen->base != 'nav-menus' ) {
			return;
		}

		// Check for Visual Composer assets.
		$vc_assets_uri = defined( 'WPB_VC_VERSION' ) ? ' data-uri-vcicons="' . vc_asset_url( 'css/lib' ) . '"' : '';
		?>
		<div id="hb-icons-modal-overlay"></div>
		<div id="hb-icons-modal" class="icons-loading" 
			data-uri-fontawesome="<?php echo get_parent_theme_file_uri( 'assets/css/icons.css' ); ?>"<?php echo $vc_assets_uri; ?>>
			<div class="hb-modal-title"><?php esc_html_e( 'Icon Finder', 'hbthemes'); ?>
				<input type="search" class="hb-search-icons" placeholder="<?php esc_html_e('Search...', 'hbthemes'); ?>"></input>
				<a href="#" class="hb-close-icon-modal"><span class="dashicons dashicons-no-alt"></span></a>

			</div>
			<div class="hb-modal-content">
				<div class="spinner"></div>
			</div>
			<div class="hb-modal-footer">
				<a href="#" class="button button-secondary hb-close-icon-modal"><?php esc_html_e( 'Cancel', 'hbthemes' ); ?></a>
				<a href="#" id="use-this-icon" class="button button-primary"><?php esc_html_e( 'Use this icon', 'hbthemes' ); ?></a>
			</div>
		</div><!-- END #hb-icons-modal -->
		<?php
	}
}
add_action( 'admin_footer', 'hb_icon_picker' );
add_action( 'wp_ajax_hb_icon_picker_modal', array( 'HB_Icon_Manager', 'icon_picker_modal' ) );


/**
 * Adds Highend links to the WordPress Admin Bar.
 *
 * @since 4.0
 */
if ( ! function_exists( 'hb_toolbar_link_to' ) ) {
	function hb_toolbar_link_to( $wp_admin_bar ) {

		// Parent menu item, which points to Highend Options.
		$args = array(
			'id' 		=> 'highend_link',
			'title' 	=> '<span class="ab-icon dashicons-before dashicons-performance" style="padding:5px 0;"></span><span class="ab-label"> Highend</span>',
			'href' 		=> admin_url( 'admin.php?page=highend_options' ),
			'parent' 	=> false,
		);
		$wp_admin_bar->add_node( $args );

		// About menu item.
		$args = array(
			'id' 		=> 'highend_about_link',
			'title' 	=> esc_html__( 'About', 'hbthemes' ),
			'href' 		=> admin_url( 'admin.php?page=hb_about' ),
			'parent' 	=> 'highend_link'
		);
		$wp_admin_bar->add_node( $args );

		// Install Plugins menu item.
		$args = array(
			'id' 		=> 'highend_plugins_link',
			'title' 	=> esc_html__( 'Install Plugins', 'hbthemes' ),
			'href' 		=> admin_url( 'admin.php?page=hb_plugins' ),
			'parent' 	=> 'highend_link'
		);
		$wp_admin_bar->add_node( $args );

		// Import Demos menu item.
		$args = array(
			'id' 		=> 'highend_demos_link',
			'title' 	=> esc_html__( 'Import Demos', 'hbthemes' ),
			'href' 		=> admin_url( 'admin.php?page=hb_import_demos' ),
			'parent' 	=> 'highend_link'
		);
		$wp_admin_bar->add_node( $args );

		// Sidebar Manager menu item.
		$args = array(
			'id' 		=> 'highend_sidebar_manager_link',
			'title' 	=> esc_html__( 'Sidebar Manager', 'hbthemes' ),
			'href' 		=> admin_url( 'admin.php?page=hb_sidebar_manager' ),
			'parent' 	=> 'highend_link'
		);
		$wp_admin_bar->add_node( $args );

		// Highend Options menu item.
		$args = array(
			'id' 		=> 'highend_theme_options_link',
			'title' 	=> esc_html__( 'Highend Options', 'hbthemes' ),
			'href' 		=> admin_url( 'admin.php?page=highend_options' ),
			'parent' 	=> 'highend_link'
		);
		$wp_admin_bar->add_node( $args );

		// WP Live Menu Item
		$args = array(
			'id' 		=> 'highend_hire_link',
			'title' 	=> '<span style="color:#f9b50f;line-height:1">' . esc_html__( 'Get Premium Support', 'hbthemes' ) . '</span>',
			'href' 		=> 'https://www.anrdoezrs.net/links/8353661/type/dlg/http://www.mojomarketplace.com/item/wordpress-support',
			'parent' 	=> 'highend_link'
		);
		$wp_admin_bar->add_node( $args );
	}
}
add_action( 'admin_bar_menu', 'hb_toolbar_link_to', 280 );
