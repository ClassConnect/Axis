dontDoIt = false;
dontCheck = false;
$(document).ready(function() {
	$('.selbtndef').click(function() {
		// this is the open effect
		if ($(this).hasClass('preselSty')) {
			$(this).removeClass('preselSty');
			$(this).addClass('selSty');
			$(this).find('.labelPanel').css('opacity', 0).slideDown('fast').animate({ opacity: 1 },{ queue: false, duration: 'fast'});

		} else {
			if (!dontDoIt) {
				$(this).removeClass('selSty');
				$(this).addClass('preselSty');
				$(this).find('.labelPanel').css('opacity', 1).slideUp('fast').animate({ opacity: 0 },{ queue: false, duration: 'fast'});
			} else {
				dontDoIt = false;
			}
		}
	});

	$('.labelPanel').click(function() {
		dontDoIt = true;
	});


	$('.checkMePlease').click(function() {
		dontCheck = true;

		var name = $(this).val();
		var dropz = $(this).parent().parent().find('.tokenManifest').html();

		if ($(this).attr('checked')) {
			addFilter(name, dropz);
		} else {
			removeFilter(name);
		}
		
	});


	$('.optListItem').click(function() {
		if (!dontCheck) {
			var tob = $(this).find(":checkbox");
			if (!tob.attr('checked')) {
				tob.attr('checked', 'checked');
			} else {
				tob.attr('checked', false);
			}

			var name = tob.val();
			var dropz = $(this).parent().find('.tokenManifest').html();
			if (tob.attr('checked')) {
				addFilter(name, dropz);
			} else {
				removeFilter(name);
			}

		} else {
			dontCheck = false;
		}
	});


	$('.selbtndef').hover(
	   function() {
	      // do nothing
	   },
	   function() {
	      $(this).removeClass('selSty');
	      $(this).addClass('preselSty');
	      $(this).find('.labelPanel').css('opacity', 1).slideUp('fast').animate({ opacity: 0 },{ queue: false, duration: 'fast'});
	   }
	);
});


// this is a test
function addFilter(filtername, dropzone) {
	$('#' + dropzone).append('<div class="label filterItem">' + filtername + '<img src="/assets/app/img/box/rem.png" style="height:12px;float:right;cursor:pointer;margin-top:4px" onclick="removeFilter(\'' + filtername + '\')" /></div>');
}

function removeFilter(filtername) {

    $('.filterItem').each(function(){
    	if ($(this).text() == filtername) {
    		$(this).remove();
    	}
    });
}