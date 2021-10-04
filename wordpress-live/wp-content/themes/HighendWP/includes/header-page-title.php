<?php
/**
 * The template for displaying theme page title bar.
 * 
 * @deprecated 3.6.5
 */
 
if ( highend_display_notices() ) {
    trigger_error( 'Template file ‘includes/header-page-title.php’ is deprecated since Highend version 3.6.5. Use ‘template-parts/header/page-title.php’ instead. This file will be removed in version 4.0.', E_USER_DEPRECATED );
}

get_template_part( 'template-parts/header/page-title' );
