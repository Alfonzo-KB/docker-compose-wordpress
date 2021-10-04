<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Direct access forbidden.' ); }

/**
 * HB About page.
 * 
 * @since 3.4.1
 */
class HB_About_Page extends HB_Admin_Screens {
	
	/**
	 * Primary class constructor.
	 *
	 * @since 3.4.1
	 */
	public function __construct() {

		parent::__construct();
		$this->page_slug = 'hb_about';

		add_action( 'admin_menu', array( $this, 'add_to_menu' ), 10 );
	}

	/**
	 * Add Install Plugins to Admin menu
	 *
	 * @since 3.4.1
	 */
	public function add_to_menu() {

		add_menu_page(
			'Highend',
			'Highend',
			'manage_options',
			$this->page_slug,
			null,
			//array( $this, 'render_page_view' ),
			'dashicons-performance',
			'2.1'
		);

		add_submenu_page(
			$this->page_slug,
			'About',
			'About',
			'manage_options',
			$this->page_slug, 
			array( $this, 'render_page_view' )
		);
	}

	/**
	 * Render Sidebar Manager page.
	 *
	 * @since 3.4.1
	 */
	public function render_page_view() {
		?>
		<div id="hb-page-wrapper">
			<div id="hb-about" class="wp-clearfix">
				<div id="hb-hero-section">
					<div class="hb-background"></div>
					<div class="hb-container">

						<?php
						$current_user_id = get_current_user_id();
						$current_user 	 = get_userdata( $current_user_id );
						$theme_version 	 = wp_get_theme()-> version;
						?>

						<div class="wp-clearfix">
							<div class="hb-left-section">
								<h3><?php esc_html_e( 'Hey', 'hbthemes' ); ?>, <span><?php echo $current_user->display_name; ?></span></h3>
								<h1><?php esc_html_e( 'Welcome to', 'hbthemes' ); ?> <strong><?php esc_html_e( 'Highend', 'hbthemes' ); ?>.</strong><span class="hb-version"><?php esc_html_e( 'version ', 'hbthemes' ); echo $theme_version; ?></span></h1>
								<h4><?php _e( 'Thank you for choosing Highend &mdash; the ultimate tool for building professional websites. Use the tips below to get started. You will be up and running in no time.', 'hbthemes' ); ?></h4>

								<a href="#first-steps" class="hero-button"><?php esc_html_e( 'Get Started', 'hbthemes' ); ?></a><a href="https://hb-themes.com/documentation/highend" target="_blank" class="how-to-use"><?php esc_html_e( 'Learn How To Use', 'hbthemes' ); ?></a>
							</div><!-- END .hb-left-section -->

							<div class="hb-right-section">
								<div class="image-holder">
									<img src="<?php echo get_parent_theme_file_uri( 'screenshot.png' ); ?>" alt="Logo" />
									<div class="play-video">
										<a href="https://www.youtube.com/watch?v=KKySsbY1t_c" target="_blank"><i class="dashicons dashicons-controls-play"></i></a>
										<p><?php esc_html_e( 'Highend Preview &mdash; 2 mins', 'hbthemes' ); ?></p>
									</div>
								</div>
							</div><!-- END .hb-right-section -->
						</div><!-- END .wp-clearfix -->
					</div><!-- ENd .hb-container -->
				</div><!-- END #hb-hero-section -->

				<div id="hb-about-content" class="wp-clearfix">
					<div class="hb-container">
						<h2 id="first-steps" class="hb-section-heading"><?php esc_html_e( 'First Steps', 'hbthemes' ); ?></h2>
						<p><?php esc_html_e( 'Follow these tips to get started.', 'hbthemes' ); ?></p>

						<ul class="hb-tips wp-clearfix">

							<li>
								<div class="hb-tip">
									<span class="tip-enum">1</span>
									<p><i class="dashicons dashicons-editor-help"></i><strong><?php esc_html_e( 'Learn How To Use', 'hbthemes' ); ?></strong></p>
									<p><?php _e( 'Read theme docs and watch video guides to learn how to use the theme efficiently.', 'hbthemes' ); ?></p>
									<a href="https://hb-themes.com/documentation/highend" target="_blank"><?php esc_html_e( 'Learn How to Use', 'hbthemes' ); ?></a>
								</div>
							</li>

							<li>
								<div class="hb-tip">
									<span class="tip-enum">2</span>
									<p><i class="dashicons dashicons-admin-plugins"></i><strong><?php esc_html_e( 'Install Plugins', 'hbthemes' ); ?></strong></p>
									<p><?php _e('Highend comes with optional and required plugins. Please install all required plugins.', 'hbthemes' ); ?></p>
									<a href="<?php echo admin_url('admin.php?page=hb_plugins'); ?>" target="_blank"><?php esc_html_e( 'Install Plugins', 'hbthemes' ); ?></a>
								</div>
							</li>

							<li>
								<div class="hb-tip">
									<span class="tip-enum">3</span>
									<p><i class="dashicons dashicons-download"></i><strong><?php esc_html_e( 'Import Demo', 'hbthemes' ); ?></strong></p>
									<p><?php _e('Don\'t want to start from scratch? Import a pre-built website and replace content.', 'hbthemes' ); ?></p>
									<a href="<?php echo admin_url('admin.php?page=hb_import_demos'); ?>" target="_blank"><?php esc_html_e( 'Import Demo', 'hbthemes' ); ?></a>
								</div>
							</li>

							<li>
								<div class="hb-tip">
									<span class="tip-enum">4</span>
									<p><i class="dashicons dashicons-admin-settings"></i><strong><?php esc_html_e( 'Highend Options', 'hbthemes' ); ?></strong>
									<p><?php _e('Choose between hundreds of options. Customize every aspect of the theme.', 'hbthemes' ); ?></p>
									<a href="<?php echo admin_url('admin.php?page=highend_options'); ?>" target="_blank"><?php esc_html_e( 'Highend Options', 'hbthemes' ); ?></a>
								</div>
							</li>

							<div class="clear"></div>

							<li>
								<div class="hb-tip">
									<span class="tip-enum">5</span>
									<p><i class="dashicons dashicons-admin-page"></i><strong><?php esc_html_e( 'Manage Pages', 'hbthemes' ); ?></strong></p>
									<p><?php _e('Add new or editing existing pages on your website.', 'hbthemes' ); ?></p>
									<a href="<?php echo admin_url('edit.php?post_type=page'); ?>" target="_blank"><?php esc_html_e( 'Manage Pages', 'hbthemes' ); ?></a>
								</div>
							</li>

							<li>
								<div class="hb-tip">
									<span class="tip-enum">6</span>
									<p><i class="dashicons dashicons-welcome-widgets-menus"></i><strong><?php esc_html_e( 'Manage Menus', 'hbthemes' ); ?></strong></p>
									<p><?php _e('Add menu items and manage everything related.', 'hbthemes' ); ?></p>
									<a href="<?php echo admin_url('nav-menus.php'); ?>" target="_blank"><?php esc_html_e( 'Manage Menus', 'hbthemes' ); ?></a>
								</div>
							</li>

							<li>
								<div class="hb-tip">
									<span class="tip-enum">7</span>
									<p><i class="dashicons dashicons-admin-home"></i><strong><?php esc_html_e( 'Set Front Page', 'hbthemes' ); ?></strong></p>
									<p><?php _e('Choose which page will be used as your home page.', 'hbthemes' ); ?></p>
									<a href="<?php echo admin_url('options-reading.php'); ?>" target="_blank"><?php esc_html_e( 'Set Front Page', 'hbthemes' ); ?></a>
								</div>
							</li>

							<li>
								<div class="hb-tip">
									<span class="tip-enum">8</span>
									<p><i class="dashicons dashicons-admin-home"></i><strong><?php esc_html_e( 'Get Support', 'hbthemes' ); ?></strong></p>
									<p><?php _e('If you need help, visit the support center.', 'hbthemes' ); ?></p>
									<a href="https://hb-themes.com/support" target="_blank"><?php esc_html_e( 'Get Support', 'hbthemes' ); ?></a>
								</div>
							</li>

							<div class="clear"></div>
						</ul>
					</div><!-- END .hb-container -->

					<div class="white-section">
						<div class="hb-container">
							<h2 id="hire-an-expert" class="hb-section-heading"><?php esc_html_e( 'Hire an Expert', 'hbthemes' ); ?></h2>
							<p class="aligncenter"><?php esc_html_e( 'WordPress experts at your side.', 'hbthemes' ); ?></p>

							<div class="boxes-wrap wp-clearfix">
								<div class="left-box">
									<h3><?php esc_html_e( 'Build me a website', 'hbthemes' ); ?></h3>
									<p><?php _e( 'If you need theme customization or a completely custom website, <strong>hire our experts to build your website</strong>, just the way you want it.', 'hbthemes' ); ?></p>
									<a href="https://hb-themes.com/hire/" target="_blank"><?php esc_html_e( 'Request a Quote', 'hbthemes' ); ?></a>	
								</div><!-- END .left-box -->

								<div class="right-box">
									<h3><?php esc_html_e( 'WP Live', 'hbthemes' ); ?></h3>
									<p><?php _e( 'WP Live connects you with passionate WordPress experts who will <strong>teach you how to build, grow, and maintain your website.</strong>', 'hbthemes' ); ?></p>
									<a href="https://www.anrdoezrs.net/links/8353661/type/dlg/http://www.mojomarketplace.com/item/wordpress-support" target="_blank"><?php esc_html_e( 'Starting at $29/mo', 'hbthemes' ); ?></a>
								</div><!-- END .right-box -->
							</div><!-- END .wp-clearfix -->

						</div><!-- END .hb-container -->
						
						<img class="bottom-image" src="<?php echo get_parent_theme_file_uri( 'hbframework/assets/img/hire.png' ) ; ?>" alt="<?php esc_html_e( 'Hire', 'hbthemes' ); ?>" />

					</div><!-- END .white-section -->
				</div><!-- END #hb-about-content -->

			</div><!-- END #hb-about -->
		</div><!-- END #hb-page-wrapper -->
		<?php
	}
}
new HB_About_Page;