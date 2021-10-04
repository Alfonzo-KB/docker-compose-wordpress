<?php
$rev_sliders = hb_get_revsliders();
$rev_sliders_items = array();
if ( ! empty ($rev_sliders) ) {
	foreach ($rev_sliders as $alias => $name) {
		$rev_sliders_items[] = array(
				'label' => $name,
				'value' => $alias,
			);
	}
}

$layer_sliders = hb_get_layersliders();
$layer_sliders_items = array();
if ( ! empty ($layer_sliders) ) {
	foreach ($layer_sliders as $alias => $name) {
		$layer_sliders_items[] = array(
				'label' => $name,
				'value' => $alias,
			);
	}
}

$hb_featured_section_options = array(
	array(
		'value' => '',
		'label' => 'Hide',
	),
	array(
		'value' => 'featured_image',
		'label' => 'Featured Image',
	),
	array(
		'value' => 'video',
		'label' => 'Video',
	),
);

if ( ! empty( $rev_sliders_items ) ) {
	$hb_featured_section_options[] = array(
		'value' => 'revolution',
		'label' => 'Revolution Slider',
	);
}

if ( ! empty( $layer_sliders_items ) ) {
	$hb_featured_section_options[] = array(
		'value' => 'layer',
		'label' => 'Layer Slider',
	);
}

return array(

	array(
		'type' => 'select',
		'name' => 'hb_featured_section_options',
		'label' => __('Featured Section Type','hbthemes'),
		'description' => __('Choose which element to display in the featured section of the page.','hbthemes'),
		'items' => $hb_featured_section_options
	),

	array(
		'type' => 'select',
		'name' => 'hb_rev_slider',
		'label' => __('Revolution Slider','hbthemes'),
		'description' => __('Choose a Revolution Slider to display in the Featured Section of the page.','hbthemes'),
		'items' => $rev_sliders_items,
		'dependency' => array(
			'field' => 'hb_featured_section_options',
			'function' => 'hb_page_featured_revslider',
		),
	),

	array(
		'type' => 'select',
		'name' => 'hb_layer_slider',
		'label' => __('Layer Slider','hbthemes'),
		'description' => __('Choose a Layer Slider to display in the Featured Section of the page.','hbthemes'),
		'items' => $layer_sliders_items,
		'dependency' => array(
			'field' => 'hb_featured_section_options',
			'function' => 'hb_page_featured_layer',
		),
	),

	array(
		'type' => 'textbox',
		'name' => 'hb_page_video',
		'label' => __('Video Link', 'hbthemes'),
		'default' => '',
		'dependency' => array(
			'field' => 'hb_featured_section_options',
			'function' => 'hb_page_featured_video',
		),
		'description' => __('Enter link to the video. Example: https://www.youtube.com/watch?v=Q_7cVyM8Efg', 'hbthemes'),
	),
	array(
		'type' => 'select',
		'name' => 'hb_featured_section_effect',
		'label' => __('Featured Section Effect','hbthemes'),
		'description' => __('Choose an effect for the Featured Section of the page.','hbthemes'),
		'items' => array(
			array(
				'value' => 'none',
				'label' => 'Disable',
			),
			array(
				'label' => 'Bokeh Effect',
				'value' => 'hb-bokeh-effect',
			),
			array(
				'label' => 'Connecting Lines Effect',
				'value' => 'hb-clines-effect',
			),
		),
		'default' => 'none',
	),

	array(
		'type' => 'select',
		'name' => 'hb_featured_section_parallax',
		'label' => __('Parallax','hbthemes'),
		'description' => __('Enable/disable parallax effect? Only for image It will not work for slider.','hbthemes'),
		'items' => array(
			array(
				'value' => 'none',
				'label' => 'Disable',
			),
			array(
				'label' => 'Enable',
				'value' => 'enable',
			),
		),
		'default' => 'none',
	),

	array(
		'type' => 'select',
		'name' => 'hb_featured_section_height',
		'label' => __('Featured Image Height','hbthemes'),
		'description' => __('Choose the height for the Featured Image for this page.','hbthemes'),
		'items' => array(
			array(
				'value' => 'original',
				'label' => 'Original size - do not crop',
			),
			array(
				'label' => 'Custom height',
				'value' => 'custom-height',
			),
			array(
				'label' => 'Window height',
				'value' => 'window-height',
			),
		),
		'default' => 'original',
	),
	array(
		'type' => 'slider',
		'name' => 'hb_featured_image_height',
		'label' => __('Featured Image Height', 'hbthemes'),
		'min' => 70,
		'max' => 1600,
		'step' => 10,
		'description' => "Specify the height of the image in pixels.",
		'default' => 400,
		'dependency' => array(
			'field' => 'hb_featured_section_height',
			'function' => 'hb_featured_image_height',
		),
	),
);
