<?php
/**
 * Template part for displaying quote format entry.
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
		<blockquote>
			<?php the_content(); ?>
			<span class="cite-author"><?php echo vp_metabox( 'post_format_settings.hb_quote_post_format.0.hb_quote_format_author' ); ?></span>
		</blockquote>
		<i class="hb-moon-quotes-right"></i>
	</a>
</div>
