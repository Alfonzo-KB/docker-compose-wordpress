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

$video = vp_metabox( 'post_format_settings.hb_video_post_format.0.hb_video_format_link' );

// If video is not set, display featured image.
if ( empty( $video ) ) {
	get_template_part( 'template-parts/entry/format/media' );
	return;
}
?>

<div class="featured-image">
	<div class="video-wrapper">
		<?php echo wp_oembed_get( $video ); ?>
	</div>
</div><!-- END .featured-image -->