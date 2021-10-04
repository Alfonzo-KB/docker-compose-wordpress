<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 * 
 * @package Highend
 * @since   1.0.0
 */

?>

<?php get_header(); ?>

<div id="main-content">

	<div class="container">

		<?php do_action( 'highend_404_page_start' ); ?>

		<?php do_action( 'highend_404_page' ); ?>

		<?php do_action( 'highend_404_page_end' ); ?>

	</div><!-- END .container -->

</div><!-- END #main-content -->

<?php get_footer(); ?>
