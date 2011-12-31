$(document).ready(function(){

	//Adjust panel height
	$.fn.adjustPanel = function(){ 
		$(this).find("ul, .subpanel").css({ 'height' : 'auto'}); //Reset subpanel and ul height
		
		var windowHeight = $(window).height(); //Get the height of the browser viewport
		var panelsub = $(this).find(".subpanel").height(); //Get the height of subpanel	
		var panelAdjust = windowHeight - 100; //Viewport height - 100px (Sets max height of subpanel)
		var ulAdjust =  panelAdjust - 25; //Calculate ul size after adjusting sub-panel (27px is the height of the base panel)
		
		if ( panelsub >= panelAdjust ) {	 //If subpanel is taller than max height...
			$(this).find(".subpanel").css({ 'height' : panelAdjust }); //Adjust subpanel to max height
			$(this).find("ul").css({ 'height' : ulAdjust}); //Adjust subpanel ul to new size
		}
		else if ( panelsub < panelAdjust ) { //If subpanel is smaller than max height...
			$(this).find("ul").css({ 'height' : 'auto'}); //Set subpanel ul to auto (default size)
		}
	};
	
	//Execute function on load
	$("#chatpanel").adjustPanel(); //Run the adjustPanel function on #chatpanel
	$("#alertpanel").adjustPanel(); //Run the adjustPanel function on #alertpanel
	$("#livelecture").adjustPanel(); //Run the adjustPanel function on #alertpanel
	$("#calculator").adjustPanel(); //Run the adjustPanel function on #alertpanel
	
	//Each time the viewport is adjusted/resized, execute the function
	$(window).resize(function () { 
		$("#chatpanel").adjustPanel();
		$("#alertpanel").adjustPanel();
		$("#livelecture").adjustPanel();
		$("#calculator").adjustPanel();
	});
	
	//Click event on alertpanel
	$("#alertpanel a:first").click(function() { //If clicked on the first link of #chatpanel and #alertpanel...
	
	
	
		if($(this).next(".subpanel").is(':visible')){ //If subpanel is already active...
			$(this).next(".subpanel").hide(); //Hide active subpanel
			$("#footpanel li a").removeClass('active'); //Remove active class on the subpanel trigger
		}
		else { //if subpanel is not active...
			$(".subpanel").hide(); //Hide all subpanels
			$(this).next(".subpanel").toggle(); //Toggle the subpanel to make active
			$("#footpanel li a").removeClass('active'); //Remove active class on all subpanel trigger
			$(this).toggleClass('active'); //Toggle the active class on the subpanel trigger
			clearNotifications();
		}
		return false; //Prevent browser jump to link anchor
	});
	
	
	
	//Click event on calc
	$("#calculator a:first").click(function() { //If clicked on the first link of #chatpanel and #alertpanel...
	
	
	
		if($(this).next(".subpanel").is(':visible')){ //If subpanel is already active...
			$(this).next(".subpanel").hide(); //Hide active subpanel
			$("#footpanel li a").removeClass('active'); //Remove active class on the subpanel trigger
		}
		else { //if subpanel is not active...
			$(".subpanel").hide(); //Hide all subpanels
			$(this).next(".subpanel").toggle(); //Toggle the subpanel to make active
			$("#footpanel li a").removeClass('active'); //Remove active class on all subpanel trigger
			$(this).toggleClass('active'); //Toggle the active class on the subpanel trigger
		}
		return false; //Prevent browser jump to link anchor
	});


	//Click event on calc
	$("#feedback a:first").click(function() { //If clicked on the first link of #chatpanel and #alertpanel...



		if($(this).next(".subpanel").is(':visible')){ //If subpanel is already active...
			$(this).next(".subpanel").hide(); //Hide active subpanel
			$("#footpanel li a").removeClass('active'); //Remove active class on the subpanel trigger
		}
		else { //if subpanel is not active...
			$(".subpanel").hide(); //Hide all subpanels
			$(this).next(".subpanel").toggle(); //Toggle the subpanel to make active
			$("#footpanel li a").removeClass('active'); //Remove active class on all subpanel trigger
			$(this).toggleClass('active'); //Toggle the active class on the subpanel trigger
		}
		return false; //Prevent browser jump to link anchor
	});



		//Click event on calc
	$("#helper a:first").click(function() { //If clicked on the first link of #chatpanel and #alertpanel...



		if($(this).next(".subpanel").is(':visible')){ //If subpanel is already active...
			$(this).next(".subpanel").hide(); //Hide active subpanel
			$("#footpanel li a").removeClass('active'); //Remove active class on the subpanel trigger
		}
		else { //if subpanel is not active...
			$(".subpanel").hide(); //Hide all subpanels
			$(this).next(".subpanel").toggle(); //Toggle the subpanel to make active
			$("#footpanel li a").removeClass('active'); //Remove active class on all subpanel trigger
			$(this).toggleClass('active'); //Toggle the active class on the subpanel trigger
			loadHelperPanel();
		}
		return false; //Prevent browser jump to link anchor
	});



//Click event on ll
	$("#livelecture a:first").click(function() { //If clicked on the first link of #chatpanel and #alertpanel...



		if($(this).next(".subpanel").is(':visible')){ //If subpanel is already active...
			$(this).next(".subpanel").hide(); //Hide active subpanel
			$("#footpanel li a").removeClass('active'); //Remove active class on the subpanel trigger
		}
		else { //if subpanel is not active...
			$(".subpanel").hide(); //Hide all subpanels
			$(this).next(".subpanel").toggle(); //Toggle the subpanel to make active
			$("#footpanel li a").removeClass('active'); //Remove active class on all subpanel trigger
			$(this).toggleClass('active'); //Toggle the active class on the subpanel trigger
                                                        loadLLCpanel();
		}
		return false; //Prevent browser jump to link anchor
	});
	
	
	//Click event on calc
	$("#img_search a:first").click(function() { //If clicked on the first link of #chatpanel and #alertpanel...
	
	
	
		if($(this).next(".subpanel").is(':visible')){ //If subpanel is already active...
			$(this).next(".subpanel").hide(); //Hide active subpanel
			$("#footpanel li a").removeClass('active'); //Remove active class on the subpanel trigger
		}
		else { //if subpanel is not active...
			$(".subpanel").hide(); //Hide all subpanels
			$(this).next(".subpanel").toggle(); //Toggle the subpanel to make active
			$("#footpanel li a").removeClass('active'); //Remove active class on all subpanel trigger
			$(this).toggleClass('active'); //Toggle the active class on the subpanel trigger
		}
		return false; //Prevent browser jump to link anchor
	});


	
	//Click event outside of subpanel
	$(document).click(function() { //Click anywhere and...
		$(".subpanel").hide(); //hide subpanel
		$("#footpanel li a").removeClass('active'); //remove active class on subpanel trigger
	});
	$('.subpanel ul').click(function(e) { 
		e.stopPropagation(); //Prevents the subpanel ul from closing on click
	});
	
	//Delete icons on Alert Panel
	//$("#notiBar li").hover(function() {
	//	$(this).find("a.delete").css({'visibility': 'visible'}); //Show delete icon on hover
	//},function() {
	//	$(this).find("a.delete").css({'visibility': 'hidden'}); //Hide delete icon on hover out
	//});
 
 
 
 // image search function
 $("#imgSearcher").submit(function(){
    	searchImg();
        return false;            

    });
    
    function searchImg() {
	$("#imgSearchResults").html('<br /><br /><br /><br /><br /><center><img src="core/site_img/sBoxLoad.gif" /></center>');
        dataString = $("#imgSearcher").serialize();

        $.ajax({
        type: "GET",
        url: "core/ajax/barjax/imgsrch.cc",
        data: dataString,
        success: function(data) {

               $("#imgSearchResults").html(data);

        }

        });

}

 $("#feedbackForm").submit(function(){
    dataString = $("#feedbackForm").serialize();
$("#feedbackDiv").html('<br /><center><img src="app/core/site_img/loading.gif" /></center><br />');
        $.ajax({
        type: "GET",
        url: "core/ajax/barjax/contact.cc",
        data: dataString,
        success: function(data) {

               $("#feedbackDiv").html(data);

        }

        });
return false;
    });
	
});

function loadLLCpanel() {
$("#LLCcontent").html('<br /><br /><center><img src="core/site_img/sBoxLoad.gif" /></center><br />');

        $.ajax({
        type: "GET",
        url: "core/ajax/barjax/llc.cc",
        success: function(data) {

               $("#LLCcontent").html(data);

        }

        });

}


function loadHelperPanel() {
$("#helperDiv").html('<br /><br /><center><img src="core/site_img/sBoxLoad.gif" /></center><br />');

        $.ajax({
        type: "GET",
        url: "core/ajax/barjax/helper.cc?p=" + escape(location.href),
        success: function(data) {

               $("#helperDiv").html(data);

        }

        });

}


function initTutorial(pageName) {
$("#helperDiv").html('<br /><br /><center><img src="core/site_img/sBoxLoad.gif" /></center><br />');

        $.ajax({
        type: "GET",
        url: "core/ajax/barjax/helper.cc?ep=" + pageName,
        success: function(data) {

               $("#helperDiv").html($("#helperDiv").html() + data);

        }

        });

}