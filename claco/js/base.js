$(document).ready(function() {
	$(".noclose").on("click", function(e){
	    e.stopPropagation();
	});

	$("#top-notch-btn").on("click", function(e){
		if (!$(".noclose").is(":visible")) {

			setTimeout(function() {$(".login-focus").focus();},100);

		}
	    
	});

});




function initAsyc(content) {
	$('.async-pop').html(content).slideDown(100);
}

function destroyAsyc() {
	$('.async-pop').slideUp(100);
}






function htmlEncode(value){
    if (value) {
        return jQuery('<div />').text(value).html();
    } else {
        return '';
    }
}
 
function htmlDecode(value) {
    if (value) {
        return $('<div />').html(value).text();
    } else {
        return '';
    }
}


// close facebox
function closefBox() {
	jQuery(document).trigger('close.facebox');
}