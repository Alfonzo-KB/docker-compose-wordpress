<?php
/**
 * Template Name: Blank Template
 * 
 * @package Highend
 * @since   1.0.0
 */

?>

<?php get_header(); ?>

<div id="main-content"<?php highend_main_content_style(); ?>>

	<div class="container">

		<div class="row main-row">

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				<div id="page-<?php the_ID(); ?>" <?php post_class( 'col-12' ); ?>>
					<?php the_content(); ?>
				</div>

			<?php endwhile; endif; ?>

		</div><!-- END .row -->

	</div><!-- END .container -->

</div><!-- END #main-content -->

<?php get_footer(); ?>
