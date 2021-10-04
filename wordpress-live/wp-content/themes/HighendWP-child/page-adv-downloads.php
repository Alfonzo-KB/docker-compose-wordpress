<?php
/**
 * Template Name: Adv Downloads
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

					<div id="down-table"></div>

					<script><?php include_once "js/downloads.js"; ?></script>
					<script>
						downloads = new DownloadOrganize;
						downloads.getDownloads();
						document.getElementById("down-table").append(downloads.returnList());
					</script>

				</div>

			<?php endwhile; endif; ?>

		</div><!-- END .row -->

	</div><!-- END .container -->

</div><!-- END #main-content -->

<?php get_footer(); ?>
