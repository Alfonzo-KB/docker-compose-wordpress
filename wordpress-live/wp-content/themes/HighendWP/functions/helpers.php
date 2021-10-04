<?php
/**
 * Frontend helper functions used throught the theme.
 *
 * @package Highend
 * @since   3.5.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'highend_get_focus_color' ) ) :

	/**
	 * Return theme focus color.
	 * 
	 * @since 3.5.0
	 */
	function highend_get_focus_color() {

		return apply_filters( 'highend_focus_color', get_theme_mod( 'hb_focus_color_setting', '#1dc6df' ) );
	}
endif;

if ( ! function_exists( 'highend_get_user_social_links' ) ) :
	/**
	 * Retrieve user social network links from profile.
	 * 
	 * @since 3.5.0
	 */
	function highend_get_user_social_links( $user_id ) {

		$networks = highend_get_social_networks_array();
		$return   = array();
		
		foreach ( $networks as $network_id => $network_name ) {

			$return[ $network_id ] = array(
				'soc_name' => $network_name,
				'soc_link' => get_user_meta( $user_id, $network_id, true ),
			);
		}

		return $return;
	}
endif;

if ( ! function_exists( 'highend_get_image_dimensions' ) ) :
	/**
	 * Get image dimension based on orientation, ratio and width.
	 * 
	 * @since 3.5.0
	 */
	function highend_get_image_dimensions( $orientation = 'landscape', $ratio = 'ratio1', $width = 600 ) {

		$height = 0;

		switch ( $ratio ) {

			case 'ratio1':

				if ( 'portrait' === $orientation)
					$height = (int) (( $width / 9 ) * 16);
				else
					$height = (int) (( $width / 16 ) * 9);
				break;

			case 'ratio2':

				if ( 'portrait' === $orientation)
					$height = (int) (( $width / 3 ) * 4);
				else
					$height = (int) (( $width / 4 ) * 3);
				break;

			case 'ratio3';
				$height = (int) ( $width );
			break;

			case 'ratio4':
				if ( 'portrait' === $orientation )
					$height = (int) (( $width / 2 ) * 3);
				else
					$height = (int) (( $width / 3 ) * 2);
				break;

			case 'ratio5':
				if ( 'portrait' === $orientation )
					$height = (int) (( $width ) * 3);
				else
					$height = (int) (( $width / 3 ));
				break;
		}

		return array( 'width' => $width, 'height' => $height );
	}
endif;

