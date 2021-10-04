<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Direct access forbidden.' ); }

/**
 * HB Demo Importer handler.
 * Class that contains functions for demo import process.
 * 
 * @since 3.4.1
 */
class HB_Demo_Importer {


	/**
	 * Demo ID
	 *
	 * @since 3.4.1
	 * @var string
	 */
	private $demo_id;

	/**
	 * Whether we should fetch attachments or not.
	 *
	 * @since 3.4.1
	 * @var bool
	 */
	private $fetch_attachments;


	/**
	 * The content-types we'll be importing.
	 *
	 * @since 3.4.1
	 * @var array
	 */
	private $import_content_types;


	/**
	 * Whether we want to import everything or not.
	 *
	 * @since 3.4.1
	 * @var bool
	 */
	private $import_all;


	/**
	 * Path to the XML file.
	 *
	 * @since 3.4.1
	 * @var string
	 */
	private $theme_xml_file;


	/**
	 * Path to the XML file.
	 *
	 * @since 3.4.1
	 * @var string
	 */
	private $theme_options_file;


	/**
	 * Path to the XML file.
	 *
	 * @since 3.4.1
	 * @var string
	 */
	private $theme_sidebars_file;


	/**
	 * Path to the customizer file.
	 *
	 * @since 3.4.1
	 * @var string
	 */
	private $theme_customizer_file;


	/**
	 * Path to the essential grid file.
	 *
	 * @since 3.4.1
	 * @var string
	 */
	private $theme_ess_grid_file;


	/**
	 * Array of paths to the revsliders.
	 *
	 * @since 3.4.1
	 * @var array
	 */
	private $rev_slider_files;


	/**
	 * The Homepage title.
	 *
	 * @since 3.4.1
	 * @var string
	 */
	private $homepage_title;


	/**
	 * Whether this is a WooCommerce site or not.
	 *
	 * @since 3.4.1
	 * @var bool
	 */
	private $is_shop;


	/**
	 * WooCommerce pages.
	 *
	 * @since 3.4.1
	 * @var array
	 */
	private $woopages;


	/**
	 * Import Steps
	 *
	 * @since 3.4.1
	 * @var array
	 */
	private $import_steps;


	/**
	 * Primary class constructor.
	 *
	 * @since 3.4.1
	 */
	public function __construct() {

		// Hook importer into admin init.
		add_action( 'wp_ajax_hb_import_demo_data', array( $this, 'import_demo_step' ) );
	}

	/**
	 * The main importer function.
	 *
	 * @access public
	 * @since 3.4.1
	 */
	public function import_demo_step() {

		// Security check
		check_ajax_referer( 'hb_import_demo_ajax', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'hb_import_exit: ' . __( 'You do not have permission to import a demo.', 'hbthemes' ) );
		}

		// Initialize class variables
		$this->import_steps = array();
		if ( isset( $_POST['import_steps'] ) ) {
			$this->import_steps = wp_unslash( $_POST['import_steps'] );
		}

		// Import parameters
		$this->demo_id = 'main-demo';
		if ( isset( $_POST['demo_id'] ) && '' !== trim( wp_unslash( $_POST['demo_id'] ) ) ) {
			$this->demo_id = wp_unslash( $_POST['demo_id'] );
		}

		$this->fetch_attachments = false;
		if ( isset( $_POST['fetch_attachments'] ) && 'true' === trim( wp_unslash( $_POST['fetch_attachments'] ) ) ) {
			$this->fetch_attachments = true;
		}

		$this->import_content_types = array();
		if ( isset( $_POST['content_types'] ) && is_array( $_POST['content_types'] ) ) {
			$this->import_content_types = wp_unslash( $_POST['content_types'] );
		}

		$this->import_all = false;
		if ( isset( $_POST['import_all'] ) && 'true' === trim( wp_unslash( $_POST['import_all'] ) ) ) {
			$this->import_all = true;
		}

		$this->is_shop 			= $this->is_shop();
		$this->woopages 		= $this->get_woopages();
		$this->homepage_title 	= $this->get_homepage_title();

		// Run before every import stage
		$this->before_import_step();

