<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Highend
 * @since 3.5.0
 */

/**
 * Filters the amount of words used in the comment excerpt.
 *
 * @since 3.5.0
 * @param int $comment_excerpt_length The amount of words you want to display in the comment excerpt.
 */
function highend_comment_excerpt_length( $comment_excerpt_length ) {
	return 10;
}
add_filter( 'comment_excerpt_length', 'highend_comment_excerpt_length' );

/**
 * Shortcode paragraph fix.
 *
 * @since  3.7
 * @param  string $content Shortcode content.
 * @return string          Shortcode content.
 */
function highend_shortcode_empty_paragraph_fix( $content ) {

	$array   = array(
		'<p>['    => '[',
		']</p>'   => ']',
		'<br/>['  => '[',
		']<br/>'  => ']',
		']<br />' => ']',
		'<br />[' => '[',
	);

	$content = strtr( $content, $array );

	return $content;
}
add_filter( 'the_content', 'highend_shortcode_empty_paragraph_fix' );

/**
 * Remove template parts for page templates.
 *
 * @since 3.5.2
 */
function highend_remove_template_parts() {

	if ( highend_is_page_template( 'blank' ) ) {
		remove_action( 'highend_before_page_wrapper', 'highend_side_navigation' );
		remove_action( 'highend_header', 'highend_header' );
		remove_action( 'highend_after_header', 'highend_page_title' );
		remove_action( 'highend_after_header', 'highend_slider_section' );
		remove_action( 'highend_before_footer', 'highend_back_to_top' );
		remove_action( 'highend_before_footer', 'highend_quick_contact_form' );
	}

	if ( highend_is_page_template( array( 'presentation-fullwidth', 'blank' ) ) ) {
		remove_action( 'highend_before_footer', 'highend_pre_footer' );
		remove_action( 'highend_footer', 'highend_footer_widgets' );
		remove_action( 'highend_after_footer', 'highend_copyright' );
	}
}
add_action( 'template_redirect', 'highend_remove_template_parts' );

/**
 * Redirect to About page when activated.
 *
 * @since 3.7
 * @return void
 */
function highend_redirect_on_activation() {

	global $pagenow;
	
	if ( is_admin() && isset( $_GET['activated'] ) && 'themes.php' === $pagenow ) {
		wp_safe_redirect( admin_url( '/admin.php?page=hb_about' ) );
		exit;
	}
}
add_action( 'admin_init', 'highend_redirect_on_activation' );

/**
 * Load Highend Maintenance mode if enabled.
 */
function highend_maintenace_mode() {

	if ( ! highend_is_module_enabled( 'hb_module_coming_soon_mode' ) ) {
		return;
	}

	$_param = isset( $_GET['hb_maintenance'] ) ? sanitize_text_field( $_GET['hb_maintenance'] ) : '';

	if ( highend_is_maintenance() || 'yes' === $_param ) {
		get_template_part( 'page-templates/maintenance' );
		exit;
	}
}
add_action( 'get_header', 'highend_maintenace_mode' );

if ( ! function_exists( 'highend_entry_meta_author' ) ) :
	/**
	 * Display author name in post meta.
	 */
	function highend_entry_meta_author( $title = '' ) {
		?>
		<span class="blog-author minor-meta">
			<?php
			if ( $title ) {				
				echo esc_html( $title ); //esc_html_e( 'Posted by' , 'hbthemes' );
			}
			?>
			<span class="entry-author-link" itemprop="name">
				<span class="vcard author">
					<span class="fn">
						<a href="<?php echo esc_url( get_author_posts_url ( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php esc_html_e( 'Posts by', 'hbthemes' ); echo ' '; the_author_meta( 'display_name' ); ?>" rel="author"><?php the_author_meta( 'display_name' ); ?></a>
					</span>
				</span>
			</span>
		</span>
		<?php
	}
endif;

if ( ! function_exists( 'highend_entry_meta_date' ) ) :
	/**
	 * Display date in post meta.
	 */
	function highend_entry_meta_date() {
		?>
		<span class="post-date minor-meta date updated">
			<time datetime="<?php echo esc_attr( get_the_time('c') ); ?>" itemprop="datePublished">
				<?php the_time( get_option( 'date_format' ) ); ?>
			</time>
		</span>
		<?php
	}
endif;

if ( ! function_exists( 'highend_entry_meta_categories' ) ) :
	/**
	 * Display categories in post meta.
	 */
	function highend_entry_meta_categories() {
		?>
		<span class="blog-categories minor-meta"> 
			<?php echo wp_kses_post( get_the_category_list( ', ') ); ?>
		</span>
		<?php
	}
