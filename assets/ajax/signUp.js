// load the webpage and fade in  
 $(document).ready(function(){  
 $("#lightbox, #lightbox-panel").fadeIn(300);
 });
 
 // function for submitting the signup form
 $(function(){
    $("#signup-form").submit(function(){
        dataString = $("#signup-form").serialize();

        $("#cont-right").html('<br /><br /><br /><br /><br /><br /><br /><br /><center><img src="/app/core/site_img/load.gif" /></center>');
        $.ajax({
        type: "POST",
        url: "signup.cc?s=1",
        data: dataString,
        success: function(data) {
        		if (data == 1) {
        			window.location = '/app/home.cc';
        		} else {
               $("#cont-right").html(data);
            }

        }

        });

        return false;            

    });
});