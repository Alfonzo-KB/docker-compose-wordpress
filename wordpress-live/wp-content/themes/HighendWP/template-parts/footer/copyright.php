<?php
/**
 * The template for displaying theme copyright bar.
 * 
 * @package  Highend
 * @author   HB-Themes
 * @since    3.5.2
 * @version  3.5.2
 */

$class = array(
	highend_option( 'hb_copyright_style' ),
	highend_option( 'hb_copyright_color_scheme' ),
	'clearfix',
);

if ( highend_option( 'hb_footer_bg_image' ) && highend_option( 'hb_enable_footer_widgets' ) ){ 
	$class[] = 'footer-bg-image';
}

$class = apply_filters( 'highend_copyright_class', $class );
$class = trim( implode( ' ', $class ) );

$backlink = highend_option( 'hb_enable_backlink' ) ? ' <a href="https://www.mojomarketplace.com/store/hb-themes?r=hb-themes&utm_source=hb-themes&utm_medium=textlink&utm_campaign=themesinthewild">Theme by HB-Themes.</a>' : ''

?>
<div id="copyright-wrapper" class="<?php echo esc_attr( $class ); ?>">

	<div class="container">

		<div id="copyright-text">
			<?php
			echo wp_kses_post(
				sprintf(
					'<p>%1$s%2$s</p>',
					do_shortcode( highend_option( 'hb_copyright_line_text' ) ),
					$backlink
				)
			);
			?>
		</div><!-- END #copyright-text -->

		<?php
		// Footer Menu.
		if ( has_nav_menu( 'footer-menu' ) ) {
			wp_nav_menu( 
				array(
					'theme_location'  => 'footer-menu',
					'container_id'    => 'footer-menu',
					'container_class' => 'clearfix',
					'menu_id'         => 'footer-nav',
					'menu_class'      => '',
					'walker'          => new HB_Custom_Walker
				)
			);
		} 
		?>

	</div><!-- END .container -->

</div><!-- END #copyright-wrapper -->