if ( ! function_exists( 'highend_pagination_standard' ) ) :
	/**
	 * Display standard WP pagination.
	 * 
	 * @since 3.5.0
	 * @param boolean $echo Return or print pagination.
	 */
	function highend_pagination_standard( $echo = true ) {

		global $wp_query;

		$big    = 99999999;
		$output = '';

		$output .= '<div class="clear"></div>';
		$output .= '<div class="pagination">';

		$output .= paginate_links( 
			apply_filters( 'highend_paginate_links_args',
				array(
					'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format'    => '?paged=%#%',
					'total'     => $wp_query->max_num_pages,
					'current'   => max( 1, get_query_var('paged') ),
					'show_all'  => false,
					'end_size'  => 2,
					'mid_size'  => 1,
					'prev_next' => true,
					'prev_text' => '<i class="icon-angle-left"></i>',
					'next_text' => '<i class="icon-angle-right"></i>',
					'type'      => 'list'
				)
			)
		);

		$output .= '</div>';
		$output .= '<div class="clear"></div>';

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
endif;

if ( ! function_exists( 'highend_nl2p' ) ) :
	/**
	 * Convert line break (new line) to paragraphs.
	 *
	 * @since  3.5.0
	 * @param  string  $string      String to convert.
	 * @param  boolean $line_breaks Line breaks.
	 * @param  boolean $xml         XML.
	 */
	function highend_nl2p( $string, $line_breaks = false, $xml = true ) {

		// Remove existing HTML formatting to avoid double-wrapping things
		$string = str_replace( array( '<p>', '</p>', '<br>', '<br />' ), '', $string );
		
		// It is conceivable that people might still want single line-breaks without breaking into a new paragraph.
		if ( true === $line_breaks ) {
			return '<p>' . preg_replace( array( "/([\n]{2,})/i", "/([^>])\n([^<])/i"), array( "</p>\n<p>", '$1<br' . ( true === $xml ? ' /' : '' ) . '>$2' ), trim( $string ) ) . '</p>';
		} else {
			return '<p>' . preg_replace( array( "/([\n]{2,})/i", "/([\r\n]{3,})/i", "/([^>])\n([^<])/i" ), array( "</p>\n<p>", "</p>\n<p>", '$1<br' . ( true === $xml ? ' /' : '' ) . '>$2' ), trim( $string ) ) . '</p>'; 
		}
	}
endif;

if ( ! function_exists( 'highend_get_short_excerpt' ) ) :

	/**
	 * Get short excert by char count, but preserve whole words.
	 *
	 * @since  3.5.0
	 * @param  string  $text   Content.
	 * @param  boolean $length Max character count.
	 */
	function highend_get_short_excerpt( $text, $length ) {

		// Text is shorter than $length.
		if ( strlen( $text ) < $length ) {
			return $text;
		}

		$words  = explode( ' ', trim( $text ) );
		$return = null;

		if ( empty( $words ) ) {
			return;
		}

		foreach ( $words as $word ) {

			// Check if first work is longer than $length, if so, shorten and return it.
			if ( ( strlen( $word ) > $length ) && $return == null ) {
				return substr( $word, 0, $len ) . '...';
			}

			// Check length with next workd and if larger than $length, return.
			if ( ( strlen( $return ) + strlen( $word ) ) > $length ) {
				return $return . '...';
			}

			// Append word.
			$return .= ' ' . $word;
		}

		return $return;
	}
endif;

if ( ! function_exists( 'highend_map_json' ) ) :

	/**
	 * Get Google Map JSON.
	 *
	 * @since  3.5.0
	 */
	function highend_map_json() {

		$map = array();

		$map[1]['lat'] = highend_option( 'hb_map_1_latitude' );
		$map[1]['lng'] = highend_option( 'hb_map_1_longitude' );
		$map[1]['ibx'] = highend_option( 'hb_location_1_info' );

		$marker_count = apply_filters( 'highend_map_marker_number', 10 );

		for ( $i = 2; $i <= $marker_count; $i++ ){

			if ( highend_option( 'hb_enable_location_' . $i ) ) {
				$map[ $i ]['lat'] = highend_option( 'hb_map_' . $i . '_latitude' );
				$map[ $i ]['lng'] = highend_option( 'hb_map_' . $i . '_longitude' );
				$map[ $i ]['ibx'] = highend_option( 'hb_location_' . $i . '_info' );
			}
		}

		return $map;
	}
endif;

if ( ! function_exists( 'highend_get_post_format_icon' ) ) :

	/**
	 * Get post format icon
	 *
	 * @since  3.5.0
	 */
	function highend_get_post_format_icon( $format ) {

		$icons = array(
			'video'    => 'hb-moon-play-2',
			'status'   => 'hb-moon-pencil',
			'standard' => 'hb-moon-pencil',
			'gallery'  => 'hb-moon-image-3',
			'image'    => 'hb-moon-image-3',
			'audio'    => 'hb-mooon-music-2',
			'quote'    => 'hb-moon-quotes-right',
			'link'     => 'hb-moon-link-5',
		);

		$icon = isset( $icons[ $format ] ) ? $icons[ $format ] : 'hb-moon-file-3';

		return apply_filters( 'highend_post_format_icon', $icon, $format );	
	}
endif;

if ( ! function_exists( 'highend_is_seo_plugin_installed' ) ) :

	/**
	 * Check if SEO plugin is enabled.
	 *
	 * @since  3.5.0
	 */
	function highend_is_seo_plugin_installed() {
		return defined( 'WPSEO_VERSION' );
	}
endif;

if ( ! function_exists( 'highend_update_google_font_json' ) ) :

	/**
	 * Update Google fonts JSON.
	 *
	 * @since  3.5.0
	 */
	function highend_update_google_font_json( $api_key ) {

		$google = wp_remote_get( 'https://www.googleapis.com/webfonts/v1/webfonts?key=' . $api_key );

		if ( 200 !== wp_remote_retrieve_response_code( $google ) ) {
			return;
		}

		$google = wp_remote_retrieve_body( $google );
		$google = json_decode( $google, true );

		$new = array();
		foreach ( $google['items'] as $font ) {
			
			$new[ $font['family'] ]['weights']  = array();
			$new[ $font['family'] ]['styles'][] = 'normal';

			foreach ( $font['variants'] as $variant ) {

				if ( false !== strpos( $variant, 'italic' ) && ! in_array( 'italic', $new[ $font['family'] ]['styles'] ) ) {
					$new[ $font['family'] ]['styles'][] = 'italic';
				}

				$weight = str_replace( 'italic', '', $variant );
				$weight = str_replace( 'regular', 'normal', $weight );

				if ( $weight && ! in_array( $weight, $new[ $font['family'] ]['weights'] ) ) {
					$new[ $font['family'] ]['weights'][] = $weight;
				}
				
			}

			array_unique( $new[ $font['family'] ]['weights'] );
			array_unique( $new[ $font['family'] ]['styles'] );

			$new[ $font['family'] ]['subsets'] = $font['subsets'];
		}

		file_put_contents( HBTHEMES_ROOT . '/options-framework/data/gwf.json', json_encode( $new ) );
	}
endif;

if ( ! function_exists( 'highend_get_page_layout' ) ) :
	/**
	 * Get page sidebar layout.
	 *
	 * @since 3.5.1
	 */
	function highend_get_page_layout() {
		
		$layout = highend_option( 'hb_page_layout_sidebar' );

		if ( is_singular() ) {
			$singular_layout = trim( vp_metabox( 'layout_settings.hb_page_layout_sidebar' ) );

			if ( 'default' !== $singular_layout && '' !== $singular_layout ) {
				$layout = $singular_layout;
			}
		}

		return apply_filters( 'highend_page_layout', $layout );
	}
endif;

if ( ! function_exists( 'highend_get_site_layout' ) ) :
	/**
	 * Get site layout.
	 *
	 * @since 3.5.2
	 */
	function highend_get_site_layout() {
		
		$layout = highend_option( 'hb_global_layout' );

		if ( 'hb-boxed-layout' === vp_metabox( 'misc_settings.hb_boxed_stretched_page' ) ) {
			$layout = 'hb-boxed-layout';
		} elseif ( 'hb-stretched-layout' === vp_metabox( 'misc_settings.hb_boxed_stretched_page' ) ) {
			$layout = 'hb-stretched-layout';
		}

		if ( isset( $_GET['layout'] ) && 'boxed' === $_GET['layout'] ){
			$layout = 'hb-boxed-layout';
		}

		return apply_filters( 'highend_site_layout', $layout );
	}
endif;

if ( ! function_exists( 'highend_get_header_layout' ) ) :
	/**
	 * Get header layout.
	 *
	 * @since 3.5.2
	 */
	function highend_get_header_layout( $post_id = '' ) {
		
		$layout  = highend_option( 'hb_header_layout_style' );
		$post_id = $post_id ? $post_id : highend_get_the_id();

		if ( vp_metabox( 'misc_settings.hb_special_header_style', null, $post_id ) ) {
		    $layout = 'nav-type-1';
		}

		return apply_filters( 'highend_header_layout', $layout, $post_id );
	}
endif;

if ( ! function_exists( 'highend_get_header_container' ) ) :
	/**
	 * Get header container.
	 *
	 * @since 3.6.7
	 */
	function highend_get_header_container() {
		
		$container = highend_option( 'hb_main_header_container' );

		return apply_filters( 'highend_header_container', $container );
	}
endif;

if ( ! function_exists( 'highend_get_page_title_settings' ) ) :
	/**
	 * Get page title type.
	 *
	 * @since 3.6.5
	 * @param string $post_id Post ID
	 */
	function highend_get_page_title_settings( $post_id = '' ) {

		if ( empty( $post_id ) ) {
			$post_id = highend_get_the_id();
		}

		$keys = array(
			'type',
			'background-color',
			'background-image',
			'background-image-parallax',
			'animation',
			'subtitle-animation',
			'height',
			'style',
			'color',
			'alignment',
		);

		$settings = array();

		foreach ( $keys as $key ) {
			$settings[ $key ] = highend_option( 'hb_page_title_' . str_replace( '-', '_', $key ) );		
        }
		
		if ( $post_id && 'custom' === vp_metabox( 'general_settings.hb_page_title_option', null, $post_id ) ) {
			foreach ( $keys as $key ) {
				$settings[ $key ] = vp_metabox( 'general_settings.hb_title_settings_group.0.hb_page_title_' . str_replace( '-', '_', $key ), null, $post_id );
			}
		}

		if ( 'stroke-title' === $settings['style'] ) {
			$settings['color'] = '';
		}

		if ( ! empty( $settings['animation'] ) ) {
			$settings['animation'] = 'hb-animate-element hb-' . $settings['animation'];
		}

		if ( ! empty( $settings['subtitle-animation'] ) ) {
			$settings['subtitle-animation'] = 'hb-animate-element hb-' . $settings['subtitle-animation'];
		}

		return apply_filters( 'highend_page_title_settings', $settings, $post_id );
	}
endif;

if ( ! function_exists( 'highend_blog_class' ) ) :
	/**
	 * Classes for the main blog container div.
	 *
	 * @since 3.5.1
	 */
	function highend_blog_class( $template = '', $post_id = '' ) {

		$post_id = empty( $post_id ) ? highend_get_the_id() : $post_id;
		$classes = array();

		$classes[] = 'clearfix';

		if ( 'blog' === $template ) {
			$classes[] = 'hb-blog-classic';
			$classes[] = 'hb-blog-large';
		} elseif ( 'blog-small' === $template ) {
			$classes[] = 'hb-blog-classic';
			$classes[] = 'hb-blog-large';
			$classes[] = 'hb-blog-small';
		} elseif ( 'blog-minimal' === $template ) {
		} elseif ( 'blog-grid' === $template ) {

			$classes[] = 'hb-blog-grid';

			if ( $post_id && 'masonry' === vp_metabox( 'blog_grid_page_settings.hb_grid_style', 'masonry', $post_id ) ) {
				$classes[] = 'masonry-holder';
			}

			if ( $post_id ) {
				$classes[] = 'grid-columns-' . intval( vp_metabox( 'blog_grid_page_settings.hb_grid_columns', 3, $post_id ) );
			} elseif ( is_home() ) {
				$classes[] = 'grid-columns-' . intval( highend_option( 'hb_posts_page_grid_columns' ) );
			} elseif ( is_archive() ) {
				$classes[] = 'grid-columns-' . intval( highend_option( 'hb_archive_grid_columns' ) );
			} elseif ( is_search() ) {
				$classes[] = 'grid-columns-' . intval( highend_option( 'hb_search_grid_columns' ) );
			}

		} elseif ( 'blog-grid-fullwidth' === $template ) {

			$classes[] = 'hb-blog-grid';
			
			if ( $post_id && 'masonry' === vp_metabox( 'blog_fw_page_settings.hb_grid_style', 'masonry', $post_id ) ) {
				$classes[] = 'masonry-holder';
			}

			if ( $post_id ) {
				$classes[] = 'grid-columns-' . intval( vp_metabox( 'blog_fw_page_settings.hb_grid_columns', 3, $post_id ) );
			} elseif ( is_home() ) {
				$classes[] = 'grid-columns-' . intval( highend_option( 'hb_posts_page_grid_columns' ) );
			} elseif ( is_archive() ) {
				$classes[] = 'grid-columns-' . intval( highend_option( 'hb_archive_grid_columns' ) );
			} elseif ( is_search() ) {
				$classes[] = 'grid-columns-' . intval( highend_option( 'hb_search_grid_columns' ) );
			}
		}

		$classes = apply_filters( 'highend_blog_class', $classes, $template );
		$classes = trim( implode( ' ', $classes ) );

		echo esc_attr( $classes );
	}
endif;

if ( ! function_exists( 'highend_blog_attributes' ) ) :
	/**
	 * Attributes for the main blog container div.
	 *
	 * @since 3.5.1
	 */
	function highend_blog_attributes( $template, $post_id = '' ) {

		$post_id = empty( $post_id ) ? highend_get_the_id() : $post_id;

		$data = array();

		if ( 'blog-grid' === $template ) {
			
			if ( $post_id ) {
				$data['data-layout-mode'] = vp_metabox( 'blog_grid_page_settings.hb_grid_style', 'masonry', $post_id );
			} else {
				$data['data-layout-mode'] = 'masonry';
			}

		} elseif ( 'blog-grid-fullwidth' === $template ) {

			if ( $post_id ) {
				$data['data-layout-mode'] = vp_metabox( 'blog_fw_page_settings.hb_grid_style', 'masonry', $post_id );
			} else {
				$data['data-layout-mode'] = 'masonry';	
			}

		}

		$data = apply_filters( 'highend_blog_data', $data, $template, $post_id );
		
		if ( empty( $data ) ) {
			return;
		}

		$output = '';

		foreach ( $data as $key => $value ) {
			$output .= esc_html( $key ) . '="' . esc_attr( $value ) . '" ';
		}

		echo trim( $output );
	}
endif;

if ( ! function_exists( 'highend_main_content_style' ) ) :
	/**
	 * Main content div style tag.
	 *
	 * @since 3.5.1
	 */
	function highend_main_content_style( $post_id = '' ) {

		$styles = array();

		$content_bg_color = vp_metabox( 'background_settings.hb_content_background_color', '', $post_id );
		if ( ! empty( $content_bg_color ) ) {
			$styles['background-color'] = $content_bg_color; 
		}

		$styles = apply_filters( 'highed_main_content_style', $styles );
		$style  = '';

		if ( ! empty( $styles ) ) {
			foreach ( $styles as $key => $value ) {
				$style .= $key . ':' . $value . ';';
			}
		}
		
		$style = ! empty( $style ) ? ' style="' . $style . '"' : '';

		echo $style;
	}
endif;

if ( ! function_exists( 'highend_get_page_template' ) ) :
	/**
	 * Get page template slug/id.
	 *
	 * @since 3.5.1
	 */
	function highend_get_page_template( $post_id = '' ) {

		$template = 'blog'; //todo from highend options

		if ( ! empty( $post_id ) ) {
			
			$template = basename( get_page_template_slug( $post_id ), '.php' );

			if ( 'blog' === $template ) {
				$template = vp_metabox( 'blog_page_settings.hb_blog_style', '', $post_id );
				$template = 'blog-small' === $template ? 'blog-small' : 'blog';
			}

		} elseif ( is_search() ) {
			$template = highend_option( 'hb_search_template' );
		} elseif ( is_archive() ) {
			$template = highend_option( 'hb_archive_template' );
		} elseif ( is_home() ) {
			$template = highend_option( 'hb_posts_page_template' );
		}

		return apply_filters( 'highend_get_page_template', $template, $post_id );
	}
endif;

if ( ! function_exists( 'highend_get_blog_query_args' ) ) :
	/**
	 * Blog page query args.
	 *
	 * @since 3.5.1
	 */
	function highend_get_blog_query_args( $post_id = '' ) {

		if ( get_query_var('paged') ) {
		    $paged = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
		    $paged = get_query_var('page');
		} else {
		    $paged = 1;
		}

		$template = highend_get_page_template( $post_id );

		$orderby = $order = $category__in = '';

		if ( 'blog' === $template || 'blog-small' === $template ) {
			$orderby      = vp_metabox( 'blog_page_settings.hb_query_orderby', 'date', $post_id );
			$order        = vp_metabox( 'blog_page_settings.hb_query_order', 'DESC', $post_id );
			$category__in = vp_metabox( 'blog_page_settings.hb_blog_category_include', '', $post_id );
		} elseif ( 'blog-minimal' === $template ) {
			$orderby      = vp_metabox( 'blog_page_minimal_settings.hb_query_orderby', 'date', $post_id );
			$order        = vp_metabox( 'blog_page_minimal_settings.hb_query_order', 'DESC', $post_id );
			$category__in = vp_metabox( 'blog_page_minimal_settings.hb_blog_category_include', '', $post_id );
		} elseif ( 'blog-grid' === $template ) {
			$orderby      = vp_metabox( 'blog_grid_page_settings.hb_query_orderby', 'date', $post_id );
			$order        = vp_metabox( 'blog_grid_page_settings.hb_query_order', 'DESC', $post_id );
			$category__in = vp_metabox( 'blog_grid_page_settings.hb_blog_category_include', '', $post_id );
		} elseif ( 'blog-grid-fullwidth' === $template ) {
			$orderby      = vp_metabox( 'blog_fw_page_settings.hb_query_orderby', 'date', $post_id );
			$order        = vp_metabox( 'blog_fw_page_settings.hb_query_orderby.hb_query_order', 'DESC', $post_id );
			$category__in = vp_metabox( 'blog_fw_page_settings.hb_blog_category_include', '', $post_id );
		}

		return array(
			'post_type'      => 'post',
			'paged'          => $paged,
			'posts_per_page' => get_option('posts_per_page'),
			'orderby'        => $orderby,
			'order'          => $order,
			'category__in'   => $category__in,
			'post_status'    => 'publish',
		);
	}
endif;

if ( ! function_exists( 'highend_is_top_bar_displayed' ) ) :
	/**
	 * Conditional check for top bar.
	 *
	 * @since 3.5.3
	 */
	function highend_is_top_bar_displayed( $post_id = '' ) {

		$post_id = empty( $post_id ) ? highend_get_the_id() : false;

		$displayed = highend_option( 'hb_top_header_bar' );

		if ( $post_id && is_singular() ) {

			$top_bar_meta_option = vp_metabox( 'layout_settings.hb_header_widgets', null, $post_id );

			if ( empty( $top_bar_meta_option ) && vp_metabox( 'misc_settings.hb_special_header_style', null, $post_id ) ) {
				$displayed = false;
			}

			if ( 'show' === $top_bar_meta_option ) {
				$displayed = true;
			} elseif ( 'hide' === $top_bar_meta_option ) {
				$displayed = false;
			}
		}

		if ( highend_is_page_template( 'presentation-fullwidth' ) ) {
			$displayed = false;
		}

		return apply_filters( 'highend_is_top_bar_displayed', $displayed, $post_id );
	}
endif;

if ( ! function_exists( 'highend_is_page_title_displayed' ) ) :
	/**
	 * Conditional check for page title.
	 *
	 * @since 3.6.3
	 */
	function highend_is_page_title_displayed( $post_id = '' ) {

		$post_id = empty( $post_id ) ? highend_get_the_id() : false;

		$displayed = true;

		$settings = highend_get_page_title_settings();

		if ( 'none' === $settings['type'] ) {
			$displayed = false;
		}

		if ( 'left-panel' === highend_get_header_layout() ) {
			$displayed = false;
		}

		if ( is_singular() ) {

			if ( vp_metabox( 'misc_settings.hb_special_header_style', null, $post_id ) ) {
				$displayed = false;
			}

			if ( highend_is_page_template( 'presentation-fullwidth' ) ) {
				$displayed = false;
			}

			if ( 'custom' === vp_metabox( 'general_settings.hb_page_title_option', null, $post_id ) ) {
				$type = vp_metabox( 'general_settings.hb_title_settings_group.0.hb_page_title_type', null, $post_id );
			}

		} elseif ( is_home() ) {
			$displayed = false;
		} elseif ( is_404() ) {
			$displayed = false;
		}

		if ( isset( $type ) && 'none' === $type ) {
			$displayed = false;
		} 

		return apply_filters( 'highend_is_page_title_displayed', $displayed, $post_id );
	}
endif;

if ( ! function_exists( 'highend_get_top_bar_widgets' ) ) :
	/**
	 * Get array of top bar widgets.
	 *
	 * @since 3.5.3
	 */
	function highend_get_top_bar_widgets() {

		$widgets = array(
			'left' => array(
				'info',
				'map',
				'email',
			),
			'right' => array(
				'socials',
				'custom_link',
				'languages',
				'cart',
				'login',
			),
		);

		return apply_filters( 'highend_top_bar_widgets', $widgets );
	}
endif;

if ( ! function_exists( 'highend_is_footer_widgets_displayed' ) ) :
	/**
	 * Conditional check for footer.
	 *
	 * @since 3.5.2
	 */
	function highend_is_footer_widgets_displayed() {

		$displayed = highend_option( 'hb_enable_footer_widgets' );

		// Individual post/page setting.
		if ( is_singular() ) {
			if ( 'show' === vp_metabox( 'layout_settings.hb_footer_widgets' ) ) {
				$displayed = true;
			} elseif ( 'hide' === vp_metabox( 'layout_settings.hb_footer_widgets' ) ) {
				$displayed = false;
			}
		}

		return apply_filters( 'highend_is_footer_widgets_displayed', $displayed );
	}
endif;

if ( ! function_exists( 'highend_is_copyright_displayed' ) ) :
	/**
	 * Conditional check for copyright.
	 *
	 * @since 3.5.2
	 */
	function highend_is_copyright_displayed() {

		$displayed = highend_option( 'hb_enable_footer_copyright' );

		return apply_filters( 'highend_is_copyright_displayed', $displayed );
	}
endif;

if ( ! function_exists( 'highend_is_pre_footer_displayed' ) ) :
	/**
	 * Conditional check for pre footer.
	 *
	 * @since 3.5.2
	 */
	function highend_is_pre_footer_displayed() {

		// Default setting from options panel.
		$displayed = highend_option( 'hb_enable_pre_footer_area' );

		// Individual post/page setting.
		if ( is_singular() ) {
			if ( 'show' === vp_metabox( 'layout_settings.hb_pre_footer_callout' ) ) {
				$displayed = true;
			} elseif ( 'hide' === vp_metabox( 'layout_settings.hb_pre_footer_callout' ) ) {
				$displayed = false;
			}
		}

		return apply_filters( 'highend_is_pre_footer_displayed', $displayed );
	}
endif;

if ( ! function_exists( 'highend_footer_widget_column_class' ) ) :
	/**
	 * Get footer widget column class.
	 *
	 * @since 3.5.2
	 */
	function highend_footer_widget_column_class( $style, $column ) {
		
		$classes = array(
			'style-1'  => array(
				'1' => 'col-3',
				'2' => 'col-3',
				'3' => 'col-3',
				'4' => 'col-3'
			),
			'style-2'  => array(
				'1' => 'col-3',
				'2' => 'col-3',
				'3' => 'col-6',
				'4' => 'hidden'
			),
			'style-3'  => array(
				'1' => 'col-6',
				'2' => 'col-3',
				'3' => 'col-3',
				'4' => 'hidden'
			),
			'style-4'  => array(
				'1' => 'col-3',
				'2' => 'col-6',
				'3' => 'col-3',
				'4' => 'hidden'
			),
			'style-5'  => array(
				'1' => 'col-4',
				'2' => 'col-4',
				'3' => 'col-4',
				'4' => 'hidden'
			),
			'style-6'  => array(
				'1' => 'col-8',
				'2' => 'col-4',
				'3' => 'hidden',
				'4' => 'hidden'
			),
			'style-7'  => array(
				'1' => 'col-4',
				'2' => 'col-8',
				'3' => 'hidden',
				'4' => 'hidden'
			),
			'style-8'  => array(
				'1'=>'col-6',
				'2'=>'col-6',
				'3'=>'hidden',
				'4'=>'hidden'
			),
			'style-9'  => array(
				'1'=>'col-3',
				'2'=>'col-9',
				'3'=>'hidden',
				'4'=>'hidden'
			),
			'style-10' => array(
				'1' => 'col-9',
				'2' => 'col-3',
				'3' => 'hidden',
				'4' => 'hidden'
			),
			'style-11' => array(
				'1' => 'col-12',
				'2' => 'hidden',
				'3' => 'hidden',
				'4' => 'hidden'
			),
		);

		$classes = apply_filters( 'highend_footer_widget_layout_classes', $classes );

		if ( isset( $classes[ $style ][ $column ] ) ) {
			return $classes[ $style ][ $column ];
		}
	}
endif;

if ( ! function_exists( 'highend_is_page_template' ) ) :
	/**
	 * Check if is page template.
	 *
	 * @since 3.6.0
	 */
	function highend_is_page_template( $template ) {

		if ( is_array( $template ) ) {
			foreach ( $template as $slug ) {
				if ( is_page_template( 'page-templates/' . $slug . '.php' ) ) {
					return true;
				}
			}
		} else {
			return is_page_template( 'page-templates/' . $template . '.php' );
		}
	}
endif;

if ( ! function_exists( 'highend_comments_open' ) ) :
	/** Check if comments are open */
	function highend_comments_open( $post_id = null ) {
		$comments_open = comments_open();

		if ( 'post' === get_post_type( $post_id ) ) {
			$comments_open = $comments_open && highend_option( 'hb_blog_enable_comments' );
		}

		return apply_filters( 'highend_comments_open', $comments_open );
	}
endif;

if ( ! function_exists( 'highend_get_main_navigation_theme_location' ) ) :
	/**
	 * Get Main Navigation theme location.
	 *
	 * @since 3.6.7
	 */
	function highend_get_main_navigation_theme_location( $post_id = '' ) {

		$post_id  = $post_id ? $post_id : highend_get_the_id();
		$location = 'main-menu';

		if ( vp_metabox( 'misc_settings.hb_onepage', null, $post_id ) && has_nav_menu( 'one-page-menu' ) ) {
			$location = 'one-page-menu';
		}

		return apply_filters( 'highend_main_navigation_theme_location', $location, $post_id );
	}
endif;

/**
 * Adds custom classes to the array of body classes.
 *
 * @since 3.5.2
 * @param array $classes Classes for the body element.
 * @return array
 */
function highend_body_classes( $classes ) {

	// Site Layout.
	$classes[] = highend_get_site_layout();

	// Extra body class.
	if ( vp_metabox('misc_settings.hb_page_extra_class') ) {
		$classes[] = vp_metabox( 'misc_settings.hb_page_extra_class' );
	}

	// Preloader class.
	if ( 'ytube-like' === highend_option( 'hb_queryloader' ) ){
		$classes[] = 'hb-preloader';
	}

	// Special header style.
	if ( is_singular() && vp_metabox( 'misc_settings.hb_special_header_style' ) ) {
		$classes[] = 'hb-special-header-style';
	}

	// Header layout - Left panel.
	if ( 'left-panel' === highend_get_header_layout() ) {
		$classes[] = 'hb-side-navigation';
	}

	// Side section.
	if ( highend_option( 'hb_side_section' ) ) {
		$classes[] = 'has-side-section';
	}

	// Transparent side menu.
	if ( 'hb-side-transparent' === highend_option( 'hb_side_nav_style' ) ) {
		$classes[] = 'transparent-side-navigation';
	}

	// Animation for side menu.
	if ( highend_option( 'hb_side_nav_with_animation' ) ) {
		$classes[] = 'side-navigation-with-animation';
	}

	// Alternative sidebar.
	if ( 'hb-alt-sidebar' === highend_option( 'hb_sidebar_style' ) ) {
		$classes[] = 'hb-alt-sidebar';
	}

	// Sidebar size.
	if ( 'hb-sidebar-20' === highend_option( 'hb_sidebar_size' ) ) {
		$classes[] = 'hb-sidebar-20';
	}

	// Check if modern search is enabled.
	if ( 'hb-modern-search' === highend_option( 'hb_search_style' ) ) {
		$classes[] = 'hb-modern-search';
	}

	// Prettyphoto class.
	if ( highend_is_module_enabled( 'hb_module_prettyphoto' ) ) {
		$classes[] = 'highend-prettyphoto';
	} else {
		$classes[] = 'disable-native-prettyphoto';
	}

	// Fixed Footer.
	if ( highend_option( 'hb_fixed_footer_effect' ) ) {
		$classes[] = 'hb-fixed-footer';
	}
	
	return $classes;
};
add_filter( 'body_class', 'highend_body_classes' );

/**
 * Classes for the main wrapper.
 *
 * @since 3.5.2
 * @param array $classes Classes for the body element.
 * @return array
 */
function highend_main_wrapper_class( $class = array() ) {

	$classes = array();

	// One page.
	if ( vp_metabox( 'misc_settings.hb_onepage' ) ) { 
		$classes[] = 'hb-one-page';
	}

	// Site layout.
	$site_layout = highend_get_site_layout();

	$classes[] = $site_layout;

	// Boxed site layout additional classes.
	if ( 'hb-boxed-layout' === $site_layout ) {

		// Boxed type.
		$classes[] = highend_option( 'hb_boxed_layout_type' );

		// Boxed with shadow.
		if ( highend_option( 'hb_boxed_shadow' ) ){
			$classes[] = 'with-shadow';
		}
	}

	// Content width.
	if ( '940px' === highend_option( 'hb_content_width' ) ) {
		$classes[] = 'width-940';
	} elseif ( 'fw-100' === highend_option( 'hb_content_width' ) ) {
		$classes[] = 'fw-100';
	} else {
		$classes[] = 'width-1140';
	}

	// Sticky Woo cart.
	if ( highend_option( 'hb_enable_sticky_shop_button' ) && class_exists( 'Woocommerce' ) ) {
		$classes[] = 'with-shop-button';
	}

	// Header layout.
	$classes[] = highend_get_header_layout();
	
	$classes = array_unique( $classes + $class );
	$classes = apply_filters( 'highend_main_wrapper_class', $classes );

	if ( ! empty( $classes ) ) {
		$classes = trim( implode( ' ', $classes ) );

		echo 'class="' . esc_attr( $classes ) . '"';
	}
};

/**
 * Classes for the header.
 *
 * @since 3.5.2
 * @param array $classes Classes for the header.
 * @return array
 */
function highend_header_class( $class = array() ) {

	$classes = array();

	$classes = array_unique( $classes + $class );
	$classes = apply_filters( 'highend_header_class', $classes );

	if ( ! empty( $classes ) ) {
		$classes = trim( implode( ' ', $classes ) );

		echo 'class="' . esc_attr( $classes ) . '"';
	}
}

/**
 * Classes for the naub navigation.
 *
 * @since 3.6.7
 * @param array $classes Classes for the header.
 * @return array
 */
function highend_main_navigation_class( $class = array() ) {

	$classes = array( 'main-navigation' );

	$classes[] = highend_option( 'hb_header_layout_skin' );
	$classes[] = highend_option( 'hb_navigation_animation' );
	$classes[] = highend_option( 'hb_main_navigation_color' );

	$classes = array_unique( $classes + $class );
	$classes = apply_filters( 'highend_main_navigation_class', $classes );

	if ( ! empty( $classes ) ) {
		$classes = trim( implode( ' ', $classes ) );

		echo 'class="' . esc_attr( $classes ) . '"';
	}
}

/**
 * Classes for the side navigation header layout.
 *
 * @since 3.6.6
 * @param array $classes Classes for the header.
 * @return array
 */
function highend_side_navigation_class( $class = array() ) {

	$classes = array();

	if ( 'hb-side-light' === highend_option( 'hb_side_color_style' ) ) {
		$classes[] = 'hb-light-style';
	}

	if ( 'hb-side-standard' === highend_option( 'hb_side_nav_style' ) ) {
		$classes[] = 'hb-non-transparent';
	} elseif ( 'hb-side-transparent' === highend_option( 'hb_side_nav_style' ) ) {
		$classes[] = 'hb-transparent';
	}

	$alignment = highend_option( 'hb_side_nav_align' );
	$classes[] = 'hb-text-' . str_replace( 'hb-side-alignment-', '', trim( $alignment ) );

	$classes = array_unique( $classes + $class );
	$classes = apply_filters( 'highend_side_navigation_class', $classes );

	if ( ! empty( $classes ) ) {
		$classes = trim( implode( ' ', $classes ) );

		echo 'class="' . esc_attr( $classes ) . '"';
	}
}

/**
 * Classes for the page title bar.
 *
 * @since 3.6.5
 * @param array $classes Classes for the page title.
 * @return array
 */
function highend_page_title_class( $class = array() ) {

	$classes = array();

	$settings = highend_get_page_title_settings();

	$classes[] = $settings['type'];
	$classes[] = $settings['height'];

	if ( 'hb-image-background' === $settings['type'] && $settings['background-image-parallax'] ) {
		$classes[] = 'parallax';
	}

	if ( ! empty( $settings['style'] ) ) {
		$classes[] = $settings['style'];
	}

	if ( ! empty( $settings['color'] ) ) {
		$classes[] = $settings['color'];
	}

	if ( ! empty( $settings['alignment'] ) ) {
		$classes[] = $settings['alignment'];
	}

	$classes = array_unique( $classes + $class );
	$classes = apply_filters( 'highend_page_title_class', $classes );

	if ( ! empty( $classes ) ) {
		$classes = trim( implode( ' ', $classes ) );

		echo 'class="' . esc_attr( $classes ) . '"';
	}
}

function highend_page_title_style( $post_id = '' ) {
	
	if ( empty( $post_id ) ) {
		$post_id = highend_get_the_id();
	}

	$settings = highend_get_page_title_settings( $post_id );
	$style    = '';

	if ( 'hb-color-background' === $settings['type'] ) {
		$style = 'background-color: ' . $settings['background-color'] . ';';
	} elseif ( 'hb-image-background' === $settings['type'] ) {
		$style = 'background-image: url(' . $settings['background-image'] . ');';
	}

	$style = apply_filters( 'highend_page_title_style', $style, $post_id );
	
	if ( ! empty( $style ) ) {
		echo 'style="' . esc_attr( $style ) . '"';
	}
}

/**
 * Classes for the featured section.
 *
 * @since 3.7
 * @param array $classes Classes for the featured section.
 * @return array
 */
function highend_featured_section_class( $class = array(), $post_id = '' ) {

	if ( empty( $post_id ) ) {
		$post_id = highend_get_the_id();
	}

	$featured_section_type = vp_metabox( 'featured_section.hb_featured_section_options', null, $post_id );

	$classes = array( 'clearfix', 'highend-featured-section-' . $featured_section_type );

	if ( 'featured_image' === $featured_section_type ) {

		$featured_image_size = vp_metabox( 'featured_section.hb_featured_section_height', null, $post_id );
		if ( 'window-height' === $featured_image_size ) {
			$classes[] = 'fullscreen-image';
		}

		$featured_image_parallax = vp_metabox( 'featured_section.hb_featured_section_parallax', null, $post_id );
		if ( 'enable' === $featured_image_parallax ) {
			$classes[] = 'hb-parallax-wrapper';
		}
	}

	$classes = array_unique( $classes + $class );
	$classes = apply_filters( 'highend_featured_section_classes', $classes, $post_id );

	if ( ! empty( $classes ) ) {
		$classes = trim( implode( ' ', $classes ) );

		echo 'class="' . esc_attr( $classes ) . '"';
	}
}

/**
 * Style for the featured section.
 *
 * @since 3.7
 * @param array $classes Classes for the featured section.
 * @return array
 */
function highend_featured_section_style( $post_id = '' ) {
	
	if ( empty( $post_id ) ) {
		$post_id = highend_get_the_id();
	}

	$style = '';

	$featured_section_type = vp_metabox( 'featured_section.hb_featured_section_options', null, $post_id );
	$featured_image_size   = vp_metabox( 'featured_section.hb_featured_section_height', null, $post_id );
	$featured_image_url    = get_the_post_thumbnail_url( $post_id, 'full' );

	if ( 'featured_image' === $featured_section_type ) {
		if ( 'custom-height' === $featured_image_size ) {
			$featured_image_custom_size = vp_metabox( 'featured_section.hb_featured_image_height', null, $post_id );
			$style .= 'height:' . $featured_image_custom_size . 'px;';
		}

		if ( $featured_image_url ) {
			$style .= 'background-image: url(' . esc_url( $featured_image_url ) . ');';	
		}
	}

	$style = apply_filters( 'highend_featured_section_style', $style, $post_id );
	
	if ( ! empty( $style ) ) {
		echo 'style="' . esc_attr( $style ) . '"';
	}
}

if ( ! function_exists( 'highend_attr' ) ) :
	/**
	 * Build list of attributes into a string and apply contextual filter on string.
	 *
	 * The contextual filter is of the form `highend_attr_{context}_output`.
	 *
	 * @since 3.6.14
	 *
	 * @param string $context    The context, to build filter name.
	 * @param array  $attributes Optional. Extra attributes to merge with defaults.
	 * @param array  $args       Optional. Custom data to pass to filter.
	 * @return string String of HTML attributes and values.
	 */
	function highend_attr( $context, $attributes = array(), $args = array() ) {

		// Parse defaults.
		$defaults = array(
			'class' => sanitize_html_class( $context ),
		);

		$attributes = wp_parse_args( $attributes, $defaults );

		// Contextual filter.
		$attributes = apply_filters( "highend_attr_{$context}", $attributes, $context, $args );

		$output = '';

		// Cycle through attributes, build tag attribute string.
		foreach ( $attributes as $key => $value ) {

			if ( ! $value ) {
				continue;
			}

			if ( true === $value ) {
				$output .= esc_html( $key ) . ' ';
			} else {
				$output .= sprintf( '%s="%s" ', esc_html( $key ), esc_attr( $value ) );
			}
		}

		$output = apply_filters( "highend_attr_{$context}_output", $output, $attributes, $context, $args );

		return trim( $output );
	}
endif;
