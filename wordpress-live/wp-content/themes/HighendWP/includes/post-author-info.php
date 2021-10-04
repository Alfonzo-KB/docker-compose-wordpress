<?php
/**
 * Template part for displaying about author box.
 *
 * @deprecated 3.5.2
 */
 
if ( highend_display_notices() ) {
    trigger_error( 'Template file ‘includes/post-author-info.php’ is deprecated since Highend version 3.5.2. Use ‘template-parts/misc/author-info.php’ instead. This file will be removed in version 4.0.', E_USER_DEPRECATED );
}

get_template_part( 'template-parts/misc/author-info' );
