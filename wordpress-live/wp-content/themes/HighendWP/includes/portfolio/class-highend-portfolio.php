<?php
/**
 * Highend Portfolio Class.
 * 
 * @package Highend
 * @since   3.6.5
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Highend_Portfolio' ) ) :

	/**
	 * Highend Portfolio Class.
	 *
	 * @since 3.6.5
	 */
	final class Highend_Portfolio {

		/**
		 * Singleton instance of the class.
		 *
		 * @since 3.6.5
		 * @var   object
		 */
		private static $instance;

		/**
		 * Main Highend_Portfolio Instance.
		 *
		 * @since  3.6.5
		 * @return Highend_Portfolio
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Highend_Portfolio ) ) {
				self::$instance = new Highend_Portfolio();
			}

			return self::$instance;
		}

		/**
		 * Primary class constructor.
		 *
		 * @since 3.6.5
		 */
		public function __construct() {
			add_action( 'after_setup_theme', array( $this, 'setup' ), 20 );
		}

		/**
		 * Setup.
		 * 
		 * @return [type] [description]
		 */
		public function setup() {
			if ( highend_is_module_enabled( 'hb_module_portfolio' ) ) {

				add_action( 'init', array( $this, 'register_post_type' ) );
				add_action( 'init', array( $this, 'register_taxonomy' ) );
				add_action( 'init', array( $this, 'register_metabox' ) );

				// add_action( 'vc_before_init', array( $this, 'vc_map_shortcodes' ) );

				// add_shortcode( 'gallery_fullwidth', array( $this, 'gallery_fullwidth_shortcode' ) );
				// add_shortcode( 'gallery_carousel', array( $this, 'gallery_carousel_shortcode' ) );

				add_action( 'highend_portfolio', array( $this, 'page_template' ) );
				add_action( 'highend_portfolio_category_archive', array( $this, 'category_archive' ) );

				add_action( 'highend_featured_section', array( $this, 'featured_section' ) );

				// include( HBTHEMES_INCLUDES . '/gallery/gallery-widget.php' );

			} else {
				add_filter( 'theme_page_templates', array( $this, 'remove_page_templates' ) );

				add_shortcode( 'portfolio_fullwidth', '__return_false' );
				add_shortcode( 'portfolio_carousel', '__return_false' );
			}

			include( HBTHEMES_INCLUDES . '/portfolio/portfolio-functions.php' );
		}

		/**
		 * Register post type.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/register_post_type
		 * 
		 * @since  3.6.5
		 * @return void
		 */
		public function register_post_type() {

			$labels = apply_filters(
				'highend_portfolio_post_type_labels',
				array(
					'name'               => esc_html__( 'Portfolio', 'hbthemes' ),
					'all_items'          => esc_html__( 'All Portfolio Items', 'hbthemes' ),
					'singular_name'      => esc_html__( 'Portfolio Item', 'hbthemes' ),
					'add_new'            => esc_html__( 'Add New Portfolio Item', 'hbthemes' ),
					'add_new_item'       => esc_html__( 'Add New Portfolio Item', 'hbthemes' ),
					'edit_item'          => esc_html__( 'Edit Portfolio Item', 'hbthemes' ),
					'new_item'           => esc_html__( 'New Portfolio Item', 'hbthemes' ),
					'view_item'          => esc_html__( 'View Portfolio Item', 'hbthemes' ),
					'search_items'       => esc_html__( 'Search For Portfolio Items', 'hbthemes' ),
					'not_found'          => esc_html__( 'No Portfolio Items found', 'hbthemes' ),
					'not_found_in_trash' => esc_html__( 'No Portfolio Items found in Trash', 'hbthemes' ),
					'parent_item_colon'  => '',
				)
			);

			$args = apply_filters(
				'highend_portfolio_post_type',
				array(
					'labels'              => $labels,
					'public'              => true,
					'show_ui'             => true,
					'_builtin'            => false,
					'_edit_link'          => 'post.php?post=%d',
					'capability_type'     => 'post',
					'hierarchical'        => false,
					'menu_position'       => 100,
					'supports'            => array(
						'title',
						'excerpt',
						'editor',
						'thumbnail',
						'page-attributes',
						'custom-fields',
						'comments',
					),
					'query_var'           => true,
					'exclude_from_search' => false,
					'show_in_nav_menus'   => true,
					'show_in_rest'        => true,
					'menu_icon'           => 'dashicons-portfolio',
				)
			);

			register_post_type( 'portfolio', $args );
		}

		/**
		 * Register custom taxonomies.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
		 *
		 * @since  3.6.5
		 * @return void
		 */
		public function register_taxonomy() {

			$labels = apply_filters(
				'highend_portfolio_category_tax_labels',
				array(
					'name'                  => esc_html__( 'Portfolio Categories', 'hbthemes' ),
					'singular_name'         => esc_html__( 'Portfolio Category', 'hbthemes' ),
					'search_items'          => esc_html__( 'Search Portfolio Categories', 'hbthemes' ),
					'all_items'             => esc_html__( 'All Portfolio Categories', 'hbthemes' ),
					'parent_item'           => esc_html__( 'Parent Portfolio Category', 'hbthemes' ),
					'parent_item_colon'     => esc_html__( 'Parent Portfolio Category:', 'hbthemes' ),
					'edit_item'             => esc_html__( 'Edit Portfolio Category', 'hbthemes' ),
					'update_item'           => esc_html__( 'Update Portfolio Category', 'hbthemes' ),
					'add_new_item'          => esc_html__( 'Add New Portfolio Category', 'hbthemes' ),
					'new_item_name'         => esc_html__( 'New Portfolio Category Name', 'hbthemes' ),
					'choose_from_most_used'	=> esc_html__( 'Choose from the most used Portfolio categories', 'hbthemes' )
				)
			);

			$args = apply_filters(
				'highend_portfolio_category_tax_args',
				array(
					'hierarchical' => true,
					'labels'       => $labels,
					'query_var'    => true,
					'show_in_rest' => true,
					'rewrite'      => array(
						'slug' => apply_filters( 'highend_portfolio_category_rewrite', 'portfolio_category' ),
					)
				)
			);

			register_taxonomy( 'portfolio_categories', 'portfolio', $args );
		}

		/**
		 * Register metabox.
		 *
		 * @since  3.6.5
		 * @return void
		 */
		public function register_metabox() {

			$mb_path_standard_portfolio_page_template_settings = HBTHEMES_ADMIN . '/metaboxes/meta-portfolio-standard-page-settings.php';
			$mb_post_settings = new VP_Metabox(array(
				'id' => 'portfolio_standard_page_settings',
				'types' => array(
					'page'
				),
				'title' => __('Portfolio Template Settings', 'hbthemes'),
				'priority' => 'low',
				'is_dev_mode' => false,
				'template' => $mb_path_standard_portfolio_page_template_settings
			));

			$mb_path_portfolio_settings                        = HBTHEMES_ADMIN . '/metaboxes/meta-portfolio-settings.php';
			$mb_post_settings = new VP_Metabox(array(
				'id' => 'portfolio_settings',
				'types' => array(
					'portfolio'
				),
				'title' => __('Portfolio Page Settings', 'hbthemes'),
				'priority' => 'low',
				'is_dev_mode' => false,
				'template' => $mb_path_portfolio_settings
			));

			$mb_path_portfolio_layout_settings                 = HBTHEMES_ADMIN . '/metaboxes/meta-portfolio-layout-settings.php';
			$mb_post_settings = new VP_Metabox(array(
				'id' => 'portfolio_layout_settings',
				'types' => array(
					'portfolio'
				),
				'title' => __('Portfolio Layout Settings', 'hbthemes'),
				'priority' => 'low',
				'is_dev_mode' => false,
				'context' => 'side',
				'template' => $mb_path_portfolio_layout_settings
			));
		}

		/**
		 * Remove page template option.
		 *
		 * @since  3.6.5
		 * @param  array $templates Array of available page templates.
		 * @return array            Modified array of page templates.
		 */
		public function remove_page_templates( $templates ) {

			unset( $templates['page-templates/portfolio-simple.php'] );
			unset( $templates['page-templates/portfolio-standard.php'] );

			return $templates;
		}

		/**
		 * Portfolio page template output.
		 * 
		 * @param  string $template Portfolio template slug.
		 * @param  string $post_id  Current page ID.
		 * @since  3.6.0
		 * @return void
		 */
		public function page_template( $template = '', $post_id = '' ) {

			$orientation = vp_metabox( 'portfolio_standard_page_settings.hb_gallery_orientation', 'landscape', $post_id );
			$ratio       = vp_metabox( 'portfolio_standard_page_settings.hb_gallery_ratio', 'ratio1', $post_id );
			$dimensions  = highend_get_image_dimensions( $orientation, $ratio, 1000 );

			$template_args = array(
				'filter'  => vp_metabox( 'portfolio_standard_page_settings.hb_gallery_filter', true, $post_id ),
				'sorter'  => vp_metabox( 'portfolio_standard_page_settings.hb_gallery_sorter', true, $post_id ),
				'columns' => vp_metabox( 'portfolio_standard_page_settings.hb_gallery_columns', 3, $post_id ),
				'items'   => array(
					'width'  => $dimensions['width'],
					'height' => $dimensions['height'],
				),
			);

			if ( 'portfolio-simple' === $template ) {

				highend_portfolio_simple_page_template(
					apply_filters(
						'highend_portfolio_simple_page_args',
						$template_args,
						$post_id
					)
				);
			} elseif ( 'portfolio-standard' === $template ) {

				highend_portfolio_standard_page_template(
					apply_filters(
						'highend_portfolio_standard_page_args',
						$template_args,
						$post_id
					)
				);
			}
		}

		/**
		 * Archive content for custom taxonomy..
		 *
		 * @since  3.6.5
		 * @return void
		 */
		public function category_archive() {

			// Get queried object.
			$term = get_queried_object();

			// Queried term description.
			if ( ! empty( $term->description ) ) {
				echo wp_kses_post(
					sprintf(
						'%s<div class="hb-separator extra-space"><div class="hb-fw-separator"></div></div>',
						$term->description
					)
				);
			}

			$orientation = highend_option( 'hb_portfolio_taxonomy_orientation' );
			$ratio       = highend_option( 'hb_portfolio_taxonomy_ratio' );
			$dimensions  = highend_get_image_dimensions( $orientation, $ratio, 1000 );

			highend_portfolio_simple_page_template(
				apply_filters(
					'highend_portfolio_category_archive_args',
					array(
						'filter'  => highend_option( 'hb_portfolio_taxonomy_filter', false ),
						'sorter'  => highend_option( 'hb_portfolio_taxonomy_sorter', false ),
						'columns' => highend_option( 'hb_portfolio_taxonomy_columns', 3 ),
						'items'   => array(
							'width'  => $dimensions['width'],
							'height' => $dimensions['height'],
						),
					)
				)
			);
		}

		/**
		 * Add featured section
		 *
		 * @since  3.6.5
		 * @return void
		 */
		public function featured_section() {

			if ( is_singular( 'portfolio' ) ) { 

				$post_id       = highend_get_the_id();
				$header_layout = vp_metabox( 'portfolio_layout_settings.hb_portfolio_header_layout', null, $post_id );

				if ( 'default' === $header_layout ) {
					$header_layout = highend_option( 'hb_portfolio_content_layout' );
				}

				if ( 'totalfullwidth' === $header_layout ) {
					get_template_part( 'includes/portfolio', 'featured-content' );
				}

			}
		}
	}
endif;

/**
 * The function which returns the one Highend_Portfolio instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $highend_portfolio = highend_portfolio(); ?>
 *
 * @since  3.6.5
 * @return object
 */
function highend_portfolio() {
	return Highend_Portfolio::instance();
}

highend_portfolio();
