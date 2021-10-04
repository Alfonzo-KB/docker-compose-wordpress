<?php
/**
 * Post Related Articles.
 * 
 * @deprecated 3.6.4
 */
 
if ( highend_display_notices() ) {
    trigger_error( 'Template file ‘includes/post-related-articles.php’ is deprecated since Highend version 3.6.4. Use ‘highend_post_related_articles’ function instead. This file will be removed in version 4.0.', E_USER_DEPRECATED );
}

highend_post_related_articles();
