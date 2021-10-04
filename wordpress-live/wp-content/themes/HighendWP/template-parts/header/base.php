<?php
/**
 * The template for header.
 * 
 * @package  Highend
 * @author   HB-Themes
 * @since    3.6.7
 * @version  3.6.7
 */

$post_id       = highend_get_the_id();
$header_layout = highend_get_header_layout();

$classes[] = $header_layout;

if ( 'nav-type-1' === $header_layout && highend_option( 'hb_sticky_header' ) ||
     'nav-type-2 centered-nav' === $header_layout && highend_option( 'hb_sticky_header_alt' ) ||
     'nav-type-2' === $header_layout && highend_option( 'hb_sticky_header_alt' ) ) {
    $classes[] = 'sticky-nav';
}

if ( highend_option( 'hb_ajax_search' ) ) {
    $classes[] = 'hb-ajax-search';
}

// Logo alignment.
$classes[] = highend_option( 'hb_logo_alignment' );

$classes = apply_filters( 'highend_header_inner_class', $classes, $post_id );
$classes = trim( implode( ' ', $classes ) );
?>

<!-- BEGIN #header-inner -->
<div id="header-inner" class="<?php echo esc_attr( $classes ); ?>" role="banner" itemscope="itemscope" itemtype="https://schema.org/WPHeader">

    <!-- BEGIN #header-inner-bg -->
    <div id="header-inner-bg">

        <?php get_template_part( 'template-parts/header/layouts/layout', sanitize_title( $header_layout ) ); ?>

    </div><!-- END #header-inner-bg -->
</div><!-- END #header-inner -->
