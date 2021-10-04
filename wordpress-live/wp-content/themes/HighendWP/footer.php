<?php
/**
 * The template for displaying the footer in our theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package  Highend
 * @author   HB-Themes
 * @since    1.0.0
 * @version  3.5.2
 */

?>

	<?php do_action( 'highend_before_footer' ); ?>
	<?php do_action( 'highend_footer' ); ?>
	<?php do_action( 'highend_after_footer' ); ?>

	</div><!-- END #main-wrapper -->

</div><!-- END #hb-wrap -->

<?php do_action( 'highend_after_page_wrapper' ); ?>
<?php wp_footer(); ?>

</body>
</html>
