<?php
/**
 * Admin class.
 *
 * This class ties together all admin classes.
 *
 * @package Highend
 * @since   3.7.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Highend_Admin' ) ) :

	/**
	 * Admin Class
	 */
	class Highend_Admin {

		/**
		 * Primary class constructor.
		 *
		 * @since 3.7.0
		 */
		public function __construct() {

			// Include admin files.
			$this->includes();

			// Load admin assets.
			add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );

			// Show post thumbnails in post list.
			add_filter( 'manage_posts_columns', array( $this, 'add_post_thumbnail_column' ), 5 );
			add_action( 'manage_posts_custom_column', array( $this, 'display_post_thumbnail_column' ), 5, 2 );

			// Add profile fields for social network links.
			add_action( 'show_user_profile', array( $this, 'show_extra_profile_fields' ) );
			add_action( 'edit_user_profile', array( $this, 'highend_show_extra_profile_fields' ) );
			add_action( 'personal_options_update', array( $this, 'save_extra_profile_fields' ) );
			add_action( 'edit_user_profile_update', array( $this, 'save_extra_profile_fields' ) );

			// After admin loaded.
			do_action( 'highend_admin_loaded' );
		}

		/**
		 * Includes files.
		 *
		 * @since 3.7.0
		 */
		private function includes() {
		}

		/**
		 * Load our required assets on admin pages.
		 *
		 * @since 3.7.0
		 * @param string $hook it holds the information about the current page.
		 */
		public function load_assets( $hook ) {

			// Admin styles.
			wp_enqueue_style(
				'highend_admin_style',
				HBTHEMES_URI . '/admin/assets/css/highend-admin.css',
				false,
				HB_THEME_VERSION,
				'all'
			);

			if ( false !== strpos( $hook, 'widgets' ) ) {
				wp_enqueue_script(
					'hb-admin-widgets-js',
					HBTHEMES_URI . '/admin/assets/js/admin-widgets.js',
					array( 'jquery', 'media-upload' ),
					HB_THEME_VERSION,
					true
				);
			}
		}

		/**
		 * Add a column for Featured image on admin post column.
		 *
		 * @since 3.5.0
		 */
		public function add_post_thumbnail_column( $columns ) {

			$columns['highend_post_thumb'] = esc_html__( 'Featured Image', 'hbthemes' );
			return $columns;
		}

		/**
		 * Display post thumbnails on admin post column.
		 *
		 * @since 3.5.0
		 */
		public function display_post_thumbnail_column( $column, $id ) {

			switch ( $column ) {
				case 'highend_post_thumb':
					if ( function_exists( 'the_post_thumbnail' ) ) {
						echo the_post_thumbnail( 'thumbnail' );
					}
					break;
			}
		}

		/**
		 * Display social network links fields in profile editor.
		 * 
		 * @since 3.5.0
		 */
		public function show_extra_profile_fields( $user ) {

			$networks = highend_get_social_networks_array();
			?>
			<h3><?php esc_html_e( 'Social Networking', 'hbthemes' ) ?></h3>
			<table class="form-table">
				<?php foreach ( $networks as $network_id => $network_name ) { ?>
					<tr>
						<th><label for="<?php echo esc_attr( $network_id ); ?>"><?php echo esc_html( $network_name ); ?></label></th>
						<td>
							<input type="text" name="<?php echo esc_attr( $network_id ); ?>" id="<?php echo esc_attr( $network_id ); ?>" value="<?php echo esc_attr( get_the_author_meta( $network_id , $user->ID ) ); ?>" class="regular-text" /><br/>
						</td>
					</tr>	
				<?php }	?>
			</table>
			<?php
		}

		/**
		 * Save user's social network links.
		 * 
		 * @since 3.5.0
		 */
		public function save_extra_profile_fields( $user_id ) {

			if ( ! current_user_can( 'edit_user', $user_id ) ) {
				return false;
			}
			
			$networks = highend_get_social_networks_array();
				
			foreach ( $networks as $network_id => $network_name ) {
				update_user_meta( $user_id, $network_id, sanitize_text_field( $_POST[ $network_id ] ) );
			}
		}
	}
endif;
