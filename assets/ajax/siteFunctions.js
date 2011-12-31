// cc lightbox
function openBox(boxURL, boxWidth, shadow) {
	var boxMargin = boxWidth/2 + 6;
	var append = "";
	boxMargin = -boxMargin;
	var mTop = $(window).scrollTop();
	$("#dialogBox").width(boxWidth);
	$("#dialogBox").margin({left: boxMargin});
	$("#dialogBox").margin({top: mTop});
	if (shadow == 1) { 
		append = ", #blackbox";
	} else {
		append = ", #clearbox";
	}
	$("#dialogBox"+append).fadeIn(300);
	$("#dialogBox").html('<div style="text-align:center; font-size:16px; font-weight:bolder;margin-top:5px;color:#666"><img src="/app/core/site_img/loading.gif" /></a></div><div style="float:right; margin-bottom:5px"><a href="#" onClick="closeBox();" class="button"><img src="/app/core/site_img/gen/cross.png" />Cancel</a></div>').fadeIn(200);
	$.ajax({
        type: "GET",
        url: boxURL,
        success: function(conf) {
               $("#dialogBox").html(conf).fadeIn(200);
        }

        });    
}

function closeBox() { 
	$("#dialogBox").fadeOut(300);
	$("#clearbox, #blackbox").fadeOut(300);
	setTimeout(function() {$("#dialogBox").html("")} , 300);
	
}


function openEmbed(conURL) {
	$.ajax({
        type: "GET",
        url: conURL,
        success: function(conf) {
              $("#blackbox").html('<div style="margin-top:50px"><center>'+conf+'</center><br /><center><span style="font-size:16px; color:fff; font-weight:bold"><a onClick="closeEmbed()">Close This Window</a></span></center></div>');
        }

        }); 
	$("#blackbox").fadeIn(300);
}


function closeEmbed() { 
	$("#blackbox").fadeOut(300);
	setTimeout(function() {$("#blackbox").html("")} , 300);

}

function receiveCCMessage(data) { 
	if (data['type'] == 1) {
		var message = data['text'].replace(/_dq_/g, '"');
		$('#growlNotify').jGrowl(message);
		addNotification();
		
	} else if (data['type'] == 2) {
		addMsgCount();

                   } else if (data['type'] == 3) {
                        var message = data['text'].replace(/_dq_/g, '"');
                        openBox('/app/core/ajax/barjax/alert.cc?data=' + escape(message), 400, 1);

	} else if (data['type'] == 4) {
                        var message = data['text'].replace(/_dq_/g, '"');
                        openBox('/app/core/ajax/barjax/forceQuit.cc?data=' + escape(message), 400, 1);
	
                   } else if (data['type'] == 5) {
                         activateLL();

	}
	
}




function addNotification() {
	$('#notCount').html(parseInt($('#notCount').html()) + 1);
	$('#notCount').removeClass('noNot');
	$('#notCount').addClass('hasNot');
}



function addMsgCount() {
	$('#msgCount').html(parseInt($('#msgCount').html()) + 1);
	$('#msgCount').removeClass('noMsg');
	$('#msgCount').addClass('hasMsg');
}




function clearNotifications() {
	$('#notCount').html(0);
	$('#notCount').removeClass('hasNot');
	$('#notCount').addClass('noNot');
	$("#notiBar").html('<br /><center><img src="/app/core/site_img/loading.gif"/></center><br />');
	$.ajax({
        type: "GET",
        url: 'core/ajax/notistream/notiBar.cc',
        success: function(conf) {
             $("#notiBar").html(conf);
             $("#alertpanel").adjustPanel();
        }

        }); 
        
        
     
        
}


function activateLL() {
if ($("#livelecture").is(':hidden')) {
temp = 1;
} else {
temp = 2;
}

$("#livelecture").fadeIn(50).animate({left:"-=10px"},50).animate({left:"+=10px"},50).animate({left:"-=10px"},50)
.animate({left:"+=10px"},50).animate({left:"-=10px"},50).animate({left:"+=10px"},50);

if (temp == 1) {
animateLL();
} else {
loadLLCpanel();
}
}


// if we have active livelectures, animate
function animateLL() {
$("#livelecture").animate({backgroundPosition:"0 -0px"},800).animate({backgroundPosition:"0 -14px"},800);
setTimeout("animateLL()",1600);
}





// initialize the helper wizard
function initWiz(step) {
  $(".wizShade").show();
	$.ajax({
        type: "GET",
        url: "core/ajax/barjax/wizard.cc?step=" + step + "&p=" + escape(location.href),
        success: function(data) {
               $(".wizShade").hide();
               $("#wizfill").html(data);
               tempEr();
        }

    });
}



// initialize the helper wizard
function endWiz() {
$("#wizthing").remove();
$("#wizpnel").remove();
guider.hideAll();
	guider.createGuider({
      attachTo: "#helper",
      buttons: [{name: "Close"}],
      description: "You can click the blue icon below to begin an interactive tutorial of the page you are on!",
      id: "n",
      next: "x",
      position: 12,
      title: "If you ever need tutorials again..."
    }).show();
	$.ajax({
        type: "GET",
        url: "core/ajax/barjax/wizard.cc?c=1",
        success: function(data) {
               $("#wizbox").html(data);
        }

    });
}



function tempEr() {
    if (!$("#wizpnel").is(":visible")) {
        $("#wizpnel").show().animate({right:"+=360px"},500);
        $("#wizthing").animate({right:"+=360px"},500);

    } else {
        $("#wizpnel").animate({right:"-=360px"},500);
        $("#wizthing").show().animate({right:"-=360px"},500);
        setTimeout("$('#wizpnel').hide();",500);
    }
}

//  Inspired by Rails' reverse_merge!, reverse_merge will attempt to combine
//  options and defaults, using defaults when no value is set in 'options', and
//  using the value in options otherwise
var reverse_merge = function(options,defaults)
{
	for(var key in defaults)
	{
		if(options[key] == undefined)
			options[key] = defaults[key]
	}
}