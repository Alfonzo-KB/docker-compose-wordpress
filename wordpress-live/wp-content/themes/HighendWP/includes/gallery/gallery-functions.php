<?php
/**
 * Gallery functions.
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

if ( ! function_exists( 'highend_get_gallery_query_args' ) ) :
	/**
	 * Gallery page query args.
	 *
	 * @since 3.6.0
	 */
	function highend_get_gallery_query_args( $post_id = '' ) {

		if ( get_query_var('paged') ) {
		    $paged = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
		    $paged = get_query_var('page');
		} else {
		    $paged = 1;
		}

		$template = highend_get_page_template( $post_id );

		$orderby = $order = $category__not_in = '';
		$posts_per_page = -1;

		if ( 'gallery-standard' === $template ) {
			$orderby          = vp_metabox( 'gallery_standard_page_settings.hb_query_orderby', 'date', $post_id );
			$order            = vp_metabox( 'gallery_standard_page_settings.hb_query_order', 'DESC', $post_id );
			$category__not_in = vp_metabox( 'gallery_standard_page_settings.hb_gallery_categories', '', $post_id );
			$posts_per_page   = vp_metabox( 'gallery_standard_page_settings.hb_gallery_posts_per_page', -1, $post_id );
		} elseif ( 'gallery-fullwidth' === $template ) {
			$orderby          = vp_metabox( 'gallery_fw_page_settings.hb_query_orderby', 'date', $post_id );
			$order            = vp_metabox( 'gallery_fw_page_settings.hb_query_order', 'DESC', $post_id );
			$category__not_in = vp_metabox( 'gallery_fw_page_settings.hb_gallery_categories', '', $post_id );
			$posts_per_page   = vp_metabox( 'gallery_fw_page_settings.hb_gallery_posts_per_page', -1, $post_id );
		}
		
		$args = array(
			'post_type'           => 'gallery',
			'orderby'             => $orderby,
			'order'               => $order,
			'paged'               => $paged,
			'posts_per_page'      => $posts_per_page,
			'ignore_sticky_posts' => true,
			'post_status'         => 'publish',
		);

		if ( ! empty( $category__not_in ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'gallery_categories',
					'field'    => 'id',
					'terms'    => $category__not_in,
					'operator' => 'NOT IN',
				)
			);
		}

		return apply_filters( 'highend_gallery_query_args', $args, $post_id );
	}
endif;

if ( ! function_exists( 'highend_gallery_standard_page_template' ) ) :
	/**
	 * Standard gallery layout.
	 * 
	 * @param  array $args  Args
	 * @since  3.6.0
	 * @return void
	 */
	function highend_gallery_standard_page_template( $args = array() ) {
		
		// Default args.
		$defaults = array(
			'filter'  => true,
			'sorter'  => true,
			'columns' => 3,
			'items'   => array(),
		);
		$args     = wp_parse_args( $args, $defaults );

		if ( $args['filter'] || $args['sorter'] ) {

			echo '<div class="standard-gallery-filter col-12 clearfix">';

			if ( $args['filter'] ) {
				highend_category_filter( 'gallery_categories' );
			}

			if ( $args['sorter'] ) {
				highend_sort_by_filter();
			}

			echo '</div>';
		}

		if ( have_posts() ) :

			echo '<div id="standard-gallery-wrapper" class="row row-special">';

			echo '<div id="standard-gallery-masonry" class="clearfix">';

			while ( have_posts() ) : the_post();

				$item_args            = $args['items'];
				$item_args['class']   = isset( $item_args['class'] ) && is_array( $item_args['class'] ) ? $item_args['class'] : array();
				$item_args['class'][] = 'col-' . ( 12 / intval( $args['columns'] ) );

				highend_gallery_standard_item( $item_args, get_the_ID() );

			endwhile;

			echo '</div>';

			echo '<div class="col-12 no-b-margin">';
			highend_pagination_standard();
			echo '</div>';

			echo '</div><!-- END #standard-gallery-wrapper -->';

		endif;
	}
endif;

if ( ! function_exists( 'highend_gallery_fullwidth_item' ) ) :
	/**
	 * Fullwidth Gallery item.
	 *
	 * @since  3.6.1
	 * @param  array   $args Gallery item args.
	 * @param  WP_Post $post_id Post object or ID.
	 * @param  boolean $echo Print or return HTML markup.
	 * @return void
	 */
	function highend_gallery_fullwidth_item( $args = array(), $post_id = null, $echo = false ) {
		
		$defaults = array(
			'atts'    => '',
			'width'   => 800,
			'height'  => 450,
			'class'   => array(),
		);

		$args   = wp_parse_args( $args, $defaults );
		$output = '';

		$class = array( 'col elastic-item' );
		$class = array_unique( array_merge( $class, (array) $args['class'] ) );
		
		$category_slugs = wp_get_post_terms( $post_id, 'gallery_categories', array( 'fields' => 'slugs' ) );
		$category_names = wp_get_post_terms( $post_id, 'gallery_categories', array( 'fields' => 'names' ) );
		
		if ( is_array( $category_slugs ) ) {
			$class = array_merge( $class, $category_slugs );
		}

		$class = trim( implode( ' ', $class ) );
		$class = apply_filters( 'highend_standard_gallery_item_class', $class, $args );

		$thumb   = get_post_thumbnail_id( $post_id );
		$gallery = rwmb_meta( 'hb_gallery_images', array( 'type' => 'plupload_image', 'size' => 'full' ), $post_id );

		if ( ! $thumb && ! empty( $gallery ) ) {
			reset( $gallery );
			$thumb = key( $gallery );
			unset( $gallery[ $thumb ] );
		}

		$image   = wp_get_attachment_url( $thumb );
		$rel     = 'gallery_' . rand( 1, 10000 );
		$resized = highend_resize( $thumb, $args['width'], $args['height'] );
		
		$gallery_color = vp_metabox( 'gallery_settings.hb_gallery_custom_bg_color', '', $post_id );
		$gallery_color = $gallery_color ? ' style="background: ' . highend_hex2rgba( $gallery_color, 0.85 ) . ';"' : '';

		// Begin building output.
		$output .= '<div class="' . esc_attr( $class ) . '"' . $args['atts'] . '>';

		$output .= '<div class="gallery-item item-has-overlay">';

		$output .= '<a href="' . esc_url( $image ) . '" rel="prettyPhoto[gallery_' . esc_attr( $rel ) . ']" title="' . esc_attr( wp_get_attachment_caption( $thumb ) ) . '">';

		if ( $resized['url'] ) {
			$output .= '<img src="' . esc_url( $resized['url'] ) . '" width="' . esc_attr( $args['width'] ) . '" height="' . esc_attr( $args['height'] ) . '" alt="' . esc_attr( get_the_title( $thumb ) ) . '"/>';
		} else {
			$output .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( get_the_title( $thumb ) ) . '"/>';
		}

		// Overlay.
		$output .= '<div class="item-overlay-text"' . $gallery_color . '>';
		$output .= '<div class="item-overlay-text-wrap">';
		
		$output .= '<h4><span class="hb-gallery-item-name">' . esc_html( get_the_title( $post_id ) ) . '</span></h4>';
		
		$output .= '<div class="hb-small-separator"></div>';
		$output .= '<span class="item-count-text">' . esc_html( get_the_time( 'j M Y', $post_id ) ) . '</span>';
		
		$output .= '</div>';
		$output .= '</div>';

		$output .= '</a>';
		$output .= '</div>';
		$output .= '</div><!-- END .hb-gal-standard-img-wrapper -->';

		// Gallery images.
		if ( ! empty( $gallery ) ) {
			$output .= '<div class="hb-reveal-gallery">';
			foreach ( $gallery as $gallery_item ) {
				$output .= '<a href="' . esc_url ( $gallery_item['url'] ) . '" title="' . esc_attr( $gallery_item['description'] ) . '" rel="prettyPhoto[gallery_' . esc_attr( $rel ) . ']"></a>';
			}
			$output .= '</div>';
		}

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
endif;

if ( ! function_exists( 'highend_gallery_standard_item' ) ) :
	/**
	 * Standard Gallery Item.
	 * 
	 * @param  array   $args Gallery item args.
	 * @param  WP_Post $post_id Post object or ID.
	 * @param  boolean $echo Return or print the item.
	 * @since  3.6.0
	 * 
	 * @return string|void
	 */
	function highend_gallery_standard_item( $args = array(), $post_id = null, $echo = true ) {

		$defaults = array(
			'atts'    => '',
			'width'   => 800,
			'height'  => 450,
			'class'   => array(),
		);

		$args   = wp_parse_args( $args, $defaults );
		$output = '';

		$class = array( 'standard-gallery-item-wrap' );
		$class = array_unique( array_merge( $class, (array) $args['class'] ) );
		
		$category_slugs = wp_get_post_terms( $post_id, 'gallery_categories', array( 'fields' => 'slugs' ) );
		$category_names = wp_get_post_terms( $post_id, 'gallery_categories', array( 'fields' => 'names' ) );
		
		if ( ! empty( $category_slugs ) && ! is_wp_error( $category_slugs ) ) {
			$class = array_merge( $class, $category_slugs );
		}
		
		$class = trim( implode( ' ', $class ) );
		$class = apply_filters( 'highend_standard_gallery_item_class', $class, $args );

		$thumb   = get_post_thumbnail_id( $post_id );
		$gallery = rwmb_meta( 'hb_gallery_images', array( 'type' => 'plupload_image', 'size' => 'full' ), $post_id );

		if ( ! $thumb && ! empty( $gallery ) ) {
			reset( $gallery );
			$thumb = key( $gallery );
			unset( $gallery[ $thumb ] );
		}

		$image   = wp_get_attachment_url( $thumb );
		$rel     = md5( get_the_title( $post_id ) . $thumb );
		$resized = highend_resize( $thumb, $args['width'], $args['height'] );
		
		$gallery_color = vp_metabox( 'gallery_settings.hb_gallery_custom_bg_color', '', $post_id );
		$gallery_color = $gallery_color ? ' style="background: ' . highend_hex2rgba( $gallery_color, 0.85 ) . ';"' : '';

		// Begin building output.
		$output .= '<div class="' . esc_attr( $class ) . '"' . $args['atts'] . '>';
		
		$output .= '<div class="standard-gallery-item" data-value="' . esc_attr( get_the_time( 'c', $post_id ) ) . '">';
		
		// Featured image.
		$output .= '<div class="hb-gal-standard-img-wrapper item-has-overlay">';
		$output .= '<a href="' . esc_url( $image ) . '" rel="prettyPhoto[gallery_' . esc_attr( $rel ) . ']" title="' . esc_attr( wp_get_attachment_caption( $thumb ) ) . '">';

		if ( $resized['url'] ) {
			$output .= '<img src="' . esc_url( $resized['url'] ) . '" width="' . esc_attr( $args['width'] ) . '" height="' . esc_attr( $args['height'] ) . '" alt="' . esc_attr( get_the_title( $thumb ) ) . '"/>';
		} else {
			$output .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( get_the_title( $thumb ) ) . '"/>';
		}

		// Overlay.
		$output .= '<div class="item-overlay"></div>';
		$output .= '<div class="item-overlay-text"' . $gallery_color . '><div class="item-overlay-text-wrap"><span class="plus-sign"></span></div></div>';

		$output .= '</a>';
		$output .= '</div><!-- END .hb-gal-standard-img-wrapper -->';

		// Gallery images.
		if ( ! empty( $gallery ) ) {
			$output .= '<div class="hb-reveal-gallery">';
			foreach ( $gallery as $gallery_item ) {
				$output .= '<a href="' . esc_url ( $gallery_item['url'] ) . '" title="' . esc_attr( $gallery_item['description'] ) . '" rel="prettyPhoto[gallery_' . esc_attr( $rel ) . ']"></a>';
			}
			$output .= '</div>';
		}

		// Description.
		$output .= '<div class="hb-gal-standard-description">';
		$output .= '<h3><span class="hb-gallery-item-name">' . esc_html( get_the_title( $post_id ) ) . '</span></h3>';
		
		if ( ! empty( $category_names ) && ! is_wp_error( $category_names ) ) {
			$output .= '<div class="hb-small-separator"></div><div class="hb-gal-standard-count">' . esc_html( trim( implode( ', ', $category_names ) ) ) . '</div>';
		}

		$output .= '</div><!-- END .hb-gal-standard-description -->';
		
		$output .= '</div><!-- END .standard-gallery-item -->';

		$output .= '</div>';

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
endif;
