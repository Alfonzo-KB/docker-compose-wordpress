<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Direct access forbidden.' ); }

/**
 * HB Sidebar Manager page.
 * 
 * @since 3.4.1
 */
class HB_Sidebar_Manager_Page extends HB_Admin_Screens {
	
	/**
	 * Primary class constructor.
	 *
	 * @since 3.4.1
	 */
	public function __construct() {

		parent::__construct();
		$this->page_slug = 'hb_sidebar_manager';

		$this->add_ajax_requests();
		$this->add_actions();
	}


	/**
	 * Add Install Plugins to Admin menu
	 *
	 * @since 3.4.1
	 */
	public function add_to_menu() {
		add_submenu_page(
			'hb_about',
			esc_html__( 'Sidebar Manager', 'hbthemes' ),
			esc_html__( 'Sidebar Manager', 'hbthemes' ),
			'manage_options',
			$this->page_slug, 
			array( $this, 'render_page_view' )
		);
	}

	/**
	 * Admin ajax actions. Add & Remove sidebars.
	 *
	 * @since 3.4.1
	 */
	public function add_ajax_requests() {

		add_action( 'wp_ajax_' . $this->prefix . 'add_sidebar', array(
			$this,
			'add_sidebar'
		) );

		add_action( 'wp_ajax_' . $this->prefix . 'remove_sidebar', array(
			$this,
			'remove_sidebar'
		) );
	}


	/**
	 * Add neccessary admin actions.
	 *
	 * @since 3.4.1
	 */
	public function add_actions() {

		add_action( 'admin_menu', array( $this, 'add_to_menu' ), 10 );
		add_action( 'init', array( $this, 'init' ) );

		if ( isset( $_GET['hb-sidebar-export'] ) ) {
			add_action( 'init', array( $this, 'export_sidebar_manager_settings' ), 20 );
		}
	}


	/**
	 * Initialize generated sidebars. 
	 * Supports compatibility with previous HB sidebar manager.
	 *
	 * @since 3.4.1
	 */
	public function init(){
		
		HB_Sidebar_Manager::import_old_sidebars();

		$sidebars = HB_Sidebar_Manager::get_sidebars();

		if ( is_array( $sidebars ) ){
			foreach ( $sidebars as $sidebar ) {
				HB_Sidebar_Manager::register_sidebar( $sidebar );
			}
		}
	}


	/**
	 * Ajax callback function to add a new sidebar.
	 *
	 * @since 3.4.1
	 */
	public function add_sidebar() {

		// Security check
		check_ajax_referer( $this->page_slug, 'security' );

		if ( ! isset( $_POST['sidebar_name' ] ) || ! isset( $_POST['sidebar_description'] ) ) {
			wp_send_json_error( array(
				'message'	=> esc_html__( 'Please fill in sidebar name and description.', 'hbthemes' )
			) );
		}

		$sidebars 		= HB_Sidebar_Manager::get_sidebars();
		$name 			= sanitize_text_field( $_POST['sidebar_name'] );
		$description 	= sanitize_text_field( $_POST['sidebar_description'] );
		$name 			= str_replace( array("\n","\r","\t"),'', $name );
		$id 			= HB_Sidebar_Manager::name_to_class( $name );

		// Check if a sidebar with same name exists.
		if ( isset( $sidebars[ $id ] ) ){
			wp_send_json_error( array(
				'message'	=> esc_html__( 'A sidebar with that name already exists.', 'hbthemes' )
			) );
		}

		$sidebars[ $id ] = array(
			'name'			=> $name,
			'description'	=> $description
		);

		HB_Sidebar_Manager::update_sidebars( $sidebars );

		wp_send_json_success( array(
			'message'		=> esc_html__( 'Sidebar created.', 'hbthemes' ),
			'name'			=> $name,
			'css'			=> '.' . strtolower( HB_Sidebar_Manager::name_to_class( $name ) ),
			'description' 	=> $description
		) );
	}


	/**
	 * Ajax callback function to remove a sidebar.
	 *
	 * @since 3.4.1
	 */
	public function remove_sidebar() {

		// Security check
		check_ajax_referer( $this->page_slug, 'security' );

		$sidebars 	= HB_Sidebar_Manager::get_sidebars();
		$name 		= sanitize_text_field( $_POST['sidebar_name'] );
		$name 		= str_replace( array("\n","\r","\t"),'', $name );
		$id 		= HB_Sidebar_Manager::name_to_class( $name );

		if ( ! isset( $sidebars[ $id ] ) ){
			wp_send_json_error( array(
				'message'	=> esc_html__( 'Sidebar does not exists.', 'hbthemes' )
			) );
		}

		unset( $sidebars[ $id ] );
		HB_Sidebar_Manager::update_sidebars( $sidebars );

		wp_send_json_success( array(
			'message'	=> esc_html__( 'Sidebar removed.', 'hbthemes' ),
			'ID'		=> $id
		) );
	}


