jQuery(document).ready(function(){

$("#login-catcher").click(function() {
if ($(this).hasClass('addLoginActive')) {
$(this).removeClass('addLoginActive');
$(".logBox").hide();
} else {
$(this).addClass('addLoginActive');
$(".logBox").show();
}

});


        if ($.browser.mozilla) {
			$("div#header-slider-inner").css({margin: '51px 0 0 1px'});
		 }
   
        // cycle
		if(jQuery('.sidebar-testimonials').length > 0){	
			avio_cycle();
		}
				
        // faq
		avio_faq();
		
		// portofolio
		avio_portfolio();	
			  
		// dropdown menu
		avio_dd_menu();

		// display your tweets info @ http://code.google.com/p/twitterjs/ or http://remysharp.com/2007/05/18/add-twitter-to-your-blog-step-by-step/
		avio_tweets(4,"ClassConnectInc");// 1 = number of tweets 2 = username
		
		// basic jqyuery animate
	    avio_animate();
		
		// login
		avio_login();
		
        // google maps info @ http://googlemaps.mayzes.org/
		avio_gmaps();
		
		// animated scroll to top	
		avio_scroll_top();
		
		if(jQuery('.50Slider').length > 0){
			// jQuery cycle info @ http://jquery.malsup.com/cycle/
			avio_50_slider();
		}
		
		if(jQuery('#piecemaker').length > 0){
			// piecemaker slider info @ http://active.tutsplus.com/freebies/exclusive/exclusive-freebie-the-piecemaker-2/
			avio_piecemaker();
		}
				
		if(jQuery('.nivoSlider').length > 0){
			// nivo slider, more info @ http://nivo.dev7studios.com/
			avio_nivo_slider();
		}
		
		if(jQuery('.coinSlider').length > 0){
			// coin slider info @ http://workshop.rs/projects/coin-slider/
			avio_coin_slider();
		}
		
		// lightbox info @ http://www.pirolab.it/pirobox/
	    avio_lightbox();
		
		// basic jquery equal height, used for the content and sidebar
	    //avio_equalHeight(jQuery("#content-inner-page, #sidebar"));
		
		// preload images 
		$(".preload_all, .kwicksSlider").img_preloader ({delay:200});
		

}); 

		function avio_piecemaker(){		
			var flashvars = {};
			flashvars.cssSource = "css/piecemaker.css";
			flashvars.xmlSource = "piecemaker.xml";
			  
			var params = {};
			params.play = "true";
			params.menu = "false";
			params.scale = "showall";
			params.wmode = "transparent";
			params.allowfullscreen = "true";
			params.allowscriptaccess = "always";
			params.allownetworking = "all";
			// 960 = width, 500 = height
			swfobject.embedSWF('piecemaker.swf', 'piecemaker', '960', '480', '1', null, flashvars, params, null);	  
		}
		//---//
		function avio_coin_slider(){
			// delay the loading of the images
			jQuery('#header-slider-inner').hide(1);
			setTimeout(function(){
				jQuery('#header-slider-inner, .cs-buttons').fadeIn(400);
			}, 500);
						
			$('.coinSlider').coinslider({
				width: 958, // width of slider panel
				height: 398, // height of slider panel
				spw: 7, // squares per width
				sph: 5, // squares per height
				delay: 3000, // delay between images in ms
				sDelay: 30, // delay beetwen squares in ms
				opacity: 0.7, // opacity of title and navigation
				titleSpeed: 500, // speed of title appereance in ms
				effect: 'random', // random, swirl, rain, straight
				navigation: true, // prev next and buttons
				links : true, // show images as links 
				hoverPause: true // pause on hover				
			});
		}
		//---//
        function avio_50_slider() {
			// delay the loading of the images
			jQuery('.slide50 img').hide(1);
			setTimeout(function(){
				jQuery('.slide50 img').fadeIn(400);
			}, 500);			
			jQuery('.50Slider').cycle({
				fx:     'fade', // see more options at http://malsup.com/jquery/cycle/options.html
				timeout: 5000, // time of the rotation
				speed:   200, // speed of the rotation
				pause:   true,
				cleartype:     !$.support.opacity,
				cleartypeNoBg: true ,
				pager:  '#nav50'
			});			
		}		
		//---//
        function avio_cycle() {
			jQuery('.sidebar-testimonials').cycle({
				fx: 'fade', // see more options at http://malsup.com/jquery/cycle/options.html
				speed:    500, // time of the rotation
    			timeout:  5000 // speed of the rotation
			});			
		}
		//---//

		function avio_portfolio(){
			jQuery(".jcap-caption").click(function(){
if ($(this).hasClass("noHov") != true) {


			    img_wdt = jQuery('.jcap').width() -40;
			    img_hgt = jQuery('.jcap').height() -40;
				jQuery(this).css({backgroundImage: "none"})
				.stop(true, true).animate({height: img_hgt, width: img_wdt}, 300)
				.children('div').stop(true, true).delay(200).fadeIn(1);
$(this).addClass('noHov');
} else {
	$(this).removeClass("noHov");
}
			}, function(){
				
			});
		}
