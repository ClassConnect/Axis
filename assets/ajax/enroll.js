// load the webpage and fade in  
 $(document).ready(function(){  
 $("#lightbox, #lightbox-panel").fadeIn(300);
$( "#progressbar" ).progressbar({
			value: 33
		});
 })  
 
 // function for submitting the signup form
 $(function(){
    $("#signup-form").submit(function(){
        dataString = $("#signup-form").serialize();

$("#cont-right").html('<br /><br /><br /><br /><br /><br /><br /><br /><center><img src="/app/core/site_img/load.gif" /></center>');
        $.ajax({
        type: "POST",
        url: "enroll.cc?s=1",
        data: dataString,
        success: function(data) {
        		if (data == 1) {
        			verifyCode(dataString);
        		} else {
               $("#cont-right").html(data);
            }

        }

        });

        return false;            

    });
});


// display the settings page after signup success
function verifyCode(logData) {
$("#main-block").html('<br /><br /><center><img src="/app/core/site_img/load.gif" /></center><br /><br />');
$( "#progressbar" ).progressbar({
			value: 66
		});
$.ajax({
   type: "POST",
   url: "enroll.cc?s=2",
   data: logData,
   success: function(msg){
   	$("#main-block").hide();
   	$("#panel-title").hide();
     $("#main-block").html(msg).fadeIn(200);
     $("#panel-title").html("<img src=\"core/site_img/main/l_arrow.png\" style=\"padding-left:10px; padding-right:10px\" />Enter Class Code").fadeIn(200);
   }
 });

    
}


// function for submitting the school email signup form
function submitCode() {
        dataString = $("#class-code-signup").serialize();

        $.ajax({
        type: "POST",
        url: "enroll.cc?s=2",
        data: dataString,
        success: function(rsp) {
        		if (rsp == 1) {
        			allowLogin(1);
        		} else {
               $("#failer").html(rsp);
            }

        }

        });
}


// display the settings page after signup success
function allowLogin() {
  $("#main-block").html('<br /><br /><center><img src="/app/core/site_img/load.gif" /></center><br /><br />');
$( "#progressbar" ).progressbar({
			value: 100
		});
$.ajax({
   type: "POST",
   url: "enroll.cc?s=3",
   success: function(msg){
   	$("#main-block").hide();
   	$("#panel-title").hide();
     $("#main-block").html(msg).fadeIn(200);
     $("#panel-title").html("<img src=\"core/site_img/main/l_arrow.png\" style=\"padding-left:10px; padding-right:10px\" /> Signup Complete!").fadeIn(200);
   }
 });

    
}