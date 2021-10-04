<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Direct access forbidden.' ); }

/**
 * HB Customizer helper
 * 
 * @since 3.4.1
 */
class HB_Customizer_Helper {

	/**
	 * Customizer panels
	 *
	 * @since 3.4.1
	 */
	var $panels;


	/**
	 * Customizer sections
	 *
	 * @since 3.4.1
	 */
	var $sections;


	/**
	 * Customizer settings
	 *
	 * @since 3.4.1
	 */	
	var $settings;


	/**
	 * Customizer controls
	 *
	 * @since 3.4.1
	 */
	var $controls;


	/**
	 * Primary class constructor.
	 *
	 * @since 3.4.1
	 */
	public function __construct( $config ) {

		$this->panels 	= isset( $config['panels'] ) 	? $config['panels'] 	: array();
		$this->sections = isset( $config['sections'] ) 	? $config['sections'] 	: array();
		$this->settings = isset( $config['settings'] ) 	? $config['settings'] 	: array();
		$this->controls = isset( $config['controls'] ) 	? $config['controls'] 	: array();

		$this->add_actions();
	}


	/**
	 * Add neccessary admin actions.
	 *
	 * @since 3.4.1
	 */
	public function add_actions() {

		add_action( 'customize_register', array( $this, 'build_customizer_fields' ) );

		add_action( 'customize_preview_init', array( $this, 'customizer_script' ), 999 );

		if ( isset( $_GET['hb-export-customizer'] ) ) {
			add_action( 'init', array( $this, 'generate_export_file' ), 20 );
		}
	}


	/**
	 * Build customizer fields
	 *
	 * @since 3.4.1
	 */
	public function build_customizer_fields( $wp_customize ) {

	    $wp_customize->get_setting( 'blogname', array( 'transport' => 'postMessage' ) );
		$wp_customize->get_setting( 'blogdescription', array( 'transport' => 'postMessage' ) );
		$wp_customize->get_setting( 'header_textcolor', array( 'transport' => 'postMessage' ) );
		$wp_customize->get_setting( 'background_color', array( 'transport' => 'postMessage' ) );

		if ( ! empty( $this->panels ) ) {
			foreach ( $this->panels as $panel ) {
				$wp_customize->add_panel( $panel['id'] , $panel['args'] );
			}
		}

		if ( ! empty( $this->sections ) ) {
			foreach ( $this->sections as $section ) {
				$wp_customize->add_section( $section['id'], $section['args'] );
			}
		}

		if ( ! empty( $this->settings ) ) {
			foreach ( $this->settings as $setting ) {
				$wp_customize->add_setting( $setting['id'], $setting['args'] );
			}
		}

		if ( ! empty( $this->controls ) ) {
			foreach ( $this->controls as $control ) {
				if ( isset( $control['class'] ) && $control['class'] ) {
					if ( class_exists( $control['class'] ) ) {
						$wp_customize->add_control( new $control['class'](
							$wp_customize,
							$control['id'],
							$control['args']
						) );
					}
				} else {
					$wp_customize->add_control( $control['id'], $control['args'] );
				}
				
			}
		}
	}


	/**
	 * Enqueue customizer script. Used for live preview of the settings.
	 *
	 * @since 3.4.1
	 */
	public function customizer_script() {

		wp_enqueue_script( 
			'hb-customizer',
			get_parent_theme_file_uri( 'hbframework/assets/js/hb-customizer.js' ),
			array( 'jquery', 'customize-preview' ),
			rand(),
			false
		);
	}


	/**
	 * Import customizer settings
	 *
	 * @since 3.4.1
	 */
	public static function import_customizer_fields( $settings ) {

		if ( ! empty( $settings ) ) {
			foreach ( $settings as $name => $value ) {
				set_theme_mod( $name, $value );
			}
		}

	}


	/**
	 * Export customizer fields
	 *
	 * @since 3.4.1
	 */
	public static function export_customizer_fields() {
		
		$config 	= include get_parent_theme_file_path( 'config/customizer/customizer.php' );
		$settings 	= isset( $config['settings'] ) ? $config['settings'] : array();
		$export 	= array();

		foreach ( $settings as $setting ) {
			$export[ $setting['id'] ] = get_theme_mod( $setting['id'] );
		}

		return $export;
	}


	/**
	 * Generate file with exported customizer fields
	 *
	 * @since 3.4.1
	 */
	public function generate_export_file() {
		
		header( 'Content-disposition: attachment; filename=customizer.txt' );
		header( 'Content-Type: application/octet-stream; charset=' . get_option( 'blog_charset' ) );

		echo serialize( HB_Customizer_Helper::export_customizer_fields() );
		
		die();
	}
}

if ( file_exists( get_parent_theme_file_path( 'config/customizer/customizer.php' ) ) ) {
	new HB_Customizer_Helper( include get_parent_theme_file_path( 'config/customizer/customizer.php' ) );
}