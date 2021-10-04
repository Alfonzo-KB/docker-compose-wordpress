(function($, plugins){

"use strict";

var $j          = jQuery.noConflict();
var $doc        = $j(document);

$doc.ready((function() {
	hb_upload_button();
	hb_update_mega_menu_dependencies();

	
}));

function hb_update_mega_menu_dependencies() {

	var hb_update_dependencies = function( $checkbox ) {

		if ( ! $checkbox.length ) {
			return;
		}

		var $field = $checkbox.closest('.menu-item');

		if ( $checkbox[0].checked ) {
			$field.addClass('hb-megamenu');
			$field.nextUntil('.menu-item-depth-0').addClass('hb-megamenu');
		} else {
			$field.removeClass('hb-megamenu');	
			$field.nextUntil('.menu-item-depth-0').removeClass('hb-megamenu');
			$field.nextUntil('.menu-item-depth-0').find('.hb-menu-item-megamenu-widget option:selected').removeAttr('selected');
		}
	};

	$j('.menu-item-depth-0').each( (function() {
		hb_update_dependencies( $j(this).find('.edit-menu-item-hb-megamenu-check') );
	}));

	$j('#post-body').on( 'click', '.edit-menu-item-hb-megamenu-check', (function() {
		hb_update_dependencies( $j(this) );
	}));

	/*$j('.menu-item').not('menu-item-depth-0').each( function() {
		$j(this).find('.edit-menu-item-hb-megamenu-check').prop("checked", false);
	});*/
}



function hb_upload_button() {
	var $this, $id;
	var file_frame, attachment;

	$j("#post-body").on('click', '.hb-upload-button', (function(e) {
		e.preventDefault();

		$this = $j(this);
		$id = '#' + $this.attr('id').replace('_button', '');

		if ( file_frame ) {
			file_frame.open();
			return;
		}

		file_frame = wp.media.frames.file_frame = wp.media({
			title: $this.data('title'),
			button: {
				text: $this.data('button'),
			},
			multiple: false
		});

		file_frame.on( 'select', (function() {
			attachment = file_frame.state().get('selection').first().toJSON();
			$j( $id + '-preview' ).find('img').attr('src', attachment.url);
			$j( $id ).val(attachment.url);
			$j( $id + '_remove' ).removeClass('hidden');
		}));

		file_frame.open();

	}));

	$j("#post-body").on('click', '.hb-remove-image', (function(e) {
		e.preventDefault();
		$this = $j(this);
		$id = '#' + $this.attr('id').replace('_remove', '');
		$j( $id + '-preview' ).find('img').removeAttr('src');
		$j( $id ).val('');
		$this.addClass('hidden');
	}));
}

})(jQuery);