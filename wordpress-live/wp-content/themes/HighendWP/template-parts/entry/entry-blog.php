<?php
/**
 * Template part for displaying blog entry.
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
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="https://schema.org/BlogPosting">

	<?php get_template_part( 'template-parts/entry/format/media' , get_post_format() ); ?>

	<?php 
	if ( ! in_array( get_post_format(), array( 'link', 'quote', 'status' ) ) ) {
		get_template_part( 'template-parts/entry/description', 'blog' ); 
	}
	?>

</article>
