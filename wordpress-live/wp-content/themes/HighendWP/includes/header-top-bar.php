<?php
/**
 * Top Bar section in header.
 * 
 * @deprecated 3.5.3
 */
 
if ( highend_display_notices() ) {
    trigger_error( 'Template file ‘includes/header-top-bar.php’ is deprecated since Highend version 3.5.3. Use ‘template-parts/top-bar/top-bar.php’ instead. This file will be removed in version 4.0.', E_USER_DEPRECATED );
}

get_template_part( 'template-parts/top-bar/top-bar' );
