<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Direct access forbidden.' ); }

/**
 * HB Install Plugin page.
 * Install, update & activate bundled plugins.
 * 
 * @since 3.4.1
 */
class HB_Install_Plugins_Page extends HB_Admin_Screens {
	
	/**
	 * Plugins array containing info about plugin.
	 *
	 * @since 3.4.2
	 */
	var $plugins;

	/**
	 * Primary class constructor.
	 *
	 * @since 3.4.1
	 */
	public function __construct() {

		parent::__construct();
		$this->page_slug = 'hb_plugins';

		$this->plugins = HB_Plugin_Installation::get_remote_plugins_config( $this->config['plugins'] );

		$this->initialize_filesystem();
		$this->dismiss_notice();
		$this->add_actions();
		$this->add_ajax_requests();
	}


	/**
	 * Add Install Plugins to Admin menu
	 *
	 * @since 3.4.1
	 */
	public function add_to_menu() {
		add_submenu_page(
			'hb_about',
			esc_html__( 'Install Plugins', 'hbthemes' ),
			esc_html__( 'Install Plugins', 'hbthemes' ),
			'manage_options',
			$this->page_slug, 
			array( $this, 'render_page_view' )
		);
	}

	/**
	 * Load required assets on the admin page(s).
	 *
	 * @since 3.4.1
	 */
	public function load_assets( $hook ) {

		parent::load_assets( $hook );

		if ( $hook != 'highend_page_' . $this->page_slug ) {
			return;
		}

		wp_enqueue_script( 'hb-isotope', get_parent_theme_file_uri( 'hbframework/assets/js/isotope.js' ), array( 'jquery', 'hb-framework-script' ), $this->config['theme_version'], true );
	}


	/**
	 * Admin ajax actions. Install & activate actions.
	 *
	 * @since 3.4.1
	 */
	public function add_ajax_requests() {

		add_action( 'wp_ajax_' . $this->prefix . 'plugin_manager_install', array(
			$this,
			'install_plugin'
		) );

		add_action( 'wp_ajax_' . $this->prefix . 'plugin_manager_activate', array(
			$this,
			'activate_plugin'
		) );

		add_action( 'wp_ajax_' . $this->prefix . 'plugin_manager_deactivate', array(
			$this,
			'deactivate_plugin'
		) );
	}


	/**
	 * Install plugin
	 *
	 * @since 3.4.1
	 */
	public function install_plugin() {

		$plugin_slug = isset ( $_POST['plugin_slug'] ) ? $_POST['plugin_slug'] : '';
		
		if ( $plugin_slug && $plugin = HB_Plugin_Installation::get_plugin_by_slug( $plugin_slug, $this->plugins ) ) {
			check_ajax_referer( $plugin_slug );
			HB_Plugin_Installation::install_plugin_json( $plugin );
		}		

		wp_send_json_error( array(
			'message' => esc_html__( 'Failed to install plugin.', 'hbthemes' ),
		) );
	}


	/**
	 * Activate plugin
	 *
	 * @since 3.4.1
	 */
	public function activate_plugin() {

		$plugin_slug = isset ( $_POST['plugin_slug'] ) ? $_POST['plugin_slug'] : '';
		
		if ( $plugin_slug && $plugin = HB_Plugin_Installation::get_plugin_by_slug( $plugin_slug, $this->plugins ) ) {
			check_ajax_referer( $plugin_slug );
			HB_Plugin_Installation::activate_plugin_json( $plugin );
		}		

		wp_send_json_error( array(
			'message' => esc_html__( 'Failed to activate plugin.', 'hbthemes' ),
		) );
	}


	/**
	 * Deactivate plugin
	 *
	 * @since 3.4.1
	 */
	public function deactivate_plugin() {

		$plugin_slug = isset ( $_POST['plugin_slug'] ) ? $_POST['plugin_slug'] : '';
		
		if ( $plugin_slug && $plugin = HB_Plugin_Installation::get_plugin_by_slug( $plugin_slug, $this->plugins ) ) {
			check_ajax_referer( $plugin_slug );
			HB_Plugin_Installation::deactivate_plugin_json( $plugin );
		}		

		wp_send_json_error( array(
			'message' => esc_html__( 'Failed to deactivate plugin.', 'hbthemes' ),
		) );
	}


