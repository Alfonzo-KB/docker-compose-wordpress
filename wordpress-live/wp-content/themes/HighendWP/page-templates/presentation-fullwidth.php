<?php
/**
 * @package WordPress
 * @subpackage Highend
 */
/*
Template Name: Scrolling Presentation
*/
?>

<?php get_header(); ?>

<?php if ( have_posts() ) : while (have_posts()) : the_post(); ?>

<?php
$items = vp_metabox('presentation_settings.hb_presentation_items');
$main_content_style = "";
if ( vp_metabox('background_settings.hb_content_background_color') )
	$main_content_style = ' style="background-color: ' . vp_metabox('background_settings.hb_content_background_color') . ';"';
?> 

<!-- BEGIN #main-content -->
<div id="main-content"<?php echo $main_content_style; ?>>
	
	<div id="page-<?php the_ID(); ?>" <?php post_class(); ?>>

		<!-- BEGIN #fullpage -->
		<div id="fullpage">


			<?php
			if (!empty($items)) {
				foreach ($items as $item) {
					$presentation_name = sanitize_title($item['hb_presentation_name']);
					$presentation_type = $item['hb_presentation_type'];
					$presentation_title = $item['hb_presentation_title'];
					$presentation_subtitle = $item['hb_presentation_subtitle'];
					$presentation_title_position = $item['hb_presentation_title_position'];
					$presentation_style = $item['hb_presentation_style'];
					$presentation_primary_button_text = $item['hb_presentation_primary_button_text'];
					$presentation_primary_button_link = $item['hb_presentation_primary_button_link'];
					$presentation_secondary_button_text = $item['hb_presentation_secondary_button_text'];
					$presentation_secondary_button_link = $item['hb_presentation_secondary_button_link'];
					$presentation_image = $item['hb_presentation_image'];
					$presentation_color = $item['hb_presentation_color'];
					$presentation_target = $item['hb_presentation_target'];
					$presentation_mobile_image = $item['hb_presentation_mobile_image'];
					$presentation_video_mp4 = $item['hb_presentation_video_mp4'];
					$presentation_video_webm = $item['hb_presentation_video_webm'];

					if ($presentation_type == 'color'){ ?>
						<div class="section" data-id="<?php echo $presentation_name; ?>" style="background-color: <?php echo $presentation_color; ?>;" data-nav-color="<?php echo $presentation_style; ?>">
						
							<div class="hb-caption-layer container">
								<?php if ($presentation_title) {
									echo '<h2 class="hb-animate-element hb-scale-down col-12 '. $presentation_style .'" data-delay="0" style="text-align: '.$presentation_title_position.'">'.$presentation_title.'</h2>';
								} ?>

								<?php if ($presentation_subtitle) {
									echo '<p class="hb-animate-element hb-scale-down col-12 '. $presentation_style .'" data-delay="200" style="text-align: '.$presentation_title_position.'">'.$presentation_subtitle.'</p>';
								} ?>

								<div class="col-12" style="text-align: <?php echo $presentation_title_position; ?>">
								<?php if ($presentation_primary_button_text){
									echo '<a href="'.$presentation_primary_button_link.'" target="'.$presentation_target.'" class="hb-animate-element hb-scale-down hb-button no-three-d hb-large-button primary '. $presentation_style .'" data-delay="400">'.$presentation_primary_button_text.'</a>';
								} ?>

								<?php if ($presentation_secondary_button_text){
									echo '<a href="'.$presentation_secondary_button_link.'" target="'.$presentation_target.'" class="hb-animate-element hb-scale-down hb-button hb-large-button no-three-d secondary '. $presentation_style .'" data-delay="600">'.$presentation_secondary_button_text.'</a>';
								} ?>
								</div>

							</div>

						</div><!-- end section -->
					<?php } else if ($presentation_type == 'image'){ ?>
						<div class="section" data-id="<?php echo $presentation_name; ?>" style="background-image: url(<?php echo $presentation_image; ?>);" data-nav-color="<?php echo $presentation_style; ?>">
						
							<div class="hb-caption-layer container">
								<?php if ($presentation_title) {
									echo '<h2 class="hb-animate-element hb-scale-down col-12 '. $presentation_style .'" data-delay="0" style="text-align: '.$presentation_title_position.'">'.$presentation_title.'</h2>';
								} ?>

								<?php if ($presentation_subtitle) {
									echo '<p class="hb-animate-element hb-scale-down col-12 '. $presentation_style .'" data-delay="200" style="text-align: '.$presentation_title_position.'">'.$presentation_subtitle.'</p>';
								} ?>

								<div class="col-12" style="text-align: <?php echo $presentation_title_position; ?>">
								<?php if ($presentation_primary_button_text){
									echo '<a href="'.$presentation_primary_button_link.'" target="'.$presentation_target.'" class="hb-animate-element hb-scale-down hb-button no-three-d hb-large-button primary '. $presentation_style .'" data-delay="400">'.$presentation_primary_button_text.'</a>';
								} ?>

								<?php if ($presentation_secondary_button_text){
									echo '<a href="'.$presentation_secondary_button_link.'" target="'.$presentation_target.'" class="hb-animate-element hb-scale-down hb-button hb-large-button no-three-d secondary '. $presentation_style .'" data-delay="600">'.$presentation_secondary_button_text.'</a>';
								} ?>
								</div>

							</div>

						</div><!-- end section -->
					<?php } else if ($presentation_type == 'video'){ ?>
						<div class="section" data-id="<?php echo $presentation_name; ?>" data-nav-color="<?php echo $presentation_style; ?>">
							<div class="hb-video-mobile" style="background-image: url(<?php echo $presentation_mobile_image; ?>);"></div>
							<div class="hb-video-color-mask"></div>

							<div class="hb-fp-video">
								<video poster="<?php echo $presentation_mobile_image; ?>" muted="muted" preload="auto" loop="true" autoplay="true"><source type="video/mp4" src="<?php echo $presentation_video_mp4; ?>"><source type="video/webm" src="<?php echo $presentation_video_webm; ?>"></video>
							</div>

							<div class="hb-caption-layer container">
								<?php if ($presentation_title) {
									echo '<h2 class="hb-animate-element hb-scale-down col-12 '. $presentation_style .'" data-delay="0" style="text-align: '.$presentation_title_position.'">'.$presentation_title.'</h2>';
								} ?>

								<?php if ($presentation_subtitle) {
									echo '<p class="hb-animate-element hb-scale-down col-12 '. $presentation_style .'" data-delay="200" style="text-align: '.$presentation_title_position.'">'.$presentation_subtitle.'</p>';
								} ?>

								<div class="col-12" style="text-align: <?php echo $presentation_title_position; ?>">
								<?php if ($presentation_primary_button_text){
									echo '<a href="'.$presentation_primary_button_link.'" target="'.$presentation_target.'" class="hb-animate-element hb-scale-down hb-button no-three-d hb-large-button primary '. $presentation_style .'" data-delay="400">'.$presentation_primary_button_text.'</a>';
								} ?>

								<?php if ($presentation_secondary_button_text){
									echo '<a href="'.$presentation_secondary_button_link.'" target="'.$presentation_target.'" class="hb-animate-element hb-scale-down hb-button hb-large-button no-three-d secondary '. $presentation_style .'" data-delay="600">'.$presentation_secondary_button_text.'</a>';
								} ?>
								</div>

							</div>
						</div><!-- end section -->
					<?php }
				}
			}
			?>

		</div>
		<!-- END #fullpage -->

	</div>

</div>
<!-- END #main-content -->
<?php endwhile; endif; ?>

<?php get_footer(); ?>