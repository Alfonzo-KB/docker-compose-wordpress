<?php
/**
 * The template for displaying theme page title bar.
 * 
 * @package  Highend
 * @author   HB-Themes
 * @since    3.6.5
 * @version  3.6.5
 */

$page_title_settings = highend_get_page_title_settings();
?>

<div id="hb-page-title" <?php highend_page_title_class(); ?>>

	<div class="hb-image-bg-wrap" <?php highend_page_title_style(); ?>></div>

	<div class="container">

		<div class="hb-page-title">

			<?php
			// Page Title.
			echo wp_kses_post(
				sprintf(
					'<%1$s class="%2$s">%3$s</%1$s>',
					tag_escape( apply_filters( 'highend_page_title_tag', 'h1' ) ),
					esc_attr( $page_title_settings['animation'] ),
					highend_get_the_title()
				)
			);

			// Page description/subtitle.
			$page_subtitle = highend_get_the_description();
			if ( $page_subtitle ) {
				echo wp_kses_post(
					sprintf(
						'<h2 class="%1$s">%2$s</h2>',
						esc_attr( $page_title_settings['subtitle-animation'] ),
						$page_subtitle
					)
				);
			}
			?>

		</div><!-- END .hb-page-title -->

		<?php hbthemes_breadcrumbs(); ?>

	</div>
</div><!-- END #hb-page-title -->
