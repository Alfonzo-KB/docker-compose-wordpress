<?php
/**
 * The template for displaying theme featured section.
 * 
 * @package  Highend
 * @author   HB-Themes
 * @since    3.7
 * @version  3.7
 */

?>

<div id="slider-section" <?php highend_featured_section_class(); ?> <?php highend_featured_section_style(); ?>>

	<?php do_action( 'highend_featured_section_before' ); ?>
	<?php do_action( 'highend_featured_section' ); ?>
	<?php do_action( 'highend_featured_section_after' ); ?>

</div><!-- END #slider-section -->
