$(document).ready(function() {
	$(".noclose").on("click", function(e){
	    e.stopPropagation();
	});

	$("#top-login-btn").on("click", function(e){
		if (!$(".noclose").is(":visible")) {

			setTimeout(function() {$(".login-focus").focus();},100);

		}
	    
	});

});