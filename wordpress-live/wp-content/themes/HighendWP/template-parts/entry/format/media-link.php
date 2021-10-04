<?php
/**
 * Template part for displaying link format entry.
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

$link = vp_metabox( 'post_format_settings.hb_link_post_format.0.hb_link_format_link' );
?>
<div class="quote-post-wrapper">
	<a href="<?php echo esc_url( $link ); ?>">
		<blockquote>
			<?php the_content(); ?>
			<span class="cite-author"><?php echo esc_html( $link ); ?></span>
		</blockquote>
		<i class="hb-moon-link-5"></i>
	</a>
</div>
