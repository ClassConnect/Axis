//	Dear Scott Rice (www.rices.co),
//	Thank you so much for this beautiful piece of code.
//	Our nav bar thanks you too.
//	Sincerely,
//	Eric.

$(document).ready(function(){    
						   
  
    $("ul.topnav li div").hover(
    	//	In
    	function() {
    		$(this).find("ul.subnav").slideDown(20).show(); //Drop down the subnav on hover
    		$(this).find("a.getme").addClass("hoverhack");  
    	},
    	//	Out
    	function(){
            $(this).find("ul.subnav").slideUp(20); //When the mouse hovers out of the subnav, move it back up  
            $(this).find("a.getme").removeClass("hoverhack");
    	}
    );
});