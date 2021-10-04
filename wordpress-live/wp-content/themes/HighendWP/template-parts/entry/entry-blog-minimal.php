<?php
/**
 * Template part for displaying blog entry - minimal.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package     Highend
 * @since       3.5.1
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-entry clearfix' ); ?> itemscope itemtype="https://schema.org/BlogPosting">

	<a href="<?php the_permalink(); ?>" class="search-thumb" title="<?php the_title(); ?>">
		<?php
		$image = has_post_thumbnail() ? highend_resize( get_post_thumbnail_id(), 80, 80 ) : false;

		if ( is_array( $image ) && isset( $image['url'] ) && ! empty( $image['url'] ) ) {

			echo wp_kses_post( sprintf(
				'<img src="%1$s" alt="%2$s" />',
				esc_url( $image['url'] ),
				esc_attr( get_the_title() )
			) );

		} else {

			$format = get_post_format();
			$icon   = 'hb-moon-file-3';

			if ( 'video' === $format ) {
				$icon = 'hb-moon-play-2';
			} elseif ( 'status' === $format || 'standard' === $format ) {
				$icon = 'hb-moon-pencil';
			} elseif ( 'gallery' === $format || 'image' === $format ){
				$icon = 'hb-moon-image-3';
			} elseif ( 'audio' === $format ) {
				$icon = 'hb-moon-music-2';
			} elseif ( 'quote' === $format ) {
				$icon = 'hb-moon-quotes-right';
			} elseif ( 'link' === $format ) {
				$icon = 'hb-moon-link-5';
			}

			$icon = apply_filters( 'highend_blog_minimal_icon', $icon, $format );

			echo wp_kses_post( sprintf(
				'<i class="%s"></i>',
				$icon
			) );
		}
		?>
	</a>

	<h4 class="semi-bold">
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
	</h4>

	<?php if ( highend_option( 'hb_blog_enable_date' ) ) { ?>
		<div class="post-meta-info">
			<div class="minor-meta"><?php echo esc_html( get_the_time( 'M j, Y' ) ); ?></div>
		</div>
	<?php } ?>

	
	<div class="excerpt-wrap">
		<?php 
		if ( post_password_required() ) {
			echo '<p>' . esc_html_e( 'This content is password protected. To view it please go to the post page and enter the password.', 'hbthemes' ) . '</p>';
		} else { 
			if ( highend_option( 'hb_blog_excerpt_disable' ) )  {
				the_content();
			} elseif ( has_excerpt() ) {
				the_excerpt();
			} else {
				$custom_excerpt = wp_trim_words( 
					wp_strip_all_tags( apply_filters( 'the_content', get_the_content() ) ),
					highend_option( 'hb_blog_excerpt_length' ),
					'...'
				);

				if ( ! empty( $custom_excerpt ) ) {
					echo wp_kses_post( '<p>' . $custom_excerpt . '</p>' );
				}
			}
		}
		?>	
	</div>
	
</article>
