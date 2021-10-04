<?php
/**
 * Template part for displaying video format entry.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package     Highend
 * @since       3.5.1
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) { 
	return;
}

?>
<div class="quote-post-wrapper">
	<a href="<?php the_permalink(); ?>">
		<blockquote><?php echo strip_tags( get_the_content() ); ?></blockquote>
		<i class="hb-moon-pencil"></i>
	</a>
</div>
