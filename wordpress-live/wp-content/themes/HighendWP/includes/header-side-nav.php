<?php
/**
 * The template for displaying side navigation header layout.
 * 
 * @deprecated 3.6.6
 */
 
if ( highend_display_notices() ) {
    trigger_error( 'Template file ‘includes/header-side-nav.php’ is deprecated since Highend version 3.6.6. Use ‘template-parts/header/side-nav.php’ instead. This file will be removed in version 4.0.', E_USER_DEPRECATED );
}

get_template_part( 'template-parts/header/side-nav' );