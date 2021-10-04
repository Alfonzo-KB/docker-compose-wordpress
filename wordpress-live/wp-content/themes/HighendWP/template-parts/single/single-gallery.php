<?php
/**
 * The template for displaying single post content.
 * 
 * @package  Highend
 * @author   HB-Themes
 * @since    3.6.14
 * @version  3.6.14
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemType="https://schema.org/BlogPosting">
	
	<div class="entry-content clearfix" itemprop="articleBody">

		<?php
		$gallery_attachments = rwmb_meta( 'hb_gallery_images', array( 'type' => 'plupload_image', 'size' => 'full' ) , get_the_ID() );
		
		if ( ! empty( $gallery_attachments ) ) {
			$gallery_ids = array_keys( $gallery_attachments );
			$gallery_ids = implode( ',', $gallery_ids );

			$gallery_shortcode = '[gallery ids="' . $gallery_ids . '" columns="4" link="file"]';

			echo '<div class="hb-gallery-single-wrap">' . do_shortcode( $gallery_shortcode ) .'</div>';
		}
		?>
		
		<?php the_content(); ?>
		
		<div class="page-links">
			<?php
			wp_link_pages(
				array(
					'next_or_number'   => 'next',
					'previouspagelink' => ' <i class="icon-angle-left"></i> ',
					'nextpagelink'     => ' <i class="icon-angle-right"></i>'
				)
			);
			?>		
		</div>
	</div><!-- END .entry-content -->
	
	<?php the_terms( get_the_ID(), 'gallery_categories', '<div class="single-post-tags">', '', '</div>' ); ?>

	<section class="bottom-meta-section clearfix">
		<?php
		if ( highend_comments_open() ) {			
			comments_popup_link( esc_html__( '0 Comments', 'hbthemes' ), esc_html__( '1 Comment', 'hbthemes' ), esc_html__( '% Comments', 'hbthemes' ), 'comments-link scroll-to-comments' );
		}

		if ( highend_option( 'hb_blog_enable_share' ) ) {
			get_template_part ( 'template-parts/misc/share-dropdown' );
		}

		if ( highend_option( 'hb_blog_enable_likes' ) ) {
			echo hb_print_likes( get_the_ID() );
		}
		?>
	</section><!-- END .bottom-meta-section -->
</article>
