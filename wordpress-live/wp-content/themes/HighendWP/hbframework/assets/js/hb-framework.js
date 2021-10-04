(function($){

"use strict";

var $j          = jQuery.noConflict();
var $doc        = $j(document);
var $wind       = $j(window);
var $body       = $j('body');

$doc.ready((function() {
	hb_tabs();
	hb_plugin_manager();
	hb_sidebar_manager();
}));

function hb_tabs() {
	var $this;
	var $hash;
	var $filter;
	var $container = $j('#filter-container');

	setTimeout((function() { window.scrollTo(0, 0); }), 1);

	$wind.on('load',(function(){
		$hash = window.location.hash;
		setTimeout((function() { window.scrollTo(0, 0); }), 1);

		if ( $hash ) {
			$j('#hb-tabs').find('a').each((function(){
				$this = $j(this);
				if ( $j('#hb-tabs').hasClass('icon-search-tabs') ){
					if ( $this.data('filter') == $hash.substring(1) ) {
						$this.click();
					}
				} else {
					if ( $this.attr('href') == $hash ) {
						$this.click();
					}
				}
			}));
		} else {
			$j('#hb-tabs > li:first-child a').click();
		}
	}));

	if ($j.fn.isotope) {
		$container.isotope({
			itemSelector: '.theme',
  			layoutMode: 'fitRows',
  			transitionDuration: '.6s'
		});
	}

	$j('#hb-tabs').on('click', 'a', (function(e) {
		e.preventDefault();
		$this = $j(this);
		$hash = $this.attr('href');

		if ( $j('#hb-tabs').hasClass('icon-search-tabs') ){
			$hash = $this.data('filter');
		}

		window.location.hash = $hash;

		if ($j.fn.isotope) {
			$filter = $this.data('filter');
			if ( $filter != '*' ) {
				$filter = '.' + $filter;
			}
			$container.isotope({
				filter: $filter
			});
		}

		$j('#hb-tabs').find('a').parent().removeClass('current');
		$this.parent().addClass('current');

		if ( !$j('#hb-tabs').hasClass('icon-search-tabs') ){
			$j('.hb-tabs-container').find('div').removeClass('active');
			$j($hash).addClass('active');
		} else {
			$filter = $this.data('filter');

			switch( $filter ) {
				case '*':
					$filter = '*';
					break;
				case 'fontawesome':
			        $filter = 'fa';
			        break;
			    case 'openiconic':
			        $filter = 'vc-oi';
			        break;
			    case 'entypo':
			        $filter = 'entypo-icon';
			        break;
			    case 'typicons':
			    	$filter = 'typcn';
			    	break;
			   	case 'linecons':
			    	$filter = 'vc_li';
			    	break;
			    case 'monosocial':
			    	$filter = 'vc-mono';
			    	break;
		    	case 'material':
		    		$filter = 'vc-material';
		    		break;
		    	default: 
		    		$filter = '*';
		    		break;
			}

			if ( $filter == '*' ) {
				$j('#icon-wrapper').find('i').removeClass('deactivated');
			} else {
				$j('#icon-wrapper').find('i').addClass('deactivated');
				$j('#icon-wrapper').find('i.' + $filter).removeClass('deactivated');
			}
		}
	}));
}


function hb_plugin_manager() {
	var $data;
	var $running = false;
	var $container = $j('#hb-container');
	var $install_text = $container.data('install');
	var $installing_text = $container.data('installing');
	var $activate_text = $container.data('activate');
	var $activating_text = $container.data('activating');
	var $deactivate_text = $container.data('deactivate');
	var $deactivating_text = $container.data('deactivating');
	var $reloading_text = $container.data('reloading');
	var $update_text = $container.data('update');

	$j("#hb-content").on('click', '.install', (function(e) {
		e.preventDefault();
		var $this = $j(this);

		if ( !$running ) {
			$running = true;
			$this.find('.spinner').addClass('activated');
			$this.find('.button').attr('disabled', 'disabled');
			$this.find('.button, .more-details').html($installing_text + '...');

			$data = {
				action: 'hb_plugin_manager_install',
				_ajax_nonce: $this.data('nonce'),
				plugin_slug: $this.data('slug')
			};

			$j.ajax({
				url: ajaxurl,
				type: 'POST',
				data: $data,
				dataType: 'json'
			}).success((function (response) {
				if ( response.success ) {
					$this.find( '.button, .more-details' ).html($activate_text);
					$this.removeClass('install');
					$this.addClass('activate');
					$running = false;
					$this.click();
				} else {
					alert(response.data.message);
					$this.find( '.button, .more-details' ).html($install_text);
					$this.find( '.button' ).removeAttr('disabled');
					$this.find( '.spinner' ).removeClass('activated');
				}
			})).fail((function (response) {
				alert(response);
				$this.find( '.button' ).removeAttr('disabled');
				$this.find( '.spinner' ).removeClass('activated');
			})).done((function(){
				$running = false;
			}));
		}

	}));

	$j("#hb-content").on('click', '.activate', (function(e) {
		e.preventDefault();
		var $this = $j(this);

		if ( !$running ) {
			$running = true;

			$this.find('.spinner').addClass('activated');
			$this.find('.button').attr('disabled', 'disabled');
			$this.find('.button, .more-details').html($activating_text + '...');

			$data = {
				action: 'hb_plugin_manager_activate',
				_ajax_nonce: $this.data('nonce'),
				plugin_slug: $this.data('slug')
			};

			$j.ajax({
				url: ajaxurl,
				type: 'POST',
				data: $data,
				dataType: 'json'
			}).success((function (response) {
				if ( response.success ) {
					$this.removeClass('activate');
					$this.removeClass('install');
					$this.addClass('disabled-click');

					// Reload page
					location.reload();
				} else {
					$this.find( '.button, .more-details' ).html($activate_text);
					$this.find( '.spinner' ).removeClass('activated');
				}
			})).fail((function (response) {
				alert(response.data.message);
				$this.find( '.spinner' ).removeClass('activated');
			})).done((function(){
				$running = false;
				$this.find( '.button, .more-details' ).html($reloading_text);
			}));
		}

	}));

	$j("#hb-content").on('click', '.deactivate', (function(e) {

		e.preventDefault();
		var $this = $j(this);

		if ( !$running ) {
			$running = true;

			$this.find('.spinner').addClass('activated');
			$this.find('.button').attr('disabled', 'disabled');
			$this.find('.button, .more-details').html($deactivating_text + '...');

			$data = {
				action: 'hb_plugin_manager_deactivate',
				_ajax_nonce: $this.data('nonce'),
				plugin_slug: $this.data('slug')
			};

			$j.ajax({
				url: ajaxurl,
				type: 'POST',
				data: $data,
				dataType: 'json'
			}).success((function (response) {
				if ( response.success ) {
					// Reload page
					location.reload();
				} else {
					$this.find( '.button, .more-details' ).html($deactivate_text);
					$this.find( '.spinner' ).removeClass('activated');
				}
			})).fail((function (response) {
				alert(response.data.message);
				$this.find( '.spinner' ).removeClass('activated');
			})).done((function(){
				$running = false;
				$this.find( '.button, .more-details' ).html($reloading_text);
			}));
		}

	}));
}


function hb_sidebar_manager() {
	var $this, $count, $name;
	var $counter = 1;
	var $container = $j('#hb-container');
	var $delete_text = $container.data('delete');
	var $save_text = $container.data('save');
	var $cancel_text = $container.data('cancel');
	var $new_sidebar_text = $container.data('new');
	var $new_desc_text = $container.data('description');

	$j("#hb-content").on('blur', '.input-holder', (function(e) {
		$this = $j(this);
		if ( $j.trim( $this.val() ) < 1 ) {
			$this.val( $this.data('placeholder') );
		}
	}));

	$j("#hb-content").on('click', '#add-new-sidebar', (function(e) {
		e.preventDefault();
		$this = $j(this);

		if ( $this.attr("disabled") ) {
			return false;
		}

		$j('#hb-sidebars-table').append('<tr class="appeneded-row"><td class="regular-field"><input type="text" id="input-name-holder" class="input-holder" data-placeholder="' + $new_sidebar_text + ' '+ $counter +'" placeholder="' + $new_sidebar_text + ' '+ $counter +'" /></td><td class="hide-on-mobile larger-field"><input type="text" id="input-description-holder" class="input-holder" data-placeholder="'+ $new_desc_text +'" value="'+ $new_desc_text +'"/></td><td class="hide-on-mobile smaller-field css-field"></td><td class="smaller-field"><a href="#" class="button button-primary save-sidebar">'+ $save_text +'</a><a href="#" class="button button-secondary cancel-sidebar-button" id="cancel-new-sidebar">'+ $cancel_text +'</a><div class="spinner"></div></td></tr>');
		$j('#input-name-holder').focus();
		$this.attr('disabled', 'disabled');
		$j('.delete-sidebar-button').attr('disabled', 'disabled');
		$j('#empty-row').removeClass('visible-empty-row');

	}));

	$j("#hb-content").on('click', '.save-sidebar', (function(e) {
		e.preventDefault();
		$this = $j(this);

		if ( $this.attr("disabled") ) {
			return false;
		}

		$this.attr('disabled', 'disabled');
		$this.parent().find('.spinner').addClass('activated');

		var data = {
			'action': 'hb_add_sidebar',
			'sidebar_name': $this.parent().parent().find('#input-name-holder').val(),
			'sidebar_description': $this.parent().parent().find('#input-description-holder').val(),
			'security': $container.data('nonce')
		};

		$j.ajax({
			url: ajaxurl,
			type: 'POST',
			data: data,
			dataType: 'JSON'
		}).success( (function(response) {
				if ( response.success ) {
					$this.parent().parent().removeClass('appeneded-row');
					$this.parent().find('.spinner').removeClass('activated');
					$this.removeAttr('disabled');
					$j('#add-new-sidebar').removeAttr('disabled');
					$j('.delete-sidebar-button').removeAttr('disabled');
					$j('#hb-manage-widgets').removeClass('hidden');

					$counter++;

					$this.parent().parent().find('.regular-field').html(response.data.name);
					$this.parent().parent().find('.larger-field').html(response.data.description);
					$this.parent().parent().find('.css-field').html('<pre>' + response.data.css + '</pre>');
					$this.parent().prepend('<a href="#" data-name="'+ response.data.name +'" class="button button-secondary delete-sidebar-button">'+ $delete_text +'</a>');

					if ( count_table_rows() % 2 === 0 ) {
						$this.parent().parent().addClass('shaded-row');
					} else {
						$this.parent().parent().addClass('not-shaded-row');
					}

					$this.parent().find('.save-sidebar, .cancel-sidebar-button').remove();
				} else {
					alert( response.data.message );
					$j('#input-name-holder').focus();

					if ( count_table_rows() === 0 ) {
						$j('#empty-row').addClass('visible-empty-row');
						$j('#hb-manage-widgets').addClass('hidden');
					}
					$this.parent().find('.spinner').removeClass('activated');
					$this.removeAttr('disabled');

				}
		})).error( (function(response) {
				alert('AJAX Error! Please check the console.');

				$this.parent().find('.spinner').removeClass('activated');
				$this.removeAttr('disabled');
				console.log(response);
		}));
	}));

	$j("#hb-content").on('click', '#cancel-new-sidebar', (function(e) {
		e.preventDefault();
		$this = $j(this);

		$this.parent().parent().remove();
		$j('#add-new-sidebar').removeAttr('disabled');
		$j('.delete-sidebar-button').removeAttr('disabled');

		if ( count_table_rows() === 0 ) {
			$j('#empty-row').addClass('visible-empty-row');
		}
	}));

	$j("#hb-content").on('click', '.delete-sidebar-button', (function(e) {
		e.preventDefault();
		$this = $j(this);
		$name = $this.data('name');

		if ( $this.attr("disabled") ) {
			return false;
		}

		if ( confirm($delete_text + ' ' + $name + '?') ) {
			$this.attr('disabled', 'disabled');
			$this.parent().find('.spinner').addClass('activated');
			$j('.delete-sidebar-button').attr('disabled', 'disabled');
			
			var data = {
				'action': 'hb_remove_sidebar',
				'sidebar_name': $this.data('name'),
				'security': $container.data('nonce')
			};

			$j.ajax({
				url: ajaxurl,
				type: 'POST',
				data: data,
				dataType: 'JSON'
			}).success( (function(response) {
					if ( response.success ) {
						$this.parent().parent().remove();

						if ( count_table_rows() === 0 ) {
							$j('#empty-row').addClass('visible-empty-row');
							$j('#hb-manage-widgets').addClass('hidden');
						}
					} else {
						alert( response.data.message );
						$this.removeAttr('disabled');
						$this.parent().find('.spinner').removeClass('activated');
					}
					$j('.delete-sidebar-button').removeAttr('disabled');
			})).error( (function(response) {
					alert('AJAX Error! Please check the console.');
					$this.removeAttr('disabled');
					$this.parent().find('.spinner').removeClass('activated');
			}));
		}
	}));
}


function count_table_rows() {
	return $j('#hb-sidebars-table > tbody tr:not(#empty-row)').length;
}

})(jQuery);