endif;

if ( ! function_exists( 'highend_entry_meta_comments' ) ) :
	/**
	 * Display comments count in post meta.
	 */
	function highend_entry_meta_comments() {
		?>
		<span class="comment-container minor-meta">
			<?php comments_popup_link( esc_html__( 'No Comments', 'hbthemes' ), esc_html__( '1 Comment', 'hbthemes' ), esc_html__( '% Comments', 'hbthemes' ), 'comments-link scroll-to-comments' ); ?>
		</span>
		<?php
	}
endif;

if ( ! function_exists( 'highend_ajax_search' ) ) :
	/**
	 * @todo Refactor code.
	 * 
	 * @since 3.7
	 */
	function highend_ajax_search() {

		$search_term  = $_REQUEST['term'];
		$search_term  = apply_filters( 'get_search_query', $search_term );
		$search_array = array(
			's'                => $search_term,
			'showposts'        => 5,
			'post_type'        => 'any',
			'post_status'      => 'publish',
			'post_password'    => '',
			'suppress_filters' => true,
		);
		$query        = http_build_query( $search_array );
		$posts        = get_posts( $query );
		$suggestions  = array();
		global $post;
		foreach ( $posts as $post ) :
			setup_postdata( $post );
			$suggestion  = array();
			$format      = get_post_format( get_the_ID() );
			$icon_to_use = 'hb-moon-file-3';
			if ( $format == 'video' ) {
				$icon_to_use = 'hb-moon-play-2';
			} elseif ( $format == 'status' || $format == 'standard' ) {
				$icon_to_use = 'hb-moon-pencil';
			} elseif ( $format == 'gallery' || $format == 'image' ) {
				$icon_to_use = 'hb-moon-image-3';
			} elseif ( $format == 'audio' ) {
				$icon_to_use = 'hb-moon-music-2';
			} elseif ( $format == 'quote' ) {
				$icon_to_use = 'hb-moon-quotes-right';
			} elseif ( $format == 'link' ) {
				$icon_to_use = 'hb-moon-link-5';
			}
			$suggestion['label'] = esc_html( $post->post_title );
			$suggestion['link']  = get_permalink();
			$suggestion['date']  = get_the_time( 'F j Y' );
			$suggestion['image'] = ( has_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail(
				$post->ID,
				'thumbnail',
				array(
					'title' => '',
				)
			) : '<i class="' . $icon_to_use . '"></i>';
			$suggestions[]       = $suggestion;
		endforeach;
		// JSON encode and echo
		$response = $_GET['callback'] . '(' . json_encode( $suggestions ) . ')';
		echo $response;
		exit;
	}
endif;
add_action( 'wp_ajax_hb_ajax_search', 'highend_ajax_search' );
add_action( 'wp_ajax_nopriv_hb_ajax_search', 'highend_ajax_search' );

if ( ! function_exists( 'highend_sending_mail' ) ) :
	/**
	 * @todo Refactor code.
	 * @since 3.7
	 * @return [type] [description]
	 */
	function highend_sending_mail() {
		$site     = get_site_url();
		$subject  = __( 'New Message!', 'hbthemes' );
		$email    = $_POST['contact_email'];
		$email_s  = filter_var( $email, FILTER_SANITIZE_EMAIL );
		$comments = stripslashes( $_POST['contact_comments'] );
		$name     = stripslashes( $_POST['contact_name'] );
		$to       = highend_option( 'hb_contact_settings_email' );
		$message  = "Name: $name \n\nEmail: $email \n\nMessage: $comments \n\nThis email was sent from $site";
		$headers  = 'From: ' . $name . ' <' . $email_s . '>' . "\r\n" . 'Reply-To: ' . $email_s;
		wp_mail( $to, $subject, $message, $headers );
		exit();
	}
endif;
add_action( 'wp_ajax_mail_action', 'highend_sending_mail' );
add_action( 'wp_ajax_nopriv_mail_action', 'highend_sending_mail' );

if ( ! function_exists( 'highend_custom_fields' ) ) :
	function highend_custom_fields( $fields ) {

		$commenter     = wp_get_current_commenter();
		$req           = get_option( 'require_name_email' );
		$aria_req      = ( $req ? " aria-required='true' required='required'" : '' );
		$comment_field = $fields['comment'];
		$cookies       = '';

		if ( get_option( 'show_comments_cookies_opt_in' ) ) {

			if ( isset( $fields['cookies'] ) ) {
				$cookies = $fields['cookies'];
			} else {
				$cookies = '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"><label for="wp-comment-cookies-consent">' . esc_html__( 'Save my name and email in this browser for the next time I comment.', 'hbthemes' ) . '</label></p>';
			}
		}

		unset( $fields['comment'] );
		unset( $fields['cookies'] );

		if ( isset( $fields['author'] ) ) {
			$fields['author'] = '<p class="comment-form-author"><input id="author" name="author" type="text" placeholder="' . __( 'Your name *', 'hbthemes' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" tabindex="107"' . $aria_req . ' /></p>';
		}

		if ( isset( $fields['email'] ) ) {
			$fields['email'] = '<p class="comment-form-email"><input id="email" name="email" type="email" placeholder="' . __( 'Your email address *', 'hbthemes' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="40"  tabindex="108"' . $aria_req . ' /></p>';
		}

		if ( isset( $fields['url'] ) ) {
			$fields['url'] = '<p class="comment-form-url"><input id="url" placeholder="' . __( 'Your website URL', 'hbthemes' ) . '" name="url" type="text" value="" tabindex="109" size="30" maxlength="200"></p>';
		}

		$fields['comment'] = $comment_field;

		if ( ! empty( $cookies ) ) {
			$fields['cookies'] = $cookies;
		}

		return $fields;
	}
endif;
add_filter( 'comment_form_fields', 'highend_custom_fields' );

if ( ! function_exists( 'highend_search_filter' ) ) :
	function highend_search_filter( $query ) {
		if ( ! is_admin() && $query->is_main_query() && $query->is_search ) {
			$query->set( 's', rtrim( get_search_query() ) );
		}
	}
endif;
add_action( 'pre_get_posts', 'highend_search_filter' );

/**
 * Custom logo on login screen.
 *
 * @since  3.7.0
 * @return void
 */
function highend_custom_login_logo() {

	if ( highend_option( 'hb_wordpress_logo' ) ) {
		echo '
			<style type="text/css">
				h1 a {
					background-image:url(' . esc_url( highend_option( 'hb_wordpress_logo' ) ) . ') !important;
					background-size: contain !important;
					width:274px !important;
					height: 63px !important;
				}
			</style>
		';
	}
}
add_action( 'login_head', 'highend_custom_login_logo' );

/**
 * Login logo URL.
 *
 * @since  3.7.0
 * @param  string $url Logo URL.
 * @return string      Logo URL.
 */
function highend_custom_login_logo_url( $url ) {
	return get_site_url();
}
add_filter( 'login_headerurl', 'highend_custom_login_logo_url' );

if ( ! function_exists( 'highend_featured_section_content' ) ) :
	/**
	 * Featured section content.
	 *
	 * @since  3.7.0
	 * @return void
	 */
	function highend_featured_section_content( $type, $post_id = '' ) {
		
		$post_id = empty( $post_id ) ? highend_get_the_id() : $post_id;

		switch ( $type ) {

			case 'revolution':

				$rev_slider = vp_metabox( 'featured_section.hb_rev_slider', null, $post_id );

				if ( ! empty( $rev_slider ) ) {
					echo do_shortcode( '[rev_slider ' . $rev_slider . ']' );
				}
				break;

			case 'layer':

				$layer_slider = vp_metabox( 'featured_section.hb_layer_slider', null, $post_id );

				if ( ! empty( $layer_slider ) ) {
					echo do_shortcode( '[layerslider id="' . $layer_slider . '"]' );
				}
				break;

			case 'video':

				$video_link = vp_metabox( 'featured_section.hb_page_video', null, $post_id );

				if ( ! empty( $video_link ) ) {
					echo wp_oembed_get( $video_link );
				}
				break;

			case 'featured_image' :

				$image_size = vp_metabox( 'featured_section.hb_featured_section_height', null, $post_id );
				$image_url  = get_the_post_thumbnail_url( $post_id, 'full' );

				$hide_featured_image = vp_metabox( 'general_settings.hb_hide_featured_image', null, $post_id );

				if ( ! $hide_featured_image && ! empty( $image_url ) && ( empty( $image_size ) || 'original' === $image_size ) ) {
					echo '<img class="fw-image" src="' . esc_attr( $image_url ) . '"/>';
				}
				break;

			default:
				break;
		}
	}
endif;
