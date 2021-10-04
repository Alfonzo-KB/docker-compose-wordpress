<?php
/**
 * Highend Gallery Class.
 * 
 * @package Highend
 * @since   3.6.1
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Highend_Gallery' ) ) :

	/**
	 * Highend Gallery Class.
	 *
	 * @since 3.6.1
	 */
	final class Highend_Gallery {

		/**
		 * Singleton instance of the class.
		 *
		 * @since 3.6.1
		 * @var   object
		 */
		private static $instance;

		/**
		 * Main Highend_Gallery Instance.
		 *
		 * @since  3.6.1
		 * @return Highend_Gallery
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Highend_Gallery ) ) {
				self::$instance = new Highend_Gallery();
			}

			return self::$instance;
		}

		/**
		 * Primary class constructor.
		 *
		 * @since 3.6.1
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

			if ( highend_is_module_enabled( 'hb_module_gallery' ) ) {

				add_action( 'init', array( $this, 'register_post_type' ) );
				add_action( 'init', array( $this, 'register_taxonomy' ) );
				add_action( 'init', array( $this, 'register_metabox' ) );

				add_action( 'vc_before_init', array( $this, 'vc_map_shortcodes' ) );

				add_shortcode( 'gallery_fullwidth', array( $this, 'gallery_fullwidth_shortcode' ) );
				add_shortcode( 'gallery_carousel', array( $this, 'gallery_carousel_shortcode' ) );

				add_action( 'highend_gallery', array( $this, 'page_template' ) );
				add_action( 'highend_gallery_category_archive', array( $this, 'category_archive' ) );

				include( HBTHEMES_INCLUDES . '/gallery/gallery-widget.php' );

			} else {
				add_filter( 'theme_page_templates', array( $this, 'remove_page_templates' ) );

				add_shortcode( 'gallery_fullwidth', '__return_false' );
				add_shortcode( 'gallery_carousel', '__return_false' );
			}

			include( HBTHEMES_INCLUDES . '/gallery/gallery-functions.php' );
		}

		/**
		 * Register post type.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/register_post_type
		 * 
		 * @since  3.6.1
		 * @return void
		 */
		public function register_post_type() {

			$labels = apply_filters(
				'highend_gallery_post_type_labels',
				array(
					'name'               => esc_html__( 'Gallery', 'hbthemes' ),
					'all_items'          => esc_html__( 'All Gallery Items', 'hbthemes' ),
					'singular_name'      => esc_html__( 'Gallery Item', 'hbthemes' ),
					'add_new'            => esc_html__( 'Add New Gallery Item', 'hbthemes' ),
					'add_new_item'       => esc_html__( 'Add New Gallery Item', 'hbthemes' ),
					'edit_item'          => esc_html__( 'Edit Gallery Item', 'hbthemes' ),
					'new_item'           => esc_html__( 'New Gallery Item', 'hbthemes' ),
					'view_item'          => esc_html__( 'View Gallery Item', 'hbthemes' ),
					'search_items'       => esc_html__( 'Search For Gallery Items', 'hbthemes' ),
					'not_found'          => esc_html__( 'No Gallery Items found', 'hbthemes' ),
					'not_found_in_trash' => esc_html__( 'No Gallery Items found in Trash', 'hbthemes' ),
					'parent_item_colon'  => '',
				)
			);

			$args = apply_filters(
				'highend_gallery_post_type',
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
						'editor',
						'thumbnail',
						'page-attributes',
						'custom-fields',
						'comments',
					),
					'query_var'           => true,
					'exclude_from_search' => false,
					'show_in_nav_menus'   => true,
					'menu_icon'           => 'dashicons-format-gallery',
				)
			);

			register_post_type( 'gallery', $args );
		}

		/**
		 * Register custom taxonomies.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
		 *
		 * @since  3.6.1
		 * @return void
		 */
		public function register_taxonomy() {

			$labels = apply_filters(
				'highend_gallery_category_tax_labels',
				array(
					'name'                  => esc_html__( 'Gallery Categories', 'hbthemes' ),
					'singular_name'         => esc_html__( 'Gallery Category', 'hbthemes' ),
					'search_items'          => esc_html__( 'Search Gallery Categories', 'hbthemes' ),
					'all_items'             => esc_html__( 'All Gallery Categories', 'hbthemes' ),
					'parent_item'           => esc_html__( 'Parent Gallery Category', 'hbthemes' ),
					'parent_item_colon'     => esc_html__( 'Parent Gallery Category:', 'hbthemes' ),
					'edit_item'             => esc_html__( 'Edit Gallery Category', 'hbthemes' ),
					'update_item'           => esc_html__( 'Update Gallery Category', 'hbthemes' ),
					'add_new_item'          => esc_html__( 'Add New Gallery Category', 'hbthemes' ),
					'new_item_name'         => esc_html__( 'New Gallery Category Name', 'hbthemes' ),
					'choose_from_most_used'	=> esc_html__( 'Choose from the most used Gallery categories', 'hbthemes' )
				)
			);

			$args = apply_filters(
				'highend_gallery_category_tax_args',
				array(
					'hierarchical' => true,
					'labels'       => $labels,
					'query_var'    => true,
					'show_in_rest' => true,
					'rewrite'      => array(
						'slug' => apply_filters( 'highend_gallery_category_rewrite', 'gallery_category' ),
					)
				)
			);

			register_taxonomy( 'gallery_categories', 'gallery', $args );
		}

		/**
		 * Register metabox.
		 *
		 * @since  3.6.1
		 * @return void
		 */
		public function register_metabox() {

			$mb_path_fw_gallery_page_template_settings = HBTHEMES_ADMIN . '/metaboxes/meta-gallery-fw-page-settings.php';
			$mb_post_settings = new VP_Metabox(array(
				'id' => 'gallery_fw_page_settings',
				'types' => array(
					'page'
				),
				'title' => esc_html__('Fullwidth Gallery Template Settings', 'hbthemes'),
				'priority' => 'low',
				'is_dev_mode' => false,
				'template' => $mb_path_fw_gallery_page_template_settings
			));

			$mb_path_standard_gallery_page_template_settings = HBTHEMES_ADMIN . '/metaboxes/meta-gallery-standard-page-settings.php';
			$mb_post_settings = new VP_Metabox(array(
				'id' => 'gallery_standard_page_settings',
				'types' => array(
					'page'
				),
				'title' => esc_html__('Standard Gallery Template Settings', 'hbthemes'),
				'priority' => 'low',
				'is_dev_mode' => false,
				'template' => $mb_path_standard_gallery_page_template_settings
			));  

			$mb_path_gallery_settings = HBTHEMES_ADMIN . '/metaboxes/meta-gallery-settings.php';
			$mb_gallery_settings = new VP_Metabox(array(
				'id' => 'gallery_settings',
				'types' => array(
					'gallery',
				),
				'title' => esc_html__('Gallery Settings', 'hbthemes'),
				'priority' => 'low',
				'is_dev_mode' => false,
				'template' => $mb_path_gallery_settings
			));
		}

		/**
		 * Remove page template option.
		 *
		 * @since  3.6.1
		 * @param  array $templates Array of available page templates.
		 * @return array            Modified array of page templates.
		 */
		public function remove_page_templates( $templates ) {

			unset( $templates['page-templates/gallery-fullwidth.php'] );
			unset( $templates['page-templates/gallery-standard.php'] );

			return $templates;
		}

		/**
		 * Gallery Fullwidth shortcode.
		 * 
		 * @param  array  $params Shortcode params.
		 * @since  3.6.1
		 * @return void
		 */
		public function gallery_fullwidth_shortcode( $params = array() ) {

			$defaults = array(
				'count'           => '8',
				'columns'         => '2',
				'ratio'           => 'ratio1',
				'orientation'     => 'landscape',
				'category'        => '',
				'orderby'         => 'date',
				'order'           => 'DESC',
				'animation'       => '',
				'animation_delay' => '',
				'class'           => ''
			);

			extract( shortcode_atts( $defaults, $params ) );

			$output = '';

			$args = array(
				'post_type'      => 'gallery',
				'orderby'        => $orderby,
				'order'          => $order,
				'posts_per_page' => $count,
				'status'         => 'publish',
			);

			if ( $category ) {

				$category = str_replace( ' ', '', $category );
				$category = explode( ',', $category );

				$args['tax_query'] = array(
					array(
						'taxonomy' => 'gallery_categories',
						'field'    => 'slug',
						'terms'    => $category
					)
				);
			}

			query_posts( $args );

			if ( have_posts() ) :

				$wrapper_class = array( 'shortcode-wrapper', 'shortcode-portfolio-fullwidth', 'gallery-carousel-wrapper-2' );

				if ( ! empty( $class ) ) {
					$wrapper_class[] = $class;
				}

				if ( ! empty( $animation ) ) {
					$wrapper_class[] = 'hb-animate-element';
					$wrapper_class[] = 'hb-' . $animation;
				}

				if ( ! empty( $animation_delay ) ) {
					
					if ( 'ms' === substr( $animation_delay, -2 ) ) {
						$animation_delay = substr( $animation_delay, 0, -2 );
					} elseif ( 's' === substr( $animation_delay, -1 ) ) {
						$animation_delay = substr( $animation_delay, 0, -1 );
					}

					$animation_delay = ' data-delay="' . esc_attr( $animation_delay ) . '"';
				}

				$wrapper_class = trim( implode( ' ', $wrapper_class ) );

				$dimensions = highend_get_image_dimensions( $orientation, $ratio, 1000 );

				$output .= '<div class="' . esc_attr( $wrapper_class ) . '"' . $animation_delay . '>';
				$output .= '<div class="fw-section without-border light-text">';
				$output .= '<div class="fw-gallery-wrap loading columns-' . $columns . '">';

				while ( have_posts() ) : the_post();

					$output .= highend_gallery_fullwidth_item(
						array(
							'width'  => $dimensions['width'],
							'height' => $dimensions['height'],
						)
					);
					
				endwhile;

				$output .= '</div>';
				$output .= '</div>';
				$output .= '</div>';

			endif;

			wp_reset_query();

			return $output;
		}

		/**
		 * Gallery Carousel shortcode.
		 *
		 * @param  array  $params Shortcode params.
		 * @since  3.6.1
		 * @return void
		 */
		public function gallery_carousel_shortcode( $params = array() ) {
			extract(shortcode_atts(array(
				'style' => 'standard',
				'visible_items' => '2',
				'total_items' => '10',
				'category' => '',
				'orderby' => 'date',
				'order' => 'DESC',
				'carousel_speed' => '3000',
				'auto_rotate' => 'yes',
				'animation' => '',
				'animation_delay' => '',
				'class' => ''
			), $params));


			if ( $class != '' ){
				$class = ' ' . $class;
			}

			if ( $style != 'modern' ) $style = 'standard';
			
			if ( $auto_rotate == "no" ) 
				$auto_rotate = "false";
			else 
				$auto_rotate = "true";
			
			if ($animation != ''){
				$animation = ' hb-animate-element hb-' . $animation;
			}

			if ($animation_delay != ''){
				// Remove ms or s, if entered in the attribute
				if ( substr($animation_delay, -2) == 'ms' ){
					$animation_delay = substr($animation_delay, 0, -2);
				}

				if ( substr($animation_delay, -1) == 's' ){
					$animation_delay = substr($animation_delay, 0, -1);
				}

				$animation_delay = ' data-delay="' . $animation_delay . '"';
			}

			$output = "";

			if ( $category ) {
				$category = str_replace(" ", "", $category);
				$category = explode(",", $category);

				$queried_items = new WP_Query( array( 
						'post_type' => 'gallery',
						'orderby' => $orderby,
						'order' => $order,
						'status' => 'publish',
						'posts_per_page' => $total_items,
						'tax_query' => array(
								array(
									'taxonomy' => 'gallery_categories',
									'field' => 'slug',
									'terms' => $category
								)
							)			
				));
			} else {
				$queried_items = new WP_Query( array( 
						'post_type' => 'gallery',
						'orderby' => $orderby,
						'order' => $order,
						'posts_per_page' => $total_items,
						'status' => 'publish',
					));
			}
			$unique_id = rand(1,10000);

			if ( $queried_items->have_posts() ) :

			if ( $style == "standard" )
				$output .= '<div class="shortcode-wrapper shortcode-gallery-carousel gallery-carousel-wrapper-2' . $class . $animation . '"' . $animation_delay . '>';
			else 
				$output .= '<div class="shortcode-wrapper shortcode-gallery-carousel gallery-carousel-wrapper' . $class . $animation . '"' . $animation_delay . '>';

			// Carousel Nav
			$output .= '<div id="carousel-nav-' . $unique_id . '" class="crsl-nav">';
			$output .= '<a href="#" class="previous"><i class="icon-angle-left"></i></a>';
			$output .= '<a href="#" class="next"><i class="icon-angle-right"></i></a>';
			$output .= '</div>';

			// Carousel Items
			$output .= '<div class="crsl-items init-carousel" id="carousel-' . $unique_id . '" data-navigation="carousel-nav-' . $unique_id . '" data-visible="'.$visible_items.'" data-speed="'.$carousel_speed.'" data-auto-rotate="'.$auto_rotate.'">';
			$output .= '<div class="crsl-wrap">';

			while ( $queried_items->have_posts() ) : $queried_items->the_post();
				$thumb = get_post_thumbnail_id();
				$filters_names = wp_get_post_terms(get_the_ID() , 'gallery_categories' , array("fields"=>"names"));
				$filters_names_string = implode( ', ', $filters_names );
				$gallery_rel = "gal_rel_" . rand(1,10000);
				$custom_color = vp_metabox('gallery_settings.hb_gallery_custom_bg_color');
				$thumb_post = get_post( $thumb );
				if ( $thumb_post ) {
					$thumb_caption = $thumb_post->post_excerpt;
				} else {
					$thumb_caption = '';
				}

				if ($custom_color){
					$custom_color = ' style="background: ' . highend_hex2rgba($custom_color, 0.85) . ';"';
				} else {
					$custom_color = "";
				}

				if ( $style == "standard" )
					$output .= '<div class="standard-gallery-item crsl-item" data-value="' . get_the_time('c') . '">';
				else
					$output .= '<div class="gallery-item crsl-item" data-value="' . get_the_time('c') . '">';

				$image = highend_resize( $thumb, 586, 349 );
				$full_image = wp_get_attachment_image_src($thumb, 'full');
				$gallery_attachments = rwmb_meta('hb_gallery_images', array('type' => 'plupload_image', 'size' => 'full') , get_the_ID());
				$filters_names = wp_get_post_terms(get_the_ID() , 'gallery_categories' , array("fields"=>"names"));
				$filters_names_string = implode( ', ', $filters_names );
					
				if ( !$image['url'] && !empty($gallery_attachments))
				{
					reset($gallery_attachments);
					$thumb = key($gallery_attachments);
					$image = highend_resize( $thumb, 586, 349 );
					$full_image = wp_get_attachment_image_src($thumb,'full');
				}
				$gallery_count = count ($gallery_attachments ) + ( get_post_thumbnail_id() ? 1 : 0 );


				if ( $style == "standard" )
					$output .= '<div class="hb-gal-standard-img-wrapper item-has-overlay">';

				$output .= '<a href="' . $full_image[0] . '" data-title="' . $thumb_caption . '" rel="prettyPhoto[' . $gallery_rel . ']">';
				$output .= '<img src="' . $image['url'] . '" width="'. $image['width'] .'" height="'. $image['height'] .'" alt="' . get_post_meta($thumb, '_wp_attachment_image_alt', true ) . '" />';
				$output .= '<div class="item-overlay"></div>';
				$output .= '<div class="item-overlay-text"'.$custom_color.'>';
				$output .= '<div class="item-overlay-text-wrap">';

				if ( $style == "modern" ) {
					$output .= '<h4><span class="hb-gallery-item-name">' . get_the_title() . '</span></h4>';
					$output .= '<div class="hb-small-separator"></div>';
					$output .= '<span class="item-count-text"><span class="photo-count">' . $gallery_count . '</span>';
					if ( $gallery_count != 1) $output .= __(' Photos' ,'hbthemes');
					else $output .= __(' Photo','hbthemes');
					$output .= '</span>';
				} else {
					$output .= '<span class="plus-sign"></span>';
				}
					
				$output .= '</div>';

				if ( $style == "modern")
					$output .= '<div class="item-date" data-value="' . get_the_time('d F Y') . '">' . get_the_time('d M Y') . '</div>';

				$output .= '</div>';
				$output .= '</a>';

				if ( $style == "standard" )
					$output .= '</div>';
				

				if ( $style == "standard" ) {
					$output .= '<div class="hb-gal-standard-description">';
					$output .= '<h3><a><span class="hb-gallery-item-name">' . get_the_title() . '</span></a></h3>';
					$output .= '<div class="hb-small-separator"></div>';
					if ( $filters_names_string ) $output .= '<div class="hb-gal-standard-count">' . $filters_names_string . '</div>';			
					$output .= '</div>';
				}


				if ( !empty ( $gallery_attachments ) ) {
					$output .= '<div class="hb-reveal-gallery">';
					foreach ( $gallery_attachments as $gal_id => $gal_att ) {
						if ( $gal_id != $thumb )
							$output .= '<a href="' . $gal_att['url'] . '" data-title="' . $gal_att['description'] . '" rel="prettyPhoto[' . $gallery_rel . ']"></a>';
					}
					$output .= '</div>';
				}
				$output .= '</div>';



			endwhile;

			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';

			endif;

			wp_reset_query();
			
			return $output; 
		}

		/**
		 * Map gallery shortcodes to WPBakery.
		 * 
		 * @return void
		 */
		public function vc_map_shortcodes() {

			// Gallery Fullwidth shortcode.
			vc_map(
				array(
					'name'          => esc_html__( 'Gallery Fullwidth', 'hbthemes' ),
					'base'          => 'gallery_fullwidth',
					'icon'          => 'icon-gallery-fullwidth',
					'wrapper_class' => 'hb-wrapper-gallery-fullwidth',
					'category'      => esc_html__( 'Highend Shortcodes', 'hbthemes' ),
					'description'   => esc_html__( 'Fullwidth Gallery Section.', 'hbthemes' ),
					'params'        => array(
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Columns', 'hbthemes' ),
							'description' => esc_html__( 'Choose how many in how many columns to show your gallery items.', 'hbthemes' ),
							'param_name'  => 'columns',
							'admin_label' => true,
							'value'       => array(
								'2' => '2',
								'3' => '3',
								'4' => '4',
								'5' => '5',
								'6' => '6',
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Total Items', 'hbthemes' ),
							'description' => esc_html__( 'Choose how many gallery items to include in the section. To get all items enter -1.', 'hbthemes' ),
							'param_name'  => 'count',
							'admin_label' => true,
							'value'       => '8',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Category', 'hbthemes' ),
							'description' => __( 'Choose which gallery categories will be shown in the carousels. Enter category <strong>slugs</strong> and separate them with commas. Example: category-1, category-2.', 'hbthemes'),
							'param_name'  => 'category',
							'admin_label' => true,
							'value'       => '',
							
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Image Orientation', 'hbthemes' ),
							'description' => esc_html__( 'Choose orientation of the gallery thumbnails.', 'hbthemes' ),
							'param_name'  => 'orientation',
							'admin_label' => true,
							'value'       => array(
								esc_html__( 'Landscape', 'hbthemes' ) => 'landscape',
								esc_html__( 'Portrait', 'hbthemes' )  => 'portrait',
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Image Ratio', 'hbthemes' ),
							'description' => esc_html__( 'Choose ratio of the gallery thumbnails.', 'hbthemes' ),
							'param_name'  => 'ratio',
							'admin_label' => true,
							'value'       => array(
						       	'16:9' => 'ratio1',
								'4:3'  => 'ratio2',
								'3:2'  => 'ratio4',
								'3:1'  => 'ratio5',
								'1:1'  => 'ratio3',
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Order By', 'hbthemes' ),
							'description' => __( 'Choose in which order to show gallery items.<br/><small>Select an order from the list of possible orders.</small>.', 'hbthemes' ),
							'param_name'  => 'orderby',
							'value'       => array(
						       	esc_html__( 'Date', 'hbthemes' )          => 'date',
								esc_html__( 'Title', 'hbthemes' )         => 'title',
								esc_html__( 'Random', 'hbthemes' )        => 'rand',
								esc_html__( 'Comment Count', 'hbthemes' ) => 'comment_count',
								esc_html__( 'Menu Order', 'hbthemes' )    => 'menu_order',
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Order', 'hbthemes' ),
							'description' => esc_html__( 'Descending or Ascending order.', 'hbthemes'),
							'param_name'  => 'order',
							'value'       => array(
						       	esc_html__( 'Descending', 'hbthemes' ) => 'DESC',
								esc_html__( 'Ascending', 'hbthemes' )  => 'ASC',
							),
						),
						highend_get_vc_map_animation(),
						highend_get_vc_map_animation_delay(),
						highend_get_vc_map_class(),
				    ),
				)
			);

			// Gallery Carousel shortcode.
			vc_map(
				array(
					'name'          => esc_html__( 'Gallery Carousel', 'hbthemes' ),
					'base'          => 'gallery_carousel',
					'icon'          => 'icon-gallery-carousel',
					'wrapper_class' => 'hb-wrapper-gallery-carousel',
					'category'      => esc_html__( 'Highend Shortcodes', 'hbthemes' ),
					'description'   => esc_html__( 'Carousel with gallery items.', 'hbthemes' ),
					'params'	    => array(
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Style', 'hbthemes' ),
							'param_name'  => 'style',
							'admin_label' => true,
							'value'       => array(
								esc_html__( 'Standard', 'hbthemes' ) => 'standard',
								esc_html__( 'Modern', 'hbthemes')    => 'modern',
							),
							'description' => esc_html__( 'Choose how the gallery items are styled.', 'hbthemes' ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Visible items', 'hbthemes' ),
							'param_name'  => 'visible_items',
							'admin_label' => true,
							'value'       => array(
								esc_html__( '2', 'hbthemes') => '2',
								esc_html__( '3', 'hbthemes') => '3',
								esc_html__( '4', 'hbthemes') => '4',
								esc_html__( '5', 'hbthemes') => '5',
								esc_html__( '6', 'hbthemes') => '6',
								esc_html__( '7', 'hbthemes') => '7',
								esc_html__( '8', 'hbthemes') => '8',
							),
							'description' => esc_html__( 'Choose how many posts are visible at a time.', 'hbthemes' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Total Items', 'hbthemes' ),
							'param_name'  => 'total_items',
							'admin_label' => true,
							'value'       => '10',
							'description' => esc_html__( 'Choose how many gallery items to include in the carousel. To get all items enter -1.', 'hbthemes' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Speed', 'hbthemes' ),
							'param_name'  => 'carousel_speed',
							'admin_label' => true,
							'value'       => '3000',
							'description' => esc_html__( 'Specify the carousel speed in miliseconds, enter just a number. Example: 2000.', 'hbthemes' ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Auto Rotate?', 'hbthemes' ),
							'param_name'  => 'auto_rotate',
							'value'       => array(
								esc_html__( 'Enable', 'hbthemes' )  => 'yes',
								esc_html__( 'Disable', 'hbthemes' ) => 'no',
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Category', 'hbthemes' ),
							'param_name'  => 'category',
							'admin_label' => true,
							'value'       => '',
							'description' => __( 'Choose which gallery categories will be shown in the carousels. Enter category <strong>slugs</strong> and separate them with commas. Example: category-1, category-2</small>.', 'hbthemes' ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Order By', 'hbthemes' ),
							'param_name'  => 'orderby',
							'value'       => array(
								esc_html__( 'Date', 'hbthemes' )          => 'date',
								esc_html__( 'Title', 'hbthemes' )         => 'title',
								esc_html__( 'Random', 'hbthemes' )        => 'rand',
								esc_html__( 'Comment Count', 'hbthemes' ) => 'comment_count',
								esc_html__( 'Menu Order', 'hbthemes' )    => 'menu_order',
							),
							'description' => __( 'Choose in which order to show gallery items.<br/><small>Select an order from the list of possible orders.</small>.', 'hbthemes' ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Order', 'hbthemes' ),
							'param_name'  => 'order',
							'value'       => array(
								esc_html__( 'Descending', 'hbthemes' ) => 'DESC',
								esc_html__( 'Ascending', 'hbthemes' )  => 'ASC',
							),
							'description' => __( 'Descending or Ascending order.', 'hbthemes' ),
						),
						highend_get_vc_map_animation(),
						highend_get_vc_map_animation_delay(),
						highend_get_vc_map_class(),
					),
				)
			);
		}

		/**
		 * Gallery - Standard page template output.
		 * 
		 * @param  string $template Gallery template slug.
		 * @param  string $post_id  Current page ID.
		 * @since  3.6.0
		 * @return void
		 */
		public function page_template( $template = '', $post_id = '' ) {

			if ( 'gallery-standard' === $template ) {

				$orientation = vp_metabox( 'gallery_standard_page_settings.hb_gallery_orientation', 'landscape', $post_id );
				$ratio       = vp_metabox( 'gallery_standard_page_settings.hb_gallery_ratio', 'ratio1', $post_id );
				$dimensions  = highend_get_image_dimensions( $orientation, $ratio, 1000 );

				highend_gallery_standard_page_template(
					apply_filters(
						'highend_gallery_standard_page_args',
						array(
							'filter'  => vp_metabox( 'gallery_standard_page_settings.hb_gallery_filter', true, $post_id ),
							'sorter'  => vp_metabox( 'gallery_standard_page_settings.hb_gallery_sorter', true, $post_id ),
							'columns' => vp_metabox( 'gallery_standard_page_settings.hb_gallery_columns', 3, $post_id ),
							'items'   => array(
								'width'  => $dimensions['width'],
								'height' => $dimensions['height'],
							),
						),
						$post_id
					)
				);
			}
		}

		/**
		 * Archive content for gallery_categories.
		 *
		 * @since  3.6.0
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

			highend_gallery_standard_page_template(
				apply_filters(
					'highend_gallery_category_archive_args',
					array()
				)
			);
		}
	}
endif;

/**
 * The function which returns the one Highend_Gallery instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $highend_gallery = highend_gallery(); ?>
 *
 * @since  3.6.1
 * @return object
 */
function highend_gallery() {
	return Highend_Gallery::instance();
}

highend_gallery();
