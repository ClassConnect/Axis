dontDoIt = false;
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

function togglePanel() {
	
}