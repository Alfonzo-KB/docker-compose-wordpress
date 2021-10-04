<?php
/**
 * Metabox Class.
 *
 * @package Highend
 * @since   3.5.0
*/

if ( ! class_exists( 'Highend_Metaboxes' ) ) :

	/**
	 * Highend Metabox Class.
	 *
	 * @since 3.5.0
	 */
	class Highend_Metaboxes {

		/**
		 * Primary class constructor.
		 *
		 * @since 3.5.1
		 */
		public function __construct() {

			add_action( 'init', array( $this, 'metaboxes' ) );

			// Enqueue scripts.
			add_action( 'admin_print_scripts-post-new.php', array( $this, 'load_assets' ) );
			add_action( 'admin_print_scripts-post.php', array( $this, 'load_assets' ) );
		}

		/**
		 * Load required assets on the admin page(s).
		 *
		 * @since 3.5.1
		 */
		public function load_assets( $hook ) {

			if ( function_exists( 'vc_is_frontend_editor' ) && vc_is_frontend_editor() ) {
				return;
			}

			wp_enqueue_script( 
				'highend-metabox-js',
				HBTHEMES_ADMIN_URI . '/assets/js/metabox.js',
				array( 'jquery' ),
				HB_THEME_VERSION,
				true
			);
		}

		/**
		 * Init VafPress metaboxes
		 *
		 * @todo To be removed in v4.0 and replaced with updated metaboxes.
		 * @return [type] [description]
		 */
		public function metaboxes() {

			if ( highend_is_module_enabled( 'hb_module_pricing_tables' ) ) {
				$mb_path_pricing_settings = HBTHEMES_ADMIN . '/metaboxes/meta-pricing-table-settings.php';
				$mb_post_settings         = new VP_Metabox(
					array(
						'id'          => 'pricing_settings',
						'types'       => array(
							'hb_pricing_table',
						),
						'title'       => __( 'Pricing Settings', 'hbthemes' ),
						'priority'    => 'low',
						'is_dev_mode' => false,
						'template'    => $mb_path_pricing_settings,
					)
				);
			}

			if ( highend_is_module_enabled( 'hb_module_testimonials' ) ) {
				$mb_path_testimonials_settings = HBTHEMES_ADMIN . '/metaboxes/meta-testimonials.php';
				$mb_post_settings              = new VP_Metabox(
					array(
						'id'          => 'testimonial_type_settings',
						'types'       => array(
							'hb_testimonials',
						),
						'title'       => __( 'Testimonial Settings', 'hbthemes' ),
						'priority'    => 'low',
						'is_dev_mode' => false,
						'template'    => $mb_path_testimonials_settings,
					)
				);
			}

			if ( highend_is_module_enabled( 'hb_module_team_members' ) ) {
				$mb_path_team_layout_settings = HBTHEMES_ADMIN . '/metaboxes/meta-team-layout-settings.php';
				$mb_post_settings             = new VP_Metabox(
					array(
						'id'          => 'team_layout_settings',
						'types'       => array(
							'team',
						),
						'title'       => __( 'Team Layout Settings', 'hbthemes' ),
						'priority'    => 'low',
						'is_dev_mode' => false,
						'context'     => 'side',
						'template'    => $mb_path_team_layout_settings,
					)
				);

				$mb_path_team_member_settings = HBTHEMES_ADMIN . '/metaboxes/meta-team-member-settings.php';
				$mb_post_settings             = new VP_Metabox(
					array(
						'id'          => 'team_member_settings',
						'types'       => array(
							'team',
						),
						'title'       => __( 'Team Member Settings', 'hbthemes' ),
						'priority'    => 'low',
						'is_dev_mode' => false,
						'template'    => $mb_path_team_member_settings,
					)
				);
			}

			if ( highend_is_module_enabled( 'hb_module_clients' ) ) {
				$mb_path_clients_settings = HBTHEMES_ADMIN . '/metaboxes/meta-clients-settings.php';
				$mb_post_settings         = new VP_Metabox(
					array(
						'id'          => 'clients_settings',
						'types'       => array(
							'clients',
						),
						'title'       => __( 'Clients Settings', 'hbthemes' ),
						'priority'    => 'low',
						'is_dev_mode' => false,
						'template'    => $mb_path_clients_settings,
					)
				);
			}

			$mb_path_presentation_settings = HBTHEMES_ADMIN . '/metaboxes/meta-presentation-settings.php';
			$mb_presentation_settings      = new VP_Metabox(
				array(
					'id'          => 'presentation_settings',
					'types'       => array(
						'page',
					),
					'title'       => __( 'Presentation Settings', 'hbthemes' ),
					'priority'    => 'low',
					'is_dev_mode' => false,
					'template'    => $mb_path_presentation_settings,
				)
			);

			$mb_path_featured_section_settings = HBTHEMES_ADMIN . '/metaboxes/meta-featured-page-section.php';
			$mb_post_settings                  = new VP_Metabox(
				array(
					'id'          => 'featured_section',
					'types'       => array(
						'page',
						'team',
					),
					'title'       => __( 'Featured Section Settings', 'hbthemes' ),
					'priority'    => 'low',
					'is_dev_mode' => false,
					'template'    => $mb_path_featured_section_settings,
				)
			);

			$mb_path_contact_page_template_settings = HBTHEMES_ADMIN . '/metaboxes/meta-contact-page-settings.php';
			$mb_post_settings                       = new VP_Metabox(
				array(
					'id'          => 'contact_page_settings',
					'types'       => array(
						'page',
					),
					'title'       => __( 'Contact Template Settings', 'hbthemes' ),
					'priority'    => 'low',
					'is_dev_mode' => false,
					'template'    => $mb_path_contact_page_template_settings,
				)
			);

			$mb_path_post_format_settings        = HBTHEMES_ADMIN . '/metaboxes/meta-post-format-settings.php';
			$mb_post_settings                    = new VP_Metabox(
				array(
					'id'          => 'post_format_settings',
					'types'       => array(
						'post',
					),
					'title'       => __( 'Post Format Settings', 'hbthemes' ),
					'priority'    => 'low',
					'is_dev_mode' => false,
					'template'    => $mb_path_post_format_settings,
				)
			);
			$mb_path_blog_page_template_settings = HBTHEMES_ADMIN . '/metaboxes/meta-blog-page-settings.php';
			$mb_post_settings                    = new VP_Metabox(
				array(
					'id'          => 'blog_page_settings',
					'types'       => array(
						'page',
					),
					'title'       => __( 'Classic Blog Template Settings', 'hbthemes' ),
					'priority'    => 'low',
					'is_dev_mode' => false,
					'template'    => $mb_path_blog_page_template_settings,
				)
			);

			$mb_path_blog_page_minimal_template_settings = HBTHEMES_ADMIN . '/metaboxes/meta-blog-page-minimal-settings.php';
			$mb_post_settings                            = new VP_Metabox(
				array(
					'id'          => 'blog_page_minimal_settings',
					'types'       => array(
						'page',
					),
					'title'       => __( 'Minimal Blog Template Settings', 'hbthemes' ),
					'priority'    => 'low',
					'is_dev_mode' => false,
					'template'    => $mb_path_blog_page_minimal_template_settings,
				)
			);

			$mb_path_grid_blog_page_template_settings = HBTHEMES_ADMIN . '/metaboxes/meta-blog-grid-page-settings.php';
			$mb_post_settings                         = new VP_Metabox(
				array(
					'id'          => 'blog_grid_page_settings',
					'types'       => array(
						'page',
					),
					'title'       => __( 'Grid Blog Template Settings', 'hbthemes' ),
					'priority'    => 'low',
					'is_dev_mode' => false,
					'template'    => $mb_path_grid_blog_page_template_settings,
				)
			);

			$mb_path_fw_blog_page_template_settings = HBTHEMES_ADMIN . '/metaboxes/meta-blog-fw-page-settings.php';
			$mb_post_settings                       = new VP_Metabox(
				array(
					'id'          => 'blog_fw_page_settings',
					'types'       => array(
						'page',
					),
					'title'       => __( 'Fullwidth Blog Template Settings', 'hbthemes' ),
					'priority'    => 'low',
					'is_dev_mode' => false,
					'template'    => $mb_path_fw_blog_page_template_settings,
				)
			);

			$mb_path_general_settings = HBTHEMES_ADMIN . '/metaboxes/meta-general-settings.php';
			$mb_post_settings         = new VP_Metabox(
				array(
					'id'          => 'general_settings',
					'types'       => array(
						'post',
						'page',
						'team',
						'portfolio',
						'faq',
					),
					'title'       => __( 'General Settings', 'hbthemes' ),
					'priority'    => 'low',
					'is_dev_mode' => false,
					'template'    => $mb_path_general_settings,
				)
			);

			$mb_path_layout_settings = HBTHEMES_ADMIN . '/metaboxes/meta-layout-settings.php';
			$mb_post_settings        = new VP_Metabox(
				array(
					'id'          => 'layout_settings',
					'types'       => array(
						'post',
						'page',
					),
					'title'       => __( 'Layout Settings', 'hbthemes' ),
					'priority'    => 'low',
					'is_dev_mode' => false,
					'context'     => 'side',
					'template'    => $mb_path_layout_settings,
				)
			);

			$mb_path_background_settings = HBTHEMES_ADMIN . '/metaboxes/meta-background-settings.php';
			$mb_post_settings            = new VP_Metabox(
				array(
					'id'          => 'background_settings',
					'types'       => array(
						'post',
						'page',
						'team',
						'portfolio',
						'faq',
					),
					'title'       => __( 'Background Settings', 'hbthemes' ),
					'priority'    => 'low',
					'is_dev_mode' => false,
					'template'    => $mb_path_background_settings,
				)
			);

			$mb_path_misc_settings = HBTHEMES_ADMIN . '/metaboxes/meta-misc-settings.php';
			$mb_post_settings      = new VP_Metabox(
				array(
					'id'          => 'misc_settings',
					'types'       => array(
						'post',
						'page',
						'team',
						'portfolio',
						'faq',
					),
					'title'       => __( 'Misc Settings', 'hbthemes' ),
					'priority'    => 'low',
					'is_dev_mode' => false,
					'template'    => $mb_path_misc_settings,
				)
			);
		}
	}
endif;

new Highend_Metaboxes;