	/**
	 * Initialize filesystem
	 *
	 * @since 3.4.1
	 */
	public function initialize_filesystem() {
		return ( WP_Filesystem() === true );
	}


	/**
	 * Add neccessary admin actions.
	 *
	 * @since 3.4.1
	 */
	public function add_actions() {

		// Don't add if no plugins are specified in the config file.
		if ( ! empty( $this->plugins ) ) {
			add_action( 'admin_menu', array( $this, 'add_to_menu' ), 10 );
		}

		// Add plugin related notices
		if  ( ! isset( $_GET['page'] ) || ( isset( $_GET['page'] ) && $_GET['page'] != $this->page_slug ) ) {
			add_action( 'admin_notices', array( $this, 'plugin_notices' ) );
		}

		// Prevent redirection from other plugins
		add_action( 'admin_init', array( $this, 'remove_redirect_transients' ), 1 );
	}


	/**
	 * Prevent redirection from other plugins on activation
	 *
	 * @since 3.4.1
	 */
	public function remove_redirect_transients() {
		delete_transient( '_vc_page_welcome_redirect' );
		delete_transient( '_wc_activation_redirect' );
	}


	/**
	 * Admin Notices
	 *
	 * @since 3.4.1
	 */
	public function plugin_notices() {

		$required_plugins 		= array();
		$recommended_plugins 	= array();
		$update_plugins 		= array();

		if ( ! empty( $this->plugins ) ) {

			foreach( $this->plugins as $slug => $plugin ) {

				if ( $plugin['required'] ) {
					if ( ! HB_Plugin_Installation::is_plugin_installed( $plugin['slug'] ) || ! HB_Plugin_Installation::is_plugin_activated( $plugin['slug'] ) ) {
						$required_plugins[] = $plugin['name'];
					}
				} else {
					if ( ! HB_Plugin_Installation::is_plugin_installed( $plugin['slug'] ) || ! HB_Plugin_Installation::is_plugin_activated( $plugin['slug'] ) ) {
						$recommended_plugins[] = $plugin['name'];
					}
				}

				if ( HB_Plugin_Installation::has_plugin_update( $plugin, true ) ) {
					$update_plugins[] = $plugin['name'];
				}
			}
		} 

		// Required plugins
		if ( ! empty( $required_plugins ) ) { ?>

			<div class="notice notice-info hb-notice">
				<p><?php echo esc_html__( 'Highend theme requires', 'hbthemes' ) . ' ' . $this->generate_notice_string( $required_plugins ); ?></p>

				<p class="hb-submit">
					<a href="<?php echo admin_url( 'admin.php?page=hb_plugins#required'); ?>" class="button button-primary"><?php printf( esc_html__( 'Go to %s Plugins', 'hbthemes' ), $this->theme_name ); ?></a>
				</p>
			</div>

		<?php }

		// Recommended plugins
		if ( ! empty( $recommended_plugins ) && ! $this->is_dismissed_notice( 'dismiss_recommended' ) ) { ?>
			
			<div class="notice notice-info hb-notice">
				<p><?php echo esc_html__( 'Optional plugins available: ', 'hbthemes' ) . ' ' . $this->generate_notice_string( $recommended_plugins ); ?><br/>
				
				<p class="hb-submit">
					<a href="<?php echo admin_url( 'admin.php?page=hb_plugins#recommended' ); ?>" class="button button-primary"><?php printf( esc_html__( 'Go to %s Plugins', 'hbthemes'), $this->theme_name ); ?></a>
					<a href="<?php echo add_query_arg( 'hb_dismiss', 'dismiss_recommended' ); ?>" class="button button-secondary"><?php esc_html_e( 'Dismiss', 'hbthemes' ); ?></a>
				</p>
			</div>

		<?php }

		// Update plugins
		if ( ! empty( $update_plugins ) && ! $this->is_dismissed_notice( 'dismiss_update' ) ) { ?>

			<div class="notice notice-info hb-notice">
				<p><?php printf( _n( 'There is an update available for %s', 'There are updates available for %s', count( $update_plugins ), 'hbthemes' ), $this->generate_notice_string( $update_plugins ) ); ?></p>

				<p class="hb-submit">
					<a href="<?php echo admin_url( 'admin.php?page=hb_plugins#update' ); ?>" class="button button-primary"><?php esc_html_e( 'Update Now', 'hbthemes'); ?></a>
					<a href="<?php echo add_query_arg( 'hb_dismiss', 'dismiss_update' ); ?>" class="button button-secondary"><?php esc_html_e('Dismiss', 'hbthemes'); ?></a>
				</p>
			</div>

		<?php }
		
	}


