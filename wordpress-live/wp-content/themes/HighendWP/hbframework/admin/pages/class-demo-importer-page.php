<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Direct access forbidden.' ); }

/**
 * HB Import Demos page.
 * 
 * @since 3.4.1
 */
class HB_Import_Demos_Page extends HB_Admin_Screens {
	
	/**
	 * Importer handler
	 *
	 * @since 3.4.1
	 */
	private $importer;
	
	/**
	 * Primary class constructor.
	 *
	 * @since 3.4.1
	 */
	public function __construct() {

		parent::__construct();
		$this->page_slug = 'hb_import_demos';

		// Don't add if no plugins are specified in the config file.
		if ( ! empty( $this->config['demos'] ) ) {
			add_action( 'admin_menu', array( $this, 'add_to_menu' ), 10 );
		}

		$this->importer = new HB_Demo_Importer();
	}


	/**
	 * Add Import Demos to admin menu
	 *
	 * @since 3.4.1
	 */
	public function add_to_menu() {
		add_submenu_page(
			'hb_about',
			esc_html__( 'Import Demos', 'hbthemes' ),
			esc_html__( 'Import Demos', 'hbthemes' ),
			'manage_options',
			$this->page_slug, 
			array( $this, 'render_page_view' )
		);
	}

	/**
	 * Load required assets on the admin page(s).
	 *
	 * @since 4.0
	 */
	public function load_assets( $hook ) {

		parent::load_assets( $hook );

		if ( $hook != 'highend_page_' . $this->page_slug ) {
			return;
		}

		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		
		wp_enqueue_script( 'hb-isotope', get_parent_theme_file_uri( 'hbframework/assets/js/isotope.js' ), array( 'jquery', 'hb-framework-script' ), $this->config['theme_version'], true );

		wp_enqueue_script( 'hb-demo-importer', get_parent_theme_file_uri( 'hbframework/assets/js/hb-demo-importer.js' ), array( 'jquery', 'hb-framework-script' ), $this->config['theme_version'], true );

		wp_localize_script( 
			'hb-demo-importer',
			'import_demo_data',
			array(
				'admin_url'				=> admin_url( 'admin-ajax.php' ),
				'initialize_import'		=> __( 'Preparing import...', 'hbthemes' ),
				'currently_processing'	=> __( 'Currently processing: %s', 'hbthemes' ),
				'content'				=> __( 'Content', 'hbthemes' ),
				'general_data'			=> __( 'General Data', 'hbthemes' ),
				'error_timeout'         => __( 'Script timed out. Please refresh the page and try again.', 'hbthemes' ),
				'error_php_limits'      => __( 'Demo import failed. Please check for PHP limits and change those to the recommended value and try again.', 'hbthemes' ),
				'forbidden'         	=> __( 'Security check failed. Please refresh the page and try again.', 'hbthemes' ),
				'complete_with_errors'  => __( 'Demo import finished with errors:', 'hbthemes' ),
				'complete_title'		=> __( 'Import Complete', 'hbthemes' ),
				'are_you_sure'			=> '<p>' . __( 'Are you sure you want to do import this template?', 'hbthemes' ) . '</p><p>' . __( 'Importing demo content will provide sliders, pages, posts, theme options, widgets, sidebars and other settings. You will get a copy of the demo.', 'hbthemes' ) . '</p><p><strong>' . __( 'Clicking this option will replace your current theme options and widgets.', 'hbthemes' ) . '</strong></p>',
				'retry_import'			=> '<p>' . __( 'Demo Import timed out and did not finish successfully. Would you like to retry the import?', 'hbthemes' ) . '</p><p>' . __( 'If this keeps happening, please contact support.', 'hbthemes' ) . '</p>'
			)
		);
	}

