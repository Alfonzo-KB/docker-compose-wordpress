<?php
/**
 * The template for displaying Header Layout - Nav Type 1.
 * 
 * @package  Highend
 * @author   HB-Themes
 * @since    3.6.7
 * @version  3.6.7
 */

?>
<div class="<?php echo highend_get_header_container(); ?>">

	<?php highend_logo(); ?>

	<?php get_template_part( 'template-parts/header/navigation' ); ?>

</div><!-- END .container or .container-wide -->
