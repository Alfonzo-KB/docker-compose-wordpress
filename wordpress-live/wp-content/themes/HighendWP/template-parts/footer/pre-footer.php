<?php
/**
 * The template for displaying theme pre footer bar.
 * 
 * @package  Highend
 * @author   HB-Themes
 * @since    3.5.2
 * @version  3.5.2
 */

?>
<div id="pre-footer-area">
	<div class="container">
		
		<span class="pre-footer-text">
			<?php echo wp_kses_post( highend_option( 'hb_pre_footer_text' ) ); ?>
		</span>
		
		<?php 
		if ( highend_option( 'hb_pre_footer_button_text' ) ) {

			$icon   = highend_option( 'hb_pre_footer_button_icon' );
			$class  = 'hb-button hb-large-button';
			$class  = apply_filters( 'highend_pre_footer_button_class', $class );
			
			echo wp_kses_post(
				sprintf(
					'<a href="%1$s" target="%2$s" class="%3$s">%4$s%5$s</a>',
					esc_url( highend_option( 'hb_pre_footer_button_link' ) ),
					esc_attr( highend_option( 'hb_pre_footer_button_target' ) ),
					esc_attr( $class ),
					$icon ? '<i class="' . esc_attr( $icon ) . '"></i>' : '',
					esc_html( highend_option( 'hb_pre_footer_button_text' ) )
				)
			);
		}
		?>
	</div>
</div><!-- END #pre-footer-area -->
