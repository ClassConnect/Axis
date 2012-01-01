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
		      data: 'courses=' + selected + '&status=' + $("#status").val(),
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



function initCalendar() {
	var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    
    $('#calInit').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,basicWeek,basicDay'
      },

      selectable: false,

      editable: false,

      loading: function(isLoading, view) {
        if (isLoading == true) {
          initAsyncBar('<img src="/assets/app/img/box/miniload.gif" style="margin-right:10px;margin-bottom:-1px" /> <span style="font-weight:bolder">Loading calendar entries...</span>', 'yellowBox', 200, 622);
        } else {
          destroyAsyncBar();
        }
      },

      eventClick: function(calEvent, jsEvent, view) {
        $('.twipsy').remove();

        jQuery.facebox({ 
            ajax: '/app/calendar/view/' + calEvent.id
          });

      },

      // me = personal, courses = csv, colleagues = bool, networks = csv
      events: '/app/calendar/feed/?courses=' + secID,

      eventDragStart: function( event, jsEvent, ui, view ) {
        $('.twipsy').remove();
      },


      eventDrop: function(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
        initAsyncBar('<img src="/assets/app/img/box/miniload.gif" style="margin-right:10px;margin-bottom:-1px" /> <span style="font-weight:bolder">Updating calendar entry...</span>', 'yellowBox', 200, 619);
        $('.twipsy').remove();
        $.ajax({
          type: "GET",
          url: "/app/calendar/write/malleable/shift/" + event.id + "/" + dayDelta,
          success: function(data) {
                 // successful write
                 destroyAsyncBar();
          }

        });
      },


      eventResize: function(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
        initAsyncBar('<img src="/assets/app/img/box/miniload.gif" style="margin-right:10px;margin-bottom:-1px" /> <span style="font-weight:bolder">Updating calendar entry...</span>', 'yellowBox', 200, 619);
        $('.twipsy').remove();
        $.ajax({
          type: "GET",
          url: "/app/calendar/write/malleable/resize/" + event.id + "/" + dayDelta,
          success: function(data) {
                 // successful write
                 destroyAsyncBar();
          }

        });
      },


      eventMouseover: function(calEvent,jsEvent) {
        $('.twipsy').remove();
        $(this).twipsy({
          placement: 'below',
          html: true,
          title: function() { return calEvent.body; },
          trigger: 'manual'
        });
        $(this).twipsy('show');
        $('#element').twipsy(true);
    },
    eventMouseout: function(calEvent,jsEvent) {
      $(this).twipsy('hide');
    }
   /*   .twipsy({
    live: true,
    placement: 'right',
    html: true
  });*/


    });
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

}


// override calendar title setter
function courseCalSet(data) {
	$("#calheadt").html(data);
}


// copy of filebox content viewing JS
// display a website, gdoc or embed
function displayWebContent(type, title, data) {
  var marTop = 70;
  var marHeight = 89;
  var res = 73;
  var topbar = '';
  var fsData = '';
  // if this is a URL
  if (type == 2) {
    topbar = '<div style="float:right;margin-top:2px"><button type="submit" class="btn danger" style="font-size:10px;font-weight:bolder" onclick="destroyWebContent()">Close</button></div><div class="alert-message elem" style="width:410px;font-size:12px;padding:1px;padding-left:5px;margin-top:4px"><div class="descTip" style="float:right;margin:0;padding:0;margin-top:-1px" data-original-title="Open in new window"><a href="' + data + '" target="_blank" onClick="destroyWebContent();$(\'.twipsy\').remove();"><img src="/assets/app/img/box/expand.png" /></a></div><div style="line-height:1;margin-top:3px;width:380px;overflow:hidden;height:12px;">' + title + '</div></div></div>';

    fsData = '<iframe allowtransparency="true" frameborder="0" id="webframe" class="webConLoader" scrolling="auto" src="' + data + '" style="width:100%;height:100%"></iframe>';

  // embed
  } else if (type == 3) {
    topbar = '<div style="float:right;margin-top:2px"><button type="submit" class="btn danger" style="font-size:10px;font-weight:bolder" onclick="destroyWebContent()">Close</button></div><div class="alert-message elem" style="width:410px;font-size:12px;padding:1px;padding-left:5px;margin-top:4px"><div style="line-height:1;margin-top:3px;width:380px;overflow:hidden;height:12px;">' + title + '</div></div></div>';

    fsData = '<center>' + data + '</center>';


    // google doc
  } else if (type == 5) {
    topbar = '<div style="float:right;margin-top:2px"><button type="submit" class="btn danger" style="font-size:10px;font-weight:bolder" onclick="destroyWebContent()">Close</button></div><div class="alert-message elem" style="width:410px;font-size:12px;padding:1px;padding-left:5px;margin-top:4px"><div class="descTip" style="float:right;margin:0;padding:0;margin-top:-1px" data-original-title="Open in new window"><a href="' + data + '" target="_blank" onClick="destroyWebContent();$(\'.twipsy\').remove();"><img src="/assets/app/img/box/expand.png" /></a></div><div style="line-height:1;margin-top:3px;width:380px;overflow:hidden;height:12px;">' + title + '</div></div></div>';

    fsData = '<iframe allowtransparency="true" frameborder="0" id="webframe" class="webConLoader" scrolling="auto" src="' + data + '" style="width:100%;height:100%"></iframe>';

    marTop = 30;
    marHeight = 93;
    res = 33;


  }
  $("#mainContent").hide();
  $("#mainNavBar").after('<div id="webControls" class="topbar" style="top:40px; z-index:1000"> <div class="fill"> <div class="container" style="height:30px">' + topbar + '</div> </div>');

  $('body').append('<div class="fullscreenContent" style="top:' + marTop + 'px;height:' + marHeight + '%">' + fsData + '</div>');
  resizeWebContent(res);
  $(window).resize(function(){
    resizeWebContent(res);
  });
}
// helper function
function resizeWebContent(num) {
  var h = $(window).height();
  $("#webframe").css('height', h - num);
}

// remove web content panel
function destroyWebContent() {
  $("#webControls").remove();
  $(".fullscreenContent").remove();
  $("#mainContent").show();
}