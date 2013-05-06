function navigate( url, navigate, limit, extra, number ) {
	$( 'document' ).ready( function() {
		$( 'body' ).keydown( function( event ) {
			if( event.keyCode == 37 && event.ctrlKey ) {
				navigateLeft = parseInt( parseInt( navigate ) - parseInt( limit ) );
				navigateMin = 0
				if( navigateLeft >= navigateMin ) {
					url += extra;
					if( navigateLeft != 0 ) {
						url += 'navigate/' + navigateLeft + '/';
					}
					window.location.href = url;
				}
				return false;
			}
		} );
		$( 'body' ).keydown( function( event ) {
			if( event.keyCode == 39 && event.ctrlKey ) {
				navigateRight = parseInt( parseInt( navigate ) + parseInt( limit ) );
				navigateMax = parseInt( parseInt( number ) * parseInt( limit ) );
				if( navigateRight < navigateMax ) {
					url += extra;
					url += 'navigate/' + navigateRight + '/';
					window.location.href = url;
				}
				return false;
			}
		} );
	} )
}