	/**
	 * Render Import Demos page.
	 *
	 * @since 3.4.1
	 */
	public function render_page_view() {

		$demos 		= $this->config[ 'demos' ];
		$demo_cats 	= array();
		$tab_index 	= -1;

		if ( ! empty( $demos ) ) {
			foreach ( $demos as $demo_id => $demo ) {
				$demo_cats = array_unique( array_merge( $demo_cats, isset( $demo['category'] ) ? $demo['category'] : array() ) );
			}
		}

		asort( $demo_cats );

		$demo_selected = isset( $_GET['demo_id'] ) ? $_GET['demo_id'] : '';

		?>
		<div id="hb-page-wrapper">
			<div id="hb-container">

			<?php if ( $demo_selected ) {  ?>
			
				<div id="hb-page-title">
					<h1 class="in-block">
						<?php echo $this->config['demos'][ $demo_selected ]['name']; ?>
					</h1><span class="hb-version-badge"></span>
				</div>

				<div class="clear"></div>

				<div id="hb-content" class="theme-browser rendered">
					<div id="hb-timeline"></div><!-- END #hb-timeline -->
					<div id="hb-timeline-content"><div class="bottom-spinner"><div class="spinner"></div></div></div><!-- END #hb-timeline-content -->
				</div>
			
			<?php } else { ?>

				<div id="hb-page-title">
					<h1 class="in-block"><?php printf( esc_html__( 'Import Demo Templates', 'hbthemes' ), $this->theme_name ); ?></h1>
					<span class="hb-version-badge"><?php printf( esc_html__( '%s available templates', 'hbthemes' ), count( $demos ) ); ?></span>
				</div>

				<div class="clear"></div>
				<!-- END #hb-page-title -->

				<ul id="hb-tabs" class="hb-demo-filter">
					<li><a data-filter="*" href="#all"><?php esc_html_e( 'All', 'hbthemes' ); ?></a></li>
					<?php 
					if ( ! empty( $demo_cats ) ) { 
						foreach ( $demo_cats as $category ) { ?>
							<li><a data-filter="<?php echo sanitize_title( $category ); ?>" href="#<?php echo sanitize_title( $category ); ?>"><?php echo $category; ?></a></li>
						<?php 
						}
					} ?>
				</ul>
				<!-- END #hb-tabs -->

				<div id="hb-content" class="theme-browser rendered">
					<div id="filter-container" class="themes wp-clearfix">
						
						<?php 
						if ( ! empty( $demos ) ) {
							foreach ( $demos as $demo_id => $demo ) { 

								$demo_settings = $this->config['demos'][$demo_id];

								$class = array( 'theme' );
								$class = array_merge( $class, array_map( 'sanitize_title', $demo['category'] ) );
								?>
								<div class="<?php echo implode( ' ', $class ); ?>" tabindex="1" data-slug="<?php echo $demo['slug']; ?>">
									<div class="theme-inner">
									
										<div class="theme-screenshot">
											<img src="<?php echo get_parent_theme_file_uri( $demo['screenshot'] ); ?>" alt="">
										</div>

										<a href="<?php echo $this->config['demos'][ $demo_id ]['live_preview']; ?>" target="_blank">
											<span class="more-details"><?php esc_html_e( 'Preview', 'hbthemes' ); ?></span>
										</a>
										
										<h3 class="theme-name"><?php echo $demo['name']; ?></h3>
										
										<div class="theme-actions">
											<a class="button button-primary init-import-demo" data-demo-id="<?php echo $demo_id; ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'hb_import_demo_ajax' ) ); ?>" data-options-nonce="<?php echo esc_attr( wp_create_nonce( 'vafpress' ) ); ?>" href="#"><?php esc_html_e( 'Import Demo', 'hbthemes' ); ?></a><div class="spinner"></div>
										</div>
										<!-- END .theme-actions -->

										<div id="import-demo-modal-<?php echo $demo_id; ?>" class="import-demo-modal-wrapper">

											<div class="import-demo-update-modal-inner">

												<div class="import-demo-modal-title">
													<h2 class="in-block"><?php echo $demo_settings['name']; ?></h2>
													<a class="button button-secondary" target="_blank" href="<?php echo $demo_settings['live_preview']; ?>"><?php _e( 'Live Preview', 'hbthemes' ); ?></a>
												</div>

												<div class="import-demo-update-modal-content">

													<?php if ( isset( $demo_settings['required_plugins'] ) ) { ?>
														<div class="import-demo-required-plugins">
															<h3><?php _e( 'This demo requires the following plugins:', 'hbthemes' ); ?></h3>
															<ul class="required-plugins-list">

																<?php foreach ( $demo_settings['required_plugins'] as $slug => $name) { ?>
																	<li>
																		<span class="required-plugin-name"><?php echo $name; ?></span>
																		
																		<?php if ( ! HB_Plugin_Installation::is_plugin_installed( $slug ) ) { ?>
																			<a href="<?php echo admin_url('admin.php?page=hb_plugins#' . $slug ); ?>" class="button button-primary" target="_blank"><?php _e( 'Install', 'hbthemes' ); ?></a>
																		<?php } elseif ( ! HB_Plugin_Installation::is_plugin_activated( $slug ) ) { ?>
																			<a href="<?php echo admin_url('admin.php?page=hb_plugins#' . $slug ); ?>" class="button button-secondary" target="_blank"><?php _e( 'Activate', 'hbthemes' ); ?></a>
																		<?php } else { ?>
																			<a href="#" class="button button-secondary" disabled="disabled"><?php _e( 'Active', 'hbthemes' ); ?></a>
																		<?php } ?>
																	</li>
																<?php } ?>

															</ul>

														</div>
													<?php } ?>

													<div class="import-demo-update-form-wrap">

														<div class="import-demo-form">
															<h4 class="import-demo-form-title">
																<?php _e( 'Import Content', 'hbthemes' ); ?>
															</h4>
															
															<form id="import-<?php echo $demo_id; ?>" data-demo-id="<?php echo $demo_id; ?>">
																<p>
																	<input type="checkbox" value="all" id="import-all-<?php echo $demo_id; ?>">
																	<label for="import-all-<?php echo $demo_id; ?>"><?php _e( 'All', 'hbthemes' ); ?></label>
																</p>
																<p>
																	<input type="checkbox" value="content" data-type="content" id="import-content-<?php echo $demo_id; ?>"> 
																	<label for="import-content-<?php echo $demo_id; ?>"><?php _e( 'Content', 'hbthemes' ); ?></label>
																</p>
																<p>
																	<input type="checkbox" value="attachment" data-type="content" id="import-attachment-<?php echo $demo_id; ?>"> 
																	<label for="import-attachment-<?php echo $demo_id; ?>"><?php _e( 'Attachments', 'hbthemes' ); ?></label>
																</p>
																<p>
																	<input type="checkbox" value="theme_options" id="import-theme_options-<?php echo $demo_id; ?>"> 
																	<label for="import-theme_options-<?php echo $demo_id; ?>"><?php _e( 'Theme Options', 'hbthemes' ); ?></label>
																</p>

																<?php if ( isset( $demo_settings['has_sidebars'] ) && $demo_settings['has_sidebars'] ) { ?>
																<p>
																	<input type="checkbox" value="widgets" id="import-widgets-<?php echo $demo_id; ?>"> 
																	<label for="import-widgets-<?php echo $demo_id; ?>"><?php _e( 'Widgets', 'hbthemes' ); ?></label>
																</p>
																<?php } ?>
																
																<p>
																	<input type="checkbox" value="customizer" id="import-customizer-<?php echo $demo_id; ?>"> 
																	<label for="import-customizer-<?php echo $demo_id; ?>"><?php _e( 'Customizer', 'hbthemes' ); ?></label>
																</p>
																<p>
																	<input type="checkbox" value="sliders" id="import-sliders-<?php echo $demo_id; ?>"> 
																	<label for="import-sliders-<?php echo $demo_id; ?>"><?php _e( 'Sliders', 'hbthemes' ); ?></label>
																</p>

																<?php if ( isset( $demo_settings['has_ess_grid'] ) && $demo_settings['has_ess_grid'] ) { ?>
																	<p>
																		<input type="checkbox" value="essgrid" id="import-essgrid-<?php echo $demo_id; ?>"> 
																		<label for="import-essgrid-<?php echo $demo_id; ?>"><?php _e( 'Essential Grid', 'hbthemes' ); ?></label>
																	</p>
																<?php } ?>

																<p>
																	<input type="checkbox" value="general_data" id="import-general-data-<?php echo $demo_id; ?>"> 
																	<label for="import-general-data-<?php echo $demo_id; ?>"><?php _e( 'Menus & Homepage setup', 'hbthemes' ); ?></label>
																</p>
																
															</form>												
														</div>

													</div>

												</div>

												<div class="import-demo-update-modal-status-bar-progress-bar"></div>

												<div class="import-demo-update-modal-status-bar">
													<a class="button button-primary button-install-demo" data-demo-id="<?php echo $demo_id; ?>" href="#" disabled="disabled"><?php _e( 'Import', 'hbthemes' ); ?></a>
													<a class="button button-primary button-uninstall-demo" data-demo-id="<?php echo $demo_id; ?>" href="#" style="display: none;"><?php _e( 'Remove', 'hbthemes' ); ?></a>
													<a class="button-done-demo demo-update-modal-close" href="#" style="display: none;"><?php _e( 'Done', 'hbthemes' ); ?></a>

													<div class="import-demo-update-modal-status-bar-label"><span></span></div>
												</div>
											</div>

											<a href="#" class="import-demo-modal-close"><span class="dashicons dashicons-no-alt"></span></a>
										</div>
									</div>
									<!-- END .theme-inner -->
								</div>
								<!-- END .theme -->
							<?php }
						} ?>
						
					</div>
					<!-- END #filter-container -->

				<div class="import-demo-overlay"></div>

				<div id="import-demo-confirm-dialog" title="<?php esc_attr_e( 'Are you sure?', 'hbthemes' ); ?>"></div>

				</div>
				<!-- END #hb-content -->
				
			<?php } ?>

			</div>
			<!-- END #hb-container -->
		</div>
		<!-- END #hb-page-wrapper -->
		<?php
	}

}
new HB_Import_Demos_Page();