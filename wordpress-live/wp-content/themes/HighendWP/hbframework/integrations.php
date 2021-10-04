<?php
/**
 * Integration with bundled and other plugins
 *
 * @package    Highend
 * @author     HB-Themes
 * @since      3.4.2
 */


/**
 * Include WooCommerce related functions
 *
 * @since 3.4.2
 */
if ( is_multisite() || ( ! is_multisite() && HB_Plugin_Installation::is_plugin_activated( 'woocommerce' ) ) ) {
	require_once get_parent_theme_file_path( 'includes/hb-woocommerce.php' );
}


/**
 * Layer Slider disable plugin autoupdate.
 *
 * @since 3.4.2
 */
function hb_layerslider_autoupdate() {

	// Remove Auto update box
	$GLOBALS['lsAutoUpdateBox'] = false;
}
add_action( 'layerslider_ready', 'hb_layerslider_autoupdate' );


/**
 * Layer Slider get list of all sliders.
 *
 * @since 3.4.2
 */
if ( ! function_exists( 'hb_get_layersliders' ) ) {
	function hb_get_layersliders() {
		
		if ( ! HB_Plugin_Installation::is_plugin_activated( 'LayerSlider' ) ) {
			return;
		}

		$sliders = array();

		if ( class_exists( 'LS_Sliders' ) && method_exists( 'LS_Sliders', 'find' ) ) {
			$all_sliders = LS_Sliders::find( array( 'limit' => 1000000 ) );

			if ( ! empty( $all_sliders ) ) {
				foreach ( $all_sliders as $slider ) {
					$sliders[ $slider['id'] ] = $slider['name'];
				}
			}
		}

		// if ( function_exists( 'lsSliders' ) ) {
		// 	$all_sliders = lsSliders( 1000000, true, true );
		// 	if ( ! empty( $all_sliders ) ) {
		// 		foreach ( $all_sliders as $slider ) {
		// 			$sliders[ $slider['id'] ] = $slider['name'];
		// 		}
		// 	}
		// }
		return $sliders;
	}	
}

/**
 * Revolution Slider get list of all sliders.
 *
 * @since 3.4.2
 */
if ( ! function_exists( 'hb_get_revsliders' ) ) {
	function hb_get_revsliders() {

		$revolutionslider     = array();
		$revolutionslider[''] = __( 'No Slider', 'hbthemes' );

		if ( class_exists( 'RevSlider' ) ) {
			$slider     = new RevSlider();
			$arrSliders = $slider->getArrSliders();
			foreach ( $arrSliders as $revSlider ) {
				$revolutionslider[ $revSlider->getAlias() ] = $revSlider->getTitle();
			}
		}
		return $revolutionslider;
	}
}


/**
 * Tell Visual composer that it's bundled with a theme
 *
 * @since 3.4.2
 */
add_action( 'vc_before_init', 'hb_vc_set_as_theme' );
function hb_vc_set_as_theme() {
	if ( function_exists( 'vc_set_as_theme' ) ) {
		vc_set_as_theme();
	}
}


/**
 * Change VC classes
 *
 * @since 3.4.2
 */
function hb_vc_class( $class_string, $tag ) {

	if ( $tag == 'vc_row' || $tag == 'vc_row_inner' || $tag == 'wpb_row' ) {
		$class_string = str_replace( 'vc_row-fluid', 'row', $class_string );
		$class_string = str_replace( 'wpb_row ', 'element-row ', $class_string );
	}

	if ( defined( 'WPB_VC_VERSION' ) && version_compare( WPB_VC_VERSION, "4.3.0" ) >= 0 ) {
		// good version
	} else {
		if ( $tag == 'vc_column' || $tag == 'vc_column_inner' ) {
			$class_string = preg_replace( '/vc_span(\d{1,2})/', 'col-$1', $class_string );
		}
	}
	return $class_string;
}


/**
 * Deregister some Visual Composer shortcodes
 * 
 * @since 3.4.2
 */
if ( function_exists( 'vc_remove_element' ) ) {
	vc_remove_element( 'vc_wp_categories' );
	vc_remove_element( 'vc_wp_custommenu' );
	vc_remove_element( 'vc_wp_links' );
	vc_remove_element( 'vc_wp_meta' );
	vc_remove_element( 'vc_wp_pages' );
	vc_remove_element( 'vc_wp_posts' );
	vc_remove_element( 'vc_wp_recentcomments' );
	vc_remove_element( 'vc_wp_rss' );
	vc_remove_element( 'vc_wp_search' );
	vc_remove_element( 'vc_wp_tagcloud' );
	vc_remove_element( 'vc_wp_text' );
	vc_remove_element( 'vc_wp_calendar' );
	vc_remove_element( 'vc_wp_archives' );
	vc_remove_element( 'vc_gmaps' );
	add_filter( 'vc_shortcodes_css_class', 'hb_vc_class', 10, 2 );
}


/**
 * Update link for plugin notices in Plugins
 * 
 * @since 3.4.2
 */
if ( ! function_exists( 'hb_update_link ') ) {
	function hb_update_link() {
		echo ' <strong><a href="' . admin_url( 'admin.php?page=hb_plugins#update' ) . '">' . __('Update through Highend Plugins', 'hbthemes') .  '.</a></strong>';
	}
}


/**
 * Remove activation warnings for bundled premium plugins
 * 
 * @since 3.4.2
 */
if ( ! function_exists( 'hb_remove_premium_plugin_actions' ) ) {
	function hb_remove_premium_plugin_actions() {

		if ( class_exists( 'RevSliderAdmin' ) ) {
			remove_action( 'admin_notices', array( 'RevSliderAdmin', 'add_plugins_page_notices' ) );
			remove_action( 'admin_notices', array( 'RevSliderAdmin', 'addActivateNotification') );
		}

		if ( defined( 'LS_PLUGIN_VERSION' ) ) {
			remove_action( 'admin_notices', 'layerslider_unauthorized_update_notice' );
			remove_action( 'admin_notices', 'layerslider_premium_support' );
			remove_action( 'after_plugin_row_' . LS_PLUGIN_BASE, 'layerslider_plugins_purchase_notice' );
			add_action( 'in_plugin_update_message-' . LS_PLUGIN_BASE, 'hb_update_link');
		}

		if ( class_exists( 'Vc_Manager' ) && function_exists( 'vc_plugin_name' ) ) {
			hb_remove_anonymous_object_filter(
				'in_plugin_update_message-' . vc_plugin_name(),
				'Vc_Updating_Manager',
				'addUpgradeMessageLink'
			);
			add_action( 'in_plugin_update_message-' . vc_plugin_name(), 'hb_update_link' );
		}
	}

	if ( function_exists( 'set_revslider_as_theme' ) ){
		set_revslider_as_theme();
	}

	if ( function_exists( 'set_ess_grid_as_theme' ) ) {
		set_ess_grid_as_theme();
	}
}
add_action( 'admin_head', 'hb_remove_premium_plugin_actions' );


if ( ! function_exists( 'hb_remove_anonymous_object_filter' ) ) {
	function hb_remove_anonymous_object_filter( $tag, $class, $method ) {
		$filters = $GLOBALS['wp_filter'][ $tag ];

		if ( empty ( $filters ) ) {
			return;
		}

		foreach ( $filters as $priority => $filter ) {
			foreach ( $filter as $identifier => $function ) {
				if ( is_array( $function)
					and is_a( $function['function'][0], $class )
					and $method === $function['function'][1]
				) {
					remove_filter(
						$tag,
						array ( $function['function'][0], $method ),
						$priority
					);
				}
			}
		}
	}
}