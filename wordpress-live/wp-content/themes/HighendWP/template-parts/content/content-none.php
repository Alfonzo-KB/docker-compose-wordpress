<?php
/**
 * Template part for displaying not found posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package     Highend
 * @since       3.5.0
 */

?>

<section class="no-results not-found">

	<div class="page-content">

		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) :

			printf(
				'<p>' . wp_kses(
					/* translators: 1: link to WP admin new post page. */
					__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'hbthemes' ),
					array(
						'a' => array(
							'href' => array(),
						),
					)
				) . '</p>',
				esc_url( admin_url( 'post-new.php' ) )
			);

		elseif ( is_search() ) :

			// printf(
			// 	'<p>' . wp_kses_post( __( 'Sorry, no results were found. Please try again with different keywords.', 'hbthemes' ) ) . '</p>'
			// );
			// get_search_form();

		elseif ( is_category() ) :

			printf(
				'<p>' . wp_kses_post( __( 'There aren&rsquo;t any posts currently published in this category.', 'hbthemes' ) ) . '</p>'
			);

		elseif ( is_tax() ) :

			printf(
				'<p>' . wp_kses_post( __( 'There aren&rsquo;t any posts currently published under this taxonomy.', 'hbthemes' ) ) . '</p>'
			);

		elseif ( is_tag() ) :

			printf(
				'<p>' . wp_kses_post( __( 'There aren&rsquo;t any posts currently published under this tag.', 'hbthemes' ) ) . '</p>'
			);

		endif;
		?>

	</div><!-- .page-content -->
</section><!-- .no-results -->
