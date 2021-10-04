<?php
/**
 * Template part for displaying entry description.
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

?>
<div class="post-content">
	
	<!-- Post Header -->
	<div class="post-header">
	
		<!-- Title -->
		<h2 class="title" itemprop="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

		<!-- Post Meta -->				
		<div class="post-meta-info">
			<?php if ( highend_option( 'hb_blog_enable_date' ) ) { ?>
				<!-- Post Date -->
				<time class="date-container minor-meta updated" itemprop="datePublished" datetime="<?php echo esc_html( get_the_time( 'c' ) ); ?>">
					<?php the_time( get_option( 'date_format' ) ); ?>
				</time>
			<?php } ?>
		</div>

		<?php if ( is_sticky() ) { ?>
			<div class="sticky-post-icon"><i class="hb-moon-pushpin"></i></div>
		<?php } ?>

	</div><!-- END .post-header -->

	<div class="hb-post-excerpt">

		<div class="excerpt">
			<?php 
			if ( post_password_required() ) {
				echo '<p>' . esc_html_e( 'This content is password protected. To view it please go to the post page and enter the password.', 'hbthemes' ) . '</p>';
			} else { 
				if ( highend_option( 'hb_blog_excerpt_disable' ) )  {
					the_content();
				} elseif ( has_excerpt() ) {
					the_excerpt();
				} else {
					$custom_excerpt = wp_trim_words( 
						wp_strip_all_tags( apply_filters( 'the_content', get_the_content() ) ),
						highend_option( 'hb_blog_excerpt_length' ),
						'...'
					);

					if ( ! empty( $custom_excerpt ) ) {
						echo wp_kses_post( '<p>' . $custom_excerpt . '</p>' );
					}
				}
			}
			?>
		</div>
	</div>

	<div class="post-meta-footer">

		<?php if ( highend_option( 'hb_blog_read_more_button' ) ) { ?>
			<a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e( 'Read More', 'hbthemes' ); ?></a>
		<?php } ?>
			
		<div class="inner-meta-footer">

			<?php
			if ( highend_option( 'hb_blog_enable_likes' ) ) {
				echo hb_print_likes( get_the_ID() ); 
			}
			?>
			
			<?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) && highend_option( 'hb_blog_enable_comments' ) ) { ?>
				<?php $comments_icon = '<i class="hb-moon-bubbles-10"></i>'; ?>
				<?php comments_popup_link( $comments_icon . esc_html__( '0', 'hbthemes' ), $comments_icon . esc_html__( '1', 'hbthemes' ), $comments_icon . esc_html__( '%', 'hbthemes' ), 'comments-holder float-right' ); ?>
			<?php } ?>

		</div>
	</div><!-- END .post-meta-footer -->

</div><!-- END .post-content -->
