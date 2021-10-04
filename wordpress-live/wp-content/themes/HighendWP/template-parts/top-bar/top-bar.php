<?php
/**
 * The template for displaying theme top bar in header.
 * 
 * @package  Highend
 * @author   HB-Themes
 * @since    3.5.3
 * @version  3.5.3
 */

$highend_top_bar_container = apply_filters( 'highend_top_bar_container', highend_option( 'hb_top_header_container' ) );
$highend_top_bar_widgets   = highend_get_top_bar_widgets();

?>

<?php do_action( 'highend_before_header_bar' ); ?>

<div id="header-bar" class="clearfix">

	<div class="<?php echo esc_attr( $highend_top_bar_container ); ?>">

		<?php if ( ! empty( $highend_top_bar_widgets['left'] ) ) { ?>
			<div class="top-widgets-left">
				<?php
				foreach ( $highend_top_bar_widgets['left'] as $highend_top_bar_widget ) {
					if ( function_exists( 'highend_top_bar_widget_' . $highend_top_bar_widget ) ) {
						call_user_func( 'highend_top_bar_widget_' . $highend_top_bar_widget );
					}
				}
				?>
			</div>
		<?php } ?>

		<?php if ( ! empty( $highend_top_bar_widgets['right'] ) ) { ?>
			<div class="top-widgets-right">
				<?php
				foreach ( $highend_top_bar_widgets['right'] as $highend_top_bar_widget ) {
					if ( function_exists( 'highend_top_bar_widget_' . $highend_top_bar_widget ) ) {
						call_user_func( 'highend_top_bar_widget_' . $highend_top_bar_widget );
					}
				}
				?>
			</div>
		<?php } ?>

	</div>

</div><!-- END #header-bar -->

<?php do_action( 'highend_after_header_bar' ); ?>
