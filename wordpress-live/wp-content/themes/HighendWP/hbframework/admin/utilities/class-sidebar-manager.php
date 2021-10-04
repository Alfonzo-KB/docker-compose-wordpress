<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Direct access forbidden.' ); }

/**
 * HB Sidebar Manager utilities.
 * Class that contains methods for manipulating widgets and widget areas.
 * 
 * @since 3.4.1
 */
class HB_Sidebar_Manager {

	/**
	 * Compatibility with previous HB sidebar manager.
	 *
	 * @since 3.4.1
	 */
	public static function import_old_sidebars() {

		$old_sidebars = get_option( 'sbg_sidebars' );

		if ( ! is_array( $old_sidebars ) ) {
			return false;
		}

		$sidebars = HB_Sidebar_Manager::get_sidebars();

		foreach ( $old_sidebars as $sidebar_id => $sidebar_name ) {
			$sidebars[ $sidebar_id ] = array(
				'name'			=> $sidebar_name,
				'description'	=> esc_html__( 'This is a widgetized area.', 'hbthemes' )
			);
		}

		if ( HB_Sidebar_Manager::update_sidebars( $sidebars ) ) {
			return delete_option( 'sbg_sidebars' );
		}

		return false;
	}


	/**
	 * Register sidebar
	 *
	 * @since 3.4.1
	 */
	public static function register_sidebar( $sidebar ) {

		global $wp_registered_sidebars;

		$sidebar_class = HB_Sidebar_Manager::name_to_class( $sidebar['name'] );

		// Already registered
		if ( isset( $wp_registered_sidebars[ 'hb-custom-sidebar-' . strtolower( $sidebar_class ) ] ) ) {
			return;
		}

		register_sidebar( array(
			'name'			=> $sidebar['name'],
			'id' 			=> 'hb-custom-sidebar-' . strtolower( $sidebar_class ),
			'description'	=> $sidebar['description'],
			'class'			=> strtolower( $sidebar_class ),
			'before_widget' => '<div id="%1$s" class="widget-item %2$s">',
			'after_widget' 	=> '</div>',
			'before_title' 	=> '<h4 class="widget-title">',
			'after_title' 	=> '</h4>',
		) );
	}


	/**
	 * Update sidebars
	 *
	 * @since 3.4.1
	 */
	public static function update_sidebars( $sidebar_array ) {
		return update_option( 'hb_sidebars', $sidebar_array );
	}


	/**
	 * Get registered sidebar list.
	 *
	 * @since 3.4.1
	 */
	public static function get_sidebars() {

		$sidebars = get_option( 'hb_sidebars' );

		if ( ! is_array( $sidebars ) ) {
			$sidebars = array();
		}

		return $sidebars;
	}


	/**
	 * Generate a class based on name.
	 *
	 * @since 3.4.1
	 */
	public static function name_to_class( $name ) {

		$class = str_replace(array(' ',',','.','"',"'",'/',"\\",'+','=',')','(','*','&','^','%','$','#','@','!','~','`','<','>','?','[',']','{','}','|',':',),'',$name);

		if ( $class == '' ) {
			$class = wp_create_nonce($name);
		}

		return $class;
	}


	/**
	 * Get widgets instances.
	 *
	 * @since 3.4.1
	 */
	public static function get_sidebar_widgets() {

		global $wp_registered_widget_controls;
		$available_widgets = $wp_registered_widget_controls;

		// Get all widget instances for each widget
		$widget_instances = array();

		foreach ( $available_widgets as $widget_data ) {

			// Get all instances for this ID base
			$instances = get_option( 'widget_' . $widget_data['id_base'] );

			// Have instances
			if ( ! empty( $instances ) ) {

				// Loop instances
				foreach ( $instances as $instance_id => $instance_data ) {

					// Key is ID (not _multiwidget)
					if ( is_numeric( $instance_id ) ) {
						$unique_instance_id = $widget_data['id_base'] . '-' . $instance_id;
						$widget_instances[ $unique_instance_id ] = $instance_data;
					}

				}

			}
		}

		$sidebars_widgets 			= get_option( 'sidebars_widgets' );
		$sidebars_widget_instances 	= array();

		if ( is_array( $sidebars_widgets ) && ! empty( $sidebars_widgets ) ) {

			foreach ( $sidebars_widgets as $sidebar_id => $widget_ids ) {

				if ( 'wp_inactive_widgets' == $sidebar_id ) {
					continue;
				}

				if ( ! is_array( $widget_ids ) || empty( $widget_ids ) ) {
					continue;
				}

				foreach ( $widget_ids as $widget_id ) {
					if ( isset( $widget_instances[ $widget_id ] ) ) {
						$sidebars_widget_instances[ $sidebar_id ][ $widget_id ] = $widget_instances[ $widget_id ];

					}
				}
			}
		}

		return $sidebars_widget_instances;
	}


