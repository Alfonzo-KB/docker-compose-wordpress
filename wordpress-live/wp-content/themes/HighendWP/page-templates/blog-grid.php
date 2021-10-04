<?php
/**
 * Template Name: Blog - Grid
 *
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
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

			<?php $page_id = get_the_ID(); ?>
		
			<div id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
				
				<?php do_action( 'highend_main_content_start' ); ?>

				<?php if ( get_the_content() ) { ?> 
					<?php the_content(); ?>
					<div class="hb-separator extra-space"><div class="hb-fw-separator"></div></div>
				<?php } ?>

				<?php query_posts( highend_get_blog_query_args( $page_id ) ); ?>

				<?php do_action( 'highend_blog', 'blog-grid', $page_id ); ?>

				<?php wp_reset_query(); ?>

				<?php				
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
