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

	<?php if ( highend_option( 'hb_blog_enable_date' ) ) { ?>
		<!-- Post Date -->
		<div class="hb-post-date float-left">
			
			<time datetime="<?php echo esc_html( get_the_time( 'c' ) ); ?>" itemprop="datePublished">
				<span class="day"><?php echo esc_html( the_time( 'd' ) ); ?></span>
				<span class="month"><?php echo esc_html( the_time( 'M' ) ); ?></span>
			</time>

			<?php
			if ( highend_option( 'hb_blog_enable_likes' ) ) {
				echo hb_print_likes( get_the_ID() ); 
			}
			?>
		</div>
	<?php } ?>

	<div class="post-inner">

		<!-- Post Header -->
		<div class="post-header">

			<!-- Title -->
			<h2 class="title" itemprop="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					
			<!-- Post Meta -->				
			<div class="post-meta-info">
				
				<?php
				if ( highend_option( 'hb_blog_enable_by_author' ) ) {
					highend_entry_meta_author( __( 'Posted by' , 'hbthemes' ) );
				}
				
				if ( has_category() && highend_option( 'hb_blog_enable_categories' ) ) {
					highend_entry_meta_categories();
				}

				if ( highend_comments_open() ) {
					highend_entry_meta_comments();
				}
				?>
			</div>
		</div>

		<!-- Post Content/Excerpt -->
		<div class="hb-post-excerpt clearfix">

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

			<?php if ( highend_option( 'hb_blog_read_more_button' ) ) { ?>
				<a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e( 'Read More', 'hbthemes' ); ?></a>	
			<?php } ?>			
		</div>
	</div><!-- END .post-inner -->

</div><!-- END .post-content -->
