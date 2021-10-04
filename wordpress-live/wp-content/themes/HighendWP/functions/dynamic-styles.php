<?php
/**
 * Dynamically generate CSS code. 
 * The code depends on options set in the Highend Options and Post/Page metaboxes.
 * 
 * If possible, write the dynamically generated code into a .css file, otherwise return the code. The file is refreshed on each modification of metaboxes & theme options.
 * 
 * @since 3.5.0
 * @return void|string, generated file containing the code or the generated css code
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Highend_Dynamic_Styles' ) ) :
	/**
	 * Dynamically generate CSS code.
	 */
	class Highend_Dynamic_Styles {

		var $dynamic_css_uri;
		var $dynamic_css_path;

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			$upload_dir = wp_upload_dir();

			if ( is_ssl() ) {
				$upload_dir['baseurl'] = str_replace( 'http://', 'https://', $upload_dir['baseurl'] );
				$upload_dir['url'] = str_replace( 'http://', 'https://', $upload_dir['url'] );
			}
		
			$this->dynamic_css_uri  = trailingslashit( $upload_dir['baseurl'] )  . 'highend/';
			$this->dynamic_css_path = trailingslashit( $upload_dir['basedir'] )  . 'highend/';

			if ( ! is_customize_preview() && file_exists( $this->dynamic_css_path . 'dynamic-styles.css' ) ) {
				add_action( 'highend_enqueue_scripts', array( $this, 'enqueue_dynamic_style' ), 99 );
			} else {
				add_action( 'highend_enqueue_scripts', array( $this, 'print_dynamic_style' ), 99 );
			}

			// Generate new styles on Customizer Save action.
			add_action( 'customize_save_after', array( $this, 'update_dynamic_file' ) );

			// Generate new styles on theme activation.
			add_action( 'after_switch_theme', array( $this, 'update_dynamic_file' ) );

			// Generate new styles on demo import.
			add_action( 'highend_demo_import_complete', array( $this, 'update_dynamic_file' ) );

			// Generate new styles on theme update.
			add_action( 'highend_theme_updated', array( $this, 'update_dynamic_file' ) );

			// Generate new styles on theme options change.
			add_action( 'vp_option_save_and_reinit-hb_highend_option', array( $this, 'update_dynamic_file' ) );

			// Print custom styles for individual pages to head.
			add_action( 'wp_head', array( $this, 'print_page_styles' ) );

			// Delete the css stye on theme deactivation.
			// add_action( 'switch_theme', array( $this, 'delete_dynamic_file' ) );
		}

		/**
		 * Enqueues dynamic styles file.
		 *
		 * @since 1.0.0
		 */
		function enqueue_dynamic_style() {

			wp_enqueue_style(
				'highend_dynamic_styles',
				$this->dynamic_css_uri . 'dynamic-styles.css',
				false,
				filemtime( $this->dynamic_css_path . 'dynamic-styles.css' ),
				'all'
			);
		}

		/**
		 * Prints inline dynamic styles if writing to file is not possible.
		 *
		 * @since 1.0.0
		 */
		function print_dynamic_style() {

			$dynamic_css = $this->generate_dynamic_css();
			wp_add_inline_style( 'highend-style', $dynamic_css );
		}

		/**
		 * Generates dynamic CSS code, minifies it and cleans cache.
		 *
		 * @param  boolean $custom_css - should we include the wp_get_custom_css.
		 * @return string, minifed code
		 * @since  1.0.0
		 */
		public function generate_dynamic_css( $custom_css = false ) {

			$css = '';

			/* Font Faces */
			$font_faces = highend_default_font_faces();

			if ( ! empty( $font_faces ) ) {
				foreach ( $font_faces as $font_family => $options ) {
					$css .= '@font-face {';

					foreach ( $options as $key => $value ) {

						if ( 'src_fallback' === $key ) {
							$css .= 'src: ' . $value . ';';
						} else {
							$css .= $key . ': ' . $value . ';';
						}
					}
					
					$css .= '}';
				}
			}

			/* BEGIN this is from scheme.php file from old Highend version. */

			$focus_hex_color 	= "#00aeef";
			$color_value 		= highend_option( 'hb_scheme_chooser' );

			if ( isset( $_GET['focus_color'] ) ){
				$color_value = $_GET['focus_color'];
			}

			if ( highend_option( 'hb_color_manager_type' ) == 'hb_color_manager_schemes' ){
				if ( $color_value == 'minimal_red' ){
					$focus_hex_color = "#c0392b";
				} else if ( $color_value == 'minimal_green' ){
					$focus_hex_color = "#27ae60";
				} else if ( $color_value == 'minimal_pink' ){
					$focus_hex_color = "#F07FB2";
				} else if ( $color_value == 'minimal_yellow' ){
					$focus_hex_color = "#f1c40f";
				} else if ( $color_value == 'minimal_orange' ){
					$focus_hex_color = "#e67e22";
				} else if ( $color_value == 'minimal_purple' ){
					$focus_hex_color = "#8e44ad";
				} else if ( $color_value == 'minimal_grey' ){
					$focus_hex_color = "#7f8c8d";
				} else if ( $color_value == 'minimal_blue_alt' || $color_value == 'business_blue' ){
					$focus_hex_color = "#2980b9";
				} else if ( $color_value == 'dark_elegance' ){
					$focus_hex_color = "#1BCBD1";
				} else if ( $color_value == 'minimal_green_alt' ){
					$focus_hex_color = "#a0ce4e";
				} else if ( $color_value == 'orchyd' ){
					$focus_hex_color = "#E8BF56";
				} else {
					$focus_hex_color = "#00aeef";
				}

			} else if ( highend_option('hb_color_manager_type') == 'hb_color_manager_color_customizer' ) {
				if ( get_theme_mod( 'hb_focus_color_setting') ) {
					$focus_hex_color = get_theme_mod( 'hb_focus_color_setting' ); 
				} else {
					$focus_hex_color = "00aeef";
				}
			}

			$css .= '

			:root {
				--colorPrimary: ' . $focus_hex_color . ';
			}

			a:hover { color:'.$focus_hex_color.'; }

			.light-text a:hover, 
			.light-menu-dropdown #main-nav ul.sub-menu li a:hover,
			.light-menu-dropdown #main-nav ul.sub-menu li.sfHover > a,
			.light-menu-dropdown #main-nav ul.sub-menu li.current-menu-item > a,
			.light-menu-dropdown #main-nav ul.sub-menu li.current-menu-ancestor > a,
			#fancy-search .ui-autocomplete li a:hover,
			#fancy-search .ui-autocomplete li:hover span.search-title,
			#fancy-search .ui-autocomplete li a,
			.author-box .social-list li a:hover,
			#respond small a:hover,
			.commentmetadata a:hover time,
			.comments-list .reply a,
			#footer.dark-style a:hover,
			.feature-box.alternative i.ic-holder-1,
			.portfolio-simple-wrap .standard-gallery-item:hover .portfolio-description h3 a,
			.hb-effect-1 #main-nav > li > a::before, 
			.hb-effect-1 a::after,
			.third-skin.hb-effect-1 #main-nav > li > a:hover, 
			.third-skin.hb-effect-1 #main-nav > li.current-menu-item > a, 
			.third-skin.hb-effect-1 #main-nav > li.sfHover > a,
			.second-skin.hb-effect-9 #main-nav #nav-search > a:hover,
			.hb-effect-10 #main-nav > li > a:hover, 
			.hb-effect-10 #main-nav > li #nav-search a:hover, 
			.hb-effect-10 #main-nav > li.current-menu-item > a,
			#main-nav ul.sub-menu li a:hover,
			.hb-side-nav li.menu-item-has-children:hover > a,
			.hb-side-nav li a:hover,
			.hb-side-nav li.current-menu-item > a,
			.hb-side-nav li.current-menu-ancestor > a,
			.hb-post-carousel .hb-post-info .hb-post-title:hover,
			.hb-post-carousel .hb-owl-item .hb-owl-read-more:hover span,
			.hb-post-carousel .hb-owl-item .hb-owl-read-more:hover { color: '.$focus_hex_color.'!important; }

			.light-style .feature-box.alternative i.ic-holder-1,
			.light-style .feature-box h4.bold {
				color: #f9f9f9 !important;
			}

			.light-style .feature-box-content p {
				color: #ccc;
			}

			.like-holder.like-active i, .like-holder.like-active { color: #da4c26 !important; }

			.main-navigation.default-skin #main-nav > li > a:hover > span, 
			.main-navigation.default-skin #main-nav > li.current-menu-item > a > span, 
			.main-navigation.default-skin #main-nav > li.sfHover > a > span,
			.team-member-box.tmb-2:hover .team-member-description,
			span[rel="tooltip"] { border-bottom-color: '.$focus_hex_color.'; }

			.hb-pricing-item:hover,
			.hb-process-steps ul:before,
			.wpb_tabs .nav-tabs li.active a,
			#hb-preloader .spinner, .default-loading-icon:before {
				border-top-color: '.$focus_hex_color.';
			}

			#main-wrapper .widget_nav_menu ul.menu li.current-menu-item > a,
			.hb-non-transparent .hb-side-nav > li > a:hover,
			.hb-non-transparent .hb-side-nav > li.current-menu-item > a, 
			.hb-non-transparent .hb-side-nav > li.current-menu-ancestor > a, 
			.hb-non-transparent .hb-side-nav > li.sfHover > a,
			.hb-tabs-wrapper.tour-style.left-tabs > .nav-tabs > li.active a,
			.logout-dropdown ul li:hover,
			.tribe-events-calendar thead th,
			.light-menu-dropdown #main-nav ul.sub-menu li a:hover,
			.light-menu-dropdown #main-nav ul.sub-menu li.sfHover > a,
			.light-menu-dropdown #main-nav ul.sub-menu li.current-menu-item > a,
			.light-menu-dropdown #main-nav ul.sub-menu li.current-menu-ancestor > a,
			.light-menu-dropdown #main-nav ul.sub-menu li.sfHover > a {
				border-left-color: '.$focus_hex_color.';
			}

			#main-wrapper .right-sidebar .widget_nav_menu ul.menu li.current-menu-item > a,
			.hb-tabs-wrapper.tour-style.right-tabs > .nav-tabs > li.active a,
			.tribe-events-calendar thead th {
				border-right-color: '.$focus_hex_color.';
			}

			.pagination ul li span, 
			.single .pagination span,
			.single-post-tags a:hover,
			div.overlay,
			.portfolio-simple-wrap .standard-gallery-item:hover .hb-gallery-item-name:before,
			.woocommerce .wc-new-badge,
			#main-wrapper .coupon-code input.button:hover,
			.woocommerce-page #main-wrapper button.button:hover,
			#main-wrapper input.checkout-button,
			.side-nav-bottom-part ul li a:hover,
			#main-wrapper #place_order,
			#mobile-menu.interactive .open-submenu.active,
			#mobile-menu.interactive .open-submenu:hover,
			.widget_product_search input[type=submit],
			.tribe-events-calendar thead th { background-color:'.$focus_hex_color.'; }

			#header-dropdown .close-map:hover,
			#sticky-shop-button:hover,
			#sticky-shop-button span,
			.type-post.format-quote .quote-post-wrapper a,
			.type-post.format-link .quote-post-wrapper a,
			.type-post.format-status .quote-post-wrapper a,
			span.highlight, mark,
			.feature-box:hover:not(.standard-icon-box) .hb-small-break,
			.content-box i.box-icon,
			.hb-button, input[type=submit], a.read-more, .woocommerce-MyAccount-content input.button,
			.hb-effect-2 #main-nav > li > a > span::after,
			.hb-effect-3 #main-nav > li > a::before,
			.hb-effect-4 #main-nav > li > a::before,
			.hb-effect-6 #main-nav > li > a::before,
			.hb-effect-7 #main-nav > li > a span::after,
			.hb-effect-8 #main-nav > li > a:hover span::before,
			.hb-effect-9 #main-nav > li > a > span::before,
			.hb-effect-9 #main-nav > li > a > span::after,
			.hb-effect-10 #main-nav > li > a:hover span::before, 
			.hb-effect-10 #main-nav > li.current-menu-item > a span::before, 
			#main-nav > li.sfHover > a span::before, 
			#main-nav > li.current-menu-ancestor > a span::before,
			.pace .pace-progress,
			#main-wrapper .hb-bag-buttons a.checkout-button,
			.hb-post-carousel.hb-owl-slider .owl-nav .owl-prev:hover,
			.hb-post-carousel.hb-owl-slider .owl-nav .owl-next:hover,
			#tribe-bar-form .tribe-bar-submit input[type=submit] { background: '.$focus_hex_color.'; }

			#main-wrapper .single_add_to_cart_button:hover {
				background: '.$focus_hex_color.' !important;
			}

			table.focus-header th,
			.second-skin #main-nav > li a:hover,
			.second-skin #main-nav > li.current-menu-item > a,
			.second-skin #main-nav > li.sfHover > a,
			.highend-sticky-header #header-inner .second-skin #main-nav > li > a:hover,
			.second-skin #main-nav > li.current-menu-item > a,
			.crsl-nav a:hover,
			.feature-box:hover i.ic-holder-1 {
				background: '.$focus_hex_color.';
				color: #FFF;
			}


			.load-more-posts:hover,
			.dropcap.fancy,
			.tagcloud > a:hover,
			.hb-icon.hb-icon-medium.hb-icon-container:hover,
			#main-wrapper #tribe-events .tribe-events-button {
				background-color: '.$focus_hex_color.';
				color: #FFF;
			}

			.filter-tabs li.selected a {
				border-color: '.$focus_hex_color.' !important;
			}

			.hb-second-light:hover {background:#FFF!important;color:'.$focus_hex_color.'!important;}

			.hb-effect-11 #main-nav > li > a:hover::before,
			.hb-effect-11 #main-nav > li.sfHover > a::before,
			.hb-effect-11 #main-nav > li.current-menu-item > a::before,
			.hb-effect-11 #main-nav > li.current-menu-ancestor > a::before  {
				color: '.$focus_hex_color.';
				text-shadow: 7px 0 '.$focus_hex_color.', -7px 0 '.$focus_hex_color.';
			}

			#main-wrapper .product-loading-icon {
				background: '. highend_hex2rgba($focus_hex_color, 0.85).';
			}

			.hb-single-next-prev a:hover {
				background: '. highend_hex2rgba($focus_hex_color, 0.95).';	
			}

			.hb-more-details:hover, .hb-buy-button:hover {
				color:#FFF;
				background: '. highend_hex2rgba($focus_hex_color, 0.8).';
			}

			.hb-button, input[type=submit], .woocommerce-MyAccount-content input.button{
				box-shadow: 0 3px 0 0 '. highend_darken_color($focus_hex_color, -50) .';
			}

			.hb-button.special-icon i,
			.hb-button.special-icon i::after {
				background:' . highend_darken_color($focus_hex_color, -50) . ';
			}

			#main-wrapper a.active-language, #main-wrapper a.active-language:hover {color: #aaa !important; }
			.feature-box:hover:not(.standard-icon-box):not(.alternative) i, #main-wrapper .hb-bag-buttons a:hover, #main-wrapper .hb-dropdown-box .hb-bag-buttons a:hover,
			#main-wrapper .social-icons.dark li a:hover i, #main-wrapper #footer .social-icons.dark li a i, 
			#footer.dark-style ul.social-icons.light li a:hover,
			#main-wrapper .hb-single-next-prev a:hover {color: #FFF !important;}';

			if ( highend_option('hb_color_manager_type') == 'hb_color_manager_color_customizer' ){
				if (get_theme_mod('hb_top_bar_bg_setting')){
					$css .= '#header-bar { background-color:' . get_theme_mod('hb_top_bar_bg_setting') . '}';
				}

				if (get_theme_mod('hb_top_bar_text_color_setting')){
					$css .= '#header-bar, #fancy-search input[type=text] { color:' . get_theme_mod('hb_top_bar_text_color_setting') . '}';
					$css .= '#fancy-search ::-webkit-input-placeholder { color:' . get_theme_mod('hb_top_bar_text_color_setting') . '}';
				}

				if (get_theme_mod('hb_top_bar_link_color_setting')){
					$css .= '#header-bar a { color:' . get_theme_mod('hb_top_bar_link_color_setting') . '}';
				}

				if (get_theme_mod('hb_top_bar_border_setting')){
					$css .= '#header-bar { border-bottom-color:' . get_theme_mod('hb_top_bar_border_setting') . '}';
					$css .= '#header-bar .top-widget { border-right-color: '. get_theme_mod('hb_top_bar_border_setting') .'!important; border-left-color: '.get_theme_mod('hb_top_bar_border_setting').' !important; }';
				}

				if (get_theme_mod('hb_nav_bar_bg_setting')){
					$css .= '#header-inner-bg {background-color: '.get_theme_mod('hb_nav_bar_bg_setting').';}';
				}

				if (get_theme_mod('hb_nav_bar_stuck_bg_setting')){
					$css .= '.highend-sticky-header #header-inner #header-inner-bg { background-color: '.get_theme_mod('hb_nav_bar_stuck_bg_setting').'; }';
				}

				if (get_theme_mod('hb_side_nav_bg_setting')){
					$css .= '#hb-side-navigation, #hb-side-navigation .hb-resp-bg { background-color: '.get_theme_mod('hb_side_nav_bg_setting').'; }';
				}

				if (get_theme_mod('hb_nav_bar_border_setting')){
					$css .= '#header-inner.nav-type-2 .main-navigation-container { border-top-color: '.get_theme_mod('hb_nav_bar_border_setting').'; }';
					$css .= '#header-inner-bg {border-bottom-color: '.get_theme_mod('hb_nav_bar_border_setting').'}';
					$css .= '#main-nav li#nav-search::before {background:'.get_theme_mod('hb_nav_bar_border_setting').'}';
					$css .= '#header-inner.nav-type-2 #main-nav > li:first-child > a, #header-inner.nav-type-2 li#nav-search > a { border-left-color: '.get_theme_mod('hb_nav_bar_border_setting').'; }';
					$css .= '#header-inner.nav-type-2 #main-nav > li > a { border-right-color: '.get_theme_mod('hb_nav_bar_border_setting').'; }';
				}

				if (get_theme_mod('hb_nav_bar_stuck_border_setting')){
					$css .= '.highend-sticky-header #header-inner #header-inner-bg { border-bottom-color:'.get_theme_mod('hb_nav_bar_stuck_border_setting').' !important; }';
					$css .= '.highend-sticky-header #header-inner #main-nav li#nav-search::before {background: '.get_theme_mod('hb_nav_bar_stuck_border_setting').'}';
				}

				if (get_theme_mod('hb_nav_bar_text_setting')){
					$css .= '#main-wrapper #main-nav > li > a, #main-wrapper #header-inner-bg { color:'.get_theme_mod('hb_nav_bar_text_setting').'; }';
					$css .= 'body:not(.page-template-presentation-fullwidth) #main-wrapper #main-nav > li > a, #main-wrapper #header-inner-bg { color:'.get_theme_mod('hb_nav_bar_text_setting').' !important; }';

				}

				if (get_theme_mod('hb_nav_bar_stuck_text_setting')){
					$css .= '.highend-sticky-header #main-wrapper #header-inner #main-nav > li > a, .highend-sticky-header #main-wrapper #header-inner #header-inner-bg { color:'.get_theme_mod('hb_nav_bar_stuck_text_setting').' !important; }';
				}

				if (get_theme_mod('hb_pfooter_bg_setting')){
					$css .= '#pre-footer-area {background-color: '.get_theme_mod('hb_pfooter_bg_setting').';}';
				}

				if (get_theme_mod('hb_pfooter_text_setting')){
					$css .= '#pre-footer-area {color: '.get_theme_mod('hb_pfooter_text_setting').';}';
				}

				if (get_theme_mod('hb_footer_bg_setting')){
					$css .= '#footer, body #lang_sel_footer { background-color: '.get_theme_mod('hb_footer_bg_setting').'; }';
				}

				if (get_theme_mod('hb_footer_text_setting')){
					$css .= '#footer { color: '.get_theme_mod('hb_footer_text_setting').'; }';
				}

				if (get_theme_mod('hb_footer_text_setting')){
					$css .= '#main-wrapper #footer {color: '.get_theme_mod('hb_footer_text_setting').';}';
				}

				if (get_theme_mod('hb_footer_link_setting')){
					$css .= '#main-wrapper #footer a, body #lang_sel_footer a { color: '.get_theme_mod('hb_footer_link_setting').'; }';
				}

				if (get_theme_mod('hb_copyright_bg_setting')){
					$css .= '#copyright-wrapper {background: '.get_theme_mod('hb_copyright_bg_setting').';}';
				}

				if (get_theme_mod('hb_copyright_text_setting')){
					$css .= '#copyright-wrapper, #copyright-text {color: '.get_theme_mod('hb_copyright_text_setting').';}';
				}

				if (get_theme_mod('hb_copyright_link_setting')){
					$css .= '#copyright-wrapper a {color: '.get_theme_mod('hb_copyright_link_setting').';}';
				}

				if (get_theme_mod('hb_content_bg_setting')){
					$css .= 'body {background-color: '.get_theme_mod('hb_content_bg_setting').';}';
				}

				if (get_theme_mod('hb_side_section_bg_setting')){
					$css .= '#hb-side-section {background-color: '.get_theme_mod('hb_side_section_bg_setting').';}';
				}

				if (get_theme_mod('hb_content_c_bg_setting')){
					$css .= '#main-wrapper, #main-wrapper.hb-stretched-layout, #main-wrapper.hb-boxed-layout {background-color: '.get_theme_mod('hb_content_c_bg_setting').';}';
					$css .= '#main-wrapper #pre-footer-area:after {border-top-color: '.get_theme_mod('hb_content_c_bg_setting').';}';
				}

				if (get_theme_mod('hb_content_text_color_setting')){
					$css .= '#main-wrapper .hb-main-content, #main-wrapper .hb-sidebar, .hb-testimonial-quote p {color: '.get_theme_mod('hb_content_text_color_setting').';}';
				}

				if (get_theme_mod('hb_content_link_color_setting')){
					$css .= '.hb-main-content a, select { color: '.get_theme_mod('hb_content_link_color_setting').' }';
					$css .= '.hb-main-content a:hover {color: '.$focus_hex_color.';}';
				}

				if (get_theme_mod('hb_content_border_setting')){
					$css .= '
						.portfolio-single-meta ul, .content-box, #main-wrapper .hb-accordion-pane, .hb-accordion-tab, .hb-box-cont, .hb-tabs-wrapper.tour-style .tab-content, .hb-tabs-wrapper .nav-tabs li a, .hb-callout-box, .hb-teaser-column .teaser-content, .hb-pricing-item, .hb-testimonial:after, .hb-testimonial, .tmb-2 .team-member-description, .recent-comments-content, .recent-comments-content:after, .hb-tweet-list.light li, .hb-tweet-list.light li:after, fieldset,table,.wp-caption-text, .gallery-caption, .author-box, .comments-list .children > li::before, .widget_nav_menu ul.menu, .comments-list li.comment > div.comment-body, .hb-dropdown-box, #contact-panel, .filter-tabs li a, #contact-panel::after, .hb-flexslider-wrapper.bordered-wrapper,.bordered-wrapper, iframe.fw {border-color:'.get_theme_mod('hb_content_border_setting').';}

					#main-content .left-sidebar .hb-main-content.col-9, table th, table th, table td, #main-content .hb-sidebar, .comments-list .children, .tmb-2 .team-member-img, .hb-tabs-wrapper .tab-content, div.pp_default .pp_close {border-left-color:'.get_theme_mod('hb_content_border_setting').';}

					table td, .hb-blog-small .meta-info, #main-wrapper .widget_nav_menu ul.menu ul li:first-child, #main-wrapper .bottom-meta-section, .comments-list .children > li::after, .tmb-2 .team-member-img, h5.hb-heading span:not(.special-amp):before, h4.hb-heading span:not(.special-amp):before,h4.hb-heading span:not(.special-amp):after,h5.hb-heading span:not(.special-amp):after,h3.hb-heading span:not(.special-amp):before,h3.hb-heading span:not(.special-amp):after,h4.lined-heading span:not(.special-amp):before,h4.lined-heading span:not(.special-amp):after, .hb-fw-separator, .hb-separator-s-1, .hb-separator-extra, .hb-separator-25, .hb-gal-standard-description .hb-small-separator {border-top-color:'.get_theme_mod('hb_content_border_setting').';}

					.woocommerce div.product .woocommerce-tabs ul.tabs:before {border-bottom-color:'.get_theme_mod('hb_content_border_setting').';}

					.pricing-table-caption, .pricing-table-price, #hb-page-title, .share-holder .hb-dropdown-box ul li, .hb-blog-small .meta-info, .hb_latest_posts_widget article, .most-liked-list li, ul.hb-ul-list.line-list li, ol.line-list li, ul.line-list li, #hb-blog-posts.unboxed-blog-layout article, .hb-tabs-wrapper .nav-tabs, #main-wrapper .wpb_content_element .wpb_tour_tabs_wrapper .wpb_tabs_nav a, .hb-tabs-wrapper .tab-content, .hb-tabs-wrapper.tour-style .nav-tabs li.active a, .hb-box-cont-header, .hb-separator.double-border, .portfolio-single-meta ul.meta-list li {border-bottom-color:'.get_theme_mod('hb_content_border_setting').';}

					#main-content .col-9.hb-main-content, #main-content .left-sidebar .hb-sidebar.col-3, .tmb-2 .team-member-img, .hb-tabs-wrapper .tab-content, .hb-tabs-wrapper.tour-style.right-tabs .nav-tabs > li.active a, div.pp_default .pp_nav {border-right-color:'.get_theme_mod('hb_content_border_setting').';}

					.pagination ul li a, .pagination ul li span.page-numbers.dots, .single .pagination a, .page-links a, .hb-skill-meter .hb-progress-bar, .hb-counter .count-separator span, .hb-small-break, hr {background-color: '.get_theme_mod('hb_content_border_setting').';}

					#main-wrapper .hb-tabs-wrapper:not(.wpb_tabs) ul li:last-child a, .darker-border .hb-separator {border-bottom-color: '.get_theme_mod("hb_content_border_setting").' !important;}
					.darker-border .hb-separator {border-top-color: '.get_theme_mod('hb_content_border_setting').' !important;}';
				}

				if (get_theme_mod('hb_content_h1_setting')){
					$css .= 'h1, h1.extra-large, h1 a, .single article h1.title { color: '.get_theme_mod('hb_content_h1_setting').'; }';
					$css .= '#hb-page-title.dark-text h1, #hb-page-title.light-text h1, p.hb-text-large { color: '.get_theme_mod('hb_content_h1_setting').'!important; }';
					$css .= '#main-wrapper h1.hb-bordered-heading {color: '.get_theme_mod('hb_content_h1_setting').'; border-color: '.get_theme_mod('hb_content_h1_setting').';}';
				}

				if (get_theme_mod('hb_content_h2_setting')){
					$css .= 'h2, #hb-page-title h2 { color: '.get_theme_mod('hb_content_h2_setting').'; }';
					$css .= '#main-wrapper h2.hb-bordered-heading {color: '.get_theme_mod('hb_content_h2_setting').'; border-color: '.get_theme_mod('hb_content_h2_setting').';}';
				}

				if (get_theme_mod('hb_content_h3_setting')){
					$css .= 'h3, #respond h3, h3.title-class, .hb-callout-box h3, .hb-gal-standard-description h3 { color: '.get_theme_mod('hb_content_h3_setting').'; }';
					$css .= '#main-wrapper h3.hb-bordered-heading {color: '.get_theme_mod('hb_content_h3_setting').'; border-color: '.get_theme_mod('hb_content_h3_setting').';}';
				}

				if (get_theme_mod('hb_content_h4_setting')){
					$css .= 'h4, .widget-item h4, .content-box h4, .feature-box h4.bold { color: '.get_theme_mod('hb_content_h4_setting').'; }';
					$css .= '#main-wrapper h4.hb-bordered-heading {color: '.get_theme_mod('hb_content_h4_setting').'; border-color: '.get_theme_mod('hb_content_h4_setting').';}';
				}

				if (get_theme_mod('hb_content_h5_setting')){
					$css .= 'h5, #comments h5, #respond h5, .testimonial-author h5 { color: '.get_theme_mod('hb_content_h5_setting').'; }';
				}

				if (get_theme_mod('hb_content_h6_setting')){
					$css .= 'h6, .single-post-tags span, h6.special, .blog-shortcode-1 h6 { color: '.get_theme_mod('hb_content_h6_setting').'; }';
				}

				if (get_theme_mod('hb_spec_head_color_setting')){
					$css .= '.hb-special-header-style #main-wrapper #header-inner #main-nav > li > a, 
						  .hb-special-header-style #main-wrapper #header-inner #fancy-search input[type=text],
						  .hb-special-header-style #close-fancy-search,
						  .hb-special-header-style #close-fancy-search:hover,
						  .hb-special-header-style #show-nav-menu { color: '.get_theme_mod('hb_spec_head_color_setting').' !important; }';

					$css .= '.hb-special-header-style #main-wrapper #header-inner #fancy-search ::-webkit-input-placeholder { color: '.get_theme_mod('hb_spec_head_color_setting').' !important; };
						  .hb-special-header-style #main-wrapper #header-inner #fancy-search ::-moz-input-placeholder { color: '.get_theme_mod('hb_spec_head_color_setting').' !important; };
						  .hb-special-header-style #main-wrapper #header-inner #fancy-search ::-ms-input-placeholder { color: '.get_theme_mod('hb_spec_head_color_setting').' !important; }';
				}

				if (get_theme_mod('hb_spec_head_bg_stuck_setting')){
					$css .= '.hb-special-header-style.highend-sticky-header #main-wrapper #header-inner #header-inner-bg { background: '.highend_hex2rgba( get_theme_mod('hb_spec_head_bg_stuck_setting'), 0.7).' !important; border-bottom: 0; }';
				}

				if (get_theme_mod('hb_spec_head_color_sticky_setting')){
					$css .= '.hb-special-header-style.highend-sticky-header #main-wrapper #header-inner #main-nav > li > a,
						  .hb-special-header-style.highend-sticky-header #main-wrapper #header-inner #close-fancy-search,
						  .hb-special-header-style.highend-sticky-header #main-wrapper #header-inner #fancy-search input[type=text] { color: '.get_theme_mod('hb_spec_head_color_sticky_setting').' !important; }';

					$css .= '.hb-special-header-style.highend-sticky-header #main-wrapper #header-inner #fancy-search ::-webkit-input-placeholder { color: '.get_theme_mod('hb_spec_head_color_sticky_setting').' !important; };
						  .hb-special-header-style.highend-sticky-header #main-wrapper #header-inner #fancy-search ::-moz-input-placeholder { color: '.get_theme_mod('hb_spec_head_color_sticky_setting').' !important; };
						  .hb-special-header-style.highend-sticky-header #main-wrapper #header-inner #fancy-search ::-ms-input-placeholder { color: '.get_theme_mod('hb_spec_head_color_sticky_setting').' !important; }';
				}
			}

			if ( highend_option('hb_color_manager_type') == 'hb_color_manager_schemes' ){
				if ( $color_value == 'business_blue' ){
					$css .= '
						#header-bar { background: #34495e; color: #FFF; color: rgba(255,255,255,0.7); border-bottom: 0 !important;  }
						#header-bar a { color: #FFF; color: rgba(255,255,255,0.7); }
						#header-bar a:hover, #header-bar.style-1 .top-widget .active { color: #FFF !important; }
						#header-bar.style-1 .top-widget .active { color: #FFF !important; }
						#main-wrapper #header-bar .top-widget {border-left-color: rgba(255,255,255,0.15) !important; border-right-color: rgba(255,255,255,0.15) !important; }
						#footer {background: #2c3e50;}
						#copyright-wrapper {background: #34495e;}
						#main-wrapper.hb-boxed-layout,
						#main-wrapper.hb-stretched-layout {background: #FFF;}
						#main-wrapper.hb-boxed-layout #pre-footer-area:after {border-top-color: #FFF;}

						#footer {color: rgba(255,255,255,0.6) !important;}
						#main-wrapper #footer a:hover, #main-wrapper #copyright-wrapper a:hover {color: #FFF !important;}

						#footer .widget_pages ul > li,
						#footer .widget_categories ul > li,
						#footer .widget_archive ul > li, 
						#footer .widget_nav_menu ul > li, 
						#footer .widget_recent_comments ul > li, 
						#footer .widget_meta ul > li, 
						#footer .widget_recent_entries ul > li, 
						#footer .widget_product_categories ul > li, 
						#footer .widget_layered_nav ul li {
							border-top-color: rgba(255,255,255,0.1);
						}

						#footer.dark-style .widget-item h4 {color: #FFF !important;}

					';
				} else if ( $color_value == 'dark_elegance' ){
					$css .= '
						#header-bar { background: #292929; color: #FFF; color: rgba(255,255,255,0.7); border-bottom: solid 1px #333; border-bottom-color: rgba(255,255,255,0.15)  }
						#header-bar a { color: #FFF; color: rgba(255,255,255,0.7); }
						#main-wrapper #header-bar .top-widget {border-left:none !important; border-right: none !important; }

						#header-inner-bg {background: #222; color:#FFF;}
						#main-nav>li>a {color: #FFF !important;}
						.highend-sticky-header #header-inner #header-inner-bg {background: #222; border-bottom: solid 1px #333;}
						#fancy-search input[type=text] {color: #FFF;}
						#fancy-search ::-webkit-input-placeholder{color:rgba(255,255,255,0.5);}
						#hb-header h1, #hb-header h2, #hb-header h3, #hb-header h4, #hb-header h5, #hb-header h6 {color: #FFF;}
						#header-inner.nav-type-2 #main-nav > li > a { border-left-color: rgba(255,255,255,0.1) !important; border-right-color: rgba(255,255,255,0.1) !important; }
						#header-inner.nav-type-2 .main-navigation-container { border-top-color: rgba(255,255,255,0.1) !important; }

						.third-skin #main-nav > li > a:hover, .highend-sticky-header #header-inner .second-skin #main-nav > li > a:hover, .third-skin #main-nav > li.current-menu-item > a, .third-skin #main-nav > li.sfHover > a, .third-skin #main-nav > li.current-menu-ancestor > a {color: '.$focus_hex_color.' !important;}
						#main-wrapper #header-inner-bg {border-bottom-color:#333 !important}
					';
				}
				else if ( $color_value == 'orchyd' ){
					$css .= '
						#header-bar { background: #6B3078; color: #FFF; color: rgba(255,255,255,0.7); border-bottom: solid 1px #333; border-bottom-color: rgba(255,255,255,0.15)  }
						#header-bar a { color: #FFF; color: rgba(255,255,255,0.7); }
						#main-wrapper #header-bar .top-widget {border-left-color:rgba(255,255,255,0.15) !important; border-right-color: rgba(255,255,255,0.15) !important; }

						#header-inner-bg {background: #6B3078; color:#FFF;}
						#main-nav>li>a {color: #FFF !important;}
						.highend-sticky-header #header-inner #header-inner-bg {background: #6B3078; border-bottom: solid 1px #6B3078;}
						#fancy-search input[type=text] {color: #FFF;}
						#fancy-search ::-webkit-input-placeholder{color:rgba(255,255,255,0.5);}
						#hb-header h1, #hb-header h2, #hb-header h3, #hb-header h4, #hb-header h5, #hb-header h6 {color: #FFF;}
						#header-inner.nav-type-2 #main-nav > li > a { border-left-color: rgba(255,255,255,0.1) !important; border-right-color: rgba(255,255,255,0.1) !important; }
						#header-inner.nav-type-2 .main-navigation-container { border-top-color: rgba(255,255,255,0.1) !important; }

						.third-skin #main-nav > li > a:hover, .highend-sticky-header #header-inner .second-skin #main-nav > li > a:hover, .third-skin #main-nav > li.current-menu-item > a, .third-skin #main-nav > li.sfHover > a, .third-skin #main-nav > li.current-menu-ancestor > a {color: '.$focus_hex_color.' !important;}
						#main-wrapper #header-inner-bg {border-bottom-color:#333 !important}
						body #ascrail2000 div {background-color: #A84A78 !important;}
						#main-wrapper { background-color: #f0f0f0 !important; }
						#pre-footer-area:after { border-top-color: #f0f0f0; }
						#hb-page-title.hb-color-background {background-color: #202020 !important;}
					';
				}
			}
				
			// Footer background image.
			if ( highend_option( 'hb_footer_bg_image' ) ) {
				$css .= '
					#footer {
						background-image: url(' . esc_url( highend_option( "hb_footer_bg_image" ) ) .');
					}
				';
			} elseif ( highend_option( 'hb_enable_footer_background' ) ) {
				$css .= '
					#footer {
						background-image:url(' . HBTHEMES_URI . '/assets/images/map.png);
						background-position:center center;
						background-repeat:no-repeat;
					}
				';
			}

			// Logo max height.
			$logo_max_height = highend_option( 'hb_logo_max_height' );

			if ( ! empty ( $logo_max_height ) ) {
				$css .= '
					#logo .hb-logo-wrap {
						height:' . $logo_max_height . ' !important;
						max-height: 100% !important;
					}
				';

				// $css .= '
				// 	#logo .hb-logo-wrap {
				// 		max-height:' . $logo_max_height . ' !important;
				// 	}
				// ';
			}

			// Header Height.
			$header_height = highend_option( 'hb_regular_header_height' );

			if ( ! empty( $header_height ) ) {
				// $css .= '
				// 	.nav-type-1 #header-inner-bg > .container,
				// 	.nav-type-1 #header-inner-bg > .container-wide,
				// 	.nav-type-1 .highend-sticky-placeholder {
				// 		height: ' . $header_height . 'px !important;
				// 		line-height: ' . $header_height . 'px !important;
				// 	}

				// 	.hb-special-header-style #hb-header {
				// 		margin-bottom: -' . $header_height . 'px;
				// 	}
				// ';
				
				// $css .= '
				// 	.nav-type-1 #header-inner-bg,
				// 	.nav-type-1 .highend-sticky-placeholder {
				// 		height: ' . $header_height . 'px !important;
				// 		line-height: ' . $header_height . 'px !important;
				// 	}

				// 	.hb-special-header-style #hb-header {
				// 		margin-bottom: -' . $header_height . 'px;
				// 	}
				// ';

				$css .= '
					.nav-type-1 #header-inner-bg,
					.nav-type-1 .highend-sticky-placeholder {
						height: ' . $header_height . 'px !important;
						line-height: ' . $header_height . 'px !important;
					}
				';
			}

			// Sticky Header Height.
			$sticky_header_height = highend_option( 'hb_sticky_header_height' );

			// $css .= '
			// 	.highend-sticky-header #header-inner.nav-type-1 #header-inner-bg > .container,
			// 	.highend-sticky-header #header-inner.nav-type-1 #header-inner-bg > .container-wide,
			// 	.highend-sticky-header #header-inner.nav-type-1 .highend-sticky-placeholder {
			// 		height: ' . $sticky_header_height . 'px !important;
			// 		line-height: ' . $sticky_header_height . 'px !important;
			// 	}
			// ';
			
			$css .= '
				.highend-sticky-header #header-inner.nav-type-1 #header-inner-bg,
				.highend-sticky-header #header-inner.nav-type-1 .highend-sticky-placeholder {
					height: ' . $sticky_header_height . 'px !important;
					line-height: ' . $sticky_header_height . 'px !important;
				}
			';

			$css .= highend_option( 'hb_custom_css' );
			/* END scheme.php content */

			// Add user custom CSS
			$css .= wp_get_custom_css();

			// Allow to be filtered.
			$css = apply_filters( 'highend_dynamic_styles', $css );

			// Minify the CSS code
			$css = $this->minify_css( $css );

			return $css;
		}

		/**
		 * Update dynamic css file with new CSS. Cleans caches after that.
		 *
		 * @return [Boolean] returns true if successfully updated the dynamic file.
		 */
		public function update_dynamic_file() {

			$css = $this->generate_dynamic_css( true );

			if ( ! $css || '' === trim( $css ) ) {
				return;
			}

			// Load file.php file.
			require_once( ABSPATH . 'wp-admin/includes/file.php' );

			global $wp_filesystem;

			// Check if the the global filesystem isn't setup yet.
			if ( is_null( $wp_filesystem ) ) {
				WP_Filesystem();
			}

			$wp_filesystem->mkdir( $this->dynamic_css_path );

			if ( $wp_filesystem->put_contents( $this->dynamic_css_path . 'dynamic-styles.css', $css ) ) {
				$this->clean_cache();
				return true;
			}

			return false;
		}

		/**
		 * Print custom css for individual pages.
		 *
		 * @since 3.5.2
		 */
		public function print_page_styles() {
			?>
			<style type="text/css">
			<?php
				
				// Disable Content Area if selected in Metabox settings.
				if ( 'hide' === vp_metabox( 'layout_settings.hb_content_area' ) && ! is_search() && ! is_archive() ) {
					echo '
						#main-content { display:none; }
						#slider-section { top:0px; }
					';
				}

				// Body background.
				if ( 'hb-boxed-layout' === highend_get_site_layout() ) {
					$bg_url        = highend_option( 'hb_default_background_image' );
					$bg_repeat     = 'background-repeat: ' . highend_option( 'hb_background_repeat' ) . ';';
					$bg_attachment = 'background-attachment: ' . highend_option( 'hb_background_attachment' ) . ';';
					$bg_position   = 'background-position: ' . highend_option( 'hb_background_position' ) . ';';
					$bg_size       = 'background-size: auto auto;';
					$bg_image      = '';

					if ( highend_option( 'hb_background_stretch' ) ) {
						$bg_size = 'background-size: cover;';
					}

					if ( highend_option( 'hb_default_background_image' ) && 'upload-image' === highend_option( 'hb_upload_or_predefined_image' ) ) {
						$bg_url   = highend_option( 'hb_default_background_image' );
						$bg_image = 'background-image: url(' . $bg_url . ');' . $bg_repeat . $bg_attachment . $bg_position . $bg_size;
					} 

					if ( 'predefined-texture' === highend_option( 'hb_upload_or_predefined_image' ) ) {
						$bg_image = 'background-image: url(' . highend_option( 'hb_predefined_bg_texure' ) . '); background-repeat:repeat; background-position: center center; background-attachment:scroll; background-size:initial;';
					}

					if ( 'image' === vp_metabox( 'background_settings.hb_background_page_settings' ) && vp_metabox( 'background_settings.hb_page_background_image' ) ) {
						$bg_url   = vp_metabox( 'background_settings.hb_page_background_image' );
						$bg_image = 'background-image: url(' . $bg_url . ');' . $bg_repeat . $bg_attachment . $bg_position . $bg_size;
					}

					if ( 'color' === vp_metabox( 'background_settings.hb_background_page_settings' ) ) {
						$bg_image = 'background-color: ' . vp_metabox( 'background_settings.hb_page_background_color' ) . ';';
					}

					if ( ! empty( $bg_image ) ) {
						echo 'body { ' . $bg_image . ' }';
					}
				}

				// Typography.
				$font_weight  = '400';
				$font_style   = 'normal';
				$font_subsets = highend_option( 'hb_font_body_subsets' );

				// Body Font.
				if ( 'hb_font_custom' === highend_option( 'hb_font_body' ) ) {

					$font_face   = highend_option( 'hb_font_body_face' );
					$font_weight = highend_option( 'hb_font_body_weight' );

					VP_Site_GoogleWebFont::instance()->add( $font_face, $font_weight, $font_style, $font_subsets );
					
					echo 'body, .team-position, .hb-single-next-prev .text-inside, .hb-dropdown-box.cart-dropdown .buttons a, input[type=text], textarea, input[type=email], input[type=password], input[type=tel], #fancy-search input[type=text], #fancy-search .ui-autocomplete li .search-title, .format-quote .quote-post-wrapper blockquote, table th, .hb-button, .woocommerce #payment #place_order, .woocommerce-page #payment #place_order, input[type=submit], a.read-more, blockquote.pullquote, blockquote, .hb-skill-meter .hb-skill-meter-title, .hb-tabs-wrapper .nav-tabs li a, #main-wrapper .coupon-code input.button,#main-wrapper .form-row input.button,#main-wrapper input.checkout-button,#main-wrapper input.hb-update-cart,.woocommerce-page #main-wrapper .shipping-calculator-form-hb button.button, .hb-accordion-pane, .hb-accordion-tab {
						font-family: "' . $font_face . '", sans-serif;
						font-size: ' . highend_option( 'hb_font_body_size' ) . 'px;
						line-height: ' . highend_option( 'hb_font_body_line_height' ) . 'px;
						letter-spacing: ' . highend_option( 'hb_font_body_letter_spacing' ) . 'px;
						font-weight: ' . $font_weight . ';
					}';

					echo 'a.read-more, input[type=submit], .hb-caption-layer .hb-button, .hb-push-button-text, #pre-footer-area .hb-button, .hb-button, .hb-single-next-prev .text-inside, #main-wrapper .coupon-code input.button,#main-wrapper .form-row input.button,#main-wrapper input.checkout-button,#main-wrapper input.hb-update-cart,.woocommerce-page #main-wrapper .shipping-calculator-form-hb button.button { font-weight: 700; letter-spacing: 1px }';
				}

				// Navigation Font.
				if ( 'hb_font_custom' === highend_option( 'hb_font_navigation' ) ) {
					$font_subsets   = highend_option( 'hb_font_nav_subsets' );
					$font_face      = highend_option( 'hb_font_navigation_face' );
					$font_weight    = highend_option( 'hb_font_nav_weight' );
					$text_transform = 'none';

					if ( highend_option( 'hb_font_navigation_transform' ) ) {
						$text_transform = 'uppercase';
					}

					VP_Site_GoogleWebFont::instance()->add( $font_face, $font_weight, $font_style, $font_subsets );
					
					echo '#hb-side-menu li a, #main-nav ul.sub-menu li a, #main-nav ul.sub-menu ul li a, #main-nav, #main-nav li a, .light-menu-dropdown #main-nav > li.megamenu > ul.sub-menu > li > a, #main-nav > li.megamenu > ul.sub-menu > li > a {
						font-family: "' . $font_face . '", sans-serif;
						font-size: ' . highend_option( 'hb_font_navigation_size' ) . 'px;
						letter-spacing: ' . highend_option( 'hb_font_navigation_letter_spacing' ) . 'px;
						font-weight: ' . $font_weight . ';
						text-transform: ' . $text_transform . ';
					}';
				}

				// Navigation Dropdown Font.
				if ( 'hb_font_custom' === highend_option( 'hb_font_navigation_dropdown' ) ) {
					$font_subsets   = highend_option( 'hb_font_nav_subsets_dropdown' );
					$font_face      = highend_option( 'hb_font_navigation_face_dropdown' );
					$font_weight    = highend_option( 'hb_font_nav_weight_dropdown' );
					$text_transform = 'none';

					if ( highend_option( 'hb_font_navigation_transform_dropdown' ) ) {
						$text_transform = 'uppercase';
					}

					VP_Site_GoogleWebFont::instance()->add( $font_face, $font_weight, $font_style, $font_subsets );
					
					echo '#main-nav ul.sub-menu li a, #hb-side-menu ul.sub-menu li a, #main-nav ul.sub-menu ul li a, ul.sub-menu .widget-item h4, #main-nav > li.megamenu > ul.sub-menu > li > a #main-nav > li.megamenu > ul.sub-menu > li > a, #main-nav > li.megamenu > ul.sub-menu > li > a {
						font-family: "' . $font_face . '", sans-serif;
						font-size: ' . highend_option( 'hb_font_navigation_size_dropdown' ) . 'px;
						letter-spacing: ' . highend_option( 'hb_font_navigation_letter_spacing_dropdown' ) . 'px;
						font-weight: ' . $font_weight . ';
						text-transform: ' . $text_transform . ';
					}';
				}

				// Copyright Font.
				if ( 'hb_font_custom' === highend_option( 'hb_font_copyright' ) ) {

					$font_subsets = highend_option( 'hb_font_copyright_subsets' );
					$font_face    = highend_option( 'hb_font_copyright_face' );
					$font_weight  = highend_option( 'hb_font_copyright_weight' );

					VP_Site_GoogleWebFont::instance()->add( $font_face, $font_weight, $font_style, $font_subsets );
					echo '#copyright-wrapper, #copyright-wrapper a {
						font-family: "' . $font_face . '", sans-serif;
						font-size: ' . highend_option( 'hb_font_copyright_size' ) . 'px;
						line-height: ' . highend_option( 'hb_font_copyright_line_height' ) . 'px;
						letter-spacing: ' . highend_option( 'hb_font_copyright_letter_spacing' ) . 'px;
						font-weight: ' . $font_weight . ';
					}';
				}

				// Heading 1.
				if ( 'hb_font_custom' === highend_option( 'hb_font_h1' ) ) {

					$font_subsets = highend_option( 'hb_font_h1_subsets' );
					$font_face    = highend_option( 'hb_font_h1_face' );
					$font_weight  = highend_option( 'hb_font_h1_weight' );

					VP_Site_GoogleWebFont::instance()->add( $font_face, $font_weight, $font_style, $font_subsets );

					echo 'h1, .single article h1.title, #hb-page-title .light-text h1, #hb-page-title .dark-text h1 {
						font-family: "' . $font_face . '", sans-serif;
						font-size: ' . highend_option( 'hb_font_h1_size' ) . 'px;
						line-height: ' . highend_option( 'hb_font_h1_line_height' ) . 'px;
						letter-spacing: ' . highend_option( 'hb_font_h1_letter_spacing' ) . 'px;
						font-weight: ' . $font_weight . ';
					}';
				}

				// Heading 2.
				if ( 'hb_font_custom' === highend_option( 'hb_font_h2' ) ) {

					$font_subsets = highend_option( 'hb_font_h2_subsets' );
					$font_face    = highend_option( 'hb_font_h2_face' );
					$font_weight  = highend_option( 'hb_font_h2_weight' );

					VP_Site_GoogleWebFont::instance()->add( $font_face, $font_weight, $font_style, $font_subsets );

					echo 'h2, #hb-page-title h2, .post-content h2.title {
						font-family: "' . $font_face . '", sans-serif;
						font-size: ' . highend_option( 'hb_font_h2_size' ) . 'px;
						line-height: ' . highend_option( 'hb_font_h2_line_height' ) . 'px;
						letter-spacing: ' . highend_option( 'hb_font_h2_letter_spacing' ) . 'px;
						font-weight: ' . $font_weight . ';
					}';
				}

				// Heading 3.
				if ( 'hb_font_custom' === highend_option( 'hb_font_h3' ) ) {

					$font_subsets = highend_option( 'hb_font_h3_subsets' );
					$font_face    = highend_option( 'hb_font_h3_face' );
					$font_weight  = highend_option( 'hb_font_h3_weight' );

					VP_Site_GoogleWebFont::instance()->add( $font_face, $font_weight, $font_style, $font_subsets );

					echo 'h3, h3.title-class, .hb-callout-box h3, .hb-gal-standard-description h3 {
						font-family: "' . $font_face . '", sans-serif;
						font-size: ' . highend_option( 'hb_font_h3_size' ) . 'px;
						line-height: ' . highend_option( 'hb_font_h3_line_height' ) . 'px;
						letter-spacing: ' . highend_option( 'hb_font_h3_letter_spacing' ) . 'px;
						font-weight: ' . $font_weight . ';
					}';
				}

				// Heading 4.
				if ( 'hb_font_custom' === highend_option( 'hb_font_h4' ) ){

					$font_subsets = highend_option( 'hb_font_h4_subsets' );
					$font_face    = highend_option( 'hb_font_h4_face' );
					$font_weight  = highend_option( 'hb_font_h4_weight' );

					VP_Site_GoogleWebFont::instance()->add( $font_face, $font_weight, $font_style, $font_subsets );
					
					echo 'h4, .widget-item h4, #respond h3, .content-box h4, .feature-box h4.bold {
						font-family: "' . $font_face . '", sans-serif;
						font-size: ' . highend_option( 'hb_font_h4_size' ) . 'px;
						line-height: ' . highend_option( 'hb_font_h4_line_height' ) . 'px;
						letter-spacing: ' . highend_option( 'hb_font_h4_letter_spacing' ) . 'px;
						font-weight: ' . $font_weight . ';
					}';
				}

				// Heading 5.
				if ( 'hb_font_custom' === highend_option( 'hb_font_h5' ) ) {
					
					$font_subsets = highend_option( 'hb_font_h5_subsets' );
					$font_face    = highend_option( 'hb_font_h5_face' );
					$font_weight  = highend_option( 'hb_font_h5_weight' );
					
					VP_Site_GoogleWebFont::instance()->add( $font_face, $font_weight, $font_style, $font_subsets );

					echo 'h5, #comments h5, #respond h5, .testimonial-author h5 {
						font-family: "' . $font_face . '", sans-serif;
						font-size: ' . highend_option( 'hb_font_h5_size' ) . 'px;
						line-height: ' . highend_option( 'hb_font_h5_line_height' ) . 'px;
						letter-spacing: ' . highend_option( 'hb_font_h5_letter_spacing' ) . 'px;
						font-weight: ' . $font_weight . ';
					}';
				}

				// Heading 6.
				if ( 'hb_font_custom' === highend_option( 'hb_font_h6' ) ) {
					
					$font_subsets = highend_option( 'hb_font_h6_subsets' );
					$font_face    = highend_option( 'hb_font_h6_face' );
					$font_weight  = highend_option( 'hb_font_h6_weight' );
					
					VP_Site_GoogleWebFont::instance()->add( $font_face, $font_weight, $font_style, $font_subsets );
					
					echo 'h6, h6.special {
						font-family: "' . $font_face . '", sans-serif;
						font-size: ' . highend_option( 'hb_font_h6_size' ) . 'px;
						line-height: ' . highend_option( 'hb_font_h6_line_height' ) . 'px;
						letter-spacing: ' . highend_option( 'hb_font_h6_letter_spacing' ) . 'px;
						font-weight: ' . $font_weight . ';
					}';
				}

				// Pre-Footer Callout.
				if ( 'hb_font_custom' === highend_option( 'hb_pre_footer_font' ) ) {
					
					$font_subsets = highend_option( 'hb_font_pre_footer_subsets' );
					$font_face    = highend_option( 'hb_pre_footer_font_face' );
					$font_weight  = highend_option( 'hb_font_pre_footer_weight' );
					
					VP_Site_GoogleWebFont::instance()->add( $font_face, $font_weight, $font_style, $font_subsets );
					
					echo '#pre-footer-area {
						font-family: "' . $font_face . '", sans-serif;
						font-size: ' . highend_option( 'hb_pre_footer_font_size' ) . 'px;
						line-height: ' . highend_option( 'hb_pre_footer_line_height' ) . 'px;
						letter-spacing: ' . highend_option( 'hb_pre_footer_letter_spacing' ) . 'px;
						font-weight: ' . $font_weight . ';
					}';
				}

				// Modern Title.
				if ( 'hb_font_custom' === highend_option( 'hb_font_modern_title' ) ) {
					
					$font_subsets   = highend_option( 'hb_font_modern_title_subsets' );
					$font_face      = highend_option( 'hb_font_modern_title_face' );
					$font_weight    = highend_option( 'hb_font_modern_title_weight' );
					$text_transform = 'none';
					
					if ( highend_option( 'hb_font_modern_title_transform' ) ) {
						$text_transform = 'uppercase';
					}
					
					VP_Site_GoogleWebFont::instance()->add( $font_face, $font_weight, $font_style, $font_subsets );
					
					echo 'h1.modern,h2.modern,h3.modern,h4.modern,h5.modern,h6.modern {
						font-family: "' . $font_face . '", sans-serif;
						letter-spacing: ' . highend_option( 'hb_font_modern_title_letter_spacing' ) . 'px;
						font-weight: ' . $font_weight . ';
						text-transform: ' . $text_transform . ';
					}';
				}

				VP_Site_GoogleWebFont::instance()->register_and_enqueue();
			?>
			</style>
			<?php
		}

		/**
		 * Simple CSS code minification.
		 *
		 * @param  string $css code to be minified.
		 * @return string, minifed code
		 * @since  1.0.0
		 */
		private function minify_css( $css ) {
			$css = preg_replace( '/\s+/', ' ', $css );
			$css = preg_replace( '/\/\*[^\!](.*?)\*\//', '', $css );
			$css = preg_replace( '/(,|:|;|\{|}) /', '$1', $css );
			$css = preg_replace( '/ (,|;|\{|})/', '$1', $css );
			$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
			$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

			return trim( $css );
		}

		/**
		 * Cleans various caches. Compatible with cache plugins.
		 *
		 * @since 1.0.0
		 */
		private function clean_cache() {

			// If W3 Total Cache is being used, clear the cache.
			if ( function_exists( 'w3tc_pgcache_flush' ) ) {
				w3tc_pgcache_flush();
			}

			// if WP Super Cache is being used, clear the cache.
			if ( function_exists( 'wp_cache_clean_cache' ) ) {
				global $file_prefix;
				wp_cache_clean_cache( $file_prefix );
			}

			// If SG CachePress is installed, reset its caches.
			if ( class_exists( 'SG_CachePress_Supercacher' ) ) {
				if ( method_exists( 'SG_CachePress_Supercacher', 'purge_cache' ) ) {
					SG_CachePress_Supercacher::purge_cache();
				}
			}

			// Clear caches on WPEngine-hosted sites.
			if ( class_exists( 'WpeCommon' ) ) {

				if ( method_exists( 'WpeCommon', 'purge_memcached' ) ) {
					WpeCommon::purge_memcached();
				}

				if ( method_exists( 'WpeCommon', 'clear_maxcdn_cache' ) ) {
					WpeCommon::clear_maxcdn_cache();
				}

				if ( method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
					WpeCommon::purge_varnish_cache();
				}
			}

			// Clean OpCache.
			if ( function_exists( 'opcache_reset' ) ) {
				opcache_reset();
			}

			// Clean WordPress cache.
			if ( function_exists( 'wp_cache_flush' ) ) {
				wp_cache_flush();
			}
		}
	}
endif;
new Highend_Dynamic_Styles();
