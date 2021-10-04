<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

return array(
	'panels' => array(
		array(
			'id'   => 'hb_panel_colors',
			'args' => array(
				'title'       => __( 'Colors', 'hbthemes' ),
				'description' => null,
				'priority'    => 300,
				'capability'  => 'edit_theme_options',
			),
		),
	),
	'sections' => array(
		array(
			'id'   => 'hb_focus_color_section',
			'args' => array(
				'title'       => __( 'Accent', 'hbthemes' ),
				'description' => null,
				'panel'       => 'hb_panel_colors',
			),
		),
		array(
			'id'   => 'hb_top_bar_section',
			'args' => array(
				'title'       => __( 'Top Bar', 'hbthemes' ),
				'description' => __( 'Accent color will be used as link hover color.', 'hbthemes' ),
				'panel'       => 'hb_panel_colors',
			),
		),
		array(
			'id'   => 'hb_side_nav_panel_section',
			'args' => array(
				'title'       => __( 'Side Navigation', 'hbthemes' ),
				'description' => __( 'Accent color will be used as link hover color.', 'hbthemes' ),
				'panel'       => 'hb_panel_colors',
			),
		),
		array(
			'id'   => 'hb_nav_bar_section',
			'args' => array(
				'title'       => __( 'Navigation Bar', 'hbthemes' ),
				'description' => __( 'Accent color will be used as link hover color.', 'hbthemes' ),
				'panel'       => 'hb_panel_colors',
			),
		),
		array(
			'id'   => 'hb_special_header_section',
			'args' => array(
				'title'       => __( 'Special Header', 'hbthemes' ),
				'description' => __( 'Colors related to the special header.', 'hbthemes' ),
				'panel'       => 'hb_panel_colors',
			),
		),
		array(
			'id'   => 'hb_pfooter_section',
			'args' => array(
				'title'       => __( 'Callout (Pre Footer)', 'hbthemes' ),
				'description' => __( 'Accent color will be used as link hover color.', 'hbthemes' ),
				'panel'       => 'hb_panel_colors',
			),
		),
		array(
			'id'   => 'hb_footer_section',
			'args' => array(
				'title'       => __( 'Footer', 'hbthemes' ),
				'description' => __( 'Accent color will be used as link hover color.', 'hbthemes' ),
				'panel'       => 'hb_panel_colors',
			),
		),
		array(
			'id'   => 'hb_copyright_section',
			'args' => array(
				'title'       => __( 'Copyright Bar', 'hbthemes' ),
				'description' => __( 'Accent color will be used as link hover color.', 'hbthemes' ),
				'panel'       => 'hb_panel_colors',
			),
		),
		array(
			'id'   => 'hb_content_section',
			'args' => array(
				'title'       => __( 'Content', 'hbthemes' ),
				'description' => __( 'Accent color will be used as link hover color.', 'hbthemes' ),
				'panel'       => 'hb_panel_colors',
			),
		),
	),
	'settings' => array(
		array(
			'id'   => 'hb_focus_color_setting',
			'args' => array(
				'default'    => '#1dc6df',
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport'  => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_top_bar_bg_setting',
			'args' => array(
				'default'       => '#ffffff',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_top_bar_border_setting',
			'args' => array(
				'default'       => '#e1e1e1',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_top_bar_text_color_setting',
			'args' => array(
				'default'       => '#777777',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_top_bar_link_color_setting',
			'args' => array(
				'default'       => '#444',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_side_nav_bg_setting',
			'args' => array(
				'default'       => '#ffffff',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_nav_bar_bg_setting',
			'args' => array(
				'default'       => '#ffffff',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_nav_bar_stuck_bg_setting',
			'args' => array(
				'default'       => '#ffffff',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_nav_bar_text_setting',
			'args' => array(
				'default'       => '#444',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_nav_bar_stuck_text_setting',
			'args' => array(
				'default'       => '#444',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_nav_bar_border_setting',
			'args' => array(
				'default'       => '#e1e1e1',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_nav_bar_stuck_border_setting',
			'args' => array(
				'default'       => '#e1e1e1',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_spec_head_color_setting',
			'args' => array(
				'default'       => '#FFF',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_spec_head_bg_stuck_setting',
			'args' => array(
				'default'       => '#000',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_spec_head_color_sticky_setting',
			'args' => array(
				'default'       => '#FFF',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_pfooter_bg_setting',
			'args' => array(
				'default'       => '#ececec',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_pfooter_text_setting',
			'args' => array(
				'default'       => '#323436',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_footer_bg_setting',
			'args' => array(
				'default'       => '#222',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_footer_text_setting',
			'args' => array(
				'default'       => '#999',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_footer_link_setting',
			'args' => array(
				'default'       => '#fff',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_copyright_bg_setting',
			'args' => array(
				'default'       => '#292929',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_copyright_text_setting',
			'args' => array(
				'default'       => '#999',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_copyright_link_setting',
			'args' => array(
				'default'       => '#fff',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_content_bg_setting',
			'args' => array(
				'default'       => '#444444',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_side_section_bg_setting',
			'args' => array(
				'default'       => '#1B1B1B',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_content_c_bg_setting',
			'args' => array(
				'default'       => '#f9f9f9',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_content_text_color_setting',
			'args' => array(
				'default'       => '#343434',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_content_link_color_setting',
			'args' => array(
				'default'       => '#222',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_content_border_setting',
			'args' => array(
				'default'       => '#e1e1e1',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_content_h1_setting',
			'args' => array(
				'default'       => '#323436',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_content_h2_setting',
			'args' => array(
				'default'       => '#323436',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_content_h3_setting',
			'args' => array(
				'default'       => '#323436',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_content_h4_setting',
			'args' => array(
				'default'       => '#323436',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_content_h5_setting',
			'args' => array(
				'default'       => '#323436',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
		array(
			'id'   => 'hb_content_h6_setting',
			'args' => array(
				'default'       => '#323436',
				'type'          => 'theme_mod',
				'capability'    => 'edit_theme_options',
				'transport'     => 'postMessage',
			),
		),
	),

	'controls' => array(
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_focus_color_setting',
			'args'  => array(
				'label'         => __( 'Accent Color', 'hbthemes' ),
				'section'       => 'hb_focus_color_section',
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_top_bar_bg_setting',
			'args'  => array(
				'label'         => __( 'Background Color', 'hbthemes' ),
				'section'       => 'hb_top_bar_section',
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_top_bar_border_setting',
			'args'  => array(
				'label'         => __( 'Border Color', 'hbthemes' ),
				'section'       => 'hb_top_bar_section',
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_top_bar_text_color_setting',
			'args'  => array(
				'label'         => __( 'Text Color', 'hbthemes' ),
				'section'       => 'hb_top_bar_section',
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_top_bar_link_color_setting',
			'args'  => array(
				'label'         => __( 'Link Color', 'hbthemes' ),
				'section'       => 'hb_top_bar_section',
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_side_nav_bg_setting',
			'args'  => array(
				'label'         => __( 'Background Color', 'hbthemes' ),
				'section'       => 'hb_side_nav_panel_section',
				'priority'      => 20,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_nav_bar_bg_setting',
			'args'  => array(
				'label'         => __( 'Background Color', 'hbthemes' ),
				'section'       => 'hb_nav_bar_section',
				'priority'      => 20,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_nav_bar_stuck_bg_setting',
			'args'  => array(
				'label'         => __( 'Background Color (Sticky)', 'hbthemes' ),
				'section'       => 'hb_nav_bar_section',
				'priority'      => 50,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_nav_bar_text_setting',
			'args'  => array(
				'label'         => __( 'Text Color', 'hbthemes' ),
				'section'       => 'hb_nav_bar_section',
				'priority'      => 30,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_nav_bar_stuck_text_setting',
			'args'  => array(
				'label'         => __( 'Text Color (Sticky)', 'hbthemes' ),
				'section'       => 'hb_nav_bar_section',
				'priority'      => 60,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_nav_bar_border_setting',
			'args'  => array(
				'label'         => __( 'Border Color', 'hbthemes' ),
				'section'       => 'hb_nav_bar_section',
				'priority'      => 40,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_nav_bar_stuck_border_setting',
			'args'  => array(
				'label'         => __( 'Border Color (Sticky)', 'hbthemes' ),
				'section'       => 'hb_nav_bar_section',
				'priority'      => 70,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_spec_head_color_setting',
			'args'  => array(
				'label'         => __( 'Text Color', 'hbthemes' ),
				'section'       => 'hb_special_header_section',
				'priority'      => 60,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_spec_head_bg_stuck_setting',
			'args'  => array(
				'label'         => __( 'Background Color (Sticky)', 'hbthemes' ),
				'section'       => 'hb_special_header_section',
				'priority'      => 70,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_spec_head_color_sticky_setting',
			'args'  => array(
				'label'         => __( 'Text Color (Sticky)', 'hbthemes' ),
				'section'       => 'hb_special_header_section',
				'priority'      => 60,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_pfooter_bg_setting',
			'args'  => array(
				'label'         => __( 'Background Color', 'hbthemes' ),
				'section'       => 'hb_pfooter_section',
				'priority'      => 20,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_pfooter_text_setting',
			'args'  => array(
				'label'         => __( 'Text Color', 'hbthemes' ),
				'section'       => 'hb_pfooter_section',
				'priority'      => 30,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_footer_bg_setting',
			'args'  => array(
				'label'         => __( 'Background Color', 'hbthemes' ),
				'section'       => 'hb_footer_section',
				'priority'      => 20,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_footer_text_setting',
			'args'  => array(
				'label'         => __( 'Text Color', 'hbthemes' ),
				'section'       => 'hb_footer_section',
				'priority'      => 30,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_footer_link_setting',
			'args'  => array(
				'label'         => __( 'Link Color', 'hbthemes' ),
				'section'       => 'hb_footer_section',
				'priority'      => 40,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_copyright_bg_setting',
			'args'  => array(
				'label'         => __( 'Background Color', 'hbthemes' ),
				'section'       => 'hb_copyright_section',
				'priority'      => 20,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_copyright_text_setting',
			'args'  => array(
				'label'         => __( 'Text Color', 'hbthemes' ),
				'section'       => 'hb_copyright_section',
				'priority'      => 30,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_copyright_link_setting',
			'args'  => array(
				'label'         => __( 'Link Color', 'hbthemes' ),
				'section'       => 'hb_copyright_section',
				'priority'      => 40,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_content_bg_setting',
			'args'  => array(
				'label'         => __( 'Body Background Color', 'hbthemes' ),
				'section'       => 'hb_content_section',
				'priority'      => 20,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_side_section_bg_setting',
			'args'  => array(
				'label'         => __( 'Side Section Background Color', 'hbthemes' ),
				'section'       => 'hb_content_section',
				'priority'      => 25,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_content_c_bg_setting',
			'args'  => array(
				'label'         => __( 'Content Background Color', 'hbthemes' ),
				'section'       => 'hb_content_section',
				'priority'      => 30,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_content_text_color_setting',
			'args'  => array(
				'label'         => __( 'Content Text Color', 'hbthemes' ),
				'section'       => 'hb_content_section',
				'priority'      => 40,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_content_link_color_setting',
			'args'  => array(
				'label'         => __( 'Content Link Color', 'hbthemes' ),
				'section'       => 'hb_content_section',
				'priority'      => 45,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_content_border_setting',
			'args'  => array(
				'label'         => __( 'Various Borders Color', 'hbthemes' ),
				'section'       => 'hb_content_section',
				'priority'      => 50,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_content_h1_setting',
			'args'  => array(
				'label'         => __( 'H1 Color', 'hbthemes' ),
				'section'       => 'hb_content_section',
				'priority'      => 55,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_content_h2_setting',
			'args'  => array(
				'label'    => __( 'H2 Color', 'hbthemes' ),
				'section'  => 'hb_content_section',
				'priority' => 51,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_content_h3_setting',
			'args'  => array(
				'label'         => __( 'H3 Color', 'hbthemes' ),
				'section'       => 'hb_content_section',
				'priority'      => 52,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_content_h4_setting',
			'args'  => array(
				'label'         => __( 'H4 Color', 'hbthemes' ),
				'section'       => 'hb_content_section',
				'priority'      => 53,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_content_h5_setting',
			'args'  => array(
				'label'         => __( 'H5 Color', 'hbthemes' ),
				'section'       => 'hb_content_section',
				'priority'      => 54,
			),
		),
		array(
			'class' => 'WP_Customize_Color_Control',
			'id'    => 'hb_content_h6_setting',
			'args'  => array(
				'label'         => __( 'H6 Color', 'hbthemes' ),
				'section'       => 'hb_content_section',
				'priority'      => 55,
			),
		),
	),
);