		if ( ! empty( $this->import_steps ) && method_exists( $this, 'import_' . $this->import_steps[0] ) ) {

			if ( 'content' === $this->import_steps[0] ) {
				$this->before_content_import();
			}

			call_user_func( array( $this, 'import_' . $this->import_steps[0] ) );

			// Menus are imported with the content.
			if ( 'content' === $this->import_steps[0] ) {
				$this->after_content_import();
			}
		}

		// Check if we've finished with the import steps
		if ( 1 >= count( $this->import_steps ) ) {
			do_action( 'highend_demo_import_complete' );
			
			delete_option( 'highend_database_version' );
			delete_option( 'highend_theme_version' );

			wp_die( 'demo_import_complete' );
		} else {
			wp_die( 'demo_import_partially_complete' );
		}

		exit;
	}


	/**
	 * Just some stuff that needs to be set before any import stage is run.
	 *
	 * @since 3.4.1
	 */
	private function before_import_step() {

		$dirname = get_parent_theme_file_path('config/demos/' . $this->demo_id );
		
		if ( ! is_dir( $dirname ) ) {
			mkdir( $dirname, 0755, true );
		}

		if ( function_exists( 'ini_get' ) ) {
			if ( 300 < ini_get( 'max_execution_time' ) ) {
				@ini_set( 'max_execution_time', 300 );
			}
			if ( 512 < intval( ini_get( 'memory_limit' ) ) ) {
				@ini_set( 'memory_limit', '512M' );
			}
		}
	}

	/**
	 * This is called before 'content' import stages are run.
	 *
	 * @since 3.4.1
	 */
	private function before_content_import() {

		add_filter( 'wxr_importer.pre_process.user', array( $this, 'skip_authors' ), 10, 2 );

		if ( in_array( 'general_data', $this->import_steps ) ) {
			wp_delete_nav_menu( 'Main Menu' );
			wp_delete_nav_menu( 'Footer Menu' );
			wp_delete_nav_menu( 'One Page Menu' );
			wp_delete_nav_menu( 'Shortcodes Menu' );
			wp_delete_nav_menu( 'Sidebar Navigation1' );
		}
	}


	/**
	 * This is called after 'content' import stages are run.
	 *
	 * @since 3.4.1
	 */
	private function after_content_import() {

		remove_filter( 'wxr_importer.pre_process.user', array( $this, 'skip_authors' ), 10 );
	}


	/**
	 * Main content importer method.
	 *
	 * @since 3.4.1
	 */
	private function import_content() {

		if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
			define( 'WP_LOAD_IMPORTERS', true ); // We are loading importers.
		}

		// If main importer class doesn't exist.
		if ( ! class_exists( 'WP_Importer' ) ) { 
			include ABSPATH . 'wp-admin/includes/class-wp-importer.php';
		}

		// If WP importer doesn't exist.
		if ( ! class_exists( 'WXR_Importer' ) ) { 
			include get_parent_theme_file_path( 'hbframework/vendor/importer/class-logger.php' );
			include get_parent_theme_file_path( 'hbframework/vendor/importer/class-logger-html.php' );
			include get_parent_theme_file_path( 'hbframework/vendor/importer/class-wxr-importer.php' );
		}

		if ( ! class_exists( 'HB_WXR_Importer' ) ) {
			include get_parent_theme_file_path( 'hbframework/vendor/importer/class-hb-wxr-importer.php' );
		}

		// Check for main import class and wp import class.
		if ( class_exists( 'WP_Importer' ) && class_exists( 'WXR_Importer' ) && class_exists( 'HB_WXR_Importer' ) ) {

			$logger 	= new WP_Importer_Logger_HTML();
			$importer 	= new HB_WXR_Importer( array(
				'fetch_attachments'      => $this->fetch_attachments,
			) );
			$importer->set_logger( $logger );

			$this->theme_xml_file = $this->get_import_file_path( 'highend.xml' );

			if ( is_wp_error( $this->theme_xml_file ) ) {
				wp_die( 'hb_import_error: ' . __( 'Content import failed.', 'hbthemes' ) . ' ' . $this->theme_xml_file->get_error_message() );
			} elseif ( file_exists( $this->theme_xml_file ) ) {

				ob_start();
				$importer->import( $this->theme_xml_file );
				ob_end_clean();

				// Import WooCommerce if WooCommerce Exists.
				if ( class_exists( 'WooCommerce' ) && $this->is_shop ) {

					foreach ( $this->woopages as $woo_page_name => $woo_page_title ) {
						$woopage = get_page_by_title( $woo_page_title );
						if ( isset( $woopage ) && $woopage->ID ) {
							update_option( $woo_page_name, $woopage->ID ); // Front Page.
						}
					}
					
					// We no longer need to install pages.
					delete_option( '_wc_needs_pages' );
					delete_transient( '_wc_activation_redirect' );
				}

				// Flush rules after install.
				flush_rewrite_rules();
			} else {
				wp_die( 'hb_import_error: ' . __( 'Content import failed. Import file highend.xml not found.', 'hbthemes' ) );
			}
		} else {
			wp_die( 'hb_import_error: ' . __( 'Content import failed. Content import class not found.', 'hbthemes' ) );
		}
	}


	/**
	 * Imports Theme Options.
	 *
	 * @access private
	 * @since 3.4.1
	 */
	private function import_theme_options() {

		$this->theme_options_file = $this->get_import_file_path( 'theme_options.txt' );

		if ( is_wp_error( $this->theme_options_file ) ) {
			wp_die( 'hb_import_error: ' .  __( 'Theme Options import failed.', 'hbthemes' ) . ' ' . $this->theme_options_file->get_error_message() );
		} elseif ( ! file_exists( $this->theme_options_file ) ) {
			wp_die( 'hb_import_error: ' . __( 'Theme Options import failed. Import file theme_options.txt not found.', 'hbthemes' ) );
			return;
		}

		$file_content = file_get_contents( $this->theme_options_file );

		if ( ! $file_content ) {
			return;
		}

		wp_die( $file_content );
	}


	/**
	 * Imports widgets.
	 *
	 * @access private
	 * @since 3.4.1
	 */
	private function import_widgets() {

		$this->theme_sidebars_file = $this->get_import_file_path( 'sidebars.txt' );

		if ( is_wp_error( $this->theme_sidebars_file ) ) {
			wp_die( 'hb_import_error: ' .  __( 'Widgets import failed.', 'hbthemes' ) . ' ' . $this->theme_sidebars_file->get_error_message() );
		} elseif ( ! file_exists( $this->theme_sidebars_file ) ) {
			wp_die( 'hb_import_error: ' . __( 'Widgets import failed. Import file sidebars.txt not found.', 'hbthemes' ) );
			return;
		}

		$file_content = file_get_contents( $this->theme_sidebars_file );

		if ( ! $file_content ) {
			wp_die( 'hb_import_error: ' . __( 'This template has no Sidebars to import.', 'hbthemes' ) );
			return;
		}

		$options = json_decode ( $file_content, true ) ;

		if ( empty( $options ) ) {
			wp_die( 'hb_import_error: ' . __( 'Widgets import failed. Sidebar import file corrupt.', 'hbthemes' ) );
			return;
		}

		$sidebars = get_option( 'hb_sidebars' ) ? get_option( 'hb_sidebars' ) : array();
		$sidebars = array_merge( $sidebars, $options['register_sidebars'] );
		update_option( 'hb_sidebars', $sidebars );

		if ( is_array( $sidebars ) ){
			foreach( $sidebars as $sidebar ) {
				HB_Sidebar_Manager::register_sidebar($sidebar);
			}
		}

		$widgets = isset( $options['sidebar_widgets'] ) ? $options['sidebar_widgets'] : array();

		$result = HB_Sidebar_Manager::import_sidebar_widgets( $widgets );
	}


	/**
	 * Rev and Layer sliders import methods.
	 *
	 * @access private
	 * @since 3.4.1
	 */
	private function import_sliders() {

		$this->import_revolution_sliders();
	}


	/**
	 * Essential Grid import method.
	 *
	 * @access private
	 * @since 3.4.1
	 */
	private function import_essgrid() {

		if ( defined( 'EG_PLUGIN_PATH' ) && class_exists( 'Essential_Grid_Import' ) ) {
			try {

				$this->theme_ess_grid_file = $this->get_import_file_path( 'ess_grid.json' );
				
				if ( is_wp_error( $this->theme_ess_grid_file ) ) {
					wp_die( 'hb_import_error: ' . __( 'Essential Grid import failed.', 'hbthemes' ) . ' ' . $this->theme_ess_grid_file->get_error_message() );
				} elseif ( file_exists( $this->theme_ess_grid_file ) ) {
					$tp_grid_meta_fonts = file_get_contents( $this->theme_ess_grid_file, true );
					
					//insert meta, grids & punchfonts
					$im = new Essential_Grid_Import();
					$im->set_overwrite_data( $tp_grid_meta_fonts ); //set overwrite data global to class
					
					if ( isset( $tp_grid_meta_fonts ) ) {

						$tp_grid_meta_fonts = json_decode( $tp_grid_meta_fonts, true );
						
						/* Skins */
						$skins     = @$tp_grid_meta_fonts['skins'];
						$skins_ids = array();
						
						if ( is_array( $skins )  && ! empty( $skins ) ) {

							foreach ( $skins as $skin ) {
								$skins_ids[] = $skin['id'];
							}

							$skins_imported = $im->import_skins( $skins, $skins_ids );
						}
						
						/* Navigation Skins */
						$navigation_skins     = @$tp_grid_meta_fonts['navigation-skins'];
						$navigation_skins_ids = array();
						
						if ( is_array( $navigation_skins ) && ! empty( $navigation_skins ) ) {

							foreach ( $navigation_skins as $nav_skin ) {
								$navigation_skins_ids[] = $nav_skin['id'];
							}
							
							$navigation_skins_imported = $im->import_navigation_skins( $navigation_skins, $navigation_skins_ids );
						}
						
						/* Grids */
						$grids     = @$tp_grid_meta_fonts['grids'];
						$grids_ids = array();
						
						if ( is_array( $grids ) && ! empty( $grids ) ) {

							foreach ( $grids as $grid ) {
								$grids_ids[] = $grid['id'];
							}

							$grids_imported = $im->import_grids( $grids, $grids_ids );
						}
						
						/* Custom Metas */
						$custom_metas        = @$tp_grid_meta_fonts['custom-meta'];
						$custom_meta_handles = array();
						
						if ( is_array( $custom_metas ) && ! empty( $custom_metas ) ) {

							foreach ( $custom_metas as $custom_meta ) {
								$custom_meta_handles[] = $custom_meta['handle'];
							}

							$custom_metas_imported = $im->import_custom_meta( $custom_metas, $custom_meta_handles );
						}
						
						/* Custom Fonts */
						$custom_fonts        = @$tp_grid_meta_fonts['punch-fonts'];
						$custom_font_handles = array();
						
						if ( is_array( $custom_fonts ) && ! empty ( $custom_fonts ) ) {
							
							foreach ( $custom_fonts as $custom_font ) {
								$custom_font_handles[] = $custom_font['handle'];
							}

							$custom_fonts_imported = $im->import_punch_fonts( $custom_fonts, $custom_font_handles );
						}

						// Global CSS
						$global_css = @$tp_grid_meta_fonts['global-css'];
						if ( ! empty( $global_css ) && is_array( $global_css ) ) {
							$global_css_imported = $im->import_global_styles( $global_css );
						}
			  
					}
				} else {
					wp_die( 'hb_import_error: ' . __( 'Essential Grid import failed. Import file ess_grid.json not found.', 'hbthemes' ) );
				}
				
			}
			catch ( Exception $d ) {
			}
		}
	}


	/**
	 * Sets home page, site title and imports menus.
	 *
	 * @access private
	 * @since 3.4.1
	 */
	private function import_general_data() {

		// Set homepage
		$homepage = get_page_by_title( $this->homepage_title );
		if ( isset( $homepage ) && $homepage->ID ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $homepage->ID ); // Front Page.
		}

		$this->assign_menus_to_locations();
	}


	/**
	 * Import customizer settings
	 *
	 * @access private
	 * @since 3.4.1
	 */
	private function import_customizer() {

		if ( ! class_exists( 'HB_Customizer_Helper' ) ) {
			wp_die( 'hb_import_error: ' . __( 'HB_Customizer_Helper not found.', 'hbthemes' ) );
			return;
		}

		$this->theme_customizer_file = $this->get_import_file_path( 'customizer.txt' );

		if ( is_wp_error( $this->theme_customizer_file ) ) {
			wp_die( 'hb_import_error: ' . __( 'Customizer import failed.', 'hbthemes' ) . ' ' . $this->theme_customizer_file->get_error_message() );
		} elseif ( ! file_exists( $this->theme_customizer_file ) ) {
			wp_die( 'hb_import_error: ' . $this->theme_customizer_file . __( 'Customizer import failed. Import file customizer.txt not found.', 'hbthemes' ) );
			return;
		}

		$file_content = file_get_contents( $this->theme_customizer_file );

		if ( ! $file_content ) {
			wp_die( 'hb_import_error: ' . __( 'No Customizer settings to import.', 'hbthemes' ) );
			return;
		}

		$settings = unserialize( $file_content );

		if ( empty( $settings ) ) {
			wp_die( 'hb_import_error: ' . __( 'Unserialize customizer.txt error.', 'hbthemes' ) );
			return;
		}

		HB_Customizer_Helper::import_customizer_fields( $settings );
	}

	/**
	 * Assigns imported menus to correct locations.
	 *
	 * @since 3.4.1
	 */
	private function assign_menus_to_locations() {

		// Registered menu locations in theme.
		$locations = get_theme_mod( 'nav_menu_locations' ); 

		// Registered menus.
		$menus     = wp_get_nav_menus(); 
		
		// Assign menus to theme locations
		if ( $menus ) {
			foreach ( $menus as $menu ) {
				if ( $menu->name == 'Main Menu' ) {
					$locations['main-menu']   = $menu->term_id;
					$locations['mobile-menu'] = $menu->term_id;
				} else if ( $menu->name == 'Footer Menu' ) {
					$locations['footer-menu'] = $menu->term_id;
				} else if ( $menu->name == 'One Page Menu' ) {
					$locations['one-page-menu'] = $menu->term_id;
				}
			}
			// Set menus to locations
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}


	/**
	 * Get homepage title
	 *
	 * @since 3.4.1
	 */
	private function get_homepage_title() {

		switch ( $this->demo_id ) {
			case 'cafe' :
				return 'Splash Page';
				break;

			default: 
				return 'Home';
				break;
		}
	}


	/**
	 * Get revolution slider names
	 *
	 * @since 3.4.1
	 */
	private function get_revsliders() {

		switch ( $this->demo_id ) {
			case 'main-demo' :
				return array(
					'boxed-corporate-slider',
					'corporate-slider',
					'home-classic-slider',
					'home-default-slider',
					'home-special-slider',
					'jobs-slider',
					'one-page-slider',
					'shop-slider'
				);
				break;

			case 'bloggera' :
				return array(
					'home_slider'
				);
				break;

			case 'cafe' :
				return array(
					'splash_slider'
				);
				break;

			case 'church' :
				return array(
					'home_hero'
				);
				break;

			case 'life-coach' :
				return array(
					'homeslider'
				);
				break;

			case 'minimalistic' :
				return array(
					'about_slider',
					'home_slider'
				);
				break;

			case 'online-shop' :
				return array(
					'shop-home-slider',
				);
				break;

			case 'photography' :
				return array(
					'blog-slider',
					'home-slider'
				);
				break;

			case 'presentation' :
				return array(
					'about-slider',
				);
				break;

			default: 
				return false;
				break;
		}
	}


	/**
	 * Imports revsliders.
	 *
	 * @since 3.4.1
	 */
	private function import_revolution_sliders() {

		// Import Revslider.
		if ( class_exists( 'UniteFunctionsRev' ) && false !== $this->get_revsliders() ) { // If revslider is activated.

			$slider = new RevSlider();

			foreach ( $this->get_revsliders() as $rev_file ) {

				$rev_file_path = $this->get_import_file_path( $rev_file . '.zip', 'revsliders' );
				
				if ( is_wp_error( $rev_file_path ) ) {
					wp_die( 'hb_import_error: ' . __( 'Slider Import failed.', 'hbthemes' ) . ' ' . $rev_file_path->get_error_message() );
				} elseif ( file_exists( $rev_file_path ) ) {

					ob_start();
					$result = $slider->importSliderFromPost( true, false, $rev_file_path );
					ob_clean();
					ob_end_clean();

					if ( true === $result['success'] ) {
						//$this->content_tracker->add_rev_slider_to_stack( $result['sliderID'] );
					}
				} else {
					wp_die( 'hb_import_error: ' . __( 'Slider Import failed. Revslider import files not found.', 'hbthemes' ) );
				}
			}
		}
	}

	/**
	 * We don't want to import demo authors.
	 *
	 * @since 3.4.1
	 * @param array $data User importer data.
	 * @param array $meta User meta.
	 * @return bool
	 */
	public function skip_authors( $data, $meta ) {
		return false;
	}


	/**
	 * Check if demo is a WooCommerce website
	 *
	 * @since 3.4.1
	 */
	private function is_shop() {

		$config = include get_parent_theme_file_path( 'config/config.php' );

		if ( isset( $config['demos'][ $this->demo_id ]['is_shop'] ) && $config['demos'][ $this->demo_id ]['is_shop'] ) {
			return true;
		}

		return false;
	}


	/**
	 * Get woopages in the demo
	 *
	 * @since 3.4.1
	 */
	private function get_woopages() {

		$woopages = array(
			'woocommerce_shop_page_id' 				=> 'Shop',
			'woocommerce_cart_page_id' 				=> 'Cart',
			'woocommerce_checkout_page_id' 			=> 'Checkout',
			'woocommerce_pay_page_id' 				=> 'Checkout \u2192 Pay',
			'woocommerce_thanks_page_id' 			=> 'Order Received',
			'woocommerce_myaccount_page_id' 		=> 'My Account',
			'woocommerce_edit_address_page_id' 		=> 'Edit My Address',
			'woocommerce_view_order_page_id' 		=> 'View Order',
			'woocommerce_change_password_page_id'	=> 'Change Password',
			'woocommerce_logout_page_id' 			=> 'Logout',
			'woocommerce_lost_password_page_id' 	=> 'Lost Password'
		);

		$config = include get_parent_theme_file_path( 'config/config.php' );

		if ( isset( $config['demos'][ $this->demo_id ]['woopages'] ) ) {
			array_merge( $woopages, $config['demos'][ $this->demo_id ]['woopages'] );
		}

		return $woopages;
	}


	/**
	 * Get import file path
	 *
	 * @since 3.4.1
	 */
	private function get_import_file_path( $file_name, $folder = '' ) {

		if ( '' !== $folder ) {
			$folder .= '/';
		}

		$filepath = get_parent_theme_file_path( 'config/demos/' . $this->demo_id . '/' . $folder . $file_name );

		// File not yet downloaded
		if ( ! file_exists( $filepath ) || 0 == filesize( $filepath ) ) {

			$download_url 	= 'https://hb-themes.com/repository/import/highend/v2/' . $this->demo_id . '/' . $folder . $file_name;

			// Try to download the file
			$filepath = $this->download_import_file( $filepath, $download_url );
		}

		return $filepath;
	}

	/**
	 * Download necessary import files
	 *
	 * @since 3.4.1
	 */
	private function download_import_file( $save_path, $download_path ) {

		// Check if remote file exists
		$response = wp_remote_get( $download_path, array( 'timeout' => 300 ) );
		$code 	  = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $code ) {
			return new WP_Error( 'error', __( 'Could not download remote file', 'hbthemes' ) . ': ' . basename( $download_path ) );
		}

		$dirname = dirname( $save_path );
		
		if ( ! is_dir( $dirname ) ) {
			mkdir( $dirname, 0755, true );
		}

		if ( ! is_writable( $dirname ) ) {
			return new WP_Error( 'error', 'Check file permissions.', 'hbthemes' );
		}

		$src 	= wp_remote_retrieve_body( $response );

		if ( ! $src ) {
			return;
		}

		$dest 	= fopen( $save_path, 'w' );

		if ( ! $dest ) {
			return new WP_Error( 'error', basename( $save_path ) . ' ' . __( ' failed to create file.', 'hbthemes' ) );
		}

		fwrite( $dest, $src );				
		fclose( $dest );

		return $save_path;
	}	
}