(function($, plugins){

"use strict";

var $j          = jQuery.noConflict();
var $doc        = $j(document);

$doc.ready((function() {
	hb_icons_modal();
}));

function hb_icons_modal() {
	var icons_loaded = false;
	var $trigger_field;
	var $this, $selected_icon;
	var timer = 0;
	var icon_families = ['/typicons/src/font/typicons.min.css', '/vc-open-iconic/vc_openiconic.min.css', '/vc-linecons/vc_linecons_icons.min.css', '/vc-entypo/vc_entypo.min.css', '/monosocialiconsfont/monosocialiconsfont.min.css', '/vc-material/vc_material.min.css'];

	$j('#wpbody').on( 'click', '.show-icon-modal', (function(e) {
		e.preventDefault();
		$this = $j(this);
		$j('#hb-icons-modal, #hb-icons-modal-overlay').addClass('show');
		$j('#hb-icons-modal li').removeClass('selected-icon');

		$trigger_field = $this.parent().parent();

		if ( !icons_loaded ) {
			var $data = {
				action: 'hb_icon_picker_modal',
				_ajax_nonce: $this.data('nonce'),
			};

			$j.ajax({
				url: ajaxurl,
				type: 'POST',
				data: $data,
				dataType: 'json'
			}).success((function(response){
				if ( response.success && response.data.html !== '' ) {
					$j('#hb-icons-modal').removeClass('icons-loading');
					
					$j('.hb-modal-content').html( response.data.html );


					if ( !$j('#dynamic-icons').length ) {

						$j('<link/>', {
						   rel: 'stylesheet',
						   type: 'text/css',
						   href: $j('#hb-icons-modal').data('uri-fontawesome'),
						   id: 'dynamic-icons'
						}).appendTo('head');

						if ( typeof( $j('#hb-icons-modal').data('uri-vcicons') ) !== 'undefined' ) {
							for ( var i = 0; i < icon_families.length; i++ ) {
								$j('<link/>', {
								   rel: 'stylesheet',
								   type: 'text/css',
								   href: $j('#hb-icons-modal').data('uri-vcicons') + icon_families[i],
								   id: 'dynamic-vc-icons-' + i
								}).appendTo('head');
							}

						}

					}			
						
					icons_loaded = true;
					$j('#hb-icons-modal').find('.hb-search-icons').focus();
					if ($this.data('current')) {
						$j('#hb-icons-modal').find('.' + $this.data('current').replace( ' ', '.' ) ).parent().addClass('selected-icon');

					}
				} else {
				}
			})).fail((function(){
				alert('An error occured. Please reload the page and try again.');
			}));
		} else {
			$j('#hb-icons-modal').find('.hb-search-icons').focus();
			if ( $this.data('current') ) {
				$j('#hb-icons-modal').find('.' + $this.data('current').replace( ' ', '.' ) ).parent().addClass('selected-icon');
			}
		}
	}));

	$j('#hb-icons-modal').on('click', '.hb-close-icon-modal', (function(e) {
		e.preventDefault();
		$j('#hb-icons-modal, #hb-icons-modal-overlay').removeClass('show');
		$j('#hb-icons-modal').find('.hb-search-icons').val('').trigger('input').blur();
		$trigger_field = null;
	}));

	$j('#hb-icons-modal').on('click', 'li', (function() {
		$j('#hb-icons-modal li').removeClass('selected-icon');
		$j(this).addClass('selected-icon');
	}));

	$j('.remove-selected-icon').on('click', (function(e) {
		e.preventDefault();
		$this = $j(this);
		$this.siblings('.selected-icon').html('<em>No icon selected.</em>');
		$this.siblings('input').val('');
		$this.siblings('.show-icon-modal').data('current', '');
		$this.addClass('hidden');
	}));

	$j('#hb-icons-modal').on('click', '#use-this-icon', (function(e) {
		e.preventDefault();

		if ( $j('#hb-icons-modal' ).find('.selected-icon').length ) {
			$j('#hb-icons-modal').find('.hb-search-icons').val('').trigger('input').blur();
			$selected_icon = $j('#hb-icons-modal' ).find('.selected-icon').find('i').attr('class');
			$j('#hb-icons-modal, #hb-icons-modal-overlay').removeClass('show');
			$trigger_field.find('.selected-icon').html($selected_icon);
			$trigger_field.find('input').val($selected_icon);
			$trigger_field.find('.show-icon-modal').data('current', $selected_icon);
			$trigger_field.find('.remove-selected-icon').removeClass('hidden');
			$trigger_field = null;
		}
	}));

	$j('#hb-icons-modal').on('click', '.hb-toggle-icons', (function(e) {
		e.preventDefault();
		$this = $j(this);

		if ( typeof( $this.data('open') ) === 'undefined' || $this.data('open') === 1 ) {
			$j(this).data('open', 0).addClass('closed').parent().parent().siblings('ul').css('display', 'none');
		} else if ( $this.data('open') === 0 ) {
			$j(this).data('open', 1).removeClass('closed').parent().parent().siblings('ul').css('display', 'block');
		}
	}));

	$j('#hb-icons-modal').on('input', '.hb-search-icons', (function(e) {
		if (timer) {
			clearTimeout(timer);
		}
		timer = setTimeout(do_icon_search, 300); 
	}));
}

function do_icon_search() {
	var search_key = $j('#hb-icons-modal').find('.hb-search-icons').val();

	$j('#hb-icons-modal').find('.hb-modal-content').find('ul').each((function(){
		$j(this).find('li').each((function() {
			if ( $j(this).attr('title').toLowerCase().indexOf(search_key) == -1 ) {
				$j(this).hide();
			} else {
				$j(this).show();
			}
		}));
	}));
}

})(jQuery);