(function( $ ) {
	'use strict';

	$(document).on('keypress','.user_input',function(e) {
		var regex = new RegExp("^[a-zA-Z0-9_ \s\r\n]+$");
		var key = String.fromCharCode(!e.charCode ? e.which : e.charCode);
		if (!regex.test(key)) {
			e.preventDefault();
			return false;
		}
	})


})( jQuery );
