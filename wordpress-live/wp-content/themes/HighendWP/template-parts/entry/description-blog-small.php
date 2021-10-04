<?php
/**
 * Template part for displaying entry description for template: Blog Small.
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
<div class="small-post-content">

	<h3 class="title" itemprop="headline">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h3>

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
</div><!-- END .small-post-content -->

<div class="clear"></div>

<div class="meta-info clearfix">

	<div class="float-left">

		<?php
		if ( highend_option( 'hb_blog_enable_by_author' ) ) {
			highend_entry_meta_author( __( 'By' , 'hbthemes' ) );
		}
		
		if ( highend_option( 'hb_blog_enable_date' ) ) {
			highend_entry_meta_date();
		}

		if ( has_category() && highend_option( 'hb_blog_enable_categories' ) ) {
			highend_entry_meta_categories();
		}

		if ( highend_comments_open() ) {
			highend_entry_meta_comments();
		}
		?>
	</div>

	<?php if ( highend_option( 'hb_blog_read_more_button' ) ) { ?>
		<div class="float-right">
			<a href="<?php the_permalink(); ?>" class="read-more-button"><?php esc_html_e( 'Read More ' , 'hbthemes' ); ?><i class="icon-double-angle-right"></i></a>
		</div>
	<?php } ?>
	
</div>
