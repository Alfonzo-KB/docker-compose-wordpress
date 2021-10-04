
//--------------------------------------------------------------------//
// Script related to metabox
//--------------------------------------------------------------------//

;(function($) {

	var HighendMetabox = {

		/**
		 * Start the engine.
		 *
		 * @since 1.0.0
		 */
		init: function() {

			// Document ready
			$(document).ready( HighendMetabox.ready );

			// Window load
			$(window).on( 'load', HighendMetabox.load );

			// Window resize
			// $(window).ss_smartresize( HighendMetabox.resize );
		},

		//--------------------------------------------------------------------//
		// Events
		//--------------------------------------------------------------------//

		/**
		 * Document ready.
		 *
		 * @since 1.0.0
		 */
		ready: function() {
			HighendMetabox.visibility();
		},

		/**
		 * Window load.
		 *
		 * @since 1.0.0
		 */
		load: function() {

		},

		/**
		 * Window resize.
		 *
		 * @since 1.0.0
		 */
		resize: function() {

		},

		//--------------------------------------------------------------------//
		// Functions
		//--------------------------------------------------------------------//
		
		/**
		 * Handle visibility of metaboxes.
		 *
		 * @since 1.0.0
		 */
		visibility: function() {

			// We're on block editor.
			if ( $( '.block-editor-page' ).length ) {

				// Page template related metabox.
				var pageTemplate;
				
				wp.data.subscribe( function() {

					var newPageTemplate = wp.data.select( 'core/editor' ).getEditedPostAttribute( 'template' );

					if ( pageTemplate !== newPageTemplate ) {
						pageTemplate = newPageTemplate;
						HighendMetabox.showPageMetaBoxes( pageTemplate );
					}
				} );

				// Post format related metabox.
				var postFormat;
				
				wp.data.subscribe( function() {

					var newpostFormat = wp.data.select( 'core/editor' ).getEditedPostAttribute( 'format' );

					if ( postFormat !== newpostFormat ) {
						postFormat = newpostFormat;
						HighendMetabox.showPostMetaBoxes( postFormat );
					}
				} );	
			} else {
				
				// Page template related metabox.
				HighendMetabox.showPageMetaBoxes( $( '#page_template option:selected' ).val() );

				$( '#page_template' ).change( function() {
					HighendMetabox.showPageMetaBoxes( $( this ).find( 'option:selected' ).val() );	
				});

				// Post format related metabox.
				HighendMetabox.showPostMetaBoxes( $('#post-formats-select input[type=radio]:checked').val() );

				$( '#post-formats-select' ).on( 'change', 'input[type=radio]', function() {
					HighendMetabox.showPostMetaBoxes( $( this ).val() );	
				});
			}
		},

		/**
		 * Hide all page metaboxes.
		 *
		 * @since 1.0.0
		 */
		hideAllPageMetaBoxes: function() {
			if ( $( '.post-type-page' ).length ) {
				$( '#contact_page_settings_metabox, #blog_page_settings_metabox, #blog_grid_page_settings_metabox, #blog_fw_page_settings_metabox, #gallery_fw_page_settings_metabox, #gallery_standard_page_settings_metabox, #portfolio_standard_page_settings_metabox, #presentation_settings_metabox, #blog_page_minimal_settings_metabox').hide();
			}	
		},

		/**
		 * Show additional metaboxes for template.
		 *
		 * @since 1.0.0
		 */
		showPageMetaBoxes: function( template ) {

			HighendMetabox.hideAllPageMetaBoxes();

			switch ( template ) {
				case 'page-templates/contact.php' :
					$( '#contact_page_settings_metabox' ).show();
				break;
				case 'page-templates/blog.php' :
					$( '#blog_page_settings_metabox' ).show();
				break;
				case 'page-templates/blog-minimal.php' :
					$( '#blog_page_minimal_settings_metabox' ).show();
				break;
				case 'page-templates/blog-grid-fullwidth.php' :
					$( '#blog_fw_page_settings_metabox' ).show();
				break;
				case 'page-templates/blog-grid.php' :
					$( '#blog_grid_page_settings_metabox' ).show();
				break;
				case 'page-templates/gallery-fullwidth.php' :
					$( '#gallery_fw_page_settings_metabox' ).show();
				break;
				case 'page-templates/gallery-standard.php' :
					$( '#gallery_standard_page_settings_metabox' ).show();
				break;
				case 'page-templates/portfolio-standard.php' :
					$( '#portfolio_standard_page_settings_metabox' ).show();
				break;
				case 'page-templates/portfolio-simple.php' :
					$( '#portfolio_standard_page_settings_metabox' ).show();
				break;
				case 'page-templates/presentation-fullwidth.php' :
					$( '#presentation_settings_metabox' ).show();
				break;
			}	
		},

		/**
		 * Hide all post format metaboxes.
		 *
		 * @since 1.0.0
		 */
		hideAllPostMetaBoxes: function() {
			if ( $( '.post-type-post' ).length ) {
				$( '.wpa_loop-hb_video_post_format, .wpa_loop-hb_audio_post_format, .wpa_loop-hb_link_post_format, .wpa_loop-hb_quote_post_format, #post_format_settings_metabox, #gallery_images' ).hide();
			}
		},

		/**
		 * Show additional metaboxes for post format.
		 *
		 * @since 1.0.0
		 */
		showPostMetaBoxes: function( format ) {

			HighendMetabox.hideAllPostMetaBoxes();
			
			switch ( format ) {
				case 'gallery' :
					$( '#gallery_images' ).show();
				break;
				case 'video' :
					$( '#post_format_settings_metabox' ).show();
					$( '.wpa_loop-hb_video_post_format' ).show();
				break;
				case 'audio' :
					$( '#post_format_settings_metabox' ).show();
					$( '.wpa_loop-hb_audio_post_format' ).show();
				break;
				case 'link' :
					$( '#post_format_settings_metabox' ).show();
					$( '.wpa_loop-hb_link_post_format' ).show();
				break;
				case 'quote' :
					$( '#post_format_settings_metabox' ).show();
					$( '.wpa_loop-hb_quote_post_format' ).show();
				break;
			}	
		}
	};

	HighendMetabox.init();
	window.highend_metabox = HighendMetabox;

})(jQuery);