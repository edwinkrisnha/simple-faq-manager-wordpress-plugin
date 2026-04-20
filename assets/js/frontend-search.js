/**
 * Simple FAQ Manager — Frontend
 *
 * Handles:
 *   1. [faq_widget] / Elementor widget accordion toggle.
 *   2. [faq_list] in accordion mode — list item toggle.
 *   3. [faq_list] "Expand All / Collapse All" button.
 *   4. [faq_list] real-time keyword search (debounced).
 *   5. [faq_list] category filter buttons.
 *
 * Settings injected by PHP via wp_localize_script as window.sfmSettings:
 *   listDisplayMode   {string}  'expanded' | 'accordion'
 *   listShowExpandAll {boolean}
 *   widgetOpenFirst   {boolean}
 *   widgetExclusive   {boolean}
 *   i18n.expandAll    {string}
 *   i18n.collapseAll  {string}
 */
( function ( $ ) {
	'use strict';

	var settings = window.sfmSettings || {
		listDisplayMode:   'expanded',
		listShowExpandAll: true,
		listExclusive:     true,
		widgetOpenFirst:   false,
		widgetExclusive:   true,
		i18n: { expandAll: 'Expand All', collapseAll: 'Collapse All' },
	};

	// -------------------------------------------------------------------------
	// Helpers
	// -------------------------------------------------------------------------

	function openAccordionItem( $btn, $body ) {
		$btn.attr( 'aria-expanded', 'true' );
		$body.removeAttr( 'hidden' );
	}

	function closeAccordionItem( $btn, $body ) {
		$btn.attr( 'aria-expanded', 'false' );
		$body.attr( 'hidden', '' );
	}

	// -------------------------------------------------------------------------
	// 1. Widget accordion (sfm-accordion-header / sfm-accordion-body)
	// -------------------------------------------------------------------------

	$( document ).on( 'click', '.sfm-accordion-header', function () {
		var $btn      = $( this );
		var $body     = $btn.next( '.sfm-accordion-body' );
		var $wrap     = $btn.closest( '.sfm-accordion' );
		var isOpen    = $btn.attr( 'aria-expanded' ) === 'true';

		if ( isOpen ) {
			closeAccordionItem( $btn, $body );
		} else {
			// Exclusive accordion: close siblings first.
			if ( settings.widgetExclusive ) {
				$wrap.find( '.sfm-accordion-header[aria-expanded="true"]' ).each( function () {
					closeAccordionItem( $( this ), $( this ).next( '.sfm-accordion-body' ) );
				} );
			}
			openAccordionItem( $btn, $body );
		}
	} );

	// -------------------------------------------------------------------------
	// 2. List accordion (sfm-list-toggle / sfm-faq-answer)
	// -------------------------------------------------------------------------

	$( document ).on( 'click', '.sfm-list-toggle', function () {
		var $btn   = $( this );
		var $item  = $btn.closest( '.sfm-faq-item' );
		var $group = $btn.closest( '.sfm-faq-groups' );
		var $body  = $item.find( '.sfm-faq-answer' );
		var isOpen = $btn.attr( 'aria-expanded' ) === 'true';

		if ( isOpen ) {
			closeAccordionItem( $btn, $body );
		} else {
			// Exclusive list accordion: close all other open items first.
			if ( settings.listExclusive ) {
				$group.find( '.sfm-list-toggle[aria-expanded="true"]' ).each( function () {
					var $other = $( this );
					closeAccordionItem( $other, $other.closest( '.sfm-faq-item' ).find( '.sfm-faq-answer' ) );
				} );
			}
			openAccordionItem( $btn, $body );
		}

		// Sync expand-all button state after manual toggle.
		sfm_syncExpandAllBtn();
	} );

	// -------------------------------------------------------------------------
	// 3. Expand All / Collapse All button
	// -------------------------------------------------------------------------

	$( document ).on( 'click', '.sfm-expand-all-btn', function () {
		var $btn     = $( this );
		var isExpand = $btn.data( 'state' ) === 'collapsed';

		$( '.sfm-mode-accordion .sfm-faq-item:not(.sfm-hidden) .sfm-list-toggle' ).each( function () {
			var $toggle = $( this );
			var $body   = $toggle.closest( '.sfm-faq-item' ).find( '.sfm-faq-answer' );
			if ( isExpand ) {
				openAccordionItem( $toggle, $body );
			} else {
				closeAccordionItem( $toggle, $body );
			}
		} );

		$btn
			.data( 'state', isExpand ? 'expanded' : 'collapsed' )
			.text( isExpand ? settings.i18n.collapseAll : settings.i18n.expandAll );
	} );

	function sfm_syncExpandAllBtn() {
		var $btns   = $( '.sfm-mode-accordion .sfm-faq-item:not(.sfm-hidden) .sfm-list-toggle' );
		var allOpen = $btns.length > 0 && $btns.filter( '[aria-expanded="true"]' ).length === $btns.length;
		var $btn    = $( '.sfm-expand-all-btn' );

		if ( ! $btn.length ) {
			return;
		}
		$btn
			.data( 'state', allOpen ? 'expanded' : 'collapsed' )
			.text( allOpen ? settings.i18n.collapseAll : settings.i18n.expandAll );
	}

	// -------------------------------------------------------------------------
	// 4 & 5. [faq_list] keyword search + category filter
	// -------------------------------------------------------------------------

	$( document ).ready( function () {

		// Widget: open first item on load.
		if ( settings.widgetOpenFirst ) {
			$( '.sfm-accordion' ).each( function () {
				var $first = $( this ).find( '.sfm-accordion-header' ).first();
				openAccordionItem( $first, $first.next( '.sfm-accordion-body' ) );
			} );
		}

		if ( $( '.sfm-faq-list-wrap' ).length === 0 ) {
			return;
		}

		var activeCategory = 'all';

		function applyFilters() {
			var term       = $( '#sfm-search' ).val().toLowerCase().trim();
			var anyVisible = false;

			$( '.sfm-faq-group' ).each( function () {
				var $group    = $( this );
				var groupCat  = $group.data( 'category' );
				var groupHas  = false;

				if ( activeCategory !== 'all' && groupCat !== activeCategory ) {
					$group.addClass( 'sfm-hidden' );
					return;
				}

				$group.find( '.sfm-faq-item' ).each( function () {
					var $item    = $( this );
					var question = $item.find( '.sfm-faq-question' ).text().toLowerCase();
					var answer   = $item.find( '.sfm-faq-answer' ).text().toLowerCase();
					var matches  = ! term || question.indexOf( term ) !== -1 || answer.indexOf( term ) !== -1;

					$item.toggleClass( 'sfm-hidden', ! matches );

					if ( matches ) {
						groupHas   = true;
						anyVisible = true;
					}
				} );

				$group.toggleClass( 'sfm-hidden', ! groupHas );
			} );

			$( '.sfm-no-results' ).toggle( ! anyVisible );

			// After filtering, re-sync the expand-all button.
			sfm_syncExpandAllBtn();
		}

		// Debounced search.
		var searchTimer;
		$( '#sfm-search' ).on( 'input', function () {
			clearTimeout( searchTimer );
			searchTimer = setTimeout( applyFilters, 180 );
		} );

		// Category filter.
		$( document ).on( 'click', '.sfm-cat-btn', function () {
			$( '.sfm-cat-btn' ).removeClass( 'active' );
			$( this ).addClass( 'active' );
			activeCategory = $( this ).data( 'category' );
			applyFilters();
		} );

	} );

} )( jQuery );
