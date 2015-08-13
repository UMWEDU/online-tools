jQuery( function() {
	jQuery( window ).on( 'resize', function() {
		if ( document.querySelectorAll( '.mega-menu-toggle' ).length >= 1 && jQuery( '.mega-menu-toggle' ).is( ':visible' ) ) {
			console.log( 'Small screen' );
			if ( document.querySelectorAll( '.header-widget-area .mega-menu-wrap .umw-helpful-links' ).length >= 1 ) {
				console.log( 'Already moved' );
				return;
			}
			console.log( 'Moving now' );
			jQuery( '.header-widget-area .mega-menu-wrap' ).append( jQuery( '.umw-helpful-links' ) ).append( jQuery( '.umw-header-bar' ) );
		} else {
			console.log( 'Big screen' );
			if ( document.querySelectorAll( 'body > .umw-helpful-links' ).length >= 1 ) {
				console.log( 'Already moved' );
				return;
			}
			console.log( 'Moving now' );
			jQuery( 'body' ).prepend( jQuery( '.umw-header-bar' ) ).prepend( jQuery( '.umw-helpful-links' ) );
		}
	} );
} );