	/**
	 * Render Sidebar Manager page.
	 *
	 * @since 3.4.1
	 */
	public function render_page_view() {

		$sidebars 			= HB_Sidebar_Manager::get_sidebars();
		$count 				= count( $sidebars );
		$empty_table_class 	= '';

		if ( $count == 0 ) {
			$empty_table_class = ' visible-empty-row';
		}

		$empty_td = "<tr id='empty-row' class='". $empty_table_class ."'><td>" . esc_html__('You do not appear to have any sidebars yet.', 'hbthemes' ); " . </td></tr>";

		?>
		<div id="hb-page-wrapper">

			<div id="hb-container" data-description="<?php esc_html_e( 'This is a widgetized area.', 'hbthemes' ); ?>" data-new="<?php esc_html_e( 'New Sidebar', 'hbthemes' ); ?>" data-save="<?php esc_html_e( 'Save', 'hbthemes' ); ?>" data-cancel="<?php esc_html_e( 'Cancel', 'hbthemes' ); ?>" data-delete="<?php esc_html_e( 'Delete', 'hbthemes' ); ?>" data-nonce="<?php echo wp_create_nonce( $this->page_slug ); ?>" data-textwait="<?php esc_html_e( 'Please wait...', 'hbthemes' ); ?>">

				<div id="hb-page-title">
					<h1 class="flexy">
						<?php esc_html_e( 'Sidebar Manager', 'hbthemes' ); ?>
						<a href="<?php echo $this->config['theme_docs_url']; ?>" class="how-to-use" target="_blank"><i class="dashicons dashicons-sos"></i><?php esc_html_e('How to use?', 'hbthemes'); ?></a>
					</h1>
				</div>
				<!-- END #hb-page-title -->

				<div class="clear"></div>

				<div id="hb-content">

					<p class="intro-description">
						<?php esc_html_e( 'Use this section to create sidebars that you can use on different pages. Populate sidebars with widgets in ', 'hbthemes' ); ?>
						<a href="<?php echo admin_url( 'widgets.php' ); ?>"><?php _e( 'Appearance > Widgets.', 'hbthemes' ); ?></a>
					</p>

					<table id="hb-sidebars-table" data-count="<?php echo $count; ?>" class="hb-status-table hb-sidebar-manager-table widefat" cellspacing="0">

						<thead>
							<tr>
								<th class="regular-field"><h2><?php esc_html_e( 'Sidebar Name', 'hbthemes' ); ?></h2></th>

								<th class="hide-on-mobile larger-field"><h2><?php esc_html_e( 'Description', 'hbthemes' ); ?></h2></th>

								<th class="hide-on-mobile smaller-field"><h2><?php esc_html_e( 'CSS Class', 'hbthemes' ); ?></h2></th>

								<th class="smaller-field"><h2><?php esc_html_e( 'Action', 'hbthemes' ); ?></h2></th>
							</tr>
						</thead>

						<tbody>
							<?php foreach( $sidebars as $sidebar ) { ?>
							<tr>
								<td class="regular-field"><?php echo $sidebar['name']; ?></td>
								<td class="hide-on-mobile larger-field"><?php echo $sidebar['description']; ?></td>
								<td class="hide-on-mobile smaller-field"><pre><?php echo '.' . strtolower( HB_Sidebar_Manager::name_to_class( $sidebar['name'] ) ); ?></pre></td>
								<td class="smaller-field"><a href="#" data-name="<?php echo $sidebar['name']; ?>" class="button button-secondary delete-sidebar-button"><?php esc_html_e( 'Delete', 'hbthemes' ); ?></a><div class="spinner"></div></td>
							</tr>
							<?php } ?>

							<?php echo $empty_td; ?>

						</tbody>
					</table>
					<!-- END table -->

					<a href="#" class="button button-primary" id="add-new-sidebar"><?php esc_html_e( 'Add New Sidebar', 'hbthemes' ); ?> </a>

					<?php if ( ! empty( $sidebars ) ) { ?>
						<a href="<?php echo admin_url( 'widgets.php' ); ?>" id="hb-manage-widgets" class="button button-secondary"><?php esc_html_e( 'Manage Widgets', 'hbthemes' ); ?> </a>
					<?php } else { ?>
						<a href="<?php echo admin_url( 'widgets.php' ); ?>" id="hb-manage-widgets" class="hidden button button-secondary"><?php esc_html_e( 'Manage Widgets', 'hbthemes' ); ?> </a>
					<?php } ?>
					
				</div>
				<!-- END #hb-content -->

			</div>
			<!-- END #hb-container -->

		</div>
		<!-- END #hb-page-wrapper -->
		<?php
	}

	public function export_sidebar_manager_settings() {
		
		header( 'Content-disposition: attachment; filename=sidebars.txt' );
		header( 'Content-Type: application/octet-stream; charset=' . get_option( 'blog_charset' ) );

		$export['register_sidebars'] 	= HB_Sidebar_Manager::get_sidebars();
		$export['sidebar_widgets'] 		= HB_Sidebar_Manager::get_sidebar_widgets();

		echo wp_json_encode( $export );
		
		die();
	}
}
new HB_Sidebar_Manager_Page();