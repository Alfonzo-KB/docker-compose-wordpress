<?php
/**
 * Template part for displaying gallery format entry.
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

// Gallery images.
$gallery_images = rwmb_meta(
	'hb_gallery_images', 
	array(
		'type' => 'plupload_image',
		'size' => 'full'
	),
	get_the_ID()
);

// No Gallery images, try to display featured image.
if ( empty( $gallery_images ) ) {
	get_template_part( 'template-parts/entry/format/media' );
	return;
}

$image_data = array();

$crop   = highend_option( 'hb_blog_enable_image_cropping' );
$width  = 900;
$height = $crop ? 500 : false;

?>
<div class="featured-image">
	<div class="hb-flexslider clearfix init-flexslider" data-speed="7000" data-pause-on-hover="true" data-control-nav="true" data-direction-nav="true">
		<ul class="hb-flex-slides clearfix">

			<?php foreach ( $gallery_images as $id => $gallery_image ) { ?>

				<?php
				$full    = wp_get_attachment_image_url( $id, 'full', false );
				$resized = highend_resize( $id, $width, $height, $crop );
				$srcset  = $crop ? '' : ' srcset="' .  wp_get_attachment_image_srcset( $id, array( $width, $height ) ) . '"';
				?>
				
				<li>
					<a href="<?php echo esc_url( $full ); ?>" rel="prettyPhoto[flexslider_<?php the_ID(); ?>]" data-title="<?php echo wp_kses_post( $gallery_image['description'] ); ?>" >
						<img src="<?php echo esc_url( $resized['url'] ); ?>" alt="<?php echo esc_attr( $gallery_image['title'] ); ?>"<?php echo $srcset; ?>/>
					</a>
				</li>
			<?php } ?>
		</ul>
	</div>
</div>
