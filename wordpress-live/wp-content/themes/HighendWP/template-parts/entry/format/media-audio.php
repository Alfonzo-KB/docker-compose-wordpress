<?php
/**
 * Template part for displaying audio format entry.
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

$mp3        = vp_metabox( 'post_format_settings.hb_audio_post_format.0.hb_audio_mp3_link' );
$ogg        = vp_metabox( 'post_format_settings.hb_audio_post_format.0.hb_audio_ogg_link' );
$soundcloud = vp_metabox( 'post_format_settings.hb_audio_post_format.0.hb_audio_soundcloud_link' );

if ( $soundcloud ) { ?>

	<div class="featured-image">
		<?php echo wp_oembed_get( $soundcloud ); ?>
	</div><!-- END .featured-image -->

<?php } elseif ( $mp3 && $ogg ) { ?>

	<div class="featured-image">

		<div class="audio-wrap">		
			<!--[if lt IE 9]><script>document.createElement('audio');</script><![endif]-->
							
			<audio class="hb-audio-element" id="audio-<?php the_ID(); ?>" preload="none" controls="controls">
				<source type="audio/mp3" src="<?php echo esc_url( $mp3 ); ?>" />
				<source type="audio/ogg" src="<?php echo esc_url( $ogg ); ?>" />
				<a href="<?php echo esc_url( $mp3 ); ?>"><?php echo esc_html( $mp3 ); ?></a>
			</audio>

		</div><!--END .audio-wrap-->

	</div><!-- END .featured-image -->
<?php }
