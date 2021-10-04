<?php
/**
 * Coming Soon / Maintenance Page.
 *
 *
 * @package Highend
 * @since   1.0.0
 */

?>
<!DOCTYPE HTML>
<!--[if lt IE 7 ]<html class="ie ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html <?php language_attributes(); ?> class="hb-maintenance">

<head>

<!-- Basic page tags -->
<meta charset="<?php bloginfo('charset'); ?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	
<?php
	$favicon = highend_option('hb_favicon');
	$apple_icon = highend_option('hb_apple_icon');

	if ( $favicon ) { ?>
	<link rel="shortcut icon" href="<?php echo $favicon; ?>" />
	<?php }

	if ($apple_icon) { ?>
	<link rel="apple-touch-icon" href="<?php echo $apple_icon; ?>" />
	<?php } ?>

<!--[if lte IE 9]>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" type="text/javascript"></script>
<![endif]-->

<title><?php _e('Under Construction | ', 'hbthemes'); ?><?php echo home_url(); ?></title>

<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />

<?php wp_head(); ?>

</head>
<!-- END head -->

<?php
	$layout_position = highend_option('hb_maintenance_layout_position');
	if ( isset ($_GET['layout_position']) ){
		$layout_position = $_GET['layout_position'];
	}
?>

<body <?php
	if ( highend_option('hb_maintenance_bg_color') ){
		echo 'style="background-color: ' . highend_option('hb_maintenance_bg_color');
		if ( highend_option('hb_maintenance_bg_image') ){
			echo '; background-image: url('. highend_option('hb_maintenance_bg_image') .'); background-attachment: fixed; background-size: cover; background-position: 50% 50%; background-repeat: no-repeat no-repeat;' . '"';
		} else {
			echo '"';
		}
	} 
	echo ' class="hb-maintenance-page '. $layout_position .'"';
?>>

	<!-- BEGIN .active_textute -->
	<div class="active_texture"></div>
	<!-- END .active_texture -->

	<!-- BEGIN .small-container -->
	<div class="small-container" id="hb-maintenance">


		<?php if ( highend_option('hb_maintenance_logo') ) { ?>
		<div id="maintenance-logo">
			<img src="<?php echo highend_option('hb_maintenance_logo'); ?>" alt="Logo" />
		</div>
		<?php } ?>

		<?php if ( highend_option('hb_maintenance_content') ) { ?>
		<div class="maintenance-content">
			<?php echo (do_shortcode(highend_option('hb_maintenance_content'))); ?>
		</div>
		<?php } ?>

		<?php if ( highend_option('hb_maintenance_enable_countdown' ) ) { 

			wp_enqueue_script( 'highend-countdown-js' );

			$countdown_date = highend_option('hb_countdown_date');
			$countdown_hour = highend_option('hb_countdown_hours');
			$countdown_minute = highend_option('hb_countdown_minutes');
			$countdown_date = explode('-', $countdown_date);
			$countdown_date[1] = strtolower(date("F", mktime(0, 0, 0, $countdown_date[1], 10)));
			$countdown_style = highend_option('hb_countdown_style');
		?>

		<script>
			jQuery(document).ready(function(){
				jQuery("#hb-countdown").highendCountdown({
					date: "<?php echo $countdown_date[2]; ?> <?php echo $countdown_date[1]; ?> <?php echo $countdown_date[0]; ?> <?php echo $countdown_hour; ?>:<?php echo $countdown_minute; ?>:00",
					format: "on"
				});
			});
		</script>

		<ul id="hb-countdown" class="<?php echo $countdown_style; ?>">
			<li>
				<span class="days timestamp">00</span>
				<span class="timeRef"><?php _e('days','hbthemes'); ?></span>
			</li>
			<li>
				<span class="hours timestamp">00</span>
				<span class="timeRef"><?php _e('hours', 'hbthemes'); ?></span>
			</li>
			<li>
				<span class="minutes timestamp">00</span>
				<span class="timeRef"><?php _e('minutes','hbthemes'); ?></span>
			</li>
			<li>
				<span class="seconds timestamp">00</span>
				<span class="timeRef"><?php _e('seconds','hbthemes'); ?></span>
			</li>
		</ul>

		<?php } ?>
		

	<div id="maintenance-footer">
		<p><?php echo do_shortcode(highend_option('hb_copyright_line_text')); ?></p>
		<?php
			if (highend_option('hb_enable_backlink')){
				echo ' <a href="http://www.mojomarketplace.com/store/hb-themes?r=hb-themes&utm_source=hb-themes&utm_medium=textlink&utm_campaign=themesinthewild">Theme by HB-Themes.</a>';
			}
		?>
		<?php highend_social_icons_output( 'social-list' ); ?>
	</div>

	</div>
	<!-- END .small-container -->

	<!-- BEGIN wp_foot -->
	<?php
		// Google Analytics from Theme Options
		$google_analytics_code = highend_option('hb_analytics_code');
		if ($google_analytics_code){
			echo $google_analytics_code;
		}

		// Custom Script from Theme Options
		$custom_script_code = highend_option('hb_custom_script');
		if ($custom_script_code){
			echo '<script type="text/javascript">' . $custom_script_code . '</script>';
		}
	?>
<?php wp_footer(); ?>
</body>

</html>