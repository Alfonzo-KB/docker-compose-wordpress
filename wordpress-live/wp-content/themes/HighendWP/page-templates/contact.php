<?php
/**
 * Template Name: Contact Template
 *
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Highend
 * @since   1.0.0
 */

?>

<?php get_header(); ?>

<div id="main-content"<?php highend_main_content_style(); ?>>

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<?php
		$page_id = get_the_ID();

		$highend_contact_background = vp_metabox( 'contact_page_settings.hb_contact_background', '', $page_id );
		$highend_contact_animation  = vp_metabox( 'contact_page_settings.hb_contact_box_enable_animation', '', $page_id );
		$highend_contact_animation  = $highend_contact_animation ? ' hb-animate-element hb-' . $highend_contact_animation : '';
		$highend_contact_title      = vp_metabox( 'contact_page_settings.hb_contact_title', '', $page_id );
		$highend_contact_content    = vp_metabox( 'contact_page_settings.hb_contact_content', '', $page_id );
		$highend_contact_details    = vp_metabox( 'contact_page_settings.hb_contact_details', '', $page_id );

		if ( empty( $highend_contact_details ) || 1 === count( $highend_contact_details ) && '' === $highend_contact_details[0]['hb_contact_detail_content'] && '' === $highend_contact_details[0]['hb_contact_detail_icon'] ) {
			$highend_contact_details = array();
		}

		$highend_form_title = vp_metabox( 'contact_page_settings.hb_contact_form_title', '', $page_id );
		?>
		
		<div id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
		
			<div class="fw-map-wrapper">

				<?php
				// Background.
				if ( 'map' === $highend_contact_background ) {
					highend_print_google_map(
						apply_filters(
							'highend_contact_map_args',
							array(
								'wrapper_start' => '<div class="fw-map">',
								'wrapper_end'   => '</div>'
							),
							$page_id
						)
					);
				} elseif ( 'image' === $highend_contact_background ) {
					echo wp_kses_post(
						sprintf(
							'<div class="fw-map parallax" style="background-image: url(\'%s\' );"></div>',
							esc_url( vp_metabox( 'contact_page_settings.hb_contact_background_image', '', $page_id ) )
						)
					);
				}
				?>
			
				<div class="container">

					<div class="map-info-section<?php echo esc_attr( $highend_contact_animation ); ?>">

						<a href="#" class="minimize-section"><i class="hb-moon-contract-3"></i></a>

						<?php if ( $highend_contact_title ) { ?>
							<h5 class="hb-heading alt-color-1"><span><?php echo esc_html( $highend_contact_title ); ?></span></h5>
						<?php } ?>

						<?php if ( ! empty( $highend_contact_content ) || ! empty( $highend_contact_details ) ) { ?>
							<div class="hb-contact-details">

								<?php
								if ( ! empty( $highend_contact_content ) ) {
									echo wp_kses_post( '<div><p>' . $highend_contact_content . '</p></div>' );
								}
								?>

								<?php if ( ! empty( $highend_contact_details ) ) { ?> 
									<div>
										<ul class="hb-ul-list">
										<?php foreach ( $highend_contact_details as $detail ) { ?>
											<li>
												<?php if ( $detail['hb_contact_detail_icon'] ) { ?>
													<i class="<?php echo esc_attr( $detail['hb_contact_detail_icon'] ); ?>"></i>
												<?php } ?>

												<span><?php echo wp_kses_post( $detail['hb_contact_detail_content'] ); ?></span></li>
										<?php } ?>
										</ul>
									</div>
								<?php } ?>
							</div>
						<?php } ?>	

						<?php if ( $highend_form_title ) { ?>
							<h5 class="hb-heading alt-color-1"><span><?php echo wp_kses_post( $highend_form_title ); ?></span></h5>
						<?php } ?>

						<?php do_action( 'highend_contact_page_form' ); ?>

					</div><!-- END .map-info-section -->

				</div>

			</div><!-- END .fw-map-wrapper -->

		</div>

	<?php endwhile; endif; ?>

</div><!-- END #main-content -->

<?php get_footer(); ?>
