<?php
/**
 * The template for displaying main navigation.
 * 
 * @package  Highend
 * @author   HB-Themes
 * @since    3.6.7
 * @version  3.6.7
 */

$location = highend_get_main_navigation_theme_location();

?>
<nav <?php highend_main_navigation_class(); ?> role="navigation" itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement">

	<?php do_action( 'highend_main_navigation_start' ); ?>

	<?php
	if ( ! vp_metabox( 'misc_settings.hb_disable_navigation', null, highend_get_the_id() ) ) {
		if ( has_nav_menu( $location ) ) {
			wp_nav_menu(
				apply_filters(
					'highend_main_navigation_args',
					array( 
						'theme_location' => $location,
						'menu_class'     => 'sf-menu',
						'menu_id'        => 'main-nav', 
						'container'      => '',
						'link_before'    => '<span>',
						'link_after'     => '</span>',
						'walker'         =>  new HB_Custom_Walker
					)
				)
			);
		} else {
			echo wp_kses_post(
				sprintf(
					'<ul id="main-nav" class="empty-menu"><li>%s</li></ul>',
					esc_html__( 'Please attach a menu to this menu location in Appearance > Menu.', 'hbthemes' )
				)
			);
		}
	}
	?>

	<?php do_action( 'highend_main_navigation_end' ); ?>

</nav>