	/**
	 * Dismiss plugin admin notice.
	 *
	 * @since 3.4.1
	 */
	private function dismiss_notice() {

		$notice_id = isset ( $_GET['hb_dismiss'] ) ? $_GET['hb_dismiss'] : '';

		if ( ! $notice_id ) {
			return;
		}

		$current_user = get_current_user_id();
		add_user_meta( $current_user, $notice_id, 'true', true );
	}


	/**
	 * Check if notice is dimissed
	 *
	 * @param string, $notice_id
	 * @return boolean
	 * @since 3.4.1
	 */
	private function is_dismissed_notice( $notice_id ) {
		return get_user_meta( get_current_user_id(), $notice_id );
	}

	/**
	 * Generate notice string based on parameters
	 *
	 * @param array, $array
	 * @return string
	 * @since 3.4.1
	 */
	private function generate_notice_string( $array ) {

		if ( ! is_array( $array ) || empty( $array ) ) {
			return;
		}

		$last 	= array_pop( $array );
		$string = count( $array ) ? implode( ', ', $array ) . ' ' . esc_html__( 'and', 'hbthemes') . ' ' . $last . ' ' . esc_html__( 'plugins', 'hbthemes' ) : $last . ' ' . esc_html__('plugin', 'hbthemes');

		return $string . '.';
	}


