/**
 * Simple FAQ Manager — Admin: drag-and-drop sort + widget toggle
 *
 * Depends on: jQuery, jQuery UI Sortable (both bundled with WordPress).
 * sfmAdmin (object) is localized by the plugin: { ajaxUrl, nonce, i18n }.
 */
( function ( $ ) {
	'use strict';

	var $notice = $( '#sfm-save-notice' );
	var noticeTimer;

	function showNotice( message, type ) {
		clearTimeout( noticeTimer );
		$notice
			.removeClass( 'success error' )
			.addClass( type )
			.text( message )
			.show();
		noticeTimer = setTimeout( function () {
			$notice.fadeOut( 400 );
		}, 3500 );
	}

	// -------------------------------------------------------------------------
	// Drag-and-drop reorder via jQuery UI Sortable
	// -------------------------------------------------------------------------

	$( '#sfm-sortable-list' ).sortable( {
		handle: '.sfm-drag-handle',
		placeholder: 'sfm-sortable-placeholder',
		axis: 'y',
		tolerance: 'pointer',
		update: function () {
			var order = [];

			$( '#sfm-sortable-list .sfm-faq-row' ).each( function () {
				order.push( $( this ).data( 'id' ) );
			} );

			$.post(
				sfmAdmin.ajaxUrl,
				{
					action: 'sfm_save_order',
					nonce: sfmAdmin.nonce,
					order: order,
				},
				function ( response ) {
					if ( response.success ) {
						showNotice( sfmAdmin.i18n.orderSaved, 'success' );
					} else {
						showNotice( sfmAdmin.i18n.orderFailed, 'error' );
					}
				}
			).fail( function () {
				showNotice( sfmAdmin.i18n.orderFailed, 'error' );
			} );
		},
	} );

	// -------------------------------------------------------------------------
	// Toggle show_on_widget via checkbox
	// -------------------------------------------------------------------------

	$( document ).on( 'change', '.sfm-widget-toggle', function () {
		var $checkbox = $( this );
		var postId    = $checkbox.data( 'id' );
		var value     = $checkbox.is( ':checked' ) ? '1' : '0';

		// Disable while saving to prevent double-clicks.
		$checkbox.prop( 'disabled', true );

		$.post(
			sfmAdmin.ajaxUrl,
			{
				action: 'sfm_toggle_widget',
				nonce: sfmAdmin.nonce,
				post_id: postId,
				value: value,
			},
			function ( response ) {
				$checkbox.prop( 'disabled', false );
				if ( response.success ) {
					sfm_updateRowState( $checkbox.closest( '.sfm-faq-row' ), value === '1' );
					sfm_updateCountSummary();
					showNotice( sfmAdmin.i18n.toggleSaved, 'success' );
				} else {
					$checkbox.prop( 'checked', value !== '1' );
					showNotice( sfmAdmin.i18n.toggleFailed, 'error' );
				}
			}
		).fail( function () {
			$checkbox.prop( 'disabled', false ).prop( 'checked', value !== '1' );
			showNotice( sfmAdmin.i18n.toggleFailed, 'error' );
		} );
	} );

	// -------------------------------------------------------------------------
	// Row state helpers
	// -------------------------------------------------------------------------

	function sfm_updateRowState( $row, isOn ) {
		$row
			.toggleClass( 'sfm-row-on', isOn )
			.toggleClass( 'sfm-row-off', ! isOn )
			.attr( 'data-widget', isOn ? '1' : '0' );
	}

	function sfm_updateCountSummary() {
		var total  = $( '.sfm-faq-row' ).length;
		var active = $( '.sfm-faq-row.sfm-row-on' ).length;
		$( '.sfm-count-summary' ).text( active + ' of ' + total + ' FAQs shown on widget' );
	}

	// -------------------------------------------------------------------------
	// Filter tabs: All / On Widget / Off Widget
	// -------------------------------------------------------------------------

	var currentFilter = 'all';

	$( document ).on( 'click', '.sfm-filter-btn', function () {
		currentFilter = $( this ).data( 'filter' );
		$( '.sfm-filter-btn' ).removeClass( 'active' );
		$( this ).addClass( 'active' );
		sfm_applyFilter();
	} );

	function sfm_applyFilter() {
		$( '.sfm-faq-row' ).each( function () {
			var isOn    = $( this ).data( 'widget' ) === 1 || $( this ).data( 'widget' ) === '1';
			var visible = currentFilter === 'all'
				|| ( currentFilter === 'on' && isOn )
				|| ( currentFilter === 'off' && ! isOn );
			$( this ).toggle( visible );
		} );
	}

} )( jQuery );
