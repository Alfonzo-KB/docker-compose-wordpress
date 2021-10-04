<?php
/**
 * The template for displaying search results pages.
 *
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 * 
 * @package Highend
 * @since   1.0.0
 */

?>

<?php get_header(); ?>

<div id="main-content">

	<div class="container">

		<div class="row main-row <?php echo highend_get_page_layout(); ?>">

			<?php do_action( 'highend_main_content_start' ); ?>

			<?php do_action( 'highend_blog', apply_filters( 'highend_search_template', highend_option( 'hb_search_template', 'blog-minimal' ) ) ); ?>

			<?php do_action( 'highend_main_content_end' ); ?>

		</div><!-- END .row -->

	</div><!-- END .container -->

</div><!-- END #main-content -->

<?php get_footer(); ?>
