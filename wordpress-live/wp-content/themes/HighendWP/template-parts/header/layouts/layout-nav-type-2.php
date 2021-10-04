<?php
/**
 * The template for displaying Header Layout - Nav Type 2.
 * 
 * @package  Highend
 * @author   HB-Themes
 * @since    3.6.7
 * @version  3.6.7
 */

?>
<div class="<?php echo highend_get_header_container(); ?>">

	<?php highend_logo(); ?>

	<?php
	if ( highend_option( 'hb_header_right_text' ) ) { 
		echo '<div class="hb-site-tagline">' . wp_kses_post( highend_option('hb_header_right_text') ) . '</div>';
	}
	?>
</div>

<div class="main-navigation-container">
	<?php get_template_part( 'template-parts/header/navigation' ); ?>
</div>
