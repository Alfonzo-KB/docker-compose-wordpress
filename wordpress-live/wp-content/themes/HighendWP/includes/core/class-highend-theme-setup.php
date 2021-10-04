<?php
/**
 * Highend Theme Setup Class.
 *
 * @package Highend
 * @since   3.7
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Highend_Theme_Setup' ) ) :

	/**
	 * Highend Theme Setup Class.
	 */
	class Highend_Theme_Setup {

		/**
		 * Singleton instance of the class.
		 *
		 * @since 3.7
		 * @var object
		 */
		private static $instance;

		/**
		 * Main Highend_Theme_Setup Instance.
		 *
		 * @since 3.7
		 * @return Highend_Theme_Setup
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Highend_Theme_Setup ) ) {
				self::$instance = new Highend_Theme_Setup();
			}
			return self::$instance;
		}

		/**
		 * Primary class constructor.
		 *
		 * @since 3.7
		 */
		public function __construct() {

			// Add theme supports.
			add_action( 'after_setup_theme', array( $this, 'setup' ), 10 );

			// Content width.
			add_action( 'wp', array( $this, 'content_width' ) );

			// Load widget classes.
			add_action( 'after_setup_theme', array( $this, 'load_widgets' ) );

			// Register custom post types.
			add_action( 'init', array( $this, 'register_post_types' ) );

			// Register custom taxonomies.
			add_action( 'init', array( $this, 'register_taxonomies' ) );

			// Register widget areas.
			add_action( 'widgets_init', array( $this, 'register_widget_areas' ) );

			// Unregister widgets.
			add_action( 'widgets_init', array( $this, 'unregister_widgets' ) );

			remove_filter( 'nav_menu_description', 'strip_tags' );
		}

		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 *
		 * @since 3.7
		 */
		public function setup() {

			// Load textdomain.
			load_theme_textdomain( 'hbthemes', HIGHEND_THEME_PATH . '/languages' );

			// Add default posts and comments RSS feed links to head.
			add_theme_support( 'automatic-feed-links' );

			// Add theme support for Post Thumbnails and image sizes.
			add_theme_support( 'post-thumbnails' );

			// Add title output.
			add_theme_support( 'title-tag' );

			// Add theme support for various Post Formats.
			add_theme_support(
				'post-formats',
				array(
					'gallery',
					'image',
					'quote',
					'video',
					'audio',
					'status',
					'link',
				)
			);

			// Add wide image support.
			add_theme_support( 'align-wide' );

			// Responsive embeds support.
			add_theme_support( 'responsive-embeds' );

			// Add support for WooCommerce.
			// @todo Move to woocommerce class.
			add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );

			// Register Navigations.
			register_nav_menus(
				array(
					'main-menu'     => esc_html__( 'Main Menu', 'hbthemes' ),
					'footer-menu'   => esc_html__( 'Footer Menu', 'hbthemes' ),
					'mobile-menu'   => esc_html__( 'Mobile Menu', 'hbthemes' ),
					'one-page-menu' => esc_html__( 'One Page Menu', 'hbthemes' ),
				)
			);
			
			// Remove support for widgets block edtior.
			remove_theme_support( 'widgets-block-editor' );

			global $themeoptions;

			if ( defined( 'WP_ADMIN' ) && WP_ADMIN ) {
				require_once HIGHEND_THEME_PATH . '/includes/tinymce/shortcode-popup.php';
			}

			do_action( 'highend_after_setup_theme' );
		}

		/**
		 * Set the content width in pixels, based on the theme's design and stylesheet.
		 *
		 * @global int $content_width
		 * @since 3.7
		 */
		public function content_width() {
			global $content_width;

			if ( ! isset( $content_width ) ) {
				
				if ( '940px' === highend_option( 'hb_content_width' ) ) {
					$content_width = 940;
				} else {
					$content_width = 1140;
				}

				$content_width = apply_filters( 'highend_content_width', $content_width );
			}
		}

		/**
		 * Register widget area.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
		 * @since 3.7
		 */
		public function register_widget_areas() {

			// Default Sidebar.
			register_sidebar(
				array(
					'name'          => esc_html__( 'Default Sidebar', 'hbthemes' ),
					'id'            => 'hb-default-sidebar',
					'description'   => esc_html__( 'This is a default sidebar for widgets. You can create unlimited sidebars in Highend > Sidebar Manager. You need to select this sidebar in page meta settings to display it.', 'hbthemes' ),
					'before_widget' => '<div id="%1$s" class="widget-item %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h4>',
					'after_title'   => '</h4>',
				)
			);

			// Side Panel Sidebar.
			register_sidebar(
				array(
					'name'          => esc_html__( 'Side Panel Section', 'hbthemes' ),
					'id'            => 'hb-side-section-sidebar',
					'description'   => esc_html__( 'Add your widgets for the side panel section here. Make sure you have enabled the offset side panel section option in Highend Options > Layout Settings > Header Settings.', 'hbthemes' ),
					'before_widget' => '<div id="%1$s" class="widget-item %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h4>',
					'after_title'   => '</h4>',
				)
			);

			// Sidebar Attributes.
			$sidebar_attr = array(
				'name'          => '',
				'description'   => __( 'This is an area for widgets. Drag and drop your widgets here.', 'hbthemes' ),
				'before_widget' => '<div id="%1$s" class="widget-item %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4>',
				'after_title'   => '</h4>',
			);

			$sidebar_id    = 0;
			$sidebar_names = array(
				'Footer 1',
				'Footer 2',
				'Footer 3',
				'Footer 4',
			);

			foreach ( $sidebar_names as $sidebar_name ) {
				$sidebar_attr['name'] = $sidebar_name;
				$sidebar_attr['id']   = 'custom-sidebar' . ( $sidebar_id++ );
				register_sidebar( $sidebar_attr );
			}
		}

		/**
		 * Load widgets.
		 *
		 * @since  3.7
		 * @return void
		 */
		public function load_widgets() {

			$widgets = apply_filters(
				'highend_widgets_array',
				array(
					'most-commented-posts',
					'latest-posts',
					'latest-posts-simple',
					'most-liked-posts',
					'recent-comments',
					'testimonials',
					'pinterest',
					'flickr',
					'dribbble',
					'google',
					'facebook',
					'contact-info',
					'social-icons',
					'gmap',
					'twitter',
					'portfolio',
					'portfolio-random',
					'most-liked-portfolio',
					'ads-300x250',
				)
			);

			if ( ! empty( $widgets ) ) {
				foreach ( $widgets as $widget_class_name ) {
					if ( file_exists( HIGHEND_THEME_PATH . '/includes/widgets/widget-' . $widget_class_name . '.php' ) ) {
						require_once HIGHEND_THEME_PATH . '/includes/widgets/widget-' . $widget_class_name . '.php';
					}
				}
			}
		}

		/**
		 * Unregister widgets for disabled modules.
		 *
		 * @since  3.7
		 * @return void
		 */
		public function unregister_widgets() {

			$widgets_to_unreg = array();

			if ( ! highend_is_module_enabled( 'hb_module_portfolio' ) ) {
				$widgets_to_unreg[] = 'HB_Liked_Portfolio_Widget';
				$widgets_to_unreg[] = 'HB_Portfolio_Widget_Rand';
				$widgets_to_unreg[] = 'HB_Portfolio_Widget';
			}

			if ( ! highend_is_module_enabled( 'hb_module_testimonials' ) ) {
				$widgets_to_unreg[] = 'HB_Testimonials_Widget';
			}

			foreach ( $widgets_to_unreg as $widget ) {
				unregister_widget( $widget );
			}
		}

		/**
		 * Register a custom post types.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/register_post_type
		 */
		public function register_post_types () {

			// Team member post type.
			if ( highend_is_module_enabled( 'hb_module_team_members' ) ) {
				register_post_type(
					'team',
					array(
						'labels'              => array(
							'name'               => esc_html__( 'Team Members', 'hbthemes'),
							'all_items'          => esc_html__( 'All Members', 'hbthemes' ),
							'singular_name'      => esc_html__( 'Team Member', 'hbthemes' ),
							'add_new'            => esc_html__( 'Add New', 'hbthemes' ),
							'add_new_item'       => esc_html__( 'Add New Team Member', 'hbthemes' ),
							'edit_item'          => esc_html__( 'Edit Team Member', 'hbthemes' ),
							'new_item'           => esc_html__( 'New Team Member', 'hbthemes' ),
							'view_item'          => esc_html__( 'View Team Member', 'hbthemes' ),
							'search_items'       => esc_html__( 'Search Team Members', 'hbthemes' ),
							'not_found'          => esc_html__( 'No team members have been added yet', 'hbthemes' ),
							'not_found_in_trash' => esc_html__( 'Nothing found in Trash', 'hbthemes' ),
							'parent_item_colon'  => '',
						),
						'public'              => true,
						'show_ui'             => true,
						'_builtin'            => false,
						'_edit_link'          => 'post.php?post=%d',
						'capability_type'     => 'post',
						'hierarchical'        => false,
						'menu_position'       => 100,
						'supports'            => array(
							'editor',
							'title', 
							'excerpt',
							'thumbnail',
							'page-attributes',
						),
						'query_var'           => true,
						'exclude_from_search' => false,
						'show_in_nav_menus'   => true,
						'menu_icon'           => 'dashicons-id',
					)
				);
			}

			// Client post type.
			if ( highend_is_module_enabled( 'hb_module_clients' ) ) {
				register_post_type(
					'clients',
					array(
						'labels'              => array(
							'name'               => esc_html__( 'Clients', 'hbthemes' ),
							'all_items'          => esc_html__( 'All Clients', 'hbthemes' ),
							'singular_name'      => esc_html__( 'Client', 'hbthemes' ),
							'add_new'            => esc_html__( 'Add New Client', 'hbthemes' ),
							'add_new_item'       => esc_html__( 'Add New Client', 'hbthemes' ),
							'edit_item'          => esc_html__( 'Edit Client', 'hbthemes' ),
							'new_item'           => esc_html__( 'New Client', 'hbthemes' ),
							'view_item'          => esc_html__( 'View Client', 'hbthemes' ),
							'search_items'       => esc_html__( 'Search For Clients', 'hbthemes' ),
							'not_found'          => esc_html__( 'No Clients found', 'hbthemes' ),
							'not_found_in_trash' => esc_html__( 'No Clients found in Trash', 'hbthemes' ),
							'parent_item_colon'  => '',
						),
						'public'              => true,
						'publicly_queryable'  => false,
						'show_ui'             => true,
						'_builtin'            => false,
						'_edit_link'          => 'post.php?post=%d',
						'capability_type'     => 'post',
						'hierarchical'        => false,
						'menu_position'       => 100,
						'supports'            => array(
							'title', 
							'page-attributes',
						),
						'query_var'           => true,
						'exclude_from_search' => true,
						'show_in_nav_menus'   => false,
						'menu_icon'           => 'dashicons-businessman',
					)
				);
			}

			// FAQ post type.
			if ( highend_is_module_enabled( 'hb_module_faq' ) ) {
				register_post_type(
					'faq',
					array(
						'labels'             => array (
							'name'               => esc_html__( 'FAQ', 'hbthemes' ),
							'all_items'          => esc_html__( 'All FAQ Items', 'hbthemes' ),
							'singular_name'      => esc_html__( 'FAQ Item', 'hbthemes' ),
							'add_new'            => esc_html__( 'Add New FAQ Item', 'hbthemes' ),
							'add_new_item'       => esc_html__( 'Add New FAQ Item', 'hbthemes' ),
							'edit_item'          => esc_html__( 'Edit FAQ Item', 'hbthemes' ),
							'new_item'           => esc_html__( 'New FAQ Item', 'hbthemes' ),
							'view_item'          => esc_html__( 'View FAQ Item', 'hbthemes' ),
							'search_items'       => esc_html__( 'Search For FAQ Items', 'hbthemes' ),
							'not_found'          => esc_html__( 'No FAQ Items found', 'hbthemes' ),
							'not_found_in_trash' => esc_html__( 'No FAQ Items found in Trash', 'hbthemes' ),
							'parent_item_colon'  => '',
						),
						'public'             => true,
						'show_ui'            => true,
						'_builtin'           => false,
						'_edit_link'         => 'post.php?post=%d',
						'capability_type'    => 'post',
						'hierarchical'       => false,
						'menu_position'      => 100,
						'supports'           => array(
							'title',
							'editor',
							'page-attributes',
							'custom-fields',
							'comments'
						),
						'query_var'           => true,
						'exclude_from_search' => false,
						'show_in_nav_menus'   => true,
						'menu_icon'           => 'dashicons-editor-help',
					)
				);
			}

			// Pricing table post type.
			if ( highend_is_module_enabled( 'hb_module_pricing_tables' ) ) {
				register_post_type(
					'hb_pricing_table',
					array(
						'labels'              => array(
							'name'               => esc_html__( 'Pricing Tables', 'hbthemes' ),
							'all_items'          => esc_html__( 'All Pricing Tables', 'hbthemes' ),
							'singular_name'      => esc_html__( 'Pricing Table' , 'hbthemes' ),
							'add_new'            => esc_html__( 'Add Pricing Table', 'hbthemes' ),
							'add_new_item'       => esc_html__( 'Add New Pricing Table', 'hbthemes' ),
							'edit_item'          => esc_html__( 'Edit Pricing Table', 'hbthemes' ),
							'new_item'           => esc_html__( 'New Pricing Table', 'hbthemes' ),
							'view_item'          => esc_html__( 'View Pricing Table', 'hbthemes' ),
							'search_items'       => esc_html__( 'Search For Pricing Tables', 'hbthemes' ),
							'not_found'          => esc_html__( 'No Pricing Tables found', 'hbthemes' ),
							'not_found_in_trash' => esc_html__( 'No Pricing Tables found in Trash', 'hbthemes' ),
							'parent_item_colon'  => '',
						),
						'public'              => true,
						'publicly_queryable'  => false,
						'show_ui'             => true,
						'_builtin'            => false,
						'_edit_link'          => 'post.php?post=%d',
						'capability_type'     => 'post',
						'hierarchical'        => false,
						'menu_position'       => 100,
						'supports'            => array(
							'title',  
							'page-attributes',
							'custom-fields',
						),
						'query_var'           => false,
						'exclude_from_search' => true,
						'show_in_nav_menus'   => false,
						'menu_icon'           => 'dashicons-tag',
					)
				);
			}

			// Testimonials post type.
			if ( highend_is_module_enabled( 'hb_module_testimonials' ) ) {
				register_post_type(
					'hb_testimonials',
					array(
						'labels'              => array(
							'name'               => esc_html__( 'Testimonials', 'hbthemes' ),
							'all_items'          => esc_html__( 'All Testimonials' , 'hbthemes' ),
							'singular_name'      => esc_html__( 'Testimonial', 'hbthemes' ),
							'add_new'            => esc_html__( 'Add Testimonial', 'hbthemes' ),
							'add_new_item'       => esc_html__( 'Add New Testimonial', 'hbthemes' ),
							'edit_item'          => esc_html__( 'Edit Testimonial', 'hbthemes' ),
							'new_item'           => esc_html__( 'New Testimonial', 'hbthemes' ),
							'view_item'          => esc_html__( 'View Testimonial', 'hbthemes' ),
							'search_items'       => esc_html__( 'Search For Testimonials', 'hbthemes' ),
							'not_found'          => esc_html__( 'No Testimonials found', 'hbthemes' ),
							'not_found_in_trash' => esc_html__( 'No Testimonials found in Trash', 'hbthemes' ),
							'parent_item_colon'  => '',
						),
						'public'              => true,
						'show_ui'             => true,
						'_builtin'            => false,
						'_edit_link'          => 'post.php?post=%d',
						'capability_type'     => 'post',
						'hierarchical'        => false,
						'menu_position'       => 100,
						'supports'            => array(
								'title',  
								'page-attributes',
								'custom-fields',
								'editor',
								'comments',
								),
						'query_var'           => false,
						'exclude_from_search' => true,
						'show_in_nav_menus'   => false,
						'menu_icon'           => 'dashicons-format-quote',
					)
				);
			}
		}

		/**
		 * Register custom taxonomies.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
		 */
		public function register_taxonomies() {
		 
		 	// FAQ Categories.
		 	if ( highend_is_module_enabled( 'hb_module_faq' ) ) {

				$faq_category_labels = array(
					'name'                  => esc_html__( 'FAQ Categories', 'hbthemes' ),
					'singular_name'         => esc_html__( 'FAQ Category', 'hbthemes' ),
					'search_items'          => esc_html__( 'Search FAQ Categories', 'hbthemes' ),
					'all_items'             => esc_html__( 'All FAQ Categories', 'hbthemes' ),
					'parent_item'           => esc_html__( 'Parent FAQ Category', 'hbthemes' ),
					'parent_item_colon'     => esc_html__( 'Parent FAQ Category:', 'hbthemes' ),
					'edit_item'             => esc_html__( 'Edit FAQ Category', 'hbthemes' ),
					'update_item'           => esc_html__( 'Update FAQ Category', 'hbthemes' ),
					'add_new_item'          => esc_html__( 'Add New FAQ Category', 'hbthemes' ),
					'new_item_name'         => esc_html__( 'New FAQ Category Name', 'hbthemes' ),
					'choose_from_most_used'	=> esc_html__( 'Choose from the most used FAQ categories', 'hbthemes' ),
				);

				register_taxonomy(
					'faq_categories',
					'faq',
					array(
						'hierarchical' => true,
						'labels'       => $faq_category_labels,
						'query_var'    => true,
						'show_in_rest' => true,
						'rewrite'      => array(
							'slug' => apply_filters( 'highend_faq_category_rewrite', 'faq_category' ),
						),
					)
				);
			}

			// Testimonial Categories.
			if ( highend_is_module_enabled( 'hb_module_testimonials' ) ) {

				$testimonial_category_labels = array(
					'name'                  => esc_html__( 'Testimonial Categories', 'hbthemes' ),
					'singular_name'         => esc_html__( 'Testimonial Category', 'hbthemes' ),
					'search_items'          => esc_html__( 'Search Testimonial Categories', 'hbthemes' ),
					'all_items'             => esc_html__( 'All Testimonial Categories', 'hbthemes' ),
					'parent_item'           => esc_html__( 'Parent Testimonial Category', 'hbthemes' ),
					'parent_item_colon'     => esc_html__( 'Parent Testimonial Category:', 'hbthemes' ),
					'edit_item'             => esc_html__( 'Edit Testimonial Category', 'hbthemes' ),
					'update_item'           => esc_html__( 'Update Testimonial Category', 'hbthemes' ),
					'add_new_item'          => esc_html__( 'Add New Testimonial Category', 'hbthemes' ),
					'new_item_name'         => esc_html__( 'New Testimonial Category Name', 'hbthemes' ),
					'choose_from_most_used'	=> esc_html__( 'Choose from the most used Testimonial categories', 'hbthemes' )
				);

				register_taxonomy(
					'testimonial_categories',
					'hb_testimonials',
					array(
						'hierarchical' => true,
						'labels'       => $testimonial_category_labels,
						'query_var'    => true,
						'show_in_rest' => true,
						'rewrite'      => array(
							'slug' => apply_filters( 'highend_testimonial_category_rewrite', 'testimonial_category' ),
						),
					)
				);
			}

			// Client Categories
			if ( highend_is_module_enabled( 'hb_module_clients' ) ) {

				$client_category_labels = array(
					'name'                  => esc_html__( 'Client Categories', 'hbthemes' ),
					'singular_name'         => esc_html__( 'Client Category', 'hbthemes' ),
					'search_items'          => esc_html__( 'Search Client Categories', 'hbthemes' ),
					'all_items'             => esc_html__( 'All Client Categories', 'hbthemes' ),
					'parent_item'           => esc_html__( 'Parent Client Category', 'hbthemes' ),
					'parent_item_colon'     => esc_html__( 'Parent Client Category:', 'hbthemes' ),
					'edit_item'             => esc_html__( 'Edit Client Category', 'hbthemes' ),
					'update_item'           => esc_html__( 'Update Client Category', 'hbthemes' ),
					'add_new_item'          => esc_html__( 'Add New Client Category', 'hbthemes' ),
					'new_item_name'         => esc_html__( 'New Client Category Name', 'hbthemes' ),
					'choose_from_most_used'	=> esc_html__( 'Choose from the most used Client categories', 'hbthemes' )
				);

				register_taxonomy(
					'client_categories',
					'clients',
					array(
						'hierarchical' => true,
						'labels'       => $client_category_labels,
						'query_var'    => true,
						'show_in_rest' => true,
						'rewrite'      => array(
							'slug' => apply_filters( 'highend_client_category_rewrite', 'client_category' ),
						),
					)
				);
			}

			// Team Member Categories.
			if ( highend_is_module_enabled( 'hb_module_team_members' ) ) {

				$team_member_category_labels = array(
					'name'                  => esc_html__( 'Team Categories', 'hbthemes' ),
					'singular_name'         => esc_html__( 'Team Member Category', 'hbthemes' ),
					'search_items'          => esc_html__( 'Search Team Member Categories', 'hbthemes' ),
					'all_items'             => esc_html__( 'All Team Member Categories', 'hbthemes' ),
					'parent_item'           => esc_html__( 'Parent Team Member Category', 'hbthemes' ),
					'parent_item_colon'     => esc_html__( 'Parent Team Member Category:', 'hbthemes' ),
					'edit_item'             => esc_html__( 'Edit Team Member Category', 'hbthemes' ),
					'update_item'           => esc_html__( 'Update Team Member Category', 'hbthemes' ),
					'add_new_item'          => esc_html__( 'Add New Team Member Category', 'hbthemes' ),
					'new_item_name'         => esc_html__( 'New Team Member Category Name', 'hbthemes' ),
					'choose_from_most_used'	=> esc_html__( 'Choose from the most used Team Member categories', 'hbthemes' )
				);

				register_taxonomy(
					'team_categories',
					'team',
					array(
						'hierarchical' => true,
						'labels'       => $team_member_category_labels,
						'query_var'    => true,
						'show_in_rest' => true,
						'rewrite'      => array(
							'slug' => apply_filters( 'highend_team_member_category_rewrite', 'team_member_category' ),
						),
					)
				);
			}
		}
	}
endif;

/**
 * The function which returns the one Highend_Theme_Setup instance.
 *
 * @since 3.7
 * @return object
 */
function highend_theme_setup() {
	return Highend_Theme_Setup::instance();
}

highend_theme_setup();
