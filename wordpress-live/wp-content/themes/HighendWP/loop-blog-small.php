<?php
/**
 * Small blog loop template.
 * 
 * @deprecated 3.5.1
 */
 
if ( highend_display_notices() ) {
	trigger_error( 'Template file ‘loop-blog-small.php’ is deprecated since Highend version 3.5.1. Use highend_blog_loop(\'blog-small\') function instead. This file will be removed in version 4.0.', E_USER_DEPRECATED );
}

highend_blog_loop( 'blog-small' );