function closePort(obj) {
jQuery(obj).parent().parent().parent().addClass('noHov');
jQuery(obj).parent().parent().parent().delay(100).css({backgroundImage: ""})
.animate({height: '10px', width: '10px'}, 300)
.children('div').hide(10);
}
		//---//
		function avio_faq() {
			jQuery("a.faqa").toggle(function(){
				jQuery(this).next('p').slideDown(100);
				jQuery(this).prev('span').removeClass('plus').addClass('min');
			},function(){
				jQuery(this).next('p').slideUp(100);
				jQuery(this).prev('span').removeClass('min').addClass('plus');				
			});
		}
		//---//
        function avio_equalHeight(group) {
			tallest = 0;
			group.each(function() {
				thisHeight = jQuery(this).height();
				if(thisHeight > tallest) {
					tallest = thisHeight;
				}
			});
			group.height(tallest);
		}
		//---//
		function avio_gmaps(){			
			$('#sidebar-maps').googleMaps({
				latitude: 42.351505,
                longitude: -71.094455,
				markers: {
					latitude: 	42.351505,
					longitude: -71.094455,
					draggable: true,
					info: {
                		layer: '#ginfo'
            		}
				}
			});
		}		
		//---//
		function avio_dd_menu(){	
            //dropdown menu
			jQuery("ul#header-menu li").hover(function(){
				jQuery(this).stop().find('ul:first').css({visibility: "visible",display: "none"}).animate({height: 'toggle'}, 200);
			}, function(){
				jQuery('ul:first',this).css('visibility', 'hidden');
			});
			
			// add arrow first lvl
		    jQuery('.second-lvl').parent("li").addClass('arrowdown');

			// add arrow second lvl
		    jQuery('.third-lvl').parent("li").addClass('arrowright');
						
			// add an active class to the parent li
			jQuery(".second-lvl").hover(function(){
				jQuery(this).parent("li").addClass("active");							
			}, function(){
				jQuery(this).parent("li").removeClass("active");	
			});
		}
		//---//
		function avio_animate(){
			// read more links 
			jQuery(".content-3col .readmore").hover(function(){
				jQuery(this).stop().animate({width: "156px"}, 150);				   
			},function(){ 
				jQuery(this).stop().animate({width: "62px"}, 150);	
			});
		}
        //---//
		function avio_login(){
			// center the box
			jQuery.fn.center = function (absolute) {
				return this.each(function () {
					var t = jQuery(this);
					t.css({
						position:    absolute ? 'absolute' : 'fixed', 
						left:        '50%', 
						top:        '50%'
					}).css({
						marginLeft:    '-' + (t.outerWidth() / 2) + 'px', 
						marginTop:    '-' + (t.outerHeight() / 2) + 'px'
					});
					if (absolute) {
						t.css({
							marginTop:    parseInt(t.css('marginTop'), 10) + jQuery(window).scrollTop(), 
							marginLeft:    parseInt(t.css('marginLeft'), 10) + jQuery(window).scrollLeft()
						});
					}
				});
			};
			jQuery("#login-box").center();
			// slide up/down login panel
			jQuery("#login, .login-top").click(function(){
				jQuery('body').append('<div id="overlay" />');
				jQuery('#overlay').fadeTo('fast', 0.3);
				jQuery("#login-box").stop().fadeIn();
			});
			jQuery("#login-close").click(function(){
				jQuery('#overlay').fadeOut();
				jQuery("#login-box").fadeOut();
			});			
		}
		//---//
		function avio_nivo_slider(){
			// delay the loading of the images
			jQuery('#header-slider-inner').hide(1);
			setTimeout(function(){
            	jQuery('#header-slider-inner').fadeIn(400);
			}, 500);
			
			// nivo slider
			jQuery('#header-slider-inner').nivoSlider({
				effect:'random',
				slices:15,
				animSpeed:600,
				pauseTime:6000,
				directionNav:true, //Next & Prev
				directionNavHide:true, //Only show on hover
				controlNav:true, //1,2,3...
				keyboardNav:true, //Use left & right arrows
				pauseOnHover:true, //Stop animation while hovering
				manualAdvance:false, //Force manual transitions
				captionOpacity:0.8, //Universal caption opacity
				beforeChange: function(){},
				afterChange: function(){},
				slideshowEnd: function(){} //Triggers after all slides have been shown
			});
		}
		//---//
		function avio_lightbox(){	
			//basic lightbox
			jQuery().piroBox({
				my_speed: 300, //animation speed
				bg_alpha: 0.3, //background opacity
				slideShow : true, // true == slideshow on, false == slideshow off
				slideSpeed : 4, //slideshow
				close_all : '.piro_close, .piro_overlay' // add class .piro_overlay(with comma)if you want overlay click close piroBox
			});
			
			// fade the lightboxes when hoverd(adds an tip)
			jQuery(".pirobox, .pirobox_portfolio, .pirobox_gallery").hover(function(){
				jQuery(this).find("img").stop().fadeTo("fast",0.7);
				jQuery(this).append('<span class="lightbox-tip" ></span>');
		    }, function(){
				jQuery(this).find("img").stop().fadeTo("fast",1.0);
			    jQuery("span.lightbox-tip").fadeOut(200);
			});
			
			// removing the loader background
			// this willprevent the showing of the img when hoverd due the opacity of the images
			jQuery('div.gallery-box, a.blog-img, a.portfolio-img, .imgwrap-theme').hover(function() {
				jQuery(this).css({backgroundImage: "none"});	
			});
			
			// fading in the shadows
			// IE dont like fading stuff, so thsi has an IE fallback
			 if(jQuery.browser.msie){
			 	jQuery(".shadow-140, .shadow-225, .shadow-240, .shadow-300, .shadow-460, .shadow-450, .shadow-620, .shadow-960").show(1);	 
			 }else{
				jQuery(".shadow-140, .shadow-225, .shadow-240, .shadow-300, .shadow-460, .shadow-450, .shadow-620, .shadow-960").delay(1000).fadeIn(1000);	 
			 }			 
		}
		//---//
		function avio_tweets(tws, username){
			getTwitters('sidebar-tweets', {
				id: username, 
				clearContents: false, // leave the original message in place
				count: tws,
				timeout: 200,
				withFriends: true,
				ignoreReplies: false,
				newwindow: true,
				template: '<a href="http://twitter.com/%user_screen_name%">%user_screen_name%</a> said: "%text%" - <span>%time%</span>'

			});
		}
		//---//
		function avio_scroll_top(){
			jQuery('#top').click(function() {
            	jQuery('html, body').animate({scrollTop:0}, 'slow');
            });
        }
	  	//---//	
		function avio_switch_view(){
			jQuery("a.switch_thumb").toggle(function(){
				jQuery(this).addClass("swap");
				jQuery("ul.display").fadeOut("fast", function() {
					jQuery(this).fadeIn("fast").removeClass("display").addClass("thumb_view");
				});
			}, function () {
				jQuery(this).removeClass("swap");
				jQuery("ul.thumb_view").fadeOut("fast", function() {
					jQuery(this).fadeIn("fast").addClass("display").removeClass("thumb_view");
				});
			}); 
		}

