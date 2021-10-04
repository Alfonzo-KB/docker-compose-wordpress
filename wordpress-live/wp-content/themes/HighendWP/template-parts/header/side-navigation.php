<?php
/**
 * The template for displaying side navigation header layout.
 * 
 * @package  Highend
 * @author   HB-Themes
 * @since    3.6.6
 * @version  3.6.6
 */

?>

<div id="hb-side-navigation" <?php highend_side_navigation_class(); ?>>
	
	<?php
	do_action( 'highend_side_navigation_start' );

	// Logo.
	printf( '<div class="side-logo-wrapper">%s</div>', wp_kses_post( highend_logo( false ) ) );

	do_action( 'highend_side_navigation_logo_after' ); 

	// Navigation.
	if ( highend_get_the_id() && vp_metabox( 'misc_settings.hb_onepage', null, highend_get_the_id() ) ) {
		$theme_location = 'one-page-menu';
	} else {
		$theme_location = 'main-menu';
	}

	if ( has_nav_menu( $theme_location ) ) {

		$args = array( 
			'theme_location'  => $theme_location,
			'menu_class'      => 'hb-side-nav',
			'menu_id'         => 'hb-side-menu',
			'container'       => 'div',
			'container_class' => 'side-nav-wrapper',
			'link_before'     => '<span>',
			'link_after'      => '</span>',
			'walker'          =>  new HB_Custom_Walker
		);

		wp_nav_menu( $args );
	}

	do_action( 'highend_side_navigation_end' );
	?>

</div>
