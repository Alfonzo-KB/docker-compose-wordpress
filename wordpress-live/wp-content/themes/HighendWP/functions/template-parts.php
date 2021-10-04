<?php
/**
 * Template parts.
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

if ( ! function_exists( 'highend_testimonial_box' ) ) :

	/**
	 * Display Testimonial Box. Based on deprecated hb_testimonial_box.
	 *
	 * @since 3.5.0
	 */
	function highend_testimonial_box( $post_id = null ) {

		$post = get_post( $post_id );

		if ( ! $post ) {
			return;
		}

		// Setup post.
		setup_postdata( $post );

		$author_image     = vp_metabox('testimonial_type_settings.hb_testimonial_author_image');
		$author_name      = vp_metabox('testimonial_type_settings.hb_testimonial_author');
		$author_desc      = vp_metabox('testimonial_type_settings.hb_testimonial_description');
		$author_desc_link = vp_metabox('testimonial_type_settings.hb_testimonial_description_link');
		?>
		
		<div class="hb-testimonial">
			<?php the_content(); ?>
		</div>
							
		<?php 
		if ( $author_image ) {

			$author_image = highend_resize( $author_image, 60, 60 );

			if ( $author_image['url'] ) {
				echo '<img src="' . esc_url( $author_image['url'] ) . '" width="60" height="60" class="testimonial-author-img"/>';
			}
		}

		if ( $author_name || $author_desc ) {

			echo '<div class="testimonial-author">';
			
			if ( $author_name ) {
				echo '<h5 class="testimonial-author-name">' . esc_html( $author_name ) . '</h5>';
			}

			if ( $author_desc ) {
				$author_desc_link = $author_desc_link ? ' href="' . esc_url( $author_desc_link ) . '"' : '';
				echo '<a class="testimonial-company"' . $author_desc_link . '>' . esc_html( $author_desc ) . '</a>';
			}

			echo '</div>';
		}

		// Reset postdata.
		wp_reset_postdata();
	}
endif;

if ( ! function_exists( 'highend_testimonial_quote' ) ) :

	/**
	 * Display Testimonial Quote. Based on deprecated hb_testimonial_quote.
	 *
	 * @since 3.5.0
	 */
    function highend_testimonial_quote( $post_id ) {

		$post = get_post( $post_id );

		if ( ! $post ) {
			return;
		}

		// Setup postdata.
		setup_postdata( $post );

		$author_name      = vp_metabox('testimonial_type_settings.hb_testimonial_author');
		$author_desc      = vp_metabox('testimonial_type_settings.hb_testimonial_description');
		$author_desc_link = vp_metabox('testimonial_type_settings.hb_testimonial_description_link');
		?>
		<p><?php the_content(); ?></p>

		<div class="testimonial-quote-meta">
			<span>
				<?php 
				if ( $author_name ) {
					echo esc_html( $author_name );
				}

				if ( $author_desc ) {
					if ( $author_desc_link ) {
						echo ', <a href="' . esc_url( $author_desc_link ) . '">' . esc_html( $author_desc ) . '</a>';
					} else {
						echo ', ' . esc_html( $author_desc );
					}
				}
				?>
			</span>
		</div>
		 
		<?php 
		// Reset postdata.
		wp_reset_postdata();
	}
endif;

