(function ($) {

jQuery( document ).ready( function( $ ) {
	jQuery( 'input#wc_pac_new_badge' ).change( function() {
		if ( jQuery( this ).is( ':checked' ) ) {
			jQuery( 'input#wc_pac_new_badge' ).parents( ':eq(3)' ).next().show();
		} else {
			jQuery( 'input#wc_pac_new_badge' ).parents( ':eq(3)' ).next().hide();
		}
	}).change();
});

}(jQuery));