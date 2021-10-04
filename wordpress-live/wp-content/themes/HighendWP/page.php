<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Highend
 * @since   1.0.0
 */

?>

<?php get_header(); ?>

<div id="main-content"<?php highend_main_content_style(); ?>>

	<div class="container">
	
		<div class="row main-row <?php echo highend_get_page_layout(); ?>">

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				<div id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
				
					<?php do_action( 'highend_main_content_start' ); ?>

					<?php
					the_content();

					wp_link_pages( array(
						'before' => '<div id="hb-page-links">' . esc_html__( 'Pages:', 'hbthemes' ),
						'after'  => '</div>',
					) );
					
					if ( comments_open() && highend_option( 'hb_disable_page_comments' ) ) {
						comments_template(); 
					}
					?>

					<?php do_action( 'highend_main_content_end' ); ?>
				
				</div>

			<?php endwhile; endif; ?>	

		</div><!-- END .row -->

	</div><!-- END .container -->

</div><!-- END #main-content -->

<?php get_footer(); ?>
