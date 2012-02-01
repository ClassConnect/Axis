// async var
asyncOvr = false;


$(document).ready(function() {
	swapActive($("#app-" + curApp));
  // pjax stuff
  $('.js-pjax').pjax('.sectionRight', {
  timeout: null, error: function(xhr, err){
    $('.error').text('Something went wrong: ' + err)
  }});
  $('.sectionRight')
    .bind('pjax:start', function() {
      clearPage();
    	// when pjax starts
      if (!asyncActive) {
        asyncOvr = true;
        initAsyncBar('<img src="/assets/app/img/box/miniload.gif" style="margin-right:10px;margin-bottom:-1px" /> <span style="font-weight:bolder">Loading...</span>', 'yellowBox', 95, 622); 
      }
    })
    .bind('pjax:end',   function() {
      	// when pjax ends
      	swapActive($("#app-" + curApp));
        if (asyncOvr) {
        destroyAsyncBar();
        asyncOvr = false;
      }
    });
});


$(".deleter").twipsy({
    live: true,
    placement: 'above',
    html: true
});


// random unbinds and what not
function clearPage() {
  // unbind all scroll events
  $(window).unbind('scroll');
}


function swapActive(tabObj) {
	if (!tabObj.hasClass('appSelected')) {
		$('.appSelected').removeClass('appSelected');
		tabObj.addClass('appSelected');
	}
}


function initAnnouncements() {
  loading = false;
  totalPull = 0;
  // set up infinite scroll
  $(window).scroll(function(){
    if (loading) {
      return;
    }

    if(nearBottomOfPage()) {
      totalPull++;
      loading=true;
      // pull in feed data
      $.ajax({  
      type: "GET",  
      url: "/app/common/feed/retrieve/?limit=20&off=" + (totalPull * 40) + "&t2=" + secID + "&primType=2&primID=" + secID,  
      dataType: "json",
      success: function(retData) {
        if (retData['empty'] == false) {
          $("#course_feed").append(retData['result']);
          loading=false;
        } else {
          $("#noneRM").remove();
          $("#course_feed").append('<p style="text-align:center;color:#666; background:#efefef;padding:7px;margin:20px">No more announcements found for this section..</p>');
          // dont unset the loading variable!
        }

      }  
      
      }); 


    }
  });

  $('#status').elastic();
  $('#status').height(20);
	$("#status").focus(function () {
         $(this).parent().find('.statActions').show();
         $(this).height(50);
    });

	$("#statSub").click(function () {
		// if we have a status to post
		if ($("#status").val() != '') {
			$('#status').attr('disabled', 'disabled');
			var selected = '';
			$("input:checkbox['courses']:checked").each(function() {
			       selected += $(this).val() + ',';
			  });

			$('.statActions').hide();

			$('.statActions').after('<img class="RM_me" src="/assets/app/img/box/sub.gif" style="float:right;margin-right:10px;margin-top:5px" />');

			$.ajax({  
		      type: "POST",  
		      url: preURL + "latest/add",  
		      data: 'courses=' + selected + '&status=' + escape($("#status").val()),
		      success: function(retData) {
		        $("#course_feed").prepend(
				    $(retData).hide().fadeIn('slow')
				);

				$("#status").height(20).val('').removeAttr('disabled');
				$('.RM_me').remove();
				$('.statActions').show();
				$('.hidPicker').hide();
        $('#noneRM').remove();

		      }  
		      
		  	}); 




		}
    });
}

// helper function
function nearBottomOfPage() {
  return $(window).scrollTop() > $(document).height() - $(window).height() - 200;
}


linkClick = false;
$(".descTip").twipsy({
    live: true,
    placement: 'below',
    html: true
  });

function initHandout() {
  $('.fboxElement').disableSelection();
  $(".fboxElement, .sharedEl").hover(
   function() {
      $(this).addClass('fbprogHov');
   },
   function() {
      $(this).removeClass('fbprogHov');
   }
  );

  // let our option area be unclickable
  $('.optarea, .optBox').click(function() {
    return false;
  });

  // if we click on a link
  $('.fboxElement a, .sharedEl a').click(function() {
    linkClick = true;
  });

  // click on the box, go to its page
  $('.fboxElement, .sharedEl').click(function() {
    // if a link wasn't clicked
    if (linkClick == false) {
      $.pjax({
        url: '/app/course/' + secID + '/handout/' + $(this).attr('id'),
        container: '.sectionRight'
      });
    }
    // reset the link click
    linkClick = false;
  });

  initCommentBars();

}