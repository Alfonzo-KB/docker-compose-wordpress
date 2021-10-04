<?php
/**
 * Grid blog - post format gallery.
 * 
 * @deprecated 3.5.1
 */
 
if ( highend_display_notices() ) {
	trigger_error( 'Template file ‘includes/grid-blog/post-format-gallery.php’ is deprecated since Highend version 3.5.1. Use ‘template-parts/entry/entry-blog-grid.php’ instead. This file will be removed in version 4.0.', E_USER_DEPRECATED );
}

get_template_part( 'template-parts/entry/entry-blog-grid.php' );
