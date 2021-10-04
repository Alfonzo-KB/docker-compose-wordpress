<?php
/**
 * Share dropdown.
 * 
 * @deprecated 3.7
 */
 
if ( highend_display_notices() ) {
	trigger_error( 'Template file ‘includes/hb-share.php’ is deprecated since Highend version 3.7. Use ‘template-parts/misc/share-dropdown.php’ instead. This file will be removed in version 4.0.', E_USER_DEPRECATED );
}

get_template_part( 'template-parts/misc/share-dropdown' );