function openBox(objML, boxWidth, shadow) {
	var boxMargin = boxWidth/2 + 6;
	var append = "";
	boxMargin = -boxMargin;
	var mTop = $(window).scrollTop();
	$("#dialogBox").width(boxWidth);
	$("#dialogBox").margin({left: boxMargin})
	$("#dialogBox").margin({top: mTop})
	if (shadow == 1) {
		append = ", #blackbox";
	} else {
		append = ", #clearbox";
	}
	$("#dialogBox"+append).fadeIn(300);
	$("#dialogBox").html('<div style="text-align:center; font-size:16px; font-weight:bolder;margin-top:5px;color:#666"><img src="/app/core/site_img/loading.gif" /></a></div><div style="float:right; margin-bottom:5px"><a href="#" onClick="closeBox();" class="button"><img src="/app/core/site_img/gen/cross.png" />Cancel</a></div>').fadeIn(200);
	$("#dialogBox").html(objML).fadeIn(200);
}

function closeBox() {
	$("#dialogBox").fadeOut(300);
	$("#clearbox, #blackbox").fadeOut(300);
	setTimeout(function() {$("#dialogBox").html("")} , 300);

}


 $(function(){
    $("#footer-form").submit(function(){
        dataString = $("#footer-form").serialize();
$("#footer-form").html("<br /><br /><center><span style='color:#ccc'>Sending information to server...</span></center>");
        $.ajax({
        type: "POST",
        url: "/callUs.cc",
        data: dataString,
        success: function(data) {
        	$("#footer-form").html("<br /><br /><center><span style='color:#ccc;font-size:14px'>We'll give you a call as soon as possible!</span></center>");

        }

        });

        return false;

    });
});