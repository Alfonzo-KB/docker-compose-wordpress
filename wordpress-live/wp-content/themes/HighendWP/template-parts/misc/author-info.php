<?php
/**
 * Template part for displaying about author box.
 *
 * @package Highend
 * @author  HB-Themes
 */

$author_id = get_query_var( 'author' );

if ( empty( $author_id ) ) {
	$author_id = get_the_author_meta( 'ID' );
}

$author_name        = get_the_author_meta( 'display_name', $author_id );
$author_description = get_the_author_meta( 'description', $author_id );
$author_socials     = highend_get_user_social_links( $author_id );

?>
<section class="author-box clearfix" itemprop="author" itemscope="itemscope" itemtype="https://schema.org/Person">

	<span class="author-meta blog-meta">
		<span class="rounded-element"><?php echo get_avatar( get_the_author_meta( 'email', $author_id ), 75 ); ?></span>
	</span><!-- .author-meta -->

	<div class="author-description">

		<h3 class="semi-bold author-title">
			<?php 
			printf( 
				'%1$s <span class="author-box-name" itemprop="name">%2$s</span>',
				esc_html__( 'About', 'hbthemes' ),
				esc_html( $author_name )
			);
			?>
		</h3>
		        		
		<div class="author_description_text" itemprop="description">

			<p>
				<?php 
				if ( $author_description ) {
					echo wp_kses_post( $author_description );
				} else {

					esc_html_e( 'This author hasn\'t written their bio yet.', 'hbthemes' );

					printf(
						'<br><span class="author-box-name" itemprop="name">%1$s</span> %2$s %3$s %4$s',
						esc_html( $author_name ),
						esc_html__( 'has contributed', 'hbthemes' ),
						intval( count_user_posts( $author_id ) ),
						esc_html__( 'entries to our website, so far.', 'hbthemes' )
					);

					if ( ! is_archive() ) {
						printf(
							'<a href="%1$s" class="simple-read-more">%2$s <span class="author-box-name" itemprop="name">%3$s</span></a>',
							esc_url( get_author_posts_url( $author_id ) ),
							esc_html__( 'View entries by' , 'hbthemes' ),
							esc_html( $author_name )
						);
					}
				} ?>
			</p>

			<ul class="social-icons dark clearfix">
				<?php 
				if ( ! empty( $author_socials ) ) {
					foreach ( $author_socials as $soc => $soc_details ) {

						if ( '' !== $soc_details['soc_link'] ) {
							if ( $soc != 'behance' && $soc != 'vk' && $soc != 'envelop' && $soc != 'twitch' && $soc != 'sn500px' && $soc != "weibo" && $soc != "tripadvisor" ) { ?>
								<li class="<?php echo $soc; ?>"><a href="<?php echo $soc_details['soc_link']; ?>" class="<?php echo $soc; ?>" title="<?php echo $soc_details['soc_name']; ?>" target="_blank"><i class="hb-moon-<?php echo $soc; ?>"></i><i class="hb-moon-<?php echo $soc; ?>"></i></a></li>
						<?php } else if ( $soc == 'envelop' ) { ?>
							<li class="<?php echo $soc; ?>"><a href="mailto:<?php echo $soc_details['soc_link']; ?>" class="<?php echo $soc; ?>" title="<?php echo $soc_details['soc_name']; ?>" target="_blank"><i class="hb-moon-<?php echo $soc; ?>"></i><i class="hb-moon-<?php echo $soc; ?>"></i></a></li>
						<?php } else { ?>
							<li class="<?php echo $soc; ?>"><a href="<?php echo $soc_details['soc_link']; ?>" class="<?php echo $soc; ?>" title="<?php echo $soc_details['soc_name']; ?>" target="_blank"><i class="icon-<?php echo $soc; ?>"></i><i class="icon-<?php echo $soc; ?>"></i></a></li>
						<?php }	} ?>
					<?php } ?>
				<?php } ?>
			</ul>

		</div>
	</div><!-- .author-description -->

</section>

<?php if ( ! is_archive() ) { ?>
	<div class="hb-separator-extra"></div>
<?php }
