<?php
/**
 * Display breadcrumb trail.
 * 
 * @package Highend
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'hbthemes_breadcrumbs' ) ) {

	/**
	 * Show a breadcrumb.
	 *
	 * @since  1.0.0
	 */
	function hbthemes_breadcrumbs() {

		// Check if breadcrumb is turned on from WPSEO option.
		$breadcrumb_enable = is_callable( 'WPSEO_Options::get' ) ? WPSEO_Options::get( 'breadcrumbs-enable' ) : false;
		$wpseo_option      = get_option( 'wpseo_internallinks' ) ? get_option( 'wpseo_internallinks' ) : $breadcrumb_enable;

		if ( ! is_array( $wpseo_option ) ) {
			unset( $wpseo_option );
			$wpseo_option = array(
				'breadcrumbs-enable' => $breadcrumb_enable 
			);
		}

		if ( function_exists( 'yoast_breadcrumb' ) && $wpseo_option && true === $wpseo_option['breadcrumbs-enable'] ) {

			// Yoast breadcrumb.
			return yoast_breadcrumb();
		} elseif ( function_exists( 'seopress_display_breadcrumbs' ) ) {

			// SEOPress breadcrumb.
			return seopress_display_breadcrumbs();
		} elseif ( function_exists( 'rank_math_the_breadcrumbs' ) ) {

			// Rank Math breadcrumbs.
			return rank_math_the_breadcrumbs();
		}

		global $post;

		if ( is_404() ) {
			return;
		}

		if ( is_search() || is_archive() ) {

			if ( ! highend_option( 'hb_enable_breadcrumbs' ) ) {
				return;
			}
		} elseif ( ( is_home() || is_front_page() ) && ! is_paged() ) {
			return;
		} else {

			if ( 'hide' === vp_metabox( 'general_settings.hb_breadcrumbs' ) ) {
				return;
			}

			if ( 'default' === vp_metabox( 'general_settings.hb_breadcrumbs' ) && ! highend_option( 'hb_enable_breadcrumbs' ) ) {
				return;
			}

			if ( ! is_singular() && ! highend_option( 'hb_enable_breadcrumbs' ) ) {
				return;
			}
		}

		$delimiter = '<span class="sep-icon"><i class="icon-angle-right"></i></span>';
		$before    = '<span>';
		$after     = '</span>';

		if ( ! is_home() && ! is_front_page() || is_paged() ) {

			echo '<div class="breadcrumbs-wrapper">';
			echo '<div class="breadcrumbs-inside">';
					
			echo ' <a href="' . esc_url( home_url() ) . '">' . esc_html__( 'Home', 'hbthemes' ) . '</a> ' . $delimiter;
			
			if ( class_exists( 'bbPress' ) && bbp_is_forum_archive() ) {

				echo $before . esc_html__( 'Forums', 'hbthemes' ) . $after;

			} elseif ( class_exists( 'bbPress' ) && bbp_is_single_forum() ) {

				echo '<a href="' . esc_url( get_post_type_archive_link( 'forum' ) ) . '">' . esc_html__( 'Forums', 'hbthemes' ) . '</a>' . $delimiter . $before . esc_html( get_the_title() ) . $after;

			} elseif ( is_category() ) {

				$category_name = single_cat_title( '', false );
				$category_id   = get_cat_ID( $category_name );

				$category_obj  = get_category( $category_id );

				if ( $category_obj->parent ) {
					echo get_category_parents( $category_obj->parent, TRUE, $delimiter );
				}

				echo $before . single_cat_title( '', false ) . $after;

			} elseif ( is_tax() ) {

				echo $before . single_cat_title( '', false ) . $after;

			} elseif ( is_day() ) {

				echo $before . esc_html__( 'Blog', 'hbthemes' ) . $after . $delimiter;
				echo '<a href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '">' . esc_html( get_the_time( 'Y' ) ) . '</a> ' . $delimiter;
				echo '<a href="' . esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ) . '">' . esc_html( get_the_time( 'F' ) ) . '</a> ' . $delimiter;
				echo $before . esc_html( get_the_time( 'd' ) ) . $after;

			} elseif ( is_month() ) {

				echo $before . esc_html__( 'Blog', 'hbthemes' ) . $after . $delimiter;
				echo '<a href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '">' . esc_html( get_the_time( 'Y' ) ) . '</a> ' . $delimiter;
				echo $before . esc_html( get_the_time('F') ) . $after;
	 
			} elseif ( is_year() ) {

				echo $before . esc_html__( 'Blog', 'hbthemes' ) . $after . $delimiter;
				echo $before . esc_html( get_the_time( 'Y' ) ) . $after;
	 
			} elseif ( is_single() && ! is_attachment() ) {

				if ( 'post' !== get_post_type() ) {
					
					if ( function_exists( 'is_product' ) && is_product() ) {

						$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
						$shop_page_url = '<a href="' . esc_url( $shop_page_url ) . '">' . esc_html__( 'Shop', 'hbthemes' ) . '</a>';
						
						global $post;

						$terms = get_the_terms( $post->ID, 'product_cat' );

						$product_cat_id   = '';
						$product_cat_name = '';
						$product_final    = '';
						$product_cat_link = '';

						if ( is_array( $terms ) ) {
							foreach ( $terms as $term ) {
								$product_cat_id   = $term->term_id;
								$product_cat_name = $term->name;
								break;
							}
						}
						
						if ( $product_cat_id != '' ) {
							$product_cat_link = get_term_link( $product_cat_id, 'product_cat' );
							$product_final = $before . '<a href="' . esc_url( $product_cat_link ) . '">' . esc_html( $product_cat_name ) . '</a>' . $after . $delimiter;
						}

						echo $before . $shop_page_url . $after . $delimiter . $product_final . $before . esc_html( get_the_title() ) . $after;

					} else if ( class_exists( 'bbPress' ) && 'topic' === get_post_type() ) {
						echo '<a href="' . esc_url( get_post_type_archive_link( 'forum' ) ) . '">' . esc_html__( 'Forums', 'hbthemes' ) . '</a>' . $delimiter . '<a href="' . esc_url( bbp_get_forum_permalink() ) . '" class="parent-forum">' . esc_html( bbp_get_forum_title() ) . '</a>' . $delimiter . $before . esc_html( get_the_title() ) . $after;
					} elseif ( 'portfolio' === get_post_type() ) {
						
						$portfolio_category = vp_metabox( 'portfolio_settings.hb_portfolio_breadcrumbs', null, get_the_ID() );

						if ( empty( $portfolio_category ) || 'default' === $portfolio_category ) {

							$portfolio_categories = get_the_terms( get_the_ID(), 'portfolio_categories' );
						
							if ( ! empty( $portfolio_categories ) ) {
								$portfolio_category = $portfolio_categories[0];
							}
						} elseif ( 'none' !== $portfolio_category ) {
							$portfolio_category = get_term_by( 'slug', $portfolio_category, 'portfolio_categories' );
						} else {
							$portfolio_category = false;
						}

						if ( ! is_wp_error( $portfolio_category ) && ! empty( $portfolio_category ) ) {
							echo '<a href="' . esc_url( get_term_link( $portfolio_category ) ) . '">' . esc_html( $portfolio_category->name ) . '</a>' . $delimiter;
						}

						echo $before . esc_html( get_the_title() ) . $after;
					} else {
						echo $before . esc_html( get_the_title() ) . $after;
					}

				} else {

					$cat = get_the_category();
					$cat = $cat[0];

					echo get_category_parents( $cat, TRUE, $delimiter );
					echo $before . esc_html( get_the_title() ) . $after;
				}
	 
			} elseif ( is_attachment() ) {

				$parent = get_post( $post->post_parent );
				$cat    = get_the_category($parent->ID);
				$cat    = $cat[0];

				echo '<a href="' . esc_html( get_permalink( $parent ) ) . '">' . esc_html( $parent->post_title ) . '</a> ' . $delimiter;
				echo $before . esc_html( get_the_title() ) . $after;
	 
			} elseif ( is_page() && ! $post->post_parent ) {

				echo $before . esc_html( get_the_title() ) . $after;
	 
			} elseif ( is_page() && $post->post_parent ) {

				$parent_id   = $post->post_parent;
				$breadcrumbs = array();

				while ( $parent_id ) {

					$page          = get_page( $parent_id );
					$breadcrumbs[] = '<a href="' . esc_url( get_permalink( $page->ID ) ) . '">' . esc_html( get_the_title( $page->ID ) ) . '</a>';
					$parent_id     = $page->post_parent;
				}

				$breadcrumbs = array_reverse( $breadcrumbs );

				if ( ! empty ( $breadcrumbs ) ) {
					foreach ( $breadcrumbs as $crumb ) {
						echo $crumb . $delimiter;
					}
				}
				
				echo $before . esc_html( get_the_title() ) . $after;
	 
			} elseif ( is_tag() ) {

				echo $before;
				printf( __( 'Tag <span class="sep-icon"><i class="icon-angle-right"></i></span> %s', 'hbthemes' ), single_tag_title( '', false ) );
				echo  $after;
	 
			} elseif ( is_author() ) {

				global $author;
				$userdata = get_userdata( $author );
				echo $before ;
				printf( __( 'Author <span class="sep-icon"><i class="icon-angle-right"></i></span> %s', 'hbthemes' ),  $userdata->display_name );
				echo  $after;
	 
			} elseif ( is_search() ) {

				echo $before;
				echo esc_html__( 'Search Results', 'hbthemes' );
				echo $after;
				echo $delimiter;
				echo $before;
				echo get_search_query();
				echo $after;

			} elseif ( function_exists( 'is_shop' ) && is_shop() ) {

				echo $before;
				esc_html_e( 'Shop', 'hbthemes' );
				echo $after;
			}
	 
			if ( get_query_var( 'paged' ) ) {

				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
					echo $before;
				}

				echo ' (' . $before . esc_html__( 'Page ', 'hbthemes' ) . get_query_var( 'paged' ) . $after . ')';

				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
					echo $after;
				}
			}
			
			echo '</div>';
			echo '</div><!-- END .breadcrumbs-wrapper -->';
			
			echo '<div class="clear"></div>';
		}
	}
}
