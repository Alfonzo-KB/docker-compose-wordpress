<?php
return array(
	array(
	    'type'      => 'group',
	    'repeating' => true,
	    'name'      => 'hb_presentation_items',
	    'sortable' => true,
	    'title'     => __('Presentation Slides', 'hbthemes'),
		'description' => __('Add as many presentation slides as you want by clicking the Add More button.', 'hbthemes'),
	    'fields'    => array(

	    		array(
					'type' => 'textbox',
					'name' => 'hb_presentation_name',
					'label' => __('Slide Name', 'hbthemes'),
					'description' => __('Will be used for hash links. Must be UNIQUE.','hbthemes'),
					'default' => 'Untitled Presentation 1',
				),

	    		array(
		            'type' => 'select',
		            'name' => 'hb_presentation_type',
		            'label' => __('Background Type', 'hbthemes'),
		            'default' => 'color',
		            'items' => array(
		              array(
		                'value' => 'color',
		                'label' => __('Color', 'hbthemes'),
		              ),
		              array(
		                'value' => 'image',
		                'label' => __('Image', 'hbthemes'),
		              ),
		              array(
		                'value' => 'video',
		                'label' => __('Video', 'hbthemes'),
		              ),
		            ),
		        ),

		        array(
					'type' => 'color',
					'name' => 'hb_presentation_color',
					'label' => __('Background Color', 'hbthemes'),
					'description' => __('Choose background color for this slide.','hbthemes'),
					'default' => '#323436',
					'dependency' => array(
						'field' => 'hb_presentation_type',
						'function' => 'hb_presentation_type_color',
					),
				),

	    		array(
					'type' => 'upload',
					'name' => 'hb_presentation_image',
					'label' => __('Slide Background Image', 'hbthemes'),
					'description' => __('Upload a background image for this slide. Suggested dimensions are 1920x1080p.','hbthemes'),
					'dependency' => array(
						'field' => 'hb_presentation_type',
						'function' => 'hb_presentation_type_image',
					),
				),

				array(
					'type' => 'upload',
					'name' => 'hb_presentation_mobile_image',
					'label' => __('Poster Image', 'hbthemes'),
					'description' => __('Image that will be used on mobile phones and where videos are not supported. Suggested dimensions are 1920x1080p.','hbthemes'),
					'dependency' => array(
						'field' => 'hb_presentation_type',
						'function' => 'hb_presentation_type_video',
					),
				),

				array(
					'type' => 'textbox',
					'name' => 'hb_presentation_video_mp4',
					'label' => __('Video MP4', 'hbthemes'),
					'description' => __('Upload your video in MP4 format.','hbthemes'),
					'default' => '',
					'dependency' => array(
						'field' => 'hb_presentation_type',
						'function' => 'hb_presentation_type_video',
					),
				),

				array(
					'type' => 'textbox',
					'name' => 'hb_presentation_video_webm',
					'label' => __('Video WEBM', 'hbthemes'),
					'description' => __('Upload your video in WEBM format.','hbthemes'),
					'default' => '',
					'dependency' => array(
						'field' => 'hb_presentation_type',
						'function' => 'hb_presentation_type_video',
					),
				),

				array(
					'type' => 'textbox',
					'name' => 'hb_presentation_title',
					'label' => __('Title', 'hbthemes'),
					'description' => __('Add a title to this slide. Leave empty to disable. You can use HTML markup','hbthemes'),
					'default' => '',
				),
				array(
					'type' => 'textbox',
					'name' => 'hb_presentation_subtitle',
					'label' => __('Subtitle', 'hbthemes'),
					'description' => __('Add a subtitle to this slide. Leave empty to disable. You can use HTML markup.','hbthemes'),
					'default' => '',
				),

				array(
					'type' => 'select',
					'name' => 'hb_presentation_title_position',
					'label' => __('Title Position', 'hbthemes'),
					'description' => __('Choose position for title and subtitle.', 'hbthemes'),
					'items' => array(
						array(
							'value' => 'left',
							'label' => __('Left', 'hbthemes'),
						),
						array(
							'value' => 'center',
							'label' => __('Center', 'hbthemes'),
						),
						array(
							'value' => 'right',
							'label' => __('Right', 'hbthemes'),
						)
					),
					'default' => 'left',		
		
				),

			array(
				'type' => 'select',
				'name' => 'hb_presentation_style',
				'label' => __('Choose Style', 'hbthemes'),
				'default' => 'light',
				'items' => array(
					array(
						'value' => 'light',
						'label' => __('Light', 'hbthemes'),
					),
					array(
						'value' => 'dark',
						'label' => __('Dark', 'hbthemes'),
					),
				),
			),


				array(
					'type' => 'textbox',
					'name' => 'hb_presentation_primary_button_text',
					'label' => __('Primary Button Text', 'hbthemes'),
					'default' => 'Primary Button',
				),
				array(
					'type' => 'textbox',
					'name' => 'hb_presentation_primary_button_link',
					'label' => __('Primary Button Link', 'hbthemes'),
					'description' => __('Add link to this button. You need to enter the link with https:// prefix. Leave empty if you do not want to use it','hbthemes'),
					'default' => 'https://',
				),

				array(
					'type' => 'textbox',
					'name' => 'hb_presentation_secondary_button_text',
					'label' => __('Secondary Button Text', 'hbthemes'),
					'default' => 'Secondary Button',
				),
				array(
					'type' => 'textbox',
					'name' => 'hb_presentation_secondary_button_link',
					'label' => __('Secondary Button Link', 'hbthemes'),
					'description' => __('Add link to this button. You need to enter the link with https:// prefix. Leave empty if you do not want to use it','hbthemes'),
					'default' => 'https://',
				),

				array(
				'type' => 'select',
				'name' => 'hb_presentation_target',
				'label' => __('Open Links in', 'hbthemes'),
				'default' => '_blank',
				'items' => array(
					array(
						'value' => '_blank',
						'label' => __('New Tab', 'hbthemes'),
					),
					array(
						'value' => '_self',
						'label' => __('Same Tab', 'hbthemes'),
					),
				),
			),

		),
	),
);
?>