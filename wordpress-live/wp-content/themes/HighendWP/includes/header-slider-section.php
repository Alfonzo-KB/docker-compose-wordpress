<?php
/**
 * The template for displaying theme featured section.
 * 
 * @deprecated 3.7
 */

if ( highend_display_notices() ) {
    trigger_error( 'Template file ‘includes/header-slider-section.php’ is deprecated since Highend version 3.7. Use ‘template-parts/header/featured-section.php’ instead. This file will be removed in version 4.0.', E_USER_DEPRECATED );
}

get_template_part( 'template-parts/header/featured-section' );