	/**
	 * This function was taken and slightly modified from Widget Importer & Exporter plugin
	 * 
	 * @since 3.4.1
	 */
	public static function import_sidebar_widgets( $data ) {
		global $wp_registered_sidebars, $wp_registered_widget_controls;

		if ( empty( $data ) ) {
			return array(
				'success' => false,
				'message'	=> esc_html__( 'Widget import data is not an array.', 'hbthemes' ),
			);
		}

		$available_widgets = $wp_registered_widget_controls;

		$widget_instances = array();
		foreach ( $available_widgets as $widget_data ) {
			$widget_instances[ $widget_data['id_base'] ] = get_option( 'widget_' . $widget_data['id_base'] );
		}

		$results = array();

		foreach ( $data as $sidebar_id => $widgets ) {

			if ( 'wp_inactive_widgets' == $sidebar_id ) {
				continue;
			}

			if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
				$sidebar_available = true;
				$use_sidebar_id = $sidebar_id;
				$sidebar_message_type = 'success';
				$sidebar_message = '';
			} else {
				$sidebar_available = false;
				$use_sidebar_id = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
				$sidebar_message_type = 'error';
				$sidebar_message = esc_html__( 'Widget area does not exist in theme (using Inactive)', 'hbthemes' );
			}

			$results[$sidebar_id]['name'] = ! empty( $wp_registered_sidebars[$sidebar_id]['name'] ) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id;
			$results[$sidebar_id]['message_type'] = $sidebar_message_type;
			$results[$sidebar_id]['message'] = $sidebar_message;
			$results[$sidebar_id]['widgets'] = array();

			foreach ( $widgets as $widget_instance_id => $widget ) {

				$fail = false;

				$id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
				$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

				/*if ( ! $fail && ! isset( $available_widgets[$widget_instance_id] ) ) {
					$fail = true;
					$widget_message_type = 'error';
					$widget_message = esc_html__( 'Site does not support widget', 'hbthemes' );
				}*/

				$widget = json_decode( wp_json_encode( $widget ), true );

				if ( ! $fail && isset( $widget_instances[$id_base] ) ) {
					$sidebars_widgets = get_option( 'sidebars_widgets' );
					$sidebar_widgets = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array();

					$single_widget_instances = ! empty( $widget_instances[$id_base] ) ? $widget_instances[$id_base] : array();
					foreach ( $single_widget_instances as $check_id => $check_widget ) {

						if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {

							$fail = true;
							$widget_message_type = 'warning';
							$widget_message = esc_html__( 'Widget already exists', 'hbthemes' );

							break;
						}

					}
				}

				if ( ! $fail ) {
					$single_widget_instances = get_option( 'widget_' . $id_base );
					$single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 );
					$single_widget_instances[] = $widget;

					end( $single_widget_instances );
					$new_instance_id_number = key( $single_widget_instances );

					if ( '0' === strval( $new_instance_id_number ) ) {
						$new_instance_id_number = 1;
						$single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
						unset( $single_widget_instances[0] );
					}

					if ( isset( $single_widget_instances['_multiwidget'] ) ) {
						$multiwidget = $single_widget_instances['_multiwidget'];
						unset( $single_widget_instances['_multiwidget'] );
						$single_widget_instances['_multiwidget'] = $multiwidget;
					}

					update_option( 'widget_' . $id_base, $single_widget_instances );

					$sidebars_widgets = get_option( 'sidebars_widgets' );

					if ( ! $sidebars_widgets ) {
						$sidebars_widgets = array();
					}

					$new_instance_id = $id_base . '-' . $new_instance_id_number; 
					$sidebars_widgets[$use_sidebar_id][] = $new_instance_id;
					update_option( 'sidebars_widgets', $sidebars_widgets ); 

					$after_widget_import = array(
						'sidebar'           => $use_sidebar_id,
						'sidebar_old'       => $sidebar_id,
						'widget'            => $widget,
						'widget_type'       => $id_base,
						'widget_id'         => $new_instance_id,
						'widget_id_old'     => $widget_instance_id,
						'widget_id_num'     => $new_instance_id_number,
						'widget_id_num_old' => $instance_id_number
					);

					if ( $sidebar_available ) {
						$widget_message_type = 'success';
						$widget_message = esc_html__( 'Imported', 'hbthemes' );
					} else {
						$widget_message_type = 'warning';
						$widget_message = esc_html__( 'Imported to Inactive', 'hbthemes' );
					}
				}

				$results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset( $available_widgets[$id_base]['name'] ) ? $available_widgets[$id_base]['name'] : $id_base; 
				$results[$sidebar_id]['widgets'][$widget_instance_id]['title'] = ! empty( $widget['title'] ) ? $widget['title'] : esc_html__( 'No Title', 'hbthemes' ); 
				$results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
				$results[$sidebar_id]['widgets'][$widget_instance_id]['message'] = $widget_message;
			}

			
		}
		return $results;
	}
}