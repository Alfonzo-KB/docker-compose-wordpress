<?php 
if ( ! defined( 'ABSPATH' ) ) { die( 'Direct access forbidden.' ); }

if ( ! class_exists( 'WP_Upgrader' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
}

if ( ! function_exists( 'request_filesystem_credentials' ) ) {
	require_once ABSPATH . '/wp-admin/includes/file.php' ;
}

class HB_Upgrader_Skin extends WP_Upgrader_Skin {

	public function feedback( $string, ...$args ) {
		return;
	}
}

/**
 * HB Install Plugins utilities.
 * Class that contains methods for installing & activating plugins
 * 
 * @since 3.4.1
 */
class HB_Plugin_Installation {

	/**
	 * Install array of plugins
	 * 
	 * @param array, $plugins array of plugins to install
	 * @since 3.4.1
	 */
	public static function install_plugins ( $plugins ) {

		$status = array();

		foreach ( $plugins as $plugin ) {
			$status[ $plugin['slug'] ]['install'] = self::install_plugin( $plugin );
		}

		return $status;
	}

	/**
	 * Install individual plugin
	 * 
	 * @param array, $plugin plugin to be installed
	 * @since 3.4.1
	 */
	public static function install_plugin ( $plugin ) {

		@set_time_limit( 60 * 5 );

		$hooks = array(
			'type'   => 'plugin',
			'action' => 'install'
		);

		// For plugins that do not have source defined, try to look for source file
		if ( empty( $plugin['source'] ) ) {

			$plugin_api = HB_Plugin_Installation::public_plugins_api( 'plugin_information', array( 'slug' => $plugin['slug'] ) );
			
			if ( is_wp_error( $plugin_api ) ) {
				return array(
					'success' => false,
					'data'    => array(
						'message' => $plugin_api->get_error_message()
					)
				);
			}

			$plugin['source'] = $plugin_api->download_link;
		}

		$has_update 		 = HB_Plugin_Installation::has_plugin_update( $plugin );
		$is_plugin_installed = HB_Plugin_Installation::is_plugin_installed( $plugin['slug'] );

		if ( $has_update ) {
			$hooks['action'] = 'update';
			$hooks['plugin'] = $plugin['slug'] . '/' . $plugin['name'];
		} else if ( $is_plugin_installed ) {
			return array(
				'success' => true,
				'data'    => array(
					'message' => esc_html__( 'Plugin is already installed.', 'hbthemes' )
				)
			);
		}
		
		$response = self::download_install_plugin_package( $plugin['source'], WP_PLUGIN_DIR, $hooks);

		return $response;
	}


	/**
	 * Install plugin based on a json object
	 * 
	 * @param array, $plugin plugin to be installed
	 * @since 3.4.1
	 */
	public static function install_plugin_json ( $plugin ) {

		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( array( 'message' => esc_html__("Current user can't install plugins", 'hbthemes' ) ) );
		}

		if ( ! $plugin ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Failed to install required plugin.', 'hbthemes' ),
			) );
		}
		
		$response = HB_Plugin_Installation::install_plugin( $plugin );

		if ( empty( $response ) || ( ! empty( $response ) && ! $response['success'] ) ) {
			wp_send_json_error( array(
				'message' => sprintf( esc_html__( 'Failed to install %s.', 'hbthemes' ), $plugin['name'] ),
			) );
		}

		if ( ! empty( $response ) && isset ( $response['data']['message'] ) && $response['data']['message'] == esc_html__( 'Plugin is already installed.', 'hbthemes' ) ) {
			wp_send_json_success( array(
				'message' => sprintf( esc_html__( 'Latest version of plugin is already installed: %s.', 'hbthemes' ), $plugin['name'] ),
			) );
		}

		wp_send_json_success( array(
			'message' => sprintf( esc_html__( 'Plugin installed: %s.', 'hbthemes' ), $plugin['name'] ),
		) );
	}

	
	/**
	 * Activate array of plugins
	 * 
	 * @param array, $plugin plugins to be activated
	 * @since 3.4.1
	 */
	public static function activate_plugins ( $plugins ) {

		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( array(
				'message' => sprintf( esc_html__( "Current user can't activate plugins.", "hbthemes" ), WP_PLUGIN_DIR ),
			) );
		}

		$status = array();

		wp_clean_plugins_cache( false );

		// Activate installed plugins
		foreach ( $plugins as $plugin ) {
			$status[ $plugin['slug'] ]['activate'] = HB_Plugin_Installation::activate_plugin( $plugin );
		}

		return $status;
	}


	/**
	 * Activate plugin
	 * 
	 * @param array, $plugin plugin to be activated
	 * @since 3.4.1
	 */
	public static function activate_plugin_json ( $plugin ) {

		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( "Current user can't activate plugins", 'hbthemes' ) ) );
		}

		if ( ! $plugin ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Failed to activate plugin.', 'hbthemes' ),
			) );
		}

		$response = HB_Plugin_Installation::activate_plugin( $plugin );

		if ( empty( $response ) || ( ! empty( $response ) && ! $response['success'] ) ) {
			wp_send_json_error( array(
				'message' => sprintf( esc_html__( 'Failed to activate %s.', 'hbthemes' ), $plugin['name'] ),
			) );
		}

		if ( ! empty( $response ) && isset ( $response['data']['message'] ) && $response['data']['message'] == esc_html__( 'Plugin is already activated.', 'hbthemes' ) ) {
			wp_send_json_success( array(
				'message' => sprintf( esc_html__( 'Plugin is already activated: %s.', 'hbthemes' ), $plugin['name'] ),
			) );
		}

		wp_send_json_success( array(
				'message' => sprintf( esc_html__( 'Plugin activated: %s.', 'hbthemes' ), $plugin['name'] ),
			) );
	}


	/**
	 * Dectivate plugin
	 * 
	 * @param array, $plugin plugin to be deactivated
	 * @since 3.4.1
	 */
	public static function deactivate_plugin_json ( $plugin ) {

		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( "Current user can't deactivate plugins", 'hbthemes' ) ) );
		}

		if ( ! $plugin ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Failed to deactivate plugin.', 'hbthemes' ),
			) );
		}

		$response = HB_Plugin_Installation::deactivate_plugin( $plugin );

		if ( empty( $response ) || ( ! empty( $response ) && ! $response['success'] ) ) {
			wp_send_json_error( array(
				'message' => sprintf( esc_html__( 'Failed to deactivate %s.', 'hbthemes' ), $plugin['name'] ),
			) );
		}

		if ( ! empty( $response ) && isset ( $response['data']['message'] ) && $response['data']['message'] == esc_html__( 'Failed to deactivate plugin.', 'hbthemes' ) ) {
			wp_send_json_success( array(
				'message' => sprintf( esc_html__( 'Plugin not active: %s.', 'hbthemes' ), $plugin['name'] ),
			) );
		}

		wp_send_json_success( array(
				'message' => sprintf( esc_html__( 'Plugin deactivated: %s.', 'hbthemes' ), $plugin['name'] ),
			) );
	}


	/**
	 * Activate individual plugin
	 * 
	 * @param array, $plugin plugin to be activated
	 * @since 3.4.1
	 */
	public static function activate_plugin ( $plugin ) {

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_data = get_plugins( '/' . $plugin['slug'] );

		if ( empty( $plugin_data ) ) {
			return array(
				'success' => false,
				'data'    => array(
					'message' => sprintf( esc_html__( "It was not possible to activate %s. Because it isn't installed.", 'hbthemes' ), $plugin['name'] )
				)
			);
		}

		$plugin_file_array 	= array_keys ( $plugin_data );
		$plugin_file 		= $plugin_file_array[0];
		$plugin_to_activate = $plugin['slug'] . '/' . $plugin_file;
		$activate 			= activate_plugin( $plugin_to_activate );

		if ( is_wp_error( $activate ) ) {
			return array(
				'success' => false,
				'data'    => array(
					'message' => $activate->get_error_message()
				)
			);
		}

		return array(
			'success' => true,
			'data' => array(
				'message' => sprintf( esc_html__( 'Plugin %s is successfully activated.', 'hbthemes' ), $plugin['name'] ))
		);
	}


	/**
	 * Deactivate individual plugin
	 * 
	 * @param array, $plugin plugin to be deactivated
	 * @since 3.4.1
	 */
	public static function deactivate_plugin ( $plugin ) {

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_data = get_plugins( '/' . $plugin['slug'] );

		if ( empty( $plugin_data ) ) {
			return array(
				'success' => false,
				'data'    => array(
					'message' => sprintf( esc_html__( "It was not possible to deactivate %s. Because it isn't active.", 'hbthemes' ), $plugin['name'] )
				)
			);
		}

		$plugin_file_array  = array_keys ( $plugin_data );
		$plugin_file 		= $plugin_file_array[0];
		$plugin_to_activate = $plugin['slug'] . '/' . $plugin_file;
		$deactivate 		= deactivate_plugins( $plugin_to_activate );

		if ( is_wp_error( $deactivate ) ) {
			return array(
				'success' => false,
				'data'    => array(
					'message' => $deactivate->get_error_message()
				)
			);
		}

		return array(
			'success' => true,
			'data' => array(
				'message' => sprintf( esc_html__( 'Plugin %s is successfully deactivated.', 'hbthemes' ), $plugin['name'] ))
		);
	}


	/**
	 * Check if plugin has a pending update
	 * 
	 * @param array, $plugin plugin to be activated
	 * @param boolean, $strict force plugin to update
	 * @since 3.4.1
	 */
	public static function has_plugin_update ( $plugin, $strict = false ) {

		$installed_plugin = HB_Plugin_Installation::is_plugin_installed( $plugin['slug'] );

		if ( $installed_plugin ) {

			$plugin_name = null;
			if ( $installed_plugin ) {
				$plugin_name = array_keys( $installed_plugin );
				$plugin_name = $plugin_name[0];
			}
			
			$plugin_version = $installed_plugin ? $installed_plugin[$plugin_name]['Version'] : null;

			if ( $plugin_name && ! empty( $plugin_version ) ) {
				if ( isset( $plugin['version'] ) ) {
					return version_compare( $plugin_version, $plugin['version'], '<' );
				} else if ( ! $strict ) {
					return true;
				}
			}
		}
		return false;
	}


	/**
	 * Check if plugin is installed
	 * 
	 * @param array, $plugin plugin to inspect
	 * @since 3.4.1
	 */
	public static function is_plugin_installed ( $slug ) {

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		
		return get_plugins( '/' . $slug );
	}


	/**
	 * Check if plugin is activated
	 * 
	 * @param array, $plugin plugin to inspect
	 * @since 3.4.1
	 */
	public static function is_plugin_activated ( $slug ) {

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		
		$installed_plugin = get_plugins( '/' . $slug );
		
		if ( $installed_plugin ) {
			$plugin_name = array_keys( $installed_plugin );
			return is_plugin_active( $slug . '/' . $plugin_name[0] );
		}

		return false;
	}


	/**
	 * Fetch plugins config array from HB server
	 * 
	 * @since 3.4.2
	 */
	public static function get_remote_plugins_config( $plugins ) {

		if ( get_site_transient( 'hb_check_plugin_update' ) ) {
			$remote_plugins = get_site_transient( 'hb_check_plugin_update' );
		} else {

			$response = wp_remote_get( 'https://hb-themes.com/repository/plugins/highend_plugins_config.json');

			if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
				return $plugins;
			}

			$response_body 	= wp_remote_retrieve_body( $response );
			$remote_plugins = json_decode( $response_body, true );
			set_site_transient( 'hb_check_plugin_update', $remote_plugins, 60*60*12 );
		}

		if ( ! is_array( $remote_plugins ) || empty( $remote_plugins ) ) {
			return $plugins;
		}

		if ( is_array( $plugins ) && ! empty( $plugins ) ) {
			foreach ( $plugins as $slug => $settings ) {
				if ( isset( $remote_plugins[ $slug ] ) && isset( $remote_plugins[ $slug ]['version'] ) ) {
					$remote_version = $remote_plugins[ $slug ]['version'];
					if ( -1 === version_compare( $plugins[ $slug ]['version'], $remote_version ) ) {
						$plugins[ $slug ]['version'] = $remote_version;
					}
				}
			}
		}

		return $plugins;
	}


	/**
	 * Download and install a plugin package
	 * 
	 * @since 3.4.1
	 */
	public static function download_install_plugin_package ( $package, $destination, $hook_extra = array() ) {

		if ( ! class_exists( 'WP_Upgrader' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}

		@set_time_limit( 60 * 10 );

		$upgrader = new WP_Upgrader( new HB_Upgrader_Skin() );

		$upgrader->generic_strings();
		$download = $upgrader->download_package( $package );

		if ( is_wp_error( $download ) ) {
			return array(
				'success' => false,
				'data'    => array(
					'message' => $download->get_error_message()
				)
			);
		}

		//Unzips the file into a temporary directory
		$working_dir = $upgrader->unpack_package( $download, true );
		if ( is_wp_error( $working_dir ) ) {
			return array(
				'success' => false,
				'data'    => array(
					'message' => $working_dir->get_error_message()
				)
			);
		}

		if ( is_wp_error( $working_dir ) ) {
			return array(
				'success' => false,
				'data'    => array(
					'message' => $working_dir->get_error_message()
				)
			);
		}

		$result = $upgrader->install_package( array(
			'source'                      => $working_dir,
			'destination'                 => $destination,
			'clear_destination'           => true,
			'abort_if_destination_exists' => false,
			'clear_working'               => true,
			'hook_extra'                  => $hook_extra
		) );

		return array(
			'success' => true,
			'data'    => array(
				'message' => $result
			)
		);
	}

	
	/**
	 * For plugins that are located on a remote server
	 * 
	 * @since 3.4.1
	 */
	public static function public_plugins_api ( $action, $args = null ) {

		if ( is_array( $args ) ) {
			$args = (object) $args;
		}

		if ( ! isset( $args->per_page ) ) {
			$args->per_page = 24;
		}

		// Allows a plugin to override the WordPress.org API entirely.
		// Use the filter 'plugins_api_result' to merely add results.
		// Please ensure that a object is returned from the following filters.
		$args = apply_filters( 'plugins_api_args', $args, $action );
		$res  = apply_filters( 'plugins_api', false, $action, $args );

		if ( false === $res ) {
			$url = 'http://api.wordpress.org/plugins/info/1.0/';
			if ( wp_http_supports( array( 'ssl' ) ) ) {
				$url = set_url_scheme( $url, 'https' );
			}

			$request = wp_remote_post( $url, array(
				'timeout' => 15,
				'body'    => array(
					'action'  => $action,
					'request' => serialize( $args )
				)
			) );

			if ( is_wp_error( $request ) ) {
				$res = new WP_Error( 'plugins_api_failed', esc_html__( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="http://wordpress.org/support/">support forums</a>.', 'hbthemes' ), $request->get_error_message() );
			} else {
				$res = maybe_unserialize( wp_remote_retrieve_body( $request ) );
				if ( ! is_object( $res ) && ! is_array( $res ) ) {
					$res = new WP_Error( 'plugins_api_failed', esc_html__( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="http://wordpress.org/support/">support forums</a>.', 'hbthemes' ), wp_remote_retrieve_body( $request ) );
				}
			}
		} elseif ( ! is_wp_error( $res ) ) {
			$res->external = true;
		}

		return apply_filters( 'plugins_api_result', $res, $action, $args );
	}


	/**
	 * Get plugin object based on slug
	 * 
	 * @since 3.4.1
	 */
	public static function get_plugin_by_slug ( $slug, $plugins ) {
		
		if ( ! empty( $plugins ) ) {
			foreach ( $plugins as $plugin ) {
				if ( $plugin['slug'] == $slug ) {
					return $plugin;
				}
			}
		}
		
		return false;
	}
}