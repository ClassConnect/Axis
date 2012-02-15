dontDoIt = false;
dontCheck = false;
$(document).ready(function() {
  // pjax stuff
  $('.pjaxLinker').pjax('#mainBox', {
  timeout: null, error: function(xhr, err){
    $('.error').text('Something went wrong: ' + err)
  }});
  $('#mainBox').bind('pjax:start', function() {
      //$("#mainBlocker").css({cursor:"progress"});
      $('html, body').animate({ scrollTop: 0 }, 'fast');
      if (!asyncActive) {
        asyncOvr = true;
        initAsyncBar('<img src="/assets/app/img/box/miniload.gif" style="margin-right:10px;margin-bottom:-1px" /> <span style="font-weight:bolder">Loading...</span>', 'yellowBox', 95, 622); 
      }
    })
    .bind('pjax:end',   function() {
      //$("#mainBlocker").css({cursor:"auto"});
      if (asyncOvr) {
        destroyAsyncBar();
        asyncOvr = false;
      }
      initResultPane();
    });


    $('#searchboxForm').submit(function() {
    	fireQuery();
    	return false;
    });



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
				tob.removeAttr('checked');
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


  initResultPane();


});


$(".descTip").twipsy({
    live: true,
    placement: 'below',
    html: true
});
$(".topDesc").twipsy({
    live: true,
    placement: 'above',
    html: true
});


function initResultPane() {
  $(".fboxElement").hover(
   function() {
      $(this).addClass('fbprogHov');
      $(this).find('.checkBoxy').removeClass('cboxNorm').addClass('cboxHov');
   },
   function() {
      $(this).removeClass('fbprogHov');
      $(this).find('.checkBoxy').addClass('cboxNorm').removeClass('cboxHov');
      $(this).find('.optListers').hide();
   }
  );

  $('.fboxElement').click(function() {
    window.location = "/app/filebox/" + $(this).attr('id');
  });

  $('.rollFalse').click(function() {
    return false;
  });
}


// this is a test
function addFilter(filtername, dropzone) {
	$('#' + dropzone).append('<div class="label filterItem">' + filtername + '<img src="/assets/app/img/box/rem.png" style="height:12px;float:right;cursor:pointer;margin-top:4px" onclick="removeFilter(\'' + filtername + '\')" /></div>');
	// show new results
	fireQuery();
}

function removeFilter(filtername) {

    $('.filterItem').each(function(){
    	if ($(this).text() == filtername) {
    		$(this).remove();
    	}
    });

    $('.checkMePlease, .standardCheck').each(function(){
    	if ($(this).val() == filtername) {
    		$(this).prop('checked', false);
    	}
    });

    fireQuery();
}



// for commoncore swapper
function swapCore1(curr, grade, topic) {
  if (curr == null) {
    curr = '';
  }
  if (grade == null) {
    grade = '';
  }
  if (topic == null) {
    topic = '';
  }
  $("#commonSwapper").html('<center><br /><br /><img src="/assets/app/img/box/loading.gif" /><br /><br /></center>');
  $.ajax({
   type: "GET",
   url: "/app/search/commoncore/?curr=" + curr + "&grade=" + grade + "&topic=" + topic,
   success: function(msg){
     $("#commonSwapper").html(msg);
   }
 });

}



function swapCommonTag(name) {
	var isHere = false;
	$("#commonstand").find('.filterItem').each(function(){
    	if ($(this).text() == name) {
    		isHere = true;
    	}
    });

    if (!isHere) {
    	addFilter(name, 'commonstand');
    } else {
    	removeFilter(name);
    }

    fireQuery();
}



function buildQuery() {
	finArr = new Array();
	finStr = '';
	$('.filterItem').each(function(){
		if (typeof finArr[$(this).parent().attr("id")] == 'undefined') {
			finArr[$(this).parent().attr("id")] = new Array();
		}
		finArr[$(this).parent().attr("id")][$(this).text()] = $(this).text();
    });

    for (arObj in finArr) {
    	finStr += '&' + arObj + '=';
    	for (tag in finArr[arObj]) {
    		finStr += tag + ',';
    	}
    }

    return finStr;
}



function fireQuery() {
	$.pjax({
		url: '/app/search/?query=' + escape($('.searchInput').val()) + buildQuery(),
		container: '#mainBox'
	});
}