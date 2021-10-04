<?php
/**
 * Portfolio functions.
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

if ( ! function_exists( 'highend_get_portfolio_query_args' ) ) :
	/**
	 * Portfolio page query args.
	 *
	 * @since 3.6.5
	 */
	function highend_get_portfolio_query_args( $post_id = '' ) {

		if ( get_query_var('paged') ) {
		    $paged = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
		    $paged = get_query_var('page');
		} else {
		    $paged = 1;
		}
		
		$orderby          = vp_metabox( 'portfolio_standard_page_settings.hb_query_orderby', 'date', $post_id );
		$order            = vp_metabox( 'portfolio_standard_page_settings.hb_query_order', 'DESC', $post_id );
		$category__not_in = vp_metabox( 'portfolio_standard_page_settings.hb_gallery_categories', '', $post_id );
		$posts_per_page   = vp_metabox( 'portfolio_standard_page_settings.hb_portfolio_posts_per_page', -1, $post_id );
		
		$args = array(
			'post_type'           => 'portfolio',
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
					'taxonomy' => 'portfolio_categories',
					'field'    => 'id',
					'terms'    => $category__not_in,
					'operator' => 'NOT IN',
				)
			);
		}

		return apply_filters( 'highend_portfolio_query_args', $args, $post_id );
	}
endif;

if ( ! function_exists( 'highend_portfolio_simple_page_template' ) ) :
	/**
	 * Simple portfolio layout.
	 * 
	 * @param  array $args  Args
	 * @since  3.6.5
	 * @return void
	 */
	function highend_portfolio_simple_page_template( $args = array() ) {
		
		// Default args.
		$defaults = array(
			'filter'  => true,
			'sorter'  => true,
			'columns' => 3,
			'items'   => array(),
		);

		$args = wp_parse_args( $args, $defaults );

		if ( $args['filter'] || $args['sorter'] ) {

			echo '<div class="standard-gallery-filter col-12 clearfix">';

			if ( $args['filter'] ) {
				highend_category_filter( 'portfolio_categories' );
			}

			if ( $args['sorter'] ) {
				highend_sort_by_filter();
			}

			echo '</div>';
		}

		if ( have_posts() ) :

			echo '<div id="standard-gallery-wrapper" class="row row-special">';

			echo '<div id="standard-gallery-masonry" class="portfolio-simple-wrap clearfix">';

			while ( have_posts() ) : the_post();

				$item_args            = $args['items'];
				$item_args['class']   = isset( $item_args['class'] ) && is_array( $item_args['class'] ) ? $item_args['class'] : array();
				$item_args['class'][] = 'col-' . ( 12 / intval( $args['columns'] ) );

				highend_portfolio_simple_item( $item_args, get_the_ID() );

			endwhile;

			echo '</div>';

			echo '<div class="col-12 no-b-margin">';
			highend_pagination_standard();
			echo '</div>';

			echo '</div><!-- END #standard-gallery-wrapper -->';

		endif;
	}
endif;

if ( ! function_exists( 'highend_portfolio_simple_item' ) ) :
	/**
	 * Simple Portfolio Item.
	 * 
	 * @param  array   $args Gallery item args.
	 * @param  WP_Post $post_id Post object or ID.
	 * @param  boolean $echo Return or print the item.
	 * @since  3.6.5
	 * 
	 * @return string|void
	 */
	function highend_portfolio_simple_item( $args = array(), $post_id = null, $echo = true ) {

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
		
		$category_slugs = wp_get_post_terms( $post_id, 'portfolio_categories', array( 'fields' => 'slugs' ) );

		if ( ! empty( $category_slugs ) && ! is_wp_error( $category_slugs ) ) {
			$class = array_merge( $class, $category_slugs );
		}
		
		$class = trim( implode( ' ', $class ) );
		$class = apply_filters( 'highend_portfolio_simple_item_class', $class, $args );

		$thumb   = get_post_thumbnail_id( $post_id );

		$image   = wp_get_attachment_url( $thumb );
		$resized = highend_resize( $thumb, $args['width'], $args['height'] );
		
		$custom_color = vp_metabox( 'portfolio_settings.hb_portfolio_custom_bg_color', '', $post_id );
		$custom_color = $custom_color ? ' style="background: ' . highend_hex2rgba( $custom_color, 0.85 ) . ';"' : '';
		
		$permalink = trim( vp_metabox( 'portfolio_settings.hb_portfolio_custom_url', '', $post_id ) );
		$permalink = empty( $permalink ) ? get_permalink() : $permalink;

		// Begin building output.
		$output .= '<div class="' . esc_attr( $class ) . '"' . $args['atts'] . '>';
		
		$output .= '<div class="standard-gallery-item" data-value="' . esc_attr( get_the_time( 'c', $post_id ) ) . '">';
		
		// Featured image.
		$output .= '<div class="hb-gal-standard-img-wrapper item-has-overlay">';
		$output .= '<a href="' . esc_url( $permalink ) . '" title="' . esc_attr( wp_get_attachment_caption( $thumb ) ) . '">';

		if ( $resized['url'] ) {
			$output .= '<img src="' . esc_url( $resized['url'] ) . '" width="' . esc_attr( $args['width'] ) . '" height="' . esc_attr( $args['height'] ) . '" alt="' . esc_attr( get_the_title( $thumb ) ) . '"/>';
		} else {
			$output .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( get_the_title( $thumb ) ) . '"/>';
		}

		// Overlay.
		$output .= '<div class="item-overlay"></div>';
		$output .= '<div class="item-overlay-text"' . $custom_color . '><div class="item-overlay-text-wrap"><span class="plus-sign"></span></div></div>';

		$output .= '</a>';
		$output .= '</div><!-- END .hb-gal-standard-img-wrapper -->';


		// Description.
		$output .= '<div class="hb-gal-standard-description portfolio-description">';
		$output .= '<h3><a href="' . esc_url( $permalink ) . '"><span class="hb-gallery-item-name">' . esc_html( get_the_title( $post_id ) ) . '</span></a></h3>';
				
		$excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words( strip_shortcodes( get_the_content() ), 20, NULL );
		$output .= $excerpt ? '<p>' . $excerpt . '</p>' : '';

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

if ( ! function_exists( 'highend_portfolio_standard_page_template' ) ) :
	/**
	 * Standard portfolio layout.
	 * 
	 * @param  array $args  Args
	 * @since  3.7.2
	 * @return void
	 */
	function highend_portfolio_standard_page_template( $args = array() ) {
		
		// Default args.
		$defaults = array(
			'filter'  => true,
			'sorter'  => true,
			'columns' => 3,
			'items'   => array(),
		);

		$args = wp_parse_args( $args, $defaults );

		if ( $args['filter'] || $args['sorter'] ) {

			echo '<div class="standard-gallery-filter col-12 clearfix">';

			if ( $args['filter'] ) {
				highend_category_filter( 'portfolio_categories' );
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

				highend_portfolio_standard_item( $item_args, get_the_ID() );

			endwhile;

			echo '</div>';

			echo '<div class="col-12 no-b-margin">';
			highend_pagination_standard();
			echo '</div>';

			echo '</div><!-- END #standard-gallery-wrapper -->';

		endif;
	}
endif;

if ( ! function_exists( 'highend_portfolio_standard_item' ) ) :
	/**
	 * Standard Portfolio Item.
	 * 
	 * @param  array   $args Gallery item args.
	 * @param  WP_Post $post_id Post object or ID.
	 * @param  boolean $echo Return or print the item.
	 * @since  3.7.2
	 * 
	 * @return string|void
	 */
	function highend_portfolio_standard_item( $args = array(), $post_id = null, $echo = true ) {

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
		
		$category_slugs = wp_get_post_terms( $post_id, 'portfolio_categories', array( 'fields' => 'slugs' ) );

		if ( ! empty( $category_slugs ) && ! is_wp_error( $category_slugs ) ) {
			$class = array_merge( $class, $category_slugs );
		}

		$category_names = wp_get_post_terms( $post_id, 'portfolio_categories', array( 'fields' => 'names' ) );
		$category_names = empty( $category_names ) ? '' : implode( ', ', $category_names );
		
		$class = trim( implode( ' ', $class ) );
		$class = apply_filters( 'highend_portfolio_simple_item_class', $class, $args );

		$thumb   = get_post_thumbnail_id( $post_id );

		$image   = wp_get_attachment_url( $thumb );
		$resized = highend_resize( $thumb, $args['width'], $args['height'] );
		
		$custom_color = vp_metabox( 'portfolio_settings.hb_portfolio_custom_bg_color', '', $post_id );
		$custom_color = $custom_color ? ' style="background: ' . highend_hex2rgba( $custom_color, 0.85 ) . ';"' : '';
		
		$permalink = trim( vp_metabox( 'portfolio_settings.hb_portfolio_custom_url', '', $post_id ) );
		$permalink = empty( $permalink ) ? get_permalink() : $permalink;

		// Begin building output.
		$output .= '<div class="' . esc_attr( $class ) . '"' . $args['atts'] . '>';
		
		$output .= '<div class="standard-gallery-item" data-value="' . esc_attr( get_the_time( 'c', $post_id ) ) . '">';
		
		// Featured image.
		$output .= '<div class="hb-gal-standard-img-wrapper item-has-overlay">';
		$output .= '<a href="' . esc_url( $permalink ) . '" title="' . esc_attr( wp_get_attachment_caption( $thumb ) ) . '">';

		if ( $resized['url'] ) {
			$output .= '<img src="' . esc_url( $resized['url'] ) . '" width="' . esc_attr( $args['width'] ) . '" height="' . esc_attr( $args['height'] ) . '" alt="' . esc_attr( get_the_title( $thumb ) ) . '"/>';
		} else {
			$output .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( get_the_title( $thumb ) ) . '"/>';
		}

		// Overlay.
		$output .= '<div class="item-overlay"></div>';
		$output .= '<div class="item-overlay-text"' . $custom_color . '><div class="item-overlay-text-wrap"><span class="plus-sign"></span></div></div>';

		$output .= '</a>';
		$output .= '</div><!-- END .hb-gal-standard-img-wrapper -->';


		// Description.
		$output .= '<div class="hb-gal-standard-description portfolio-description">';
		$output .= '<h3><a href="' . esc_url( $permalink ) . '"><span class="hb-gallery-item-name">' . esc_html( get_the_title( $post_id ) ) . '</span></a></h3>';
				

		$output .= '<div class="hb-gal-standard-count">' . esc_html( $category_names ) . '</div>';

		$output .= highend_option( 'hb_portfolio_enable_likes' ) ? hb_print_portfolio_likes( $post_id ) : '';

		$excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words( strip_shortcodes( get_the_content() ), 20, NULL );
		$output .= $excerpt ? '<p>' . $excerpt . '</p>' : '';

		$output .= '<div class="portfolio-small-meta clearfix">';
		$output .= '<span class="float-left project-date">' . get_the_time( get_option( 'date_format' ) ) . '</span>';
		$output .= '<a href="' . esc_url( $permalink ) . '" class="float-right details-link" title="' . esc_attr( wp_get_attachment_caption( $thumb ) ) . '">' . esc_html__( 'Details', 'hbthemes' ) . ' <i class="icon-angle-right"></i></a>';
		$output .= '</div><!-- END .portfolio-small-meta -->';

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
