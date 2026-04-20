/**
 * Simple FAQ Manager — Frontend
 *
 * Handles:
 *   1. Accordion toggle for [faq_widget] and Elementor widget.
 *   2. Real-time keyword search for [faq_list].
 *   3. Category filter buttons for [faq_list].
 */
( function ( $ ) {
	'use strict';

	$( document ).ready( function () {

		// -----------------------------------------------------------------------
		// 1. Accordion
		// -----------------------------------------------------------------------

		$( document ).on( 'click', '.sfm-accordion-header', function () {
			var $btn    = $( this );
			var $body   = $btn.next( '.sfm-accordion-body' );
			var isOpen  = $btn.attr( 'aria-expanded' ) === 'true';

			if ( isOpen ) {
				$btn.attr( 'aria-expanded', 'false' );
				$body.attr( 'hidden', '' );
			} else {
				$btn.attr( 'aria-expanded', 'true' );
				$body.removeAttr( 'hidden' );
			}
		} );

		// -----------------------------------------------------------------------
		// 2 & 3. FAQ List: search + category filter
		// -----------------------------------------------------------------------

		if ( $( '.sfm-faq-list-wrap' ).length === 0 ) {
			return;
		}

		var activeCategory = 'all';

		function applyFilters() {
			var term    = $( '#sfm-search' ).val().toLowerCase().trim();
			var anyVisible = false;

			$( '.sfm-faq-group' ).each( function () {
				var $group   = $( this );
				var groupCat = $group.data( 'category' );

				// Hide the entire group when category filter doesn't match.
				if ( activeCategory !== 'all' && groupCat !== activeCategory ) {
					$group.addClass( 'sfm-hidden' );
					return; // continue .each()
				}

				var groupHasVisible = false;

				$group.find( '.sfm-faq-item' ).each( function () {
					var $item    = $( this );
					var question = $item.find( '.sfm-faq-question' ).text().toLowerCase();
					var answer   = $item.find( '.sfm-faq-answer' ).text().toLowerCase();
					var matches  = ! term || question.indexOf( term ) !== -1 || answer.indexOf( term ) !== -1;

					$item.toggleClass( 'sfm-hidden', ! matches );

					if ( matches ) {
						groupHasVisible = true;
						anyVisible      = true;
					}
				} );

				// Hide group heading if all its items are filtered out.
				$group.toggleClass( 'sfm-hidden', ! groupHasVisible );
			} );

			$( '.sfm-no-results' ).toggle( ! anyVisible );
		}

		// Debounce search input to avoid thrashing on fast typing.
		var searchTimer;
		$( '#sfm-search' ).on( 'input', function () {
			clearTimeout( searchTimer );
			searchTimer = setTimeout( applyFilters, 180 );
		} );

		// Category filter buttons
		$( document ).on( 'click', '.sfm-cat-btn', function () {
			var $btn = $( this );
			$( '.sfm-cat-btn' ).removeClass( 'active' );
			$btn.addClass( 'active' );
			activeCategory = $btn.data( 'category' );
			applyFilters();
		} );

	} );

} )( jQuery );
