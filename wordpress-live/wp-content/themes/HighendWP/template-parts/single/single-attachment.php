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
	
	<div class="single-post-content">
		
		<div class="entry-content clearfix" itemprop="articleBody">
			<?php the_content(); ?>
		</div>

	</div><!-- END .single-post-content -->

</article>
