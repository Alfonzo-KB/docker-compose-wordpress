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
	
	<?php 
	// Featured image.
	if ( highend_option( 'hb_blog_enable_featured_image' ) && ! vp_metabox( 'general_settings.hb_hide_featured_image' ) ) {
		get_template_part( 'template-parts/entry/format/media' , get_post_format() ) ; 
	}
	?>

	<div class="post-header">
		<h1 class="title entry-title" itemprop="headline"><?php the_title(); ?></h1>
	</div><!-- END .post-header -->
	
	<?php
	if ( ! has_post_format( 'quote' ) && ! has_post_format( 'link' ) && ! has_post_format( 'status' ) ) { ?>
		<div class="entry-content clearfix" itemprop="articleBody">
			
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
		<?php
	}

	the_terms( get_the_ID(), 'faq_categories', '<div class="single-post-tags">', '', '</div>' );
	?>

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
