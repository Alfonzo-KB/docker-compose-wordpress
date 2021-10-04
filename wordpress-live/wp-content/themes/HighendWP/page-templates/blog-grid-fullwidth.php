<?php
/**
 * Template Name: Blog - Fullwidth
 *
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Highend
 * @since   1.0.0
 */

$background_image = vp_metabox( 'blog_fw_page_settings.hb_background_image' );
$background_image = ! empty( $background_image ) ? ' style="background-image:url(' . esc_url( $background_image ) . '); padding-top:70px; margin-top:-70px !important;"' : ''; 
?>

<?php get_header(); ?>

<div id="main-content"<?php highend_main_content_style(); ?>>
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<?php $page_id = get_the_ID(); ?>

		<div class="row extra-wide-container"<?php echo $background_image; ?>>
			
			<div class="extra-wide-inner clearfix">
					
				<div id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
					
					<?php query_posts( highend_get_blog_query_args( $page_id ) ); ?>

					<?php do_action( 'highend_blog', 'blog-grid-fullwidth', $page_id ); ?>

					<?php wp_reset_query(); ?>
					
				</div>

			</div><!-- BEGIN .extra-wide-inner -->

		</div><!-- END .extra-wide-container -->

		<?php if ( comments_open() && highend_option('hb_disable_page_comments') ) { ?>
			<!-- Comments -->
			<div class="container">
				<div class="row">
					<div class="col-12">
						<?php comments_template(); ?>
					</div>
				</div>
			</div> 
		<?php } ?>

	<?php endwhile; endif; ?>	

</div><!-- END #main-content -->

<?php get_footer(); ?>
