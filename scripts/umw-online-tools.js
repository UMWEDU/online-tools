jQuery( function() {
	jQuery( window ).on( 'resize', function() {
		return do_umw_online_tools_resize();
	} );
	
	function do_umw_online_tools_resize() {
		if ( document.querySelectorAll( '.mega-menu-toggle' ).length >= 1 && jQuery( '.mega-menu-toggle' ).is( ':visible' ) ) {
			if ( document.querySelectorAll( '.header-widget-area .mega-menu-wrap .umw-helpful-links' ).length >= 1 ) {
				return;
			}
			jQuery( '.header-widget-area .mega-menu-wrap > ul.mega-menu' ).append( '<li class="mega-menu-item mega-menu-item-has-children mega-menu-item-umw-online-tools"><a href="#">Tools & Search</a><ul class="mega-sub-menu"><li></li></ul></li>' );
			jQuery( '.mega-menu-item-umw-online-tools > a' ).on( 'click', function() { jQuery( this ).closest( '.mega-menu-item' ).toggleClass( 'mega-toggle-on' ); return false; } );
			jQuery( '.header-widget-area .mega-menu-item-umw-online-tools .mega-sub-menu li' ).append( jQuery( '.umw-helpful-links' ) ).append( jQuery( '.umw-header-bar' ) );
			jQuery( '.umw-audience-menu' ).insertBefore( jQuery( '.umw-search-wrapper' ) );
		} else {
			if ( document.querySelectorAll( 'body > .umw-helpful-links' ).length >= 1 ) {
				return;
			}
			jQuery( 'body' ).prepend( jQuery( '.umw-header-bar' ) ).prepend( jQuery( '.umw-helpful-links' ) );
			jQuery( '.umw-audience-menu' ).appendTo( jQuery( '.umw-header-bar > .wrap' ) );
			jQuery( '.mega-menu-item-umw-online-tools' ).remove();
			
		}
	}
	
	do_umw_online_tools_resize();
} );