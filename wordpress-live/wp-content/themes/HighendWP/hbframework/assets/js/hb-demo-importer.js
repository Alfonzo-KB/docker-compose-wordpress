(function($){

var $j          = jQuery.noConflict();
var $doc        = $j(document);
var $wind       = $j(window);
var $body       = $j('body');

$doc.ready((function() {
	import_demo_content();
}));
 
function import_demo_content() {

	var demo_id,
		demo_import_nonce,
		demo_import_steps_count,
		demo_import_dialog = $j('#import-demo-confirm-dialog'),
		error_messages;

	// Detect changes of import options. Enable/Disable the Import button based on these options.
	$j( '.import-demo-form input:checkbox' ).on( 'change', (function(e) {

		var form = $j( this ).closest( 'form' );

		// Import All checkbox is changed
		if ( 'all' === $j( this ).val() ) {

			// Automatically change all other options to same value
			form.find( 'input:checkbox:not(:disabled)' ).prop( 'checked', $j( this ).prop( 'checked' ) );

			if ( $j( this ).is( ':checked' ) ) {

				// Enable the import button
				$j( '.button-install-demo[data-demo-id="' + demo_id + '"]' ).removeAttr( 'disabled' );

				// Clean the status bar
				$j( '#import-demo-modal-' + demo_id  + ' .import-demo-update-modal-status-bar-label span' ).html( '' );
				$j( '#import-demo-modal-' + demo_id + ' .button-done-demo' ).css( 'display', 'none' );
			} else {
				// Disable the import button since nothing is selected
				$j( '.button-install-demo[data-demo-id="' + demo_id + '"]' ).attr( 'disabled', 'disabled' );
			}
		} else if ( form.find( 'input[type="checkbox"]:checked' ).not( ':disabled' ).length > 0 ) {
			// Enable the import button if at least one option is selected
			$j( '.button-install-demo[data-demo-id="' + demo_id + '"]' ).removeAttr( 'disabled' );
		} else {
			// Disable the import button if no options are selected
			$j( '.button-install-demo[data-demo-id="' + demo_id + '"]' ).attr( 'disabled', 'disabled' );
		}

		// Uncheck 'all' if any other checkbox was unchecked.
		if ( false === $j( this ).prop( 'checked' ) ) {
			$j( '#import-demo-modal-' + demo_id + ' input[type="checkbox"][value="all"]' ).prop( 'checked', false );
		}
	}));


	// Open the demo import modal with options
	$j( '#hb-content' ).on( 'click', '.init-import-demo', (function(e){
		e.preventDefault();

		var $this 			= $j(this);

		// Save demo data for further functions
		demo_id 			= $this.data('demo-id');
		demo_import_nonce 	= $this.data('nonce');
		demo_options_nonce	= $this.data('options-nonce');

		// Show import modal
		$j('#import-demo-modal-' + $this.data('demo-id')).show();
		$j('.import-demo-overlay').show();
	}));


	// Close the demo import modal
	$j( '#hb-content' ).on( 'click', '.import-demo-modal-close', (function(e){
		e.preventDefault();

		var $this 			= $j(this);

		// Reset the demo data
		demo_id 			= null;
		demo_import_nonce 	= null;

		// Close modal
		$this.parent().hide();
		$j('.import-demo-overlay').hide();
	}));

	$j( '#hb-content' ).on( 'click', '.button-install-demo', (function(e) {
		e.preventDefault();

		// Open "Are you sure?" dialog
		demo_import_dialog.html( import_demo_data.are_you_sure );
		$j( '#' + demo_import_dialog.attr( 'id' ) ).dialog({
			dialogClass: 'hb-import-demo-dialog',
			resizable: false,
			draggable: false,
			height: 'auto',
			width: 400,
			modal: true,
			buttons: {
				Cancel: function() {
					demo_import_dialog.html('');
					$j(this).dialog( 'close' );
				},
				'OK': function() {

					// Alert on page exit
					window.onbeforeunload = function (ev) {
						var event = ev || window.event;
						if (event) { // For IE and Firefox
							event.returnValue = 'The demo import process is still in progress.';
						}
						return 'The demo import process is still in progress.'; // For Safari
					};
					
					init_import_demo();
					demo_import_dialog.html('');
					$j(this).dialog( 'close' );
				}
			}
		});
	}));

	// Import demo ajax wrapper function
	import_demo = function( data ) {	

		if ( 0 >= data.import_steps.length ) {
			import_status_bar( 'Import Complete', 1 );
			$j( '.button-install-demo[data-demo-id=' + demo_id + ']' ).removeAttr('disabled');
			return;
		}

		var status_percentage 	= ( demo_import_steps_count - data.import_steps.length + 1 ) / ( demo_import_steps_count + 1 );
		var status_label;

		// Set status label
		if ( 'content' === data.import_steps[0] ) {
			if ( 1 === data.content_types.length ) {
				status_label = $j( 'label[for=import-' + data.content_types[0] + '-' + demo_id + ']' ).html();
			} else {
				status_label = import_demo_data.content;						
			}

		} else if ( 'general_data' === data.import_steps[0] ) {
			status_label = import_demo_data.general_data;
		} else {
			status_label = $j('label[for=import-' + data.import_steps[0] + '-' + demo_id + ']' ).html();
		}

		// Update status bar
		import_status_bar( import_demo_data.currently_processing.replace( '%s', status_label ), status_percentage );

		$j.post( import_demo_data.admin_url, data, (function( response ) {

			if ( -1 !== response.indexOf( 'demo_import_partially_complete' ) && 0 < data.import_steps.length ) {
				
				// Import uncomplete
				// Proceed with next step
				data.import_steps.shift();
				import_demo( data );
			} else if ( -1 !== response.indexOf( 'demo_import_complete' ) ) {
				
				import_status_bar( 'Import Complete', 1 );
				$j( '.button-install-demo[data-demo-id=' + demo_id + ']' ).removeAttr('disabled');
				$j( '.import-demo-modal-close' ).show();

				window.onbeforeunload = null;

				// Import complete
				// Check for error messages
				if ( '' !== error_messages ) {

					error_messages = '<p><strong>' + import_demo_data.complete_with_errors + '</strong></p>' + '<ul>' + error_messages + '</ul>';
				
					demo_import_dialog.html( error_messages );
					$j( '#' + demo_import_dialog.attr( 'id' ) ).dialog({
						dialogClass: 'hb-import-demo-dialog',
						resizable: false,
						draggable: false,
						height: 'auto',
						width: 400,
						modal: true,
						title: import_demo_data.complete_title,
						buttons: {
							'OK': function() {
								demo_import_dialog.html('');
								error_messages = '';
								$j( this ).dialog( 'close' );
							}
						}
					});
				}

			} else if ( -1 !== response.indexOf( 'hb_import_error' ) ) {

				// Save error message
				error_messages += '<li>' + response.replace( 'hb_import_error: ', '' ) + '</li>';
				
				// Proceed with next step
				data.import_steps.shift();
				import_demo( data );

			} else if ( -1 !== response.indexOf( 'hb_import_exit' ) ) {

				
			} else {

				var import_option_data = {
					action: 'vp_ajax_hb_highend_option_import_option', 
					option: response,
					nonce: 	demo_options_nonce
				};

				$j.post( import_demo_data.admin_url, import_option_data, (function( options_response ) {
					//console.log(options_response);
				}), 'JSON' );

				// Proceed with next step
				data.import_steps.shift();
				import_demo( data );
			}

		})).fail( (function( xhr, textStatus, errorThrown ) {
			var message, original_message, dialogbuttons;

			dialogbuttons = {
					'OK': function() {
						$j( this ).dialog( 'close' );
						window.onbeforeunload = null;
						location.reload();
					}
				};

			if ( 'Forbidden' == errorThrown ) {
				message = import_demo_data.forbidden;
			} else {
				message = import_demo_data.retry_import;
				dialogbuttons = {
					'Retry': function() {
						demo_import_dialog.html('');
						$j( this ).dialog( 'close' );
						import_demo( data );
					},
					'Cancel': function() {
						$j( this ).dialog( 'close' );
						window.onbeforeunload = null;
						location.reload();
					}
				};
			}

			console.log( 'Highend Demo Import Failed' );
			console.log( 'ERROR Demo Import: ' + errorThrown );
			console.log( 'xhr response text: ' + xhr.responseText );
			console.log( 'text status: ' +textStatus );
			console.log( '---------------' );

			demo_import_dialog.html( message );
			$j( '#' + demo_import_dialog.attr( 'id' ) ).dialog({
				dialogClass: 'hb-import-demo-dialog',
				resizable: false,
				draggable: false,
				height: 'auto',
				width: 400,
				modal: true,
				title: 'Import Failed',
				buttons: dialogbuttons
			});
		}));
	};


	// Prepare the data and the modal window for ajax function and status report
	init_import_demo = function() {

		var import_demo_id,
			import_steps_arr,
			import_content_types,
			fetch_attachments,
			import_all,
			data,
			$this;

		import_steps_arr 		= [];
		import_content_types 	= [];
		error_messages			= '';

		// Populate steps array for each enabled option
		$j( '#import-' + demo_id + ' input:checkbox:checked' ).each( (function(){
			
			$this = $j(this);

			if ( ! $this.disabled ) {
				if ( 'content' === $this.data('type') ) {
					import_content_types.push( this.value );

					if ( -1 === import_steps_arr.indexOf( 'content' ) ) {
						import_steps_arr.push( 'content' );
					}
				} else {
					import_steps_arr.push( this.value );
				}
			}

			if ( 'all' === this.value ) {
				$this.disabled 	= true;
				import_all 		= true;
			} else {
				import_all 		= false;
			}
		}));

		if ( -1 !== import_steps_arr.indexOf( 'all' ) ) {
			import_steps_arr.splice( import_steps_arr.indexOf( 'all' ), 1 );
		}
		
		//import_steps_arr.push( 'general_data' );

		// Set the fetch attachment parameter
		if ( 0 < import_content_types.length && -1 !== import_content_types.indexOf( 'attachment' ) ) {
			fetch_attachments = true;
		} else {
			fetch_attachments = false;
		}

		// Save the steps count to be used in the status bar
		demo_import_steps_count = import_steps_arr.length;

		$j( '#import-demo-modal-' + demo_id ).addClass( 'demo-import-in-progress' );
		$j( '.button-install-demo[data-demo-id=' + demo_id + ']' ).attr('disabled','disabled');

		data = {
			action: 'hb_import_demo_data',
			security: demo_import_nonce,
			demo_id: demo_id,
			import_steps: import_steps_arr,
			content_types: import_content_types,
			fetch_attachments: fetch_attachments,
			import_all: import_all
		};

		$j( '.import-demo-update-modal-status-bar-progress-bar' ).show();
		$j( '.import-demo-modal-close' ).hide();

		// Finally, start import
		import_demo( data );
	};

	import_status_bar = function( message, progress ) {


		// Add animation class
		if ( progress == 1 ) {
			$j( '#import-demo-modal-' + demo_id  + ' .import-demo-update-modal-status-bar-progress-bar' ).removeClass('hb-loading-animation');
		} else {
			$j( '#import-demo-modal-' + demo_id  + ' .import-demo-update-modal-status-bar-progress-bar' ).addClass('hb-loading-animation');
		}

		$j( '#import-demo-modal-' + demo_id  + ' .import-demo-update-modal-status-bar-label span' ).html( message );
		$j( '#import-demo-modal-' + demo_id  + ' .import-demo-update-modal-status-bar-progress-bar' ).css( 'width', 100 * progress + '%' );
	};

}

})(jQuery);