<?php
/**
 * Header section.
 * 
 * @deprecated 3.6.7
 */
 
if ( highend_display_notices() ) {
	trigger_error( 'Template file ‘includes/header-navigation.php’ is deprecated since Highend version 3.6.7. Use ‘template-parts/header/base.php’ instead. This file will be removed in version 4.0.', E_USER_DEPRECATED );
}

get_template_part( 'template-parts/header/base' );
