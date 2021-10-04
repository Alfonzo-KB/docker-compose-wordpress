<?php
/**
 * The template for displaying single testimonial content.
 * 
 * @package  Highend
 * @author   HB-Themes
 * @since    3.6.14
 * @version  3.6.14
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemType="https://schema.org/BlogPosting">
	
	<?php highend_testimonial_box(); ?>

	<section class="bottom-meta-section clearfix">
		<?php
		if ( highend_comments_open() ) {			
			comments_popup_link( esc_html__( '0 Comments', 'hbthemes' ), esc_html__( '1 Comment', 'hbthemes' ), esc_html__( '% Comments', 'hbthemes' ), 'comments-link scroll-to-comments' );
		}

		if ( highend_option( 'hb_blog_enable_share' ) ) {
			get_template_part ( 'template-parts/misc/share-dropdown' );
		}

		if ( highend_option( 'hb_blog_enable_likes' ) ) {
			echo hb_print_likes( get_the_ID() );
		}
		?>
	</section><!-- END .bottom-meta-section -->
</article>
