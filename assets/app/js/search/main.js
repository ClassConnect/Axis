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

		
		
	});


	$('.optListItem').click(function() {

		var tob = $(this).find(":checkbox");

		if (!dontCheck) {
			if (!tob.attr('checked')) {
				tob.prop('checked', true);
			} else {
				tob.prop('checked', false);
			}
		} else {
			dontCheck = false;
		}

		var name = tob.val();
		var dropz = $(this).parent().find('.tokenManifest').html();
		if (tob.attr('checked')) {
			addFilter(name, dropz);
		} else {
			removeFilter(name);
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

    $('.checkMePlease').each(function(){
    	if ($(this).val() == filtername) {
    		$(this).prop('checked', false);
    	}
    });
}



// for commoncore swapper
function swapCore(curr, grade, topic) {
  if (curr == null) {
    curr = '';
  }
  if (grade == null) {
    grade = '';
  }
  if (topic == null) {
    topic = '';
  }
  $("#addStandardSub").html('<center><br /><br /><img src="/assets/app/img/box/loading.gif" /><br /><br /></center>');
  $.ajax({
   type: "GET",
   url: "/app/search/commoncore/?curr=" + curr + "&grade=" + grade + "&topic=" + topic,
   success: function(msg){
     $("#addStandardSub").html(msg);
   }
 });

}