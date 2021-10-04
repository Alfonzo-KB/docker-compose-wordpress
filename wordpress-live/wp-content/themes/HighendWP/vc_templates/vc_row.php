<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $full_width
 * @var $full_height
 * @var $equal_height
 * @var $columns_placement
 * @var $content_placement
 * @var $parallax
 * @var $parallax_image
 * @var $css
 * @var $el_id
 * @var $video_bg
 * @var $video_bg_url
 * @var $video_bg_parallax
 * @var $parallax_speed_bg
 * @var $parallax_speed_video
 * @var $content - shortcode content
 * @var $css_animation
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Row
 */
$el_class = $full_height = $parallax_speed_bg = $parallax_speed_video = $full_width = $equal_height = $flex_row = $columns_placement = $content_placement = $parallax = $parallax_image = $css = $el_id = $video_bg = $video_bg_url = $video_bg_parallax = $css_animation = $margin_bottom = '';
$disable_element = '';
$output = $after_output = '';

// Highend START. added for backward compatibility.
// $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
$vc_atts = vc_map_get_attributes( $this->getShortcode(), $atts );
$vc_atts = array_merge( $vc_atts, shortcode_atts( array( 'margin_bottom' => '', 'padding' => '' ), $atts ) );
$atts = $vc_atts;
// Highend END.

extract( $atts );

wp_enqueue_script( 'wpb_composer_front_js' );

$el_class = $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_classes = array(
	'vc_row',
	'wpb_row',
	//deprecated
	'vc_row-fluid',
	$el_class,
	vc_shortcode_custom_css_class( $css ),
);

if ( 'yes' === $disable_element ) {
	if ( vc_is_page_editable() ) {
		$css_classes[] = 'vc_hidden-lg vc_hidden-xs vc_hidden-sm vc_hidden-md';
	} else {
		return '';
	}
}

if ( vc_shortcode_custom_css_has_property( $css, array(
		'border',
		'background',
	) ) || $video_bg || $parallax
) {
	$css_classes[] = 'vc_row-has-fill';
}

if ( ! empty( $atts['gap'] ) ) {
	$css_classes[] = 'vc_column-gap-' . $atts['gap'];
}

if ( ! empty( $atts['rtl_reverse'] ) ) {
	$css_classes[] = 'vc_rtl-columns-reverse';
}

$wrapper_attributes = array();
// Highend START.
// build attributes for wrapper
// if ( ! empty( $el_id ) ) {
// 	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
// }
// Highend END.
if ( ! empty( $full_width ) ) {
	$wrapper_attributes[] = 'data-vc-full-width="true"';
	$wrapper_attributes[] = 'data-vc-full-width-init="false"';
	if ( 'stretch_row_content' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
	} elseif ( 'stretch_row_content_no_spaces' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
		$css_classes[] = 'vc_row-no-padding';
	}
	$after_output .= '<div class="vc_row-full-width vc_clearfix"></div>';
}

if ( ! empty( $full_height ) ) {
	$css_classes[] = 'vc_row-o-full-height';
	if ( ! empty( $columns_placement ) ) {
		$flex_row = true;
		$css_classes[] = 'vc_row-o-columns-' . $columns_placement;
		if ( 'stretch' === $columns_placement ) {
			$css_classes[] = 'vc_row-o-equal-height';
		}
	}
}

if ( ! empty( $equal_height ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_row-o-equal-height';
}

if ( ! empty( $content_placement ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_row-o-content-' . $content_placement;
}

if ( ! empty( $flex_row ) ) {
	$css_classes[] = 'vc_row-flex';
}

$has_video_bg = ( ! empty( $video_bg ) && ! empty( $video_bg_url ) && vc_extract_youtube_id( $video_bg_url ) );

$parallax_speed = $parallax_speed_bg;
if ( $has_video_bg ) {
	$parallax = $video_bg_parallax;
	$parallax_speed = $parallax_speed_video;
	$parallax_image = $video_bg_url;
	$css_classes[] = 'vc_video-bg-container';
	wp_enqueue_script( 'vc_youtube_iframe_api_js' );
}

if ( ! empty( $parallax ) ) {
	wp_enqueue_script( 'vc_jquery_skrollr_js' );
	$wrapper_attributes[] = 'data-vc-parallax="' . esc_attr( $parallax_speed ) . '"'; // parallax speed
	$css_classes[] = 'vc_general vc_parallax vc_parallax-' . $parallax;
	if ( false !== strpos( $parallax, 'fade' ) ) {
		$css_classes[] = 'js-vc_parallax-o-fade';
		$wrapper_attributes[] = 'data-vc-parallax-o-fade="on"';
	} elseif ( false !== strpos( $parallax, 'fixed' ) ) {
		$css_classes[] = 'js-vc_parallax-o-fixed';
	}
}

if ( ! empty( $parallax_image ) ) {
	if ( $has_video_bg ) {
		$parallax_image_src = $parallax_image;
	} else {
		$parallax_image_id = preg_replace( '/[^\d]/', '', $parallax_image );
		$parallax_image_src = wp_get_attachment_image_src( $parallax_image_id, 'full' );
		if ( ! empty( $parallax_image_src[0] ) ) {
			$parallax_image_src = $parallax_image_src[0];
		}
	}
	$wrapper_attributes[] = 'data-vc-parallax-image="' . esc_attr( $parallax_image_src ) . '"';
}
if ( ! $parallax && $has_video_bg ) {
	$wrapper_attributes[] = 'data-vc-video-bg="' . esc_attr( $video_bg_url ) . '"';
}

// Highend custom start.
$hb_css_class = vc_shortcode_custom_css_class( $css );
$hb_css_class = empty( $hb_css_class ) ? uniqid( '.highend_' ) : '.highend_' . $hb_css_class;
$hb_custom_css = array();
$hb_before_output_end = '';

$css_classes[] = trim( $hb_css_class, '.' );

// Fullwidth row.
if ( 'yes' === $fullwidth ) {
	$wrapper_attributes[] = 'data-vc-full-width="true"';
	$wrapper_attributes[] = 'data-vc-full-width-init="false"';
	$after_output .= '<div class="vc_row-full-width vc_clearfix"></div>';

	// FW columns / stretch content.
	if ( 'yes' === $fw_columns ) {
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
		$css_classes[] = 'vc_row-no-padding';
	}
}

// Columns equal height.
if ( 'yes' === $fw_same_height ) {
	$css_classes[] = 'vc_row-flex';
	$css_classes[] = 'vc_row-o-equal-height';
}

// Gradient background.
if ( 'gradient' === $bg_type ) {

	$first_gr_color  = esc_attr( $first_gr_color );
	$second_gr_color = esc_attr( $second_gr_color );

	if ( 'vertical' === $gr_orientation ) {
		$hb_custom_css[ $hb_css_class ][] = "
			background: " . $first_gr_color . "; /* Old browsers */
			background: -moz-linear-gradient(top,  " . $first_gr_color . " 0%, " . $second_gr_color . " 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%," . $first_gr_color . "), color-stop(100%," . $second_gr_color . ")); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(top,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* IE10+ */
			background: linear-gradient(to bottom,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $first_gr_color . "', endColorstr='" . $second_gr_color . "',GradientType=0 ); /* IE6-8 */
		";
	} elseif ( 'horizontal' === $gr_orientation ) {
		$hb_custom_css[ $hb_css_class ][] = "
			background: " . $first_gr_color . "; /* Old browsers */
			background: -moz-linear-gradient(left,  " . $first_gr_color . " 0%, " . $second_gr_color . " 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, right top, color-stop(0%," . $first_gr_color . "), color-stop(100%," . $second_gr_color . ")); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(left,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(left,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(left,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* IE10+ */
			background: linear-gradient(to right,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $first_gr_color . "', endColorstr='" . $second_gr_color . "',GradientType=1 ); /* IE6-8 */
		";
	} elseif ( 'left_top' === $gr_orientation ) {
		$hb_custom_css[ $hb_css_class ][] = "
			background: " . $first_gr_color . "; /* Old browsers */
			background: -moz-linear-gradient(-45deg,  " . $first_gr_color . " 0%, " . $second_gr_color . " 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, right bottom, color-stop(0%," . $first_gr_color . "), color-stop(100%," . $second_gr_color . ")); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(-45deg,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(-45deg,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(-45deg,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* IE10+ */
			background: linear-gradient(135deg,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $first_gr_color . "', endColorstr='" . $second_gr_color . "',GradientType=1 ); /* IE6-8 fallback on horizontal gradient */
		";
	} elseif ( 'left_bottom' === $gr_orientation ) {
		$hb_custom_css[ $hb_css_class ][] = "
			background: " . $first_gr_color . "; /* Old browsers */
			background: -moz-linear-gradient(45deg,  " . $first_gr_color . " 0%, " . $second_gr_color . " 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left bottom, right top, color-stop(0%," . $first_gr_color . "), color-stop(100%," . $second_gr_color . ")); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(45deg,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(45deg,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(45deg,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* IE10+ */
			background: linear-gradient(45deg,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $first_gr_color . "', endColorstr='" . $second_gr_color . "',GradientType=1 ); /* IE6-8 fallback on horizontal gradient */
		";
	} elseif ( 'radial' === $gr_orientation ) {
		$hb_custom_css[ $hb_css_class ][] = "
			background: " . $first_gr_color . "; /* Old browsers */
			background: -moz-radial-gradient(center, ellipse cover,  " . $first_gr_color . " 0%, " . $second_gr_color . " 100%); /* FF3.6+ */
			background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%," . $first_gr_color . "), color-stop(100%," . $second_gr_color . ")); /* Chrome,Safari4+ */
			background: -webkit-radial-gradient(center, ellipse cover,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* Chrome10+,Safari5.1+ */
			background: -o-radial-gradient(center, ellipse cover,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* Opera 12+ */
			background: -ms-radial-gradient(center, ellipse cover,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* IE10+ */
			background: radial-gradient(ellipse at center,  " . $first_gr_color . " 0%," . $second_gr_color . " 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='".$first_gr_color."', endColorstr='" . $second_gr_color . "',GradientType=1 ); /* IE6-8 fallback on horizontal gradient */
		";
	}
} elseif ( 'bg_image' === $bg_type && ! empty( $image_url ) ) {	

	if ( false === strpos( $image_url, 'http' ) ){
		$image_url = wp_get_attachment_image_src( $image_url, 'full' );
		$image_url = ! empty( $image_url[0] ) ? $image_url[0] : '';
	}

	$hb_custom_css[ $hb_css_class ][] = 'background-image: url(' . esc_url( $image_url ) . ');';

	if ( 'repeat' === $bg_repeat ) {
		$css_classes[] = 'background-texture';
	} else {
		$hb_custom_css[ $hb_css_class ][] = 'background-size:cover;background-position:center;';
	}
} elseif ( 'bg_video' === $bg_type && ! empty( $video_mp4 ) ) {

	if ( false === strpos( $video_mp4, 'http' ) || is_numeric( $video_mp4 ) ) {
		$video_mp4 = wp_get_attachment_url( $video_mp4 );
	}

	if ( ! empty( $video_mp4 ) ) {

		// Video Poster.
		if ( ! empty( $video_poster ) ) {
			if ( false === strpos( $video_poster, 'http' ) ) {
				$video_poster = wp_get_attachment_image_src( $video_poster, 'full' );
				$video_poster = ! empty( $video_poster[0] ) ? $video_poster[0] : '';
			}
		}
		$video_poster = empty( $video_poster ) ? '' : ' poster="' . esc_url( $video_poster ) . '"';

		// WEBM video.
		if ( ! empty( $video_webm ) ) {
			if ( false === strpos( $video_webm, 'http' ) || is_numeric( $video_webm ) ) {
				$video_webm = wp_get_attachment_url( $video_webm );
			}
		}
		$video_webm = empty( $video_webm ) ? '' : '<source src="' . esc_url( $video_webm ) . '" type="video/webm">';

		// Video Texture.
		$video_texture = 'yes' === $video_texture ? '' : ' no-overlay';

		// Build video html.
		$hb_before_output_end .= "
			<div class='video-wrap'>
				<video class='hb-video-element'$video_poster autoplay loop='loop' muted='muted'>
				<source src='$video_mp4' type='video/mp4'>
				$video_webm
			</video>
			<div class='video-overlay$video_texture'></div>
		</div>";
	}
}

// Background color.
if ( ! empty( $background_color ) ) {
	$hb_custom_css[ $hb_css_class ][] = 'background-color: ' . $background_color . ';';
} 

// Waved border.
if ( 'yes' === $waved_border_top || 'yes' === $waved_border_bottom ) {
	$css_classes[] = 'waved-border';

	$waved_border_color = urlencode( $background_color );
	$waved_border_selectors = array();

	if ( 'yes' === $waved_border_top ) {
		$waved_selectors[] = $hb_css_class . ':before';
	}

	if ( 'yes' === $waved_border_bottom ) {
		$waved_selectors[] = $hb_css_class . ':after';
	}

	$waved_selectors = ! empty( $waved_selectors ) ? trim( implode( ',', $waved_selectors ) ) : '';

	if ( ! empty( $waved_selectors ) ) {
		$waved_style = "
			background-image:url(\"data:image/svg+xml;utf8,<svg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' viewBox='0 0 19 14' width='19' height='14' enable-background='new 0 0 19 14' xml:space='preserve' preserveAspectRatio='none slice'><g><path fill='$waved_border_color' d='M0,0c4,0,6.5,5.9,9.5,5.9S15,0,19,0v7H0V0z'/><path fill='$waved_border_color' d='M19,14c-4,0-6.5-5.9-9.5-5.9S4,14,0,14l0-7h19V14z'/></g></svg>\");
		";
		$hb_custom_css[ $waved_selectors ][] = $waved_style;
	}
}

// Animation delay.
if ( $animation_delay ) {
	$wrapper_attributes[] = 'data-delay="' . esc_attr( $animation_delay ) . '"';
}

// Animation.
if ( $animation ) {
	$css_classes[] = 'hb-animate-element';
	$css_classes[] = 'hb-' . $animation;
}

// Row ID.
$hb_row_id = ! empty( $el_id ) ? $el_id : '';
$hb_row_id = empty( $hb_row_id ) && ! empty( $section_id ) ? $section_id : $hb_row_id;

if ( ! empty( $hb_row_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $hb_row_id ) . '"';
}

// Section title.
if ( ! empty( $section_title ) ) {
	$wrapper_attributes[] = 'data-title="' . esc_attr( $section_title ) . '"'; 
}

if ( ! empty( $section_title ) || ! empty( $section_id ) ) {
	$css_classes[] = 'hb-one-page-section';
}

// Border.
if ( 'yes' === $border ) {
	$css_classes[] = 'with-border';
}

// Parallax
if ( 'yes' === $parallax || empty( $parallax ) && 'yes' === $hb_parallax ) {
	wp_enqueue_script( 'vc_jquery_skrollr_js' );
	$wrapper_attributes[] = 'data-vc-parallax="' . esc_attr( $parallax_speed ) . '"'; // parallax speed
	$css_classes[] = 'vc_general vc_parallax vc_parallax-' . $parallax;
	$css_classes[] = 'js-vc_parallax-o-fixed';
}

// Margins.
if ( ! empty( $top_margin ) ) {
	$hb_custom_css[ $hb_css_class ][] = 'margin-top:' . $top_margin . 'px !important;';
}

if ( ! empty( $bottom_margin ) ) {
	$hb_custom_css[ $hb_css_class ][] = 'margin-bottom:' . $bottom_margin . 'px !important;';
}

// added for backward compatibility.
if ( ! empty( $margin_bottom ) ) {
	if ( is_numeric( $margin_bottom ) ) {
		$margin_bottom .= 'px';
	}
	$hb_custom_css[ $hb_css_class ][] = 'margin-bottom:' . $margin_bottom . '!important;';
}

if ( ! empty( $padding ) ) {
	$hb_custom_css[ $hb_css_class ][] = 'padding:' . $padding . 'px !important;';
}

// Build custom style tag.
if ( ! empty( $hb_custom_css ) ) {
	$hb_before_output_end .= '<style type="text/css">';

	foreach ( $hb_custom_css as $selector => $line ) {
		
		if ( ! empty( $line ) ) {
			$hb_before_output_end .= $selector . '{';
			foreach ( $line as $css_line ) {
				$hb_before_output_end .= $css_line;
			}
			$hb_before_output_end .= '}';
		}
	}

	$hb_before_output_end .= '</style>';
}
// Highend custom end.

$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( array_unique( $css_classes ) ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';

$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';

if ( 'yes' === $fullwidth && 'yes' !== $fw_columns || 'stretch_row' === $full_width ) {
	$output .= '<div class="fw-content-wrap"><div class="vc_row">';
}

$output .= wpb_js_remove_wpautop( $content );
$output .= $hb_before_output_end; // Highend mod.

if ( 'yes' === $fullwidth && 'yes' !== $fw_columns || 'stretch_row' === $full_width ) {
	$output .= '</div></div>';
}

$output .= '</div>';

$output .= $after_output;

echo $output;
