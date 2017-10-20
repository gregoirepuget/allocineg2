/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	'use strict';

    wp.customize('header_image', function(value) {
    	value.bind(function( to ) {
			if( to !== '' && to !== 'remove-header' ){
				$('.home-top-area').removeClass('themotion-only-customizer');
				$('.home-three-videos').removeClass('themotion-only-customizer');
				$('.home-ribbon-intro').css('margin-top','150px');
			}
			$('.home-top-area').css('background-image','url('+ to +')');
		} );
    } );

	/* == About page == */
	wp.customize('themotion_about_header_image', function(value) {
		value.bind(function( to ) {
			if(to !== ''){
				$('.about-top-area').removeClass('themotion-only-customizer');
			} else {
				var header_text = wp.customize._value.themotion_about_header_text();
				var button_text = wp.customize._value.themotion_about_button_text();
				if(header_text === '' && button_text === ''){
					$('.about-top-area').addClass('themotion-only-customizer');
				}
			}
			$('.about-top-area').css('background-image','url('+ to +')');
		} );
	} );

	/* == Contact page == */
	wp.customize('themotion_contact_header_image', function(value) {
		value.bind(function( to ) {
			if(to !== ''){
				$('.about-top-area').removeClass('themotion-only-customizer');
			}
			$('.contact-section.about-top-area').css('background-image','url('+ to +')');
		} );
	} );

} )( jQuery );


