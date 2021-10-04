<?php
/**
 * Template part for displaying footer widgets.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Highend
 * @since   3.5.2
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$class = array( 'dark-style' );

if ( highend_option( 'hb_footer_bg_image' ) || highend_option( 'hb_enable_footer_background' ) ) {
	$class[] = 'footer-bg-image';
}

$class = apply_filters( 'highend_footer_widgets_class', $class );
$class = trim( implode( ' ', $class ) );

$style     = highend_option( 'hb_footer_layout', 'style-1' );
$separator = highend_option( 'hb_enable_footer_separators' ) ? '' : ' no-separator';
?>

<footer id="footer" class="<?php echo esc_attr( $class ); ?>">
	
	<div class="container">
		<div class="row footer-row">

			<?php
			for ( $column = 1; $column <= 4 ; $column++ ) {
				
				$column_class = highend_footer_widget_column_class( $style, $column );

				if ( 'hidden' === $column_class ) {
					continue;
				}

				echo '<div class="' . esc_attr( $column_class ) . esc_attr( $separator ) . ' widget-column">';
				dynamic_sidebar( 'Footer ' . $column );
				echo '</div>';
			}
			?>

		</div>		
	</div>

</footer><!-- END #footer -->