	/**
	 * Render Plugins page.
	 *
	 * @since 3.4.1
	 */
	public function render_page_view() {

		$all_plugins 			= $this->plugins;
		$required_plugins 		= array();
		$recommended_plugins 	= array();
		$tab_index 				= -1;

		$pending_updates 		= array();

		if ( ! empty( $all_plugins ) ) {
			foreach ( $all_plugins as $slug => $plugin ) {
				if ( $plugin['required'] ) {
					array_push( $required_plugins, $plugin );
				} else {
					array_push( $recommended_plugins, $plugin );
				}

				if ( HB_Plugin_Installation::has_plugin_update( $plugin, true ) ) {
					array_push( $pending_updates, $plugin );
				}
			}
		}

		?>
		<div id="hb-page-wrapper">

			<div id="hb-container" data-install="<?php esc_html_e( 'Install Plugin', 'hbthemes' ); ?>" data-installing="<?php esc_html_e('Installing', 'hbthemes'); ?>" data-activate="<?php esc_html_e( 'Activate', 'hbthemes' ); ?>" data-activating="<?php esc_html_e( 'Activating', 'hbthemes' ); ?>" data-deactivate="<?php esc_html_e( 'Deactivate', 'hbthemes' ); ?>" data-deactivating="<?php esc_html_e( 'Deactivating', 'hbthemes' ); ?>" data-reloading="<?php esc_html_e( 'Reloading...', 'hbthemes' ); ?>" data-update="<?php esc_html_e( 'Update', 'hbthemes' ); ?>">

				<div id="hb-page-title">
					<h1 class="in-block">
						<?php printf( esc_html__( 'Install Plugins', 'hbthemes' ), $this->theme_name ); ?>	
					</h1>
					<span class="hb-version-badge"><?php echo count( $all_plugins ); ?> <?php esc_html_e( 'available plugins', 'hbthemes' ); ?></span>
				</div>
				<!-- END #hb-page-title -->

				<div class="clear"></div>

				<ul id="hb-tabs" class="hb-demo-filter">
					<li><a data-filter="*" href="#all"><?php esc_html_e( 'All', 'hbthemes' ); ?></a></li>

					<?php if ( ! empty( $required_plugins ) ) { ?>
					<li><a data-filter="required" href="#required"><?php esc_html_e( 'Required', 'hbthemes' ); ?></a></li>
					<li><a data-filter="recommended" href="#recommended"><?php esc_html_e( 'Optional', 'hbthemes' ); ?></a></li>
					<?php } ?>

					<?php if ( ! empty ( $pending_updates ) ) { ?>
					<li><a data-filter="update" href="#update"><?php esc_html_e( 'Update', 'hbthemes' ); ?></a></li>
					<?php } ?>

					<?php foreach ( $all_plugins as $slug => $plugin ) { ?>
					<li class="hidden"><a data-filter="<?php echo $plugin['slug']; ?>" href="#<?php echo $plugin['slug']; ?>"><?php echo $plugin['name']; ?></a></li>
					<?php } ?>

				</ul>

				<div id="hb-content" class="theme-browser rendered">
					<div id="filter-container" class="themes wp-clearfix">

						<?php foreach ( $all_plugins as $slug => $plugin ) {

							$class 			= array('theme');
							$is_installed 	= HB_Plugin_Installation::is_plugin_installed( $plugin['slug'] );
							$is_activated 	= HB_Plugin_Installation::is_plugin_activated( $plugin['slug'] ); 
							$has_update 	= HB_Plugin_Installation::has_plugin_update( $plugin, true ); 

							$plugin_text 	= esc_html__( 'Install Plugin', 'hbthemes' );
							$plugin_version = isset( $plugin['version'] ) ? 'v' . $plugin['version'] : '';
							$disabled 		= '';

							$tab_index++;

							if ( $has_update ) {
								$plugin_text 	= esc_html__( 'Update', 'hbthemes' );
								$class[] 		= 'install';
								$class[] 		= 'update';
							} else if ( $is_installed ) {

								if ( $is_activated ) {
									$plugin_text 	= esc_html__( 'Deactivate', 'hbthemes' );
									$disabled 		= '';
									$class[] 		= 'deactivate';
								} else {
									$plugin_text 	= esc_html__( 'Activate', 'hbthemes' );
									$class[] 		= 'activate';
								}

								$plugin_version = 'v' . $is_installed[ key( $is_installed ) ]['Version'];

							} else {
								$class[] = 'install';
							}

							if ( $plugin['required'] ) {
								$class[] = 'required';
							} else {
								$class[] = 'recommended';
							}

							$class[] = $plugin['slug'];

							$class = implode( ' ', $class );
						?>

						<div class="<?php echo $class; ?>" tabindex="<?php echo $tab_index; ?>" data-slug="<?php echo $plugin['slug']; ?>" data-nonce="<?php echo wp_create_nonce( $plugin['slug'] ); ?>">
							<div class="theme-inner">
								
								<div class="theme-screenshot">
									<img src="<?php echo get_parent_theme_file_uri( $plugin['screenshot'] ); ?>" alt="">
									<span><?php echo $plugin_version; ?></span>
								</div>

								<span class="more-details"><?php echo $plugin_text; ?></span>
								<h3 class="theme-name"><?php echo $plugin['name']; ?></h3>
								
								<div class="theme-actions">
									<a class="button button-primary" href="#"<?php echo $disabled; ?>><?php echo $plugin_text; ?></a><div class="spinner"></div>
								</div>
								<!-- END .theme-actions -->

							</div>
							<!-- END .theme-inner -->

						</div>
						<!-- END .theme -->
						<?php } ?>

					</div>
					<!-- END .themes -->

				</div>
				<!-- END #hb-content -->

			</div>
			<!-- END #hb-container -->

		</div>
		<!-- END #hb-page-wrapper -->
		<?php
	}
}
new HB_Install_Plugins_Page();