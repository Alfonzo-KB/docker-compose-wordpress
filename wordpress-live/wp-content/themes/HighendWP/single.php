<?php
/**
 * The template for displaying single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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

				<?php
				do_action( 'highend_main_content_start' );

				do_action( 'highend_before_single_content' );
				do_action( 'highend_single_content' );
				do_action( 'highend_after_single_content' );

				do_action( 'highend_main_content_end' );
				?>

			<?php endwhile; endif; ?>

		</div><!-- END .row -->

	</div><!-- END .container -->

</div><!-- END #main-content -->

<?php get_footer(); ?>
