<?php
/**
 * Single post - featured section.
 * 
 * @deprecated 3.5.1
 */
 
if ( highend_display_notices() ) {
	trigger_error( 'Template file ‘includes/single-featured-format.php’ is deprecated since Highend version 3.5.1. Use ‘template-parts/entry/format/media-{$format}.php’ instead. This file will be removed in version 4.0.', E_USER_DEPRECATED );
}

get_template_part( 'template-parts/entry/format/media', get_post_format() );
