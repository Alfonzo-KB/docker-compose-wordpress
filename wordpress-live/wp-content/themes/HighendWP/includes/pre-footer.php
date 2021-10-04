<?php
/**
 * Pre-Footer section.
 * 
 * @deprecated 3.5.2
 */
 
if ( highend_display_notices() ) {
    trigger_error( 'Template file ‘includes/pre-footer.php’ is deprecated since Highend version 3.5.2. Use ‘template-parts/footer/pre-footer.php’ instead. This file will be removed in version 4.0.', E_USER_DEPRECATED );
}

get_template_part( 'template-parts/footer/pre-footer' );