if ( ! function_exists( 'highend_team_member_box' ) ) :

	/**
	 * Display Team Member Box. Based on deprecated hb_team_member_box.
	 *
	 * @since   3.5.0
	 * @version 3.6.1
	 */
	function highend_team_member_box( $args = array(), $post_id = null, $echo = false ) {

		if ( null !== $post_id ) {

			$post = get_post( $post_id );

			if ( ! $post ) {
				return;
			}

			// Setup post.
			setup_postdata( $post );
		}

		$args = wp_parse_args(
			$args,
			array(
				'style'          => '',
				'excerpt_length' => 20,
				'content'        => true,
				'social_links'   => true,
			)
		);

		$thumb = get_post_thumbnail_id();
		$style = '' !== $args['style'] ? ' tmb-2' : $args['style'];

		ob_start();
		?>

		<div class="team-member-box<?php echo esc_attr( $style ); ?>">
									
			<div class="team-member-img">

				<?php 
				if ( $thumb ) {

					$dimension = apply_filters( 
						'highend_team_member_image_size',
						array(
							'width'  => 350,
							'height' => 350,
							'crop'   => true,
						)
					);

					$image        = highend_resize( $thumb, $dimension['width'], $dimension['height'], $dimension['crop'] );
					$social_links = highend_get_social_networks_array();

					if ( $image['url'] ) {
						echo '<img src="' . esc_url( $image['url'] ) . '" alt="' . esc_attr( get_the_title() ) . '"/>';
					}
					?>

					<?php if ( $args['social_links'] ) { ?>
					<ul class="social-icons dark">
					
						<?php foreach ( $social_links as $soc_id => $soc_name ) { ?>

							<?php 
							if ( ! vp_metabox('team_member_settings.hb_employee_social_' . $soc_id ) ) {
								continue;
							}
							?>

							<li class="<?php echo esc_attr( $soc_id ); ?>">

								<?php if ( ! in_array( $soc_id, array( 'behance', 'vk', 'envelop', 'sn500px', 'weibo', 'tripadvisor' ) ) ) { ?>
									<a href="<?php echo esc_url( vp_metabox('team_member_settings.hb_employee_social_' . $soc_id ) ); ?>" target="_blank">
										<i class="hb-moon-<?php echo esc_attr( $soc_id ); ?>"></i>
										<i class="hb-moon-<?php echo esc_attr( $soc_id ); ?>"></i>
									</a>
								<?php } elseif ( 'envelop' === $soc_id ) { ?>
									<a href="mailto:<?php echo sanitize_email( vp_metabox('team_member_settings.hb_employee_social_' . $soc_id ) ); ?>">
										<i class="hb-moon-<?php echo esc_attr( $soc_id ); ?>"></i>
										<i class="hb-moon-<?php echo esc_attr( $soc_id ); ?>"></i>
									</a>
								<?php } else { ?>
									<a href="<?php echo esc_url( vp_metabox('team_member_settings.hb_employee_social_' . $soc_id ) ); ?>" target="_blank">
										<i class="icon-<?php echo esc_attr( $soc_id ); ?>"></i>
										<i class="icon-<?php echo esc_attr( $soc_id ); ?>"></i>
									</a>
								<?php } ?>

							</li>

						<?php } ?>
					</ul>
					<?php } ?>

				<?php } ?>
			</div><!-- END .team-member-img -->
										
			<div class="team-member-description">

				<div class="team-header-info clearfix">

					<div class="team-name">
						<h4 class="team-member-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>

						<?php if ( vp_metabox('team_member_settings.hb_employee_position') ) { ?>
							<p class="team-position"><?php echo esc_html( vp_metabox('team_member_settings.hb_employee_position') ); ?></p>
						<?php } ?>
					</div><!-- END .team-name -->
												
												
				</div><!-- END .team-header-info -->

				<?php if ( $args['content'] ) { ?>

					<?php echo '<div class="spacer" style="height:10px;"></div>'; ?>

					<div class="team-member-content">
						<?php 
						if ( has_excerpt() ) {
							echo '<p class="nbm">' . get_the_excerpt() . '</p>';
							echo '<div class="spacer" style="height:15px;"></div>';
						}
						else {
							echo '<div class="spacer" style="height:15px;"></div>';
							echo '<p class="nbm">' . wp_trim_words( wp_strip_all_tags( apply_filters( 'the_content', get_the_content() ) ), $args['excerpt_length'], NULL ) . '</p>';
						}
						?>
					</div><!-- END .team-member-content -->
				<?php } ?>

				<a class="simple-read-more" href="<?php the_permalink(); ?>" target="_self"><?php esc_html_e( 'View Profile', 'hbthemes' ); ?></a>
			</div><!-- END .team-member-description -->	

		</div><!-- END .team-member-box -->

		<?php
		if ( null !== $post_id ) {
			// Reset postdata.
			wp_reset_postdata();
		}

		$output = ob_get_clean();

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
endif;

if ( ! function_exists( 'highend_mobile_menu' ) ) :
	/**
	 * Display Mobile menu.
	 *
	 * @since 3.5.0
	 */
	function highend_mobile_menu( $echo = true ) {

		$class = highend_option( 'hb_interactive_mobile_dropdown' ) ? ' interactive' : '';
		$class = apply_filters( 'highend_mobile_menu_nav_class', $class );

		if ( vp_metabox( 'misc_settings.hb_onepage_also' ) ) {
			$args = array(
				'echo'           => false,
				'theme_location' => 'one-page-menu',
				'menu_class'     => 'menu-main-menu-container',
				'fallback_cb'    => ''
			);   
		} else {
			if ( has_nav_menu( 'mobile-menu' ) ) {
				$args = array(
					'echo'           => false,
					'theme_location' => 'mobile-menu',
					'menu_class'     => 'menu-main-menu-container',
					'fallback_cb'    => ''
				);
			} else {
				$args = array(
					'echo'           => false,
					'theme_location' => 'main-menu',
					'menu_class'     => 'menu-main-menu-container',
					'fallback_cb'    => ''
				);
			}
		}

		$output = '<div id="mobile-menu-wrap">';

		if ( highend_option( 'hb_search_in_menu' ) ) {
			$output .= '<form method="get" class="mobile-search-form" action="' . esc_url( home_url() ) . '/"><input type="text" placeholder="' . esc_html__( 'Search', 'hbthemes' ) . '" name="s" autocomplete="off" /></form>';
		} else {
			$output .= '<div class="hb-top-holder"></div>';
		}

		// Close icon.
		$output .= '<a class="mobile-menu-close"><i class="hb-icon-x"></i></a>';
		
		// Cart widget.
		if ( class_exists( 'Woocommerce' ) && highend_option( 'hb_enable_sticky_shop_button' ) ) {
			global $woocommerce;
			$output .= '<a class="mobile-menu-shop" href="' . esc_url( wc_get_cart_url() ) . '"><i class="hb-icon-cart"></i><span class="hb-cart-total-header">' . $woocommerce->cart->get_cart_total() . '</span></a>';
		}

		// Menu navigation.
		$output .= '<nav id="mobile-menu" class="clearfix' . esc_attr( $class ) . '">';
									
		if ( function_exists( 'wp_nav_menu' ) ) {
			$output .= wp_nav_menu( $args );
		}
		$output .= '</nav>';

		$output .= '</div><!-- END #mobile-menu-wrap -->';

		$output = apply_filters( 'highend_mobile_menu', $output );

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
endif;

if ( ! function_exists( 'highend_pagination_ajax' ) ) :
	/**
	 * Infinite Scroll/Load More pagination.
	 *
	 * @since 3.5.0
	 */
	function highend_pagination_ajax( $post_id ) {

		if ( is_singular( $post_id ) ) {
			$query_vars = highend_get_blog_query_args( $post_id );
		} else {
			global $wp_query;
			$query_vars = $wp_query->query;
		}

		?>
		<a class="load-more-posts" href="#" data-query-vars="<?php echo esc_attr( wp_json_encode( $query_vars ) ); ?>" data-template="<?php echo esc_attr( highend_get_page_template( $post_id ) ); ?>">
			<span class="load-more-text"><?php esc_html_e( 'Load More Posts', 'hbthemes' ); ?></span>
			<span class="hb-spin non-visible"><i class="hb-moon-spinner-5"></i></span>
		</a>
		<?php
	}
endif;

if ( ! function_exists( 'highend_load_more_posts' ) ) :
	/**
	 * Infinite Scroll/Load More pagination.
	 *
	 * @since 3.5.0
	 */
	function highend_load_more_posts() {

		check_ajax_referer( 'highend_nonce' );

		$args     = isset( $_POST['query_vars'] ) ? wp_unslash( $_POST['query_vars'] ) : array();
		$paged    = isset( $_POST['paged'] ) ? sanitize_text_field( $_POST['paged'] ) : false;
		$template = isset( $_POST['template'] ) ? sanitize_text_field( $_POST['template'] ) : 'blog';

		$args['paged'] = $paged;
		$args['page']  = $paged;

		query_posts( $args );

		if ( have_posts() ) :
			do_action( 'highend_blog_loop', $template );
		endif;

		wp_reset_query();
		
		exit;
	}
endif;
add_action( 'wp_ajax_highend_pagination_ajax', 'highend_load_more_posts' );
add_action( 'wp_ajax_nopriv_highend_pagination_ajax', 'highend_load_more_posts' );

if ( ! function_exists( 'highend_pagination' ) ) :
	/**
	 * Display Pagination.
	 *
	 * @since 3.5.1
	 */
	function highend_pagination( $post_id = '' ) {

		$template = highend_get_page_template( $post_id );

		$type = highend_option( 'hb_pagination_style' );

		// Check post metabox.
		if ( ! empty( $post_id ) ) {

			$_type = '';

			// Blog & Blog small.
			if ( 'blog' === $template || 'blog-small' === $template ) {
				$_type = vp_metabox( 'blog_page_settings.hb_pagination_style', '', $post_id );
			} elseif ( 'blog-minimal' === $template ) {
				$_type = vp_metabox( 'blog_page_minimal_settings.hb_pagination_style', '', $post_id );
			} elseif ( 'blog-grid' === $template ) {
				$_type = vp_metabox( 'blog_grid_page_settings.hb_pagination_style', '', $post_id );
			} elseif ( 'blog-grid-fullwidth' === $template ) {
				$_type = vp_metabox( 'blog_fw_page_settings.hb_pagination_style', '', $post_id );
			}

			if ( ! empty( $_type ) ) {
				$type = $_type;
			}
		}

		if ( 'ajax' === $type ) {
			highend_pagination_ajax( $post_id );
		} else {
			highend_pagination_standard();
		}
	}
endif;

if ( ! function_exists( 'highend_top_bar_widget_info' ) ) :

	/**
	 * Display Top Bar info widget.
	 *
	 * @since 3.5.3
	 *
	 * @param string  $class Additional widget class.
	 * @param boolean $echo  Return or print the widget.
	 */
	function highend_top_bar_widget_info( $class = '', $echo = true ) {

		$classes = array( 'top-widget', $class );
		$classes = apply_filters( 'highend_top_bar_widget_info_class', $classes );
		$classes = trim( implode( ' ', $classes ) );

		$icon = apply_filters( 'highend_top_bar_widget_info_icon', 'hb-moon-arrow-right-5' );
		$text = apply_filters( 'highend_top_bar_widget_info_text', highend_option( 'hb_top_header_info_text' ) );

		$output = '';

		if ( $text ) {
			$output = sprintf(
				'<div id="top-info-widget" class="%1$s"><p><i class="%2$s"></i>%3$s</p></div>',
				esc_attr( $classes ),
				esc_attr( $icon ),
				wp_kses_post( $text )
			);
		}

		$output = wp_kses_post( apply_filters( 'highend_top_bar_widget_info_output', $output ) );

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
endif;

if ( ! function_exists( 'highend_top_bar_widget_map' ) ) :

	/**
	 * Display Top Bar map widget.
	 *
	 * @since 3.5.3
	 *
	 * @param string  $class Additional widget class.
	 * @param boolean $echo  Return or print the widget.
	 */
	function highend_top_bar_widget_map( $class = '', $echo = true ) {

		if ( ! highend_option( 'hb_top_header_map' ) ) {
			return;
		}

		$classes = array( 'top-widget', $class );
		$classes = apply_filters( 'highend_top_bar_widget_map_class', $classes );
		$classes = trim( implode( ' ', $classes ) );

		$icon = apply_filters( 'highend_top_bar_widget_map_icon', 'hb-moon-location-4' );
		$text = apply_filters( 'highend_top_bar_widget_map_text', highend_option( 'hb_top_header_map_text' ) );

		$output = '';

		if ( $text ) {
			$output = sprintf(
				'<div id="top-map-widget" class="%1$s"><a href="#" id="show-map-button"><i class="%2$s"></i>%3$s</a></div>',
				esc_attr( $classes ),
				esc_attr( $icon ),
				wp_kses_post( $text )
			);
		}

		$output = wp_kses_post( apply_filters( 'highend_top_bar_widget_map_output', $output ) );

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
endif;

if ( ! function_exists( 'highend_top_bar_widget_email' ) ) :

	/**
	 * Display Top Bar email widget.
	 *
	 * @since 3.5.3
	 *
	 * @param string  $class Additional widget class.
	 * @param boolean $echo  Return or print the widget.
	 */
	function highend_top_bar_widget_email( $class = '', $echo = true ) {

		$classes = array( 'top-widget', $class );
		$classes = apply_filters( 'highend_top_bar_widget_email_class', $classes );
		$classes = trim( implode( ' ', $classes ) );

		$icon = apply_filters( 'highend_top_bar_widget_email_icon', 'hb-moon-envelop' );
		$text = apply_filters( 'highend_top_bar_widget_email_text', highend_option( 'hb_top_header_email' ) );

		$output = '';

		if ( $text ) {
			$output = sprintf(
				'<div id="top-email-widget" class="%1$s"><a href="mailto:%3$s"><i class="%2$s"></i>%3$s</a></div>',
				esc_attr( $classes ),
				esc_attr( $icon ),
				esc_attr( $text )
			);
		}

		$output = wp_kses_post( apply_filters( 'highend_top_bar_widget_email_output', $output ) );

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
endif;

if ( ! function_exists( 'highend_top_bar_widget_login' ) ) :

	/**
	 * Display Top Bar login widget.
	 *
	 * @since 3.5.3
	 *
	 * @param string  $class Additional widget class.
	 * @param boolean $echo  Return or print the widget.
	 */
	function highend_top_bar_widget_login( $class = '', $echo = true ) {

		if ( ! highend_option( 'hb_top_header_login' ) ) {
			return;
		}

		$classes = array( 'top-widget', $class );
		$classes = apply_filters( 'highend_top_bar_widget_login_class', $classes );
		$classes = trim( implode( ' ', $classes ) );

		$output = '<div id="top-login-widget" class="' . esc_attr( $classes ) . '">';

		ob_start();

		if ( ! is_user_logged_in() ) {
			?>
			<a href="#"><?php esc_html_e( 'Login', 'hbthemes' ); ?><i class="icon-angle-down"></i></a>
			
			<div class="hb-dropdown-box login-dropdown">
				<?php get_template_part( 'includes/login', 'form' ); ?>
				<div class="big-overlay"><i class="hb-moon-user"></i></div>
			</div>
			<?php
		} else {

			$current_user = wp_get_current_user();
			$admin_url    = admin_url();

			if ( class_exists( 'Woocommerce' ) && ! current_user_can( 'manage_options' ) ) {

				$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );

				if ( $myaccount_page_id ) {
					$admin_url = get_permalink( $myaccount_page_id );
				}
			}

			?>
			<a href="<?php echo esc_url( $admin_url ); ?>">
				<i class="hb-moon-user-8"></i>
				<?php echo esc_html( $current_user->display_name ); ?>
				<i class="icon-angle-down"></i>
			</a>
			
			<div class="hb-dropdown-box logout-dropdown">
				<ul>
					<?php if ( is_user_logged_in() && class_exists( 'Woocommerce' ) && ! current_user_can( 'manage_options' ) ) { ?>
						<li>
							<a href="<?php echo esc_url( $admin_url ); ?>" class="my-account">
								<i class="hb-moon-user-8"></i>
								<?php esc_html_e( 'My Account', 'hbthemes' ); ?>
							</a>
						</li>
					<?php } else { ?>
						<li>
							<a href="<?php echo esc_url( admin_url() ); ?>">
								<i class="hb-moon-cog-3"></i>
								<?php esc_html_e( 'Dashboard', 'hbthemes' ); ?>
							</a>
						</li>
					<?php } ?>

					<?php if ( class_exists( 'Woocommerce' ) ) { ?>
						<li>
							<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-contents">
								<i class="hb-icon-cart"></i>
								<?php esc_html_e( 'My Cart','hbthemes'); ?>
							</a>
						</li>
					<?php } ?>
					
					<li>
						<a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>">
							<i class="hb-moon-arrow-right-5"></i>
							<?php esc_html_e( 'Log Out', 'hbthemes' ); ?>
						</a>
					</li>
				</ul>
			</div>
			<?php
		}

		$output .= ob_get_clean();
		$output .= '</div>';

		$output = apply_filters( 'highend_top_bar_widget_login_output', $output );

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
endif;

if ( ! function_exists( 'highend_top_bar_widget_languages' ) ) :

	/**
	 * Display Top Bar language switcher widget.
	 *
	 * @since 3.5.3
	 *
	 * @param string  $class Additional widget class.
	 * @param boolean $echo  Return or print the widget.
	 */
	function highend_top_bar_widget_languages( $class = '', $echo = true ) {

		if ( ! highend_option( 'hb_top_header_languages' ) || ! function_exists( 'icl_get_languages' ) ) {
			return;
		}

		$classes = array( 'top-widget', $class );
		$classes = apply_filters( 'highend_top_bar_widget_languages_class', $classes );
		$classes = trim( implode( ' ', $classes ) );

		$languages = icl_get_languages();

		ob_start();
		?>
		
		<div id="top-icl-languages-widget" class="<?php echo esc_attr( $classes ); ?>">

			<a href="#" id="hb-current-lang">
				<span class="active-lang-img"></span>
				<span class="lang-val"><?php esc_html_e( 'Language', 'hbthemes' ); ?></span>
				<i class="icon-angle-down"></i>
			</a>

			<div class="hb-dropdown-box language-selector">

			<?php if ( ! empty( $languages ) ) { ?>
				<ul>
					<?php foreach ( $languages as $language ) {  ?>
						<li>
							<?php if ( $language['active'] ) { ?>
								<a class="active-language">
							<?php } else { ?>
								<a href="<?php echo esc_url( $language['url'] ); ?>">
							<?php } ?>
								<span class="lang-img">
									<img src="<?php echo esc_url( $language['country_flag_url'] ); ?>" height="12" alt="lang" width="18">
								</span>
								<span class="icl_lang_sel_native"><?php echo esc_html( $language['native_name'] ); ?></span>
							</a>
						</li>
					<?php } ?>
				</ul>
			<?php } ?>
			</div>
		</div>

		<?php
		$output = ob_get_clean();

		$output = apply_filters( 'highend_top_bar_widget_languages_output', $output );

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
endif;

if ( ! function_exists( 'highend_top_bar_widget_cart' ) ) :

	/**
	 * Display Top Bar cart widget.
	 *
	 * @since 3.5.3
	 *
	 * @param string  $class Additional widget class.
	 * @param boolean $echo  Return or print the widget.
	 */
	function highend_top_bar_widget_cart( $class = '', $echo = true ) {

		if ( ! highend_option( 'hb_top_header_checkout' ) || ! class_exists( 'Woocommerce' ) ) {
			return;
		}

		$classes = array( 'top-widget', $class );
		$classes = apply_filters( 'highend_top_bar_widget_cart_class', $classes );
		$classes = trim( implode( ' ', $classes ) );

		$output = hb_woo_cart();

		$output = apply_filters( 'highend_top_bar_widget_cart_output', $output );

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
endif;

if ( ! function_exists( 'highend_top_bar_widget_custom_link' ) ) :

	/**
	 * Display Top Bar cart widget.
	 *
	 * @since 3.5.3
	 *
	 * @param string  $class Additional widget class.
	 * @param boolean $echo  Return or print the widget.
	 */
	function highend_top_bar_widget_custom_link( $class = '', $echo = true ) {

		if ( ! highend_option( 'hb_top_header_link' ) ) {
			return;
		}

		$classes = array( 'top-widget', $class );
		$classes = apply_filters( 'highend_top_bar_widget_custom_link_class', $classes );
		$classes = trim( implode( ' ', $classes ) );

		$icon = apply_filters( 'highend_top_bar_widget_custom_link_icon', highend_option( 'hb_top_header_link_icon' ) );
		$link = apply_filters( 'highend_top_bar_widget_custom_link_link', highend_option( 'hb_top_header_link_link' ) );
		$text = apply_filters( 'highend_top_bar_widget_custom_link_text', highend_option( 'hb_top_header_link_txt' ) );

		$output = '';

		if ( $text ) {
			$output = sprintf(
				'<div id="top-custom-link-widget" class="%1$s"><a href="%3$s"><i class="%2$s"></i>%4$s</a></div>',
				esc_attr( $classes ),
				esc_attr( $icon ),
				esc_url( $link ),
				wp_kses_post( $text )
			);
		}

		$output = apply_filters( 'highend_top_bar_widget_custom_link_output', $output );

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
endif;

if ( ! function_exists( 'highend_top_bar_widget_socials' ) ) :

	/**
	 * Display Top Bar social links widget.
	 *
	 * @since 3.5.3
	 *
	 * @param string  $class Additional widget class.
	 * @param boolean $echo  Return or print the widget.
	 */
	function highend_top_bar_widget_socials( $class = '', $echo = true ) {

		if ( ! highend_option( 'hb_top_header_socials_enable' ) ) {
			return;
		}

		$classes = array( 'top-widget', $class );
		$classes = apply_filters( 'highend_top_bar_widget_socials_class', $classes );
		$classes = trim( implode( ' ', $classes ) );

		$output = sprintf(
			'<div id="top-socials-widget" class="%1$s">%2$s</div>',
			esc_attr( $classes ),
			wp_kses( highend_social_icons_output( 'social-list', false, false ), highend_get_allowed_html_tags() )
		);

		$output = apply_filters( 'highend_top_bar_widget_socials_output', $output );

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
endif;

if ( ! function_exists( 'highend_social_icons_output' ) ) :

	/**
	 * Display social icon list.
	 *
	 * @since 3.5.3
	 *
	 * @param array   $socials Array of social links to display.
	 * @param boolean $echo    Return or print the icons.
	 */
	function highend_social_icons_output( $class = array(), $socials = false, $echo = true ) {

		// Populate default socials.
		if ( false === $socials ) {

			$default = highend_option( 'hb_top_header_socials' );
			$socials = array();

			if ( ! empty( $default ) ) {
				foreach ( $default as $social ) {
					$socials[ $social ] = highend_option( 'hb_' . $social . '_link' );
				}
			}
		}

		// Check if empty.
		if ( empty ( $socials ) ) {
			return;
		}

		$target  = highend_option( 'hb_soc_links_new_tab' ) ? 'target="_blank" rel="noopener noreferrer"' : 'target="_self"';
		
		$classes = array();
		$classes = array_unique( array_merge( $classes, (array) $class ) );
		$classes = trim( implode( ' ', $classes ) );

		ob_start();
		?>
		<ul class="<?php echo esc_attr( $classes ); ?>">
				
			<?php
			foreach ( $socials as $id => $link ) {

				$slug = $id;

				if ( 'custom-url' === $id ) {
					$slug = 'link-5';
				} elseif ( 'vkontakte' === $id ) {
					$slug = 'vk';
				}

				if ( ! in_array( $slug, array( 'behance', 'vk', 'twitch', 'sn500px', 'weibo', 'tripadvisor' ), true ) ) {
					$icon = 'hb-moon-' . $slug;
				} else {
					$icon = 'icon-' . $slug;
				}

				if ( 'envelop' === $slug ) {
					$link = 'mailto:' . esc_attr( $link );
				} else {
					$link = esc_url( $link );
				}
				?>
				<li class="<?php echo esc_attr( $slug ); ?>">
					<a href="<?php echo $link; ?>" original-title="<?php echo esc_attr( ucfirst( $id ) ); ?>" <?php echo $target; ?>>
						<i class="<?php echo esc_attr( $icon ); ?>"></i>
						
						<?php if ( false !== strpos( $classes, 'social-icons' ) ) { ?>
							<i class="<?php echo esc_attr( $icon ); ?>"></i>
						<?php } ?>
					</a> 
				</li>
			<?php } ?>

		</ul>
		<?php

		$output = ob_get_clean();

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
endif;

if ( ! function_exists( 'highend_category_filter' ) ) :
	/**
	 * Filter - Taxonomy.
	 * 
	 * @param  string      $taxonomy Taxonomy ID.
	 * @since  3.6.0
	 * @return void
	 */
	function highend_category_filter( $taxonomy = 'category' ) {

		// Get current loop if not specified.
		$categories = array();

		if ( have_posts() ) : 
			while ( have_posts() ) : the_post();
				$cats = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'all' ) );

				if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
					foreach ( $cats as $cat ) {
						$categories[ $cat->slug ] = $cat->name;
					}
				}
			endwhile;
		endif;

		wp_reset_postdata();
		?>
		<ul class="filter-tabs filt-tabs clearfix">
			<li class="selected">
				<a href="#" title="<?php esc_html_e( 'View all items', 'hbthemes' ); ?>" class="all" data-filter="*">
					<span class="item-name"><?php esc_html_e( 'All', 'hbthemes' ); ?></span>
					<span class="item-count">0</span>
				</a>
			</li>

			<?php if ( ! empty ( $categories ) ) { ?>
				<?php foreach ( $categories as $slug => $name ) { ?>
					<li>
						<a href="#" data-filter=".<?php echo esc_attr( $slug ); ?>" title="<?php echo esc_attr( sprintf( __( 'View all %s items', 'hbthemes' ), $name ) ); ?>">
							<span class="item-name"><?php echo esc_html( $name ); ?></span>
							<span class="item-count">0</span>
						</a>
					</li>
				<?php } ?>
			<?php } ?>
		</ul>
		<?php
	}
endif;

if ( ! function_exists( 'highend_sort_by_filter' ) ) :
	/**
	 * Filter - Sort By.
	 *
	 * @since  3.6.0
	 * @return void
	 */
	function highend_sort_by_filter() {
		?>
		<ul class="filter-tabs sort-tabs clearfix">
			<li class="selected">
				<a href="#" title="<?php esc_html_e( 'Show Newest First', 'hbthemes' ); ?>" class="all" data-sort="data">
					<span class="item-name"><?php esc_html_e( 'Date', 'hbthemes' ); ?></span>
				</a>
			</li>
			<li>
				<a href="#" title="<?php esc_html_e( 'Sort by Name', 'hbthemes' ); ?>" data-sort="name">
					<span class="item-name"><?php esc_html_e( 'Name','hbthemes' ); ?></span>
				</a>
			</li>
		</ul>
		<?php
	}
endif;

if ( ! function_exists( 'highend_print_google_map' ) ) :
	/**
	 * Print Google Map.
	 * 
	 * @param  array   $args Map parameters
	 * @param  boolean $echo Return or print HTML
	 * @since  3.6.1
	 * @return void
	 */
	function highend_print_google_map( $args = array(), $echo = true ) {

		wp_enqueue_script( 'highend-google-map' );
		
		$defaults = array(
			'show_location' => -1,
			'zoom'          => highend_option( 'hb_map_zoom' ),
			'zoom_control'  => highend_option( 'hb_enable_map_buttons' ),
			'latitude'      => highend_option( 'hb_map_latitude' ),
			'longitude'     => highend_option( 'hb_map_longitude' ),
			'marker'        => highend_option( 'hb_enable_custom_pin' ) ? highend_option( 'hb_custom_marker_image' ) : '',
			'api'           => highend_option( 'hb_gmap_api_key' ),
			'color'         => highend_option( 'hb_enable_map_color' ) ? highend_option( 'hb_map_focus_color' ) : 'none',
			'pan_control'   => false,
			'wrapper_start' => '',
			'wrapper_end'   => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$output = sprintf(
			'<div class="hb-gmap-map" data-show-location="%1$s" data-map-level="%2$s" data-zoom-control="%3$s" data-map-lat="%4$s" data-map-lng="%5$s" data-pan-control="%6$s"  data-map-img="%7$s" data-api-key="%8$s" data-overlay-color="%9$s"></div>',
			esc_attr( $args['show_location'] ),
			esc_attr( $args['zoom'] ),
			$args['zoom_control'] ? 'true' : 'false',
			esc_attr( $args['latitude'] ),
			esc_attr( $args['longitude'] ),
			$args['pan_control'] ? 'true' : 'false',
			esc_attr( $args['marker'] ),
			esc_attr( $args['api'] ),
			esc_attr( $args['color'] )
		);

		$output = $args['wrapper_start'] . $output . $args['wrapper_end'];

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
endif;

if ( ! function_exists( 'highend_format_comment' ) ) :

	/**
	 * Comment and pingback output function.
	 *
	 * @since 3.6.3
	 * @param string $comment Comment content.
	 * @param array  $args    Comment arguments.
	 * @param int    $depth   Comment depth.
	 */
	function highend_format_comment( $comment, $args, $depth ) {

		$is_by_author = $comment->comment_author_email === get_the_author_meta( 'email' );
		?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
		 
			<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
				<div class="comment-author vcard">

					<span class="rounded-element float-left"><?php echo get_avatar( $comment,'76' ); ?></span>

					<cite class="fn"><?php comment_author_link(); ?></cite>

					<div class="reply">
						<?php
						comment_reply_link(
							array_merge(
								$args,
								array(
									'add_below' => 'div-comment',
									'depth'     => $depth,
									'before'    => '<span class="sep">&middot;</span> ',
									'max_depth' => $args['max_depth']
								)
							),
							get_comment_ID()
						);
						?>
					</div>

					<br/>

					<?php if ( $is_by_author ) { ?>
						<span class="author-tag"><?php esc_html_e( 'Author', 'hbthemes' ) ?></span>
					<?php } ?>

				</div>

				<div class="comment-meta commentmetadata">
					<a href="<?php comment_link(); ?>">
						<time itemprop="commentTime" datetime="<?php comment_time( 'c' ); ?>"><?php printf( '%1$s at %2$s', get_comment_date( get_option( 'date_format' ), get_comment_ID() ), get_comment_time( 'g:i A' ) ); ?></time>
					</a>
				</div>

				<div class="comment-inner" itemprop="commentText">      

					<?php if ( '0' === $comment->comment_approved ) { ?>
						<em class="moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'hbthemes' ); ?></em>
						<br/>
					<?php } ?>

					<?php comment_text(); ?>

				</div>  
			</div>
		<?php 
	}
endif;

if ( ! function_exists( 'highend_featured_image_thumb' ) ) :

	/**
	 * HTML Markup for featured image thumb.
	 *
	 * @since 3.6.4
	 */
	function highend_featured_image_thumb( $args = array() ) {

		$args = wp_parse_args(
			$args,
			array(
				'post_id'  => '',
				'width'    => false,
				'height'   => false,
				'crop'     => false,
				'alt'      => '',
				'lightbox' => false,
				'img_atts' => array(),
			)
		);
		$post_id = $args['post_id'];

		$thumb = get_post_thumbnail_id( $post_id );
		$image = highend_resize( $thumb, $args['width'], $args['height'], $args['crop'] );

		// Caption
		$caption = '';

		if ( is_single( $post_id ) || is_page( $post_id ) ) {
			$caption = wp_get_attachment_caption( $thumb );

			if ( ! empty( $caption ) ) {
				$caption = '<em class="post-thumb-caption">' . wp_kses( $caption, wp_kses_allowed_html('html') ) . '</em>';
			}
		}

		// If custom ALT tag is provided use it, otherwise use page title.
		if ( empty( $args['alt'] ) ) {
			$thumb_alt = trim( strip_tags( get_post_meta( $thumb, '_wp_attachment_image_alt', true ) ) ); // phpcs:ignore

			if ( ! empty( $thumb_alt ) ) {
				$args['alt'] = $thumb_alt;
			} else {
				$args['alt'] = get_the_title();
			}
		}

		if ( empty( $image ) || ! isset( $image['url'] ) || empty( $image['url'] ) ) {
			return; 
		}

		if ( $args['lightbox'] ) {
			$atts = 'data-title="' . esc_attr( get_the_title( $post_id ) ) . '" href="' . esc_url( wp_get_attachment_url( $thumb ) ) . '" rel="prettyPhoto"';
		} else {
			$atts = 'href="' . esc_url( get_permalink( $post_id ) ) . '"';
		}

		$srcset = $args['crop'] ? '' : ' srcset="' . esc_attr( wp_get_attachment_image_srcset( $thumb, array( $image['width'], $image['height'] ) ) ) . '"';

		$img_atts = highend_attr( 'featured_image_thumb', $args['img_atts'] );

		?>
		<div class="featured-image item-has-overlay">
			<a <?php echo $atts; ?>>
				<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $args['alt'] ); ?>" width="<?php echo esc_attr( $image['width'] ); ?>" height="<?php echo esc_attr( $image['height'] ); ?>"<?php echo $srcset; ?><?php echo $img_atts; ?> />
				<div class="item-overlay-text">
					<div class="item-overlay-text-wrap">
						<span class="plus-sign"></span>
					</div>
				</div>
			</a>
			<?php echo $caption; ?>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'highend_login_form_template' ) ) : 
	/**
	 * Login form HTML markup.
	 *
	 * @since  3.6.1
	 * @return void
	 */
	function highend_login_form_template() {
		get_template_part( 'template-parts/misc/login-form' );
	}
endif;
add_action( 'highend_login_form', 'highend_login_form_template' );

if ( ! function_exists( 'highend_contact_page_template_form' ) ) : 
	/**
	 * Contact form for Contact page template.
	 *
	 * @since  3.6.1
	 * @return void
	 */
	function highend_contact_page_template_form() {
		get_template_part( 'template-parts/misc/special-contact-form' );
	}
endif;
add_action( 'highend_contact_page_form', 'highend_contact_page_template_form' );

/**
 * Adds the meta tag to the site header.
 *
 * @since 1.0.0
 */
function highend_meta_viewport() {

	if ( highend_option( 'hb_responsive' ) ) {
		echo '<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />';
	} else {
		echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
	}
}
add_action( 'wp_head', 'highend_meta_viewport', 1 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 *
 * @since 1.0.0
 */
function highend_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'highend_pingback_header' );

/**
 * Adds the meta tag for website accent color.
 *
 * @since 1.0.0
 */
function highend_meta_theme_color() {

	$color = highend_get_focus_color();

	if ( $color ) {
		printf( '<meta name="theme-color" content="%s">', esc_attr( $color ) );
	}
}
add_action( 'wp_head', 'highend_meta_theme_color' );

/**
 * Adds the meta tag for apple icons.
 *
 * @since 1.0.0
 */
function highend_meta_apple_icons() {

	// Apple Icon 144x144.
	if ( highend_option( 'hb_apple_icon_144' ) ) {
		echo '<link rel="apple-touch-icon-precomposed" sizes="144x144" href="' . esc_url( highend_option( 'hb_apple_icon_144' ) ) . '" />';
	}
	
	// Apple Icon 114x114.
	if ( highend_option( 'hb_apple_icon_114' ) ) {
		echo '<link rel="apple-touch-icon-precomposed" sizes="114x114" href="' . esc_url( highend_option( 'hb_apple_icon_114' ) ) . '" />';
	}

	// Apple Icon 72x72.
	if ( highend_option( 'hb_apple_icon_72' ) ) {
		echo '<link rel="apple-touch-icon-precomposed" sizes="72x72" href="' . esc_url( highend_option( 'hb_apple_icon_72' ) ) . '" />';
	}
	
	// Apple Icon 57x57.
	if ( highend_option( 'hb_apple_icon' ) ) {
		echo '<link rel="apple-touch-icon-precomposed" sizes="57x57" href="' . esc_url( highend_option( 'hb_apple_icon' ) ) . '" />';
	}

	// Bookmark title.
	if ( ! empty( highend_option( 'hb_ios_bookmark_title' ) ) ) {
		echo '<meta name="apple-mobile-web-app-title" content="' . esc_attr( highend_option( 'hb_ios_bookmark_title' ) ) . '" />';
	}
}
add_action( 'wp_head', 'highend_meta_apple_icons' );

/**
 * Adds OG Image tag to head.
 *
 * @since 1.0.0
 */
function highend_meta_og_image() {

	// SEO Plugin will take care of this.
	if ( highend_is_seo_plugin_installed() ) {
		return;
	}

	$og_image = wp_get_attachment_url( get_post_thumbnail_id(), 'single-post-thumbnail' );
	
	if ( ! empty( $og_image ) ) {
		echo '<meta property="og:image" content="' . esc_url( $og_image ) . '" />';
	}
}
add_action( 'wp_head', 'highend_meta_og_image' );

/**
 * Preload Font Faces
 *
 * @since 3.7
 * @return [type] [description]
 */
function highend_preload_font_faces() {

	// Preload Font
	$font_faces = highend_default_font_faces();

	if ( ! empty( $font_faces ) && is_array( $font_faces ) ) {
		foreach ( $font_faces as $font => $options ) {
			echo '<link rel="preload" href="' . HBTHEMES_URI . '/assets/css/fonts/' . $font . '.woff2" as="font" type="font/woff2" crossorigin>';
		}
	}

	echo '<link rel="preload" href="' . HBTHEMES_URI . '/assets/css/icons/fontawesome-webfont.woff2" as="font" type="font/woff2" crossorigin>';
	echo '<link rel="preload" href="' . HBTHEMES_URI . '/assets/css/icons/Icomoon.woff2" as="font" type="font/woff2" crossorigin>';
	echo '<link rel="preload" href="' . HBTHEMES_URI . '/assets/css/icons/hbicons.woff2" as="font" type="font/woff2" crossorigin>';
}
add_action( 'wp_head', 'highend_preload_font_faces' );

/**
 * Main Content Start.
 *
 * @since 3.5.1
 */
function highend_main_content_start() {

	$layout = highend_get_page_layout();

	if ( 'fullwidth' !== $layout ) {
		echo '<div class="hb-main-content col-9 hb-equal-col-height">';
	} else {
		echo '<div class="hb-main-content col-12">';
	}
}
add_action( 'highend_main_content_start', 'highend_main_content_start' );

/**
 * Main Content End.
 *
 * @since 3.5.1
 */
function highend_main_content_end() {

	echo '</div><!-- END .hb-main-content -->';

	$layout = highend_get_page_layout();

	if ( 'fullwidth' !== $layout ) {

		$sidebar = highend_option( 'hb_choose_sidebar' );

		if ( is_singular() ) {
			$_sidebar = vp_metabox( 'layout_settings.hb_choose_sidebar' );

			if ( ! empty( $_sidebar ) && 'default' !== $_sidebar ) {
				$sidebar = $_sidebar;
			}
		}

		$sidebar = apply_filters( 'highend_sidebar_name', $sidebar );
	
		echo '<div class="hb-sidebar col-3 hb-equal-col-height">';
			
		if ( $sidebar && function_exists( 'dynamic_sidebar' ) ) {
			dynamic_sidebar( $sidebar );
		}

		echo '</div><!-- END .hb-sidebar -->';
	}
}
add_action( 'highend_main_content_end', 'highend_main_content_end' );

/**
 * Blog HTML output.
 *
 * @since 3.5.1
 */
function highend_blog_output( $template = '', $post_id = '' ) {

	if ( have_posts() ) : 
	?>
		<div id="hb-blog-posts" class="<?php highend_blog_class( $template, $post_id ); ?>"<?php highend_blog_attributes( $template, $post_id ); ?> itemprop="mainContentOfPage" itemscope="itemscope" itemtype="https://schema.org/Blog">

			<?php do_action( 'highend_before_blog_loop' ); ?>
			<?php do_action( 'highend_blog_loop', $template ); ?>
			<?php do_action( 'highend_after_blog_loop' ); ?>

		</div><!-- END #hb-blog-posts -->

		<?php highend_pagination( $post_id ); ?>
	
	<?php
	else :
		get_template_part( 'template-parts/content/content', 'none' );
	endif;
}
add_action( 'highend_blog', 'highend_blog_output', 10, 2 );

/**
 * Blog loop.
 *
 * @since 3.5.1
 */
function highend_blog_loop( $template = '' ) {
	while ( have_posts() ) : the_post();
		get_template_part( 'template-parts/entry/entry', $template );
	endwhile;
}
add_action( 'highend_blog_loop', 'highend_blog_loop' );

/**
 * Before blog listing section. Print more info on non-singular pages.
 *
 * @since 3.5.1
 */
function highend_before_blog_listing() {

	$separator = '<div class="hb-separator extra-space"><div class="hb-fw-separator"></div></div>';

	if ( is_author() ) {
		get_template_part( 'template-parts/misc/author-info' ); 
		echo $separator;
	} elseif ( is_category() && category_description() ) {
		echo category_description();
		echo $separator;
	} elseif ( is_tag() && tag_description() ) {		
		echo tag_description();
		echo $separator;
	} elseif ( is_search() ) {
		?>
		<div class="aligncenter refine-search-wrapper">
			<h4 class="title-class semi-bold"><?php esc_html_e( 'Not happy with the results?', 'hbthemes' ); ?></h4>
			<h5 class="lighter-text"><?php esc_html_e( 'Type your search again', 'hbthemes' ); ?></h5>
			<?php get_search_form(); ?>
		</div>
		<?php
		echo $separator;
	}	
}
add_action( 'highend_main_content_start', 'highend_before_blog_listing', 20 );

/**
 * Preloader.
 *
 * @since 3.5.2
 */
function highend_preloader() {

	if ( 'circle-spinner' === highend_option( 'hb_queryloader' ) ) {
		echo '<div id="hb-preloader"><span class="default-loading-icon"></span></div>';
	}
}
add_action( 'highend_before_page_wrapper', 'highend_preloader' );

/**
 * Alternative mobile menu.
 *
 * @since 3.5.2
 */
function highend_mobile_menu_output() {

	if ( highend_option( 'hb_responsive' ) ) {
		highend_mobile_menu();
	}
}
add_action( 'highend_before_page_wrapper', 'highend_mobile_menu_output' );

/**
 * Header Layout - Side Navigation.
 *
 * @since 3.5.2
 */
function highend_side_navigation() {

	if ( 'left-panel' !== highend_get_header_layout() ) {
		return;
	}

	get_template_part( 'template-parts/header/side-navigation' );
}
add_action( 'highend_before_page_wrapper', 'highend_side_navigation' );

/**
 * Container for side section.
 *
 * @since 3.5.2
 */
function highend_side_section() {

	// Check if side section is enabled.
	if ( ! highend_option( 'hb_side_section' ) ) {
		return;
	}

	?>
	<div id="hb-side-section">
		
		<a href="#" class="hb-close-side-section"><i class="hb-icon-x"></i></a>
		
		<?php
		if ( is_active_sidebar( 'hb-side-section-sidebar' ) ) {
			dynamic_sidebar( 'hb-side-section-sidebar' );
		} else {
			echo '<p class="aligncenter" style="margin-top:30px;">';
			esc_html_e( 'Please add widgets to this widgetized area ("Side Panel Section") in Appearance > Widgets.', 'hbthemes' );
			echo '</p>';
		}
		?>
	</div>
	<?php
}
add_action( 'highend_after_page_wrapper', 'highend_side_section' );

/**
 * Fullscreen form for modern search.
 *
 * @since 3.5.2
 */
function highend_modern_search_form() {

	// Print for modern search only.
	if ( 'hb-modern-search' !== highend_option( 'hb_search_style' ) ) {
		return;
	}

	?>
	<div id="modern-search-overlay">
		
		<a href="#" class="hb-modern-search-close"><i class="hb-icon-x"></i></a>

		<div class="table-middle hb-modern-search-content">
			<p><?php esc_html_e( 'Type and press Enter to search', 'hbthemes' ); ?></p>
			<form method="get" id="hb-modern-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" novalidate="" autocomplete="off">
				<input type="text" value="" name="s" id="hb-modern-search-input" autocomplete="off">
			</form>
		</div>

	</div>
	<?php
}
add_action( 'highend_after_page_wrapper', 'highend_modern_search_form' );

/**
 * Print modal overlay container div.
 *
 * @since 3.5.2
 */
function highend_modal_overlay() {
	echo '<div id="hb-modal-overlay"></div>';
}
add_action( 'highend_after_page_wrapper', 'highend_modal_overlay' );

/**
 * Print map dropdown for header bar.
 *
 * @since 3.5.3
 */
function highend_header_bar_map_dropdown() {

	$map_widget = highend_top_bar_widget_map( '', false );

	if ( ! $map_widget ) {
		return;
	}

	wp_enqueue_script( 'highend-google-map' );

	?>
	<div id="header-dropdown">
		<div id="contact-map" 
			 data-api-key="<?php echo esc_attr( highend_option( 'hb_gmap_api_key' ) ); ?>" 
			 data-map-buttons="<?php echo esc_attr( highend_option( 'hb_enable_map_buttons' ) ); ?>" 
			 data-map-level="<?php echo esc_attr( highend_option( 'hb_map_zoom' ) ); ?>" 
			 data-map-lat="<?php echo esc_attr( highend_option( 'hb_map_latitude' ) ) ?>" 
			 data-map-lng="<?php echo esc_attr( highend_option( 'hb_map_longitude' ) ); ?>" 
			 data-map-img="<?php echo esc_url( highend_option( 'hb_custom_marker_image' ) ); ?>" 
			 data-overlay-color="<?php if ( highend_option( 'hb_enable_map_color' ) ) { echo esc_attr( highend_option( 'hb_map_focus_color' ) ); } else { echo 'none'; } ?>">
		</div>
		<div class="close-map"><i class="hb-moon-close-2"></i></div>
	</div>
	<?php
}
add_action( 'highend_after_header_bar', 'highend_header_bar_map_dropdown' );

/**
 * Header section.
 *
 * @since 3.5.2
 */
function highend_header() {

	if ( 'left-panel' === highend_get_header_layout() ) {
		return;
	}

	?>
	<header id="hb-header" <?php highend_header_class(); ?>>

		<?php
		if ( highend_is_top_bar_displayed() ) {
			get_template_part( 'template-parts/top-bar/top-bar' );
		}
		?>
		<?php get_template_part( 'template-parts/header/base' ); ?>

	</header>
	<?php
}
add_action( 'highend_header', 'highend_header' );

/**
 * Page title section.
 *
 * @since 3.5.2
 */
function highend_page_title() {

	if ( ! highend_is_page_title_displayed() ) {
		return;
	}
	
	get_template_part( 'template-parts/header/page-title' );
}
add_action( 'highend_after_header', 'highend_page_title' );

/**
 * Open container for nav-type-2.
 */
function highend_main_navigation_start() {
	
	$header_layout = highend_get_header_layout();

	if ( 'nav-type-2 centered-nav' === $header_layout || 'nav-type-2' === $header_layout ) {
		echo '<div class="' . highend_get_header_container() . '">';
	}
}
add_action( 'highend_main_navigation_start', 'highend_main_navigation_start' );

/**
 * Close container for nav-type-2.
 */
function highend_main_navigation_end() {

	$header_layout = highend_get_header_layout();

	if ( 'nav-type-2 centered-nav' === $header_layout || 'nav-type-2' === $header_layout ) {
		echo '</div>';
	}
}
add_action( 'highend_main_navigation_end', 'highend_main_navigation_end', 99 );

/**
 * Main Navigation hamburger menu.
 *
 * @since 3.6.7
 */
function highend_main_navigation_searchform() {

	if ( highend_option( 'hb_search_in_menu' ) && 'hb-default-search' === highend_option( 'hb_search_style' ) ) {
		?>
		<div id="fancy-search">
			<form id="fancy-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" novalidate="" autocomplete="off">
				<input type="text" name="s" id="s" placeholder="<?php esc_attr_e( 'Type keywords and press enter', 'hbthemes' ); ?>" autocomplete="off">
			</form>
			<a href="#" id="close-fancy-search" class="no-transition"><i class="hb-icon-x"></i></a>
			<span class="spinner"></span>
		</div>
		<?php
	}
}
add_action( 'highend_main_navigation_end', 'highend_main_navigation_searchform' );

/**
 * Main Navigation hamburger menu.
 *
 * @since 3.6.7
 */
function highend_main_navigation_hamburger() {

	if ( ! highend_option( 'hb_responsive' ) ) {
		return;
	}

	echo wp_kses_post(
		sprintf(
			'<a href="#" id="show-nav-menu">%s</a>',
			apply_filters( 'highend_hamburger_menu', '<i class="icon-bars"></i></a>' )
		)
	);
}
add_action( 'highend_main_navigation_end', 'highend_main_navigation_hamburger' );

/**
 * One Page template navigation bullets.
 *
 * @since 3.5.2
 */
function highend_one_page_navigation() {

	if ( vp_metabox( 'misc_settings.hb_onepage' ) && ! vp_metabox( 'misc_settings.hb_disable_navigation' ) ) {
		echo '<ul id="hb-one-page-bullets"></ul>';
	}
}
add_action( 'highend_before_footer', 'highend_one_page_navigation' );

/**
 * Back to top button.
 *
 * @since 3.5.2
 */
function highend_back_to_top() {

	if ( ! highend_option( 'hb_to_top_button' ) ) {
		return;
	}

	echo wp_kses_post( 
		sprintf(
			'<a id="to-top"><i class="%s"></i></a>', 
			esc_attr( highend_option( 'hb_back_to_top_icon' ) )
		)
	);
}
add_action( 'highend_before_footer', 'highend_back_to_top' );

/**
 * Quick contact form.
 *
 * @since 3.5.2
 */
function highend_quick_contact_form() {

	if ( ! highend_option( 'hb_enable_quick_contact_box' ) ) {
		return;
	}

	get_template_part( 'template-parts/misc/quick-contact-form' );
}
add_action( 'highend_before_footer', 'highend_quick_contact_form' );

/**
 * Pre Footer section.
 *
 * @since 3.5.2
 */
function highend_pre_footer() {

	if ( ! highend_is_pre_footer_displayed() ) {
		return;
	}

	get_template_part( 'template-parts/footer/pre-footer' );
}
add_action( 'highend_before_footer', 'highend_pre_footer' );

/**
 * Footer section.
 *
 * @since 3.5.2
 */
function highend_footer_widgets() {

	if ( ! highend_is_footer_widgets_displayed() ) {
		return;
	}

	get_template_part( 'template-parts/footer/widgets' );
}
add_action( 'highend_footer', 'highend_footer_widgets' );

/**
 * Copyright section in the footer.
 *
 * @since 3.5.2
 */
function highend_copyright() {

	if ( ! highend_is_copyright_displayed() ) {
		return;
	}

	get_template_part( 'template-parts/footer/copyright' );
}
add_action( 'highend_after_footer', 'highend_copyright' );

/**
 * Next - Previous buttons on singular pages.
 *
 * @since 3.5.2
 */
function highend_single_next_prev() {

	$displayed = false;

	if ( is_singular( 'post' ) && highend_option( 'hb_blog_enable_next_prev' ) || 
		 is_singular( 'portfolio' ) && highend_option( 'hb_portfolio_enable_next_prev' ) || 
		 is_singular( 'team' ) && highend_option( 'hb_staff_enable_next_prev' ) ) {
		$displayed = true;
	}

	$displayed = apply_filters( 'highend_single_next_prev_displayed', $displayed );

	if ( ! $displayed ) {
		return;
	}

	$prev_post = get_previous_post();
	$next_post = get_next_post();

	if ( empty( $prev_post ) && empty( $next_post ) ) {
		return;
	}
	?>
	<nav class="hb-single-next-prev">
		
		<?php if ( ! empty( $prev_post ) ) { ?>
			<a href="<?php the_permalink( $prev_post ); ?>" title="<?php echo esc_attr( get_the_title( $prev_post ) ); ?>" class="hb-prev-post">
				<i class="hb-moon-arrow-left-4"></i>
				<span class="text-inside"><?php esc_html_e( 'Prev', 'hbthemes' ); ?></span>
			</a>
		<?php } ?>

		<?php if ( ! empty( $next_post ) ) { ?>
			<a href="<?php the_permalink( $next_post ); ?>" title="<?php echo esc_attr( get_the_title( $next_post ) ); ?>" class="hb-next-post">
				<i class="hb-moon-arrow-right-5"></i>
				<span class="text-inside"><?php esc_html_e( 'Next', 'hbthemes' ); ?></span>
			</a>
		<?php } ?>

	</nav>
	<?php
}
add_action( 'highend_after_footer', 'highend_single_next_prev' );

/**
 * Print custom script from Highend Options into footer scripts.
 *
 * @since 3.5.2
 */
function highend_options_scripts() {

	// Analytics from Theme Options.
	$analytics = highend_option( 'hb_analytics_code' );
	if ( ! empty( $analytics ) ) {
		echo $analytics;
	}

	// Custom Script from Theme Options.
	$custom = highend_option( 'hb_custom_script' );
	if ( ! empty( $custom ) ){

		if ( 0 === strpos( $custom, '<script' ) ) {
			echo $custom;
		} else {
			echo '<script type="text/javascript">' . $custom . '</script>';
		}
	}
}
add_action( 'wp_footer', 'highend_options_scripts' );

/**
 * Archive content.
 *
 * @since  3.6.0
 * @return void
 */
function highend_archive_output() {

	if ( is_tax( 'gallery_categories' ) ) {
		do_action( 'highend_gallery_category_archive' );
	} elseif ( is_tax( 'portfolio_categories' ) ) {
		do_action( 'highend_portfolio_category_archive' );
	} elseif ( is_tax( 'team_categories' ) ) {
		do_action( 'highend_team_category_archive' );
	} else {
		do_action(
			'highend_blog',
			apply_filters(
				'highend_archive_template',
				highend_option( 'hb_archive_template', 'blog-minimal' )
			)
		);
	}
}
add_action( 'highend_archive', 'highend_archive_output' );

/**
 * Archive content for team_category.
 *
 * @since  3.6.0
 * @return void
 */
function highend_team_member_category_archive() {

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

	if ( have_posts() ) :

		$args = apply_filters(
			'highend_team_member_archive_args',
			array(
				'content'     => false,
				'social_link' => false,
				'columns'     => 3,
			)
		);

		$class   = 'col-' . intval( 12 / intval( $args['columns'] ) );
		$class   = apply_filters( 'highend_team_member_archive_class', $class );

		echo '<div id="team-wrapper" class="row related-members">';

		while ( have_posts() ) : the_post();
			echo '<div class="' . esc_attr( $class ) . '">';
			highend_team_member_box( $args, null, true );
			echo '</div>';
		endwhile;

		echo '</div><!-- END #team-wrapper -->';

		echo '<div class="col-12 no-b-margin">';
		highend_pagination_standard();
		echo '</div>';

	endif;
}
add_action( 'highend_team_category_archive', 'highend_team_member_category_archive' );

/**
 * 404 Page Content.
 *
 * @since  3.6.1
 * @return void
 */
function highend_404_page() {

	$highend_404_title          = __( 'File not Found', 'hbthemes' );
	$highend_404_subtitle       = __( 'Sorry, but we couldn\'t find the content you were looking for.', 'hbthemes' );
	$highend_404_button_caption = __( 'Back to our Home', 'hbthemes' );
	$highend_404_icon           = 'hb-moon-construction';

	if ( highend_is_module_enabled( 'hb_module_not_found_page' ) ) {
		$highend_404_title          = highend_option( 'hb_404_title' );
		$highend_404_subtitle       = highend_option( 'hb_404_subtitle' );
		$highend_404_button_caption = highend_option( 'hb_404_button_caption' );
		$highend_404_icon           = highend_option( 'hb_404_icon' );
	}

	$content = apply_filters(
		'highend_404_page_content',
		sprintf(
			'<h1 class="extra-large">%1$s</h1><h4 class="additional-desc">%2$s</h4><div class="hb-separator-s-1"></div><a href="%3$s" class="hb-button">%4$s</a>',
			esc_html( $highend_404_title ),
			esc_html( $highend_404_subtitle ),
			esc_url( home_url() ),
			esc_html( $highend_404_button_caption )
		)
	);
	?>
	<div class="not-found-box aligncenter">

		<div class="not-found-box-inner">

			<?php do_action( 'highend_404_page_before_content' ); ?>

			<?php echo wp_kses_post( $content ); ?>

			<?php do_action( 'highend_404_page_after_content' ); ?>

		</div>

		<i class="<?php echo esc_attr( $highend_404_icon ); ?>"></i>
	</div>
	<?php
}
add_action( 'highend_404_page', 'highend_404_page' );

if ( ! function_exists( 'highend_post_author_infobox' ) ) {
	/**
	 * Single post author infobox.
	 *
	 * @since  3.6.4
	 * @param  int $post_id Post ID.
	 * @return void
	 */
	function highend_post_author_infobox( $post_id = '' ) {

		$enabled = highend_option( 'hb_blog_author_info' ) && is_singular( 'post' );
		$enabled = apply_filters( 'highend_enable_post_author_infobox', $enabled, $post_id );

		if ( $enabled ) { 
			get_template_part( 'template-parts/misc/author-info' ); 
		}
	}
}
add_action( 'highend_after_single_content', 'highend_post_author_infobox' );

if ( ! function_exists( 'highend_post_related_articles' ) ) {
	/**
	 * Single post related articles.
	 *
	 * @since  3.6.4
	 * @param  int $post_id Post ID.
	 * @return void
	 */
	function highend_post_related_articles( $post_id = '' ) {

		$post_id = empty( $post_id ) ? highend_get_the_id() : $post_id;

		$enabled = is_singular( 'post' ) && highend_option( 'hb_blog_enable_related_posts' );

		if ( ! apply_filters( 'highend_enable_related_posts', $enabled, $post_id ) ) {
			return;
		}

		$args = array(
			'tag__in'             => wp_get_post_tags( $post_id, array( 'fields' => 'ids' ) ),
			'post__not_in'        => (array) $post_id,
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => 1,
			'orderby'             => 'rand',
			'post_status'         => 'publish',
		);

		$args = apply_filters( 'highend_post_related_articles_args', $args, $post_id );

		// Query related articles.
		$query = new WP_Query( $args );

		if ( $query->have_posts() ) :

			$title = apply_filters( 'highend_related_posts_title', esc_html__( 'You also might be interested in', 'hbthemes' ) );
			?>
			<section class="hb-related-posts clearfix">

				<?php if ( $title ) { ?>
					<h4 class="semi-bold aligncenter"><?php echo wp_kses_post( $title ); ?></h4>
				<?php } ?>

				<div class="row">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>

						<div class="col-4 related-item">

							<?php
							if ( has_post_thumbnail() ) {

								$image_args = apply_filters(
									'highend_related_post_image_args',
									array(
										'width'  => 300,
										'height' => 200,
										'crop'   => true,
									)
								);

								highend_featured_image_thumb( $image_args );
							}
							?>

							<div class="post-content">

								<div class="post-header clearfix">
									<h2 class="title entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
									<div class="post-meta-info">
										<time class="date-container minor-meta updated date float-left" itemprop="datePublished" datetime="<?php the_time('c'); ?>"><?php the_time('M j, Y'); ?></time>
									</div>
								</div><!-- END .post-header -->

								<p class="hb-post-excerpt clearfix">
									<?php 
										if ( has_excerpt() ) {
											echo wp_kses_post( get_the_excerpt() );
										} else {
											echo wp_kses_post( wp_trim_words( wp_strip_all_tags( apply_filters( 'the_content', get_the_content() ) ), 10 , '[...]' ) );
										}
									?>
								</p>

							</div><!-- END .post-content -->

						</div><!-- END .related-item-->

					<?php endwhile; ?>
				</div>

			</section>

			<div class="hb-separator-extra"></div>
			<?php
		endif;

		wp_reset_query();
	}
}
add_action( 'highend_after_single_content', 'highend_post_related_articles' );

if ( ! function_exists( 'highend_single_comments_template' ) ) {
	/**
	 * Display comment form if enabled.
	 * 
	 * @return void
	 */
	function highend_single_comments_template() {

		if ( comments_open() ) {
			comments_template();
		}
	}
}
add_action( 'highend_after_single_content', 'highend_single_comments_template' );

/**
 * Side Navigation background image.
 *
 * @since 3.6.6
 */
function highend_side_navigation_bg_image() {

	$highend_side_nav_bg_image = apply_filters( 'highend_side_nav_bg_image', highend_option( 'hb_side_nav_bg' ) );

	if ( ! empty( $highend_side_nav_bg_image ) ) {
		echo '<div class="hb-side-background" style="background-image: url(' . esc_url( $highend_side_nav_bg_image ) . ');"></div>';
	}
} 
add_action( 'highend_side_navigation_start', 'highend_side_navigation_bg_image' );

/**
 * Side Navigation mobile menu.
 *
 * @since 3.6.6
 */
function highend_side_navigation_mobile_menu() {
	echo '<div class="hb-resp-bg"><a href="#" id="show-nav-menu"><i class="icon-bars"></i></a></div>';
}
add_action( 'highend_side_navigation_start', 'highend_side_navigation_mobile_menu' );

/**
 * Side Navigation bottom bar.
 * 
 * @since 3.6.6
 */
function highend_side_navigation_bottom_bar() {

	$social_links = highend_option( 'hb_side_nav_enable_socials' ) ? highend_social_icons_output( array(), false, false ) : '';

	$bottom_text = highend_option( 'hb_side_nav_bottom_text' );
	$bottom_text = ! empty( $bottom_text ) ? '<div class="side-nav-bottom-text">' . do_shortcode( $bottom_text ) . '</div>' : '';

	if ( ! empty( $social_links ) || ! empty( $bottom_text ) ) {
		echo wp_kses_post(
			sprintf(
				'<div class="side-nav-bottom-part">%1$s%2$s</div>',
				$social_links,
				$bottom_text
			)
		);
	}
}
add_action( 'highend_side_navigation_end', 'highend_side_navigation_bottom_bar' );

/**
 * Outputs theme logo markup.
 *
 * @since 3.6.6
 * @param boolean|string $echo - Print the logo or return as string.
 */
function highend_logo( $echo = true ) {

	$site_title = get_bloginfo( 'name' );

	$logo_images = array(
		'standard'     => highend_option( 'hb_logo_option' ),
		'retina'       => highend_option( 'hb_logo_option_retina' ),
		'light'        => highend_option( 'hb_logo_light_option' ),
		'light_retina' => highend_option( 'hb_logo_light_option_retina' ),
	);

	$post_id     = highend_get_the_id();
	$alternative = $post_id ? vp_metabox( 'misc_settings.hb_page_alternative_logo', null, $post_id ) : false;

	if ( ! empty( $alternative ) ) {
		$logo_images['standard'] = $alternative;
		$logo_images['retina']   = $alternative;
	}

	$output = '';

	// Standard logo.
	if ( ! empty( $logo_images['standard'] ) ) {

		$image = '<img src="' . esc_url( $logo_images['standard'] ) . '" class="default" alt="' . esc_attr( $site_title ) . '"/>';

		if ( $logo_images['retina'] ) {
			$image .= '<img src="' . esc_url( $logo_images['retina'] ) . '" class="retina" alt="' . esc_attr( $site_title ) . '"/>';
		}

		$output .= '<div class="hb-dark-logo hb-logo-wrap hb-visible-logo"><a href="' . esc_url( home_url( '/' ) ) . '">' . $image . '</a></div>';
	}

	// Light Logo.
	if ( ! empty( $logo_images['light'] ) ) {

		$image = '<img src="' . esc_url( $logo_images['light'] ) . '" class="default" alt="' . esc_attr( $site_title ) . '"/>';

		if ( $logo_images['light_retina'] ) {
			$image .= '<img src="' . esc_url( $logo_images['light_retina'] ) . '" class="retina" alt="' . esc_attr( $site_title ) . '"/>';
		}

		$output .= '<div class="hb-light-logo hb-logo-wrap"><a href="' . esc_url( home_url( '/' ) ) . '">' . $image . '</a></div>';
	}

	// No image logo set.
	if ( empty( $output ) ) {
		$output = '<h1><a href="' . esc_url( home_url( '/' ) ) . '" class="plain-logo">' . esc_html( $site_title ) . '</a></h1>';
	}

	$output = '<div id="logo">' . $output . '</div>';

	// Allow output to be filtered.
	$output = apply_filters( 'highend_logo_output', $output );

	// Echo or return the output.
	if ( $echo ) {
		echo $output; // phpcs:ignore
	} else {
		return $output;
	}
}

function highend_single_content() {
	get_template_part( 'template-parts/single/single', get_post_type() );
}
add_action( 'highend_single_content', 'highend_single_content' );

/**
 * Slider section.
 *
 * @since 3.7
 */
function highend_featured_section_template() {

	if ( highend_is_page_template( 'presentation-fullwidth' ) ) {
		return;
	}

	get_template_part( 'template-parts/header/featured-section' );
}
add_action( 'highend_after_header', 'highend_featured_section_template' );

/**
 * Featured section animation effect.
 *
 * @since  3.7
 * @return void
 */
function highend_featured_section_effect() {

	if ( ! is_singular() ) {
		return;
	}

	if ( 'none' !== vp_metabox( 'featured_section.hb_featured_section_effect', null, highend_get_the_id() ) ) {
		echo '<canvas id="hb-canvas-effect"></canvas>';
	}
}
add_action( 'highend_featured_section_before', 'highend_featured_section_effect' );

/**
 * Featured section content.
 * 
 * @since  3.7
 * @return void
 */
function highend_featured_section( $post_id = '' ) {

	$post_id = empty( $post_id ) ? highend_get_the_id() : $post_id;

	if ( is_singular( array( 'page', 'team' ) ) ) {

		$section_type = vp_metabox( 'featured_section.hb_featured_section_options', null, $post_id );

		highend_featured_section_content( $section_type, $post_id );
	}
}
add_action( 'highend_featured_section', 'highend_featured_section' );
