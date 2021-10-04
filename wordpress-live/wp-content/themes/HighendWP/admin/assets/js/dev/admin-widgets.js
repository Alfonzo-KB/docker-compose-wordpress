//--------------------------------------------------------------------//
// Highend script that handles our widget functionality.
//--------------------------------------------------------------------//

;(function($) {

	"use strict";

	/**
	 * Holds most important methods for the widgets.
	 * 
	 * @type {Object}
	 */
	var HighendWidgets = {

		/**
		 * Start the engine.
		 *
		 * @since 1.0.0
		 */
		init: function() {

			// Document ready
			$(document).ready( HighendWidgets.ready );

			// Window load
			$(window).on( 'load', HighendWidgets.load );

			// Bind UI actions
			HighendWidgets.bindUIActions();
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
		 * Bind UI actions.
		 *
		 * @since 1.0.0
		*/
		bindUIActions: function() {
			var $wrap = $( '#wpwrap' );
			var $body = $( 'body' );
			var $this;

			$body.on( 'click', '.select-image', function(e) {

				$this = $(this);

				e.preventDefault();

				var image = wp.media({ 
					multiple: false,
					title: 'Select Image',
					button: {
						text: 'Select Image',
					},
				}).open().on( 'select', function() {

					// This will return the selected image from the Media Uploader, the result is an object.
					var uploadedImage = image.state().get( 'selection' ).first(),
						uploadedImageJSON = uploadedImage.toJSON(),
						previewImage;
						
					console.log(uploadedImageJSON);

					if ( ! _.isUndefined( uploadedImageJSON.sizes ) ) {
						if ( ! _.isUndefined( uploadedImageJSON.sizes.medium ) ) {
							previewImage = uploadedImageJSON.sizes.medium.url;
						} else if ( ! _.isUndefined( uploadedImageJSON.sizes.thumbnail ) ) {
							previewImage = uploadedImageJSON.sizes.thumbnail.url;
						} else if ( ! _.isUndefined( uploadedImageJSON.sizes.full ) ) {
							previewImage = uploadedImageJSON.sizes.full.url;
						} else {
							previewImage = uploadedImageJSON.url;
						}
					} else {
						previewImage = uploadedImageJSON.url;
					}

					previewImage = uploadedImageJSON.url;

					$this.siblings( 'input.img' ).val( previewImage ).trigger( 'input' );
					$this.siblings( '.ad-preview' ).toggleClass( 'hidden' ).find( 'img' ).attr( 'src', previewImage );
					$this.toggleClass( 'hidden' );
					$this.closest( 'p' ).siblings( '.ad-link' ).toggleClass( 'hidden' );
				});
			});

			$body.on( 'click', '.remove-image', function(e) {
				e.preventDefault();

				$this = $(this);

				var $ad_preview = $this.closest( '.ad-preview' );

				$ad_preview.siblings( 'input.img' ).val( '' ).trigger( 'input' );
				$ad_preview.toggleClass( 'hidden' ).find( 'img' ).attr( 'src', '' );
				$ad_preview.siblings( '.select-image' ).toggleClass( 'hidden' );
				$ad_preview.parent().siblings( '.ad-link' ).toggleClass( 'hidden' ).find( 'input' ).val( '' );
			});
			
		},

	}; // END var HighendWidgets

	HighendWidgets.init();
	window.highendwidgets = HighendWidgets;
	
})(jQuery);