<?php
/**
 * Template Name: Gallery - Fullwidth
 *
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Highend
 * @since   1.0.0
 */

?>

<?php get_header(); ?>

<div id="main-content"<?php highend_main_content_style(); ?>>
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<?php $page_id = get_the_ID(); ?>
		
		<div id="page-<?php the_ID(); ?>" <?php post_class(); ?>>

		<div id="fw-gallery-grid" class="clearfix">

			<?php  
			$gallery_columns     = vp_metabox('gallery_fw_page_settings.hb_gallery_columns');
			$gallery_filter      = vp_metabox('gallery_fw_page_settings.hb_gallery_filter');
			$gallery_sorter      = vp_metabox('gallery_fw_page_settings.hb_gallery_sorter');
			$gallery_title       = vp_metabox('gallery_fw_page_settings.hb_gallery_title');
			$gallery_categories  = vp_metabox('gallery_fw_page_settings.hb_gallery_categories');
			$gallery_orientation = vp_metabox('gallery_fw_page_settings.hb_gallery_orientation');
			$gallery_ratio       = vp_metabox('gallery_fw_page_settings.hb_gallery_ratio');
			
			$dimensions = highend_get_image_dimensions( $gallery_orientation, $gallery_ratio );

			query_posts( highend_get_gallery_query_args( $page_id ) );
	 		?>

			<?php if ( $gallery_filter || $gallery_sorter || $gallery_title ) { ?>
				<!-- Gallery Filter -->		
				<div class="hb-gallery-sort">
					<div class="container clearfix">

						<?php if ( $gallery_title ) { ?>
							<h3 class="hb-gallery-caption"><?php echo $gallery_title; ?></h3>
						<?php } ?>

						<?php if ( $gallery_sorter ) { ?>
						<ul class="hb-sort-filter">
							<li class="hb-dd-header"><?php _e('Sort by' , 'hbthemes'); ?>: <strong><?php _e('Date','hbthemes'); ?></strong>
								<ul class="hb-gallery-dropdown">
									<li><a href="#sortBy=date" data-sort-value="date"><?php _e('Date','hbthemes'); ?></a></li>
									<li><a href="#sortBy=name" data-sort-value="name"><?php _e('Name','hbthemes'); ?></a></li>
									<li><a href="#sortBy=count" data-sort-value="count"><?php _e('Count','hbthemes'); ?></a></li>
									<li><a href="#sortBy=random" data-sort-value="random"><?php _e('Random','hbthemes'); ?></a></li>
								</ul>
							</li>
						</ul>
						<?php } ?>

						<?php if ( $gallery_filter ) { 
							$gallery_filters = array();
							if ( have_posts() ) : while ( have_posts() ) : the_post(); 
								$gallery_post_filters = wp_get_post_terms( get_the_ID(), 'gallery_categories', array("fields" => "all"));
								if ( !empty ( $gallery_post_filters) )
								{
									foreach($gallery_post_filters as $gallery_fil)
									{
										$gallery_filters[$gallery_fil->slug] = $gallery_fil->name;
									}
								}
							endwhile; endif;

							wp_reset_postdata();
							
							array_unique($gallery_filters);
						?>
						<ul class="hb-grid-filter">
							<li class="hb-dd-header"><?php _e('Filter by:' , 'hbthemes'); ?> <strong><?php _e('ALL','hbthemes'); ?></strong>
								<ul class="hb-gallery-dropdown">
									<li><a href="#" data-filter="*" data-filter-name="<?php _e('All','hbthemes'); ?>"><?php _e('All' , 'hbthemes'); ?> <span class="hb-filter-count">(0)</span></a></li>
									<?php if ( !empty($gallery_filters) ) { 
										foreach ( $gallery_filters as $slug=>$name ) { 
										?>
											<li><a href="#" data-filter="<?php echo $slug; ?>" data-filter-name="<?php echo $name; ?>"><?php echo $name; ?> <span class="hb-filter-count">(0)</span></a></li>
										<?php
										} 
									}?>
								</ul>
							</li>
						</ul>
						<?php } ?>
					</div>
				</div>
				<!-- END Gallery Filter -->
			<?php } ?>


			<?php if ( have_posts() ) : ?>
				
				<div class="fw-gallery-wrap loading columns-<?php echo $gallery_columns; ?>">

				<?php while ( have_posts() ) : the_post(); ?>
					
					<?php
					highend_gallery_fullwidth_item(
						array(
							'width'  => $dimensions['width'],
							'height' => $dimensions['height'],
						),
						get_the_ID(),
						true
					);
					?>
						
				<?php endwhile; ?>

				</div><!-- END .fw-gallery-wrap -->
				
				<div class="col-12 no-b-margin">
						
					<?php highend_pagination_standard(); ?>

				</div>

			<?php endif; ?>

			<?php wp_reset_query(); ?>

		</div><!-- END #fw-gallery-grid -->
		
		</div>

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