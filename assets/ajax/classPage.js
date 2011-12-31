//<![CDATA[
		var crumbSplit = "<img src=\"core/site_img/main/l_arrow.png\" />";
		var currentApp = 0;
		$(document).ready(function(){   
		var pageSplit = self.document.location.hash.search("_");
		if (pageSplit == -1) {
			var retID = self.document.location.hash.substring(1);
			var getPage = "";
		} else {
			var retID = self.document.location.hash.substring(1, pageSplit);
			var getPage = self.document.location.hash.substring(pageSplit + 1, self.document.location.hash.length);
		}

		if (retID == 0) {
			retID = 1;
		}
			currentApp = retID;
			selectApp(retID, getPage);
				
});
		
		// select our application
		function selectApp(appID, getPage) {
				//document.getElementById("app" + remApp).className = "";
				document.getElementById("app" + currentApp).className = "";
				document.getElementById("app" + appID).className += " active_item";
				currentApp = appID;
				changeDivContent("appLoad.cc?cid=" + classID + "&id=" + appID +  "&appType=" + appType + "&page=" + cleanData(getPage));
		}
		
		// changing pages within an application
		function changePage(appID, getPage) {
			changeDivContent("appLoad.cc?cid=" + classID + "&id=" + appID + "&appType=" + appType + "&page=" + cleanData(getPage));
		}
		
		
		function changeDivContent(page) {
			$("#navcrumbs").html($("#navcrumbs").html() + " <img src=\"core/site_img/loading.gif\" style=\"height:18px\" />");
			
			// send ajax request to retrieve page
			$.ajax({
        type: "GET",
        url: page,
        success: function(jaxGet) {
        	var getData = jaxGet;
        	getData = getData.replace(/{className}/g, className);
        	var openTag = getData.search("<cc:crumbs>");
						var endTag = getData.search("</cc:crumbs>");
						var crumbRaw = getData.substring(openTag + 11,endTag);
						var mainContent = getData.substring(endTag+12,getData.length);
						var crumbClean = crumbRaw.replace(/{crumbSplit}/g," " + crumbSplit + " ");
						
						// if there is no crumb nav detected
						if (endTag == -1) {
							crumbClean = "Undefined";
							mainContent = getData;
						}
						
						// Display our app
                     swapCrumbs(crumbClean);
                     
							$("#class_main").html('<div id="appHandler">' + mainContent + '</div>');
							
							apiEventHandler();
        	
        }

        });		
			
			
			
		}
		
		
// nav crumbs function
function swapCrumbs(crumbStr) {
	if (currentApp == 1) {
		var crumbTotal = crumbStr;
	} else {
		var crumbTotal = "<a href=\"#1\" onclick=\"selectApp(1)\">" + className + "</a> " + crumbSplit + " " + crumbStr;
	}
	$("#navcrumbs").html(crumbTotal);
}





// api event handler for clicks and form submissions
function apiEventHandler() {
	

// handle href clicks
							$(document).ready(function(){   
								$("#appHandler a, #navcrumbs a").click(function() {
									var URLlocation = $(this).attr('href');
									
								
									// ie backwards compatibility
									if (/msie/i.test(navigator.userAgent) && !/opera/i.test(navigator.userAgent) == true) {
										var position = URLlocation.indexOf('/app/');
										if (position != -1) {
											URLlocation = URLlocation.substr(position + 5, URLlocation.length);
										}
									}

									var URLtarget = $(this).attr('target');
									
									// if we want to open another window
									if (URLtarget == '_blank') {
										// do nothing
											
									} else {
									
									// if we want a dialog box (openBox)
									if (URLtarget == 'dialog') {
										var dialogWidth = $(this).attr('width');
										var shadow = $(this).attr('shadow');
										if (shadow != 1) {
											shadow = 0;
										}
										openBox("appLoad.cc?cid=" + classID + "&id=" + currentApp + "&appType=" + appType + "&page=" + cleanData(URLlocation), dialogWidth, shadow);
										return false;
										
									// if we want a dialog box (openBox)
									} else if (URLtarget == 'external') {
										window.location = 'class-fs.cc?cid=' + classID + '&id=' + currentApp + '&page=' + URLlocation;
										return false;
										
									// if we want an embed box
									} else if (URLtarget == 'embed') {
										openEmbed('class-fs.cc?cid=' + classID + '&id=' + currentApp + '&page=' + URLlocation);
										return false;
										
									// regular app page swap
									} else {
										// make sure it's not a javascript command
										if (URLlocation.indexOf('javascript:', 0) == -1) {
											if (URLlocation.indexOf('http://', 0) == -1 && URLlocation.indexOf('https://', 0) == -1) {
												changePage(currentApp, URLlocation);
												parent.location.hash = currentApp + '_' + URLlocation;
												} else {
													window.location = URLlocation
												}
										}
										return false;	
									}
									
									
									} // target blank else
								});
								
								// handle form submissions
								$("#appHandler form").submit(function(){
        							dataString = $(this).serialize();
        							
        							dataString = dataString.replace(/=/g,"(*)");
        							dataString = dataString.replace(/&/g,"[*]");
        							
        							action = $(this).attr("action").replace(/=/g,"(*)");
        							action = $(this).attr("action").replace(/&/g,"[*]");
        							
        							$.ajax({
        								type: "POST",
       								url: 'appPost.php?type=' + $(this).attr("method") + '&action=' + escape(action) + '&appID=' + currentApp + '&classID=' + classID +  '&appType=' + appType + '&data=' + escape(dataString),
       								success: function(data) {
       									// handler in here
        									var redirectTag = data.search("<cc:redirect>");
        									var inlineTag = data.search("<cc:inline>");
											
						
											// if redirect
											if (redirectTag >= 0) {
												var redirectEnd = data.search("</cc:redirect>");
												var redirURL = data.substring(redirectTag + 13,redirectEnd);
												
												changePage(currentApp, redirURL);
											// if inline	
											} else if (inlineTag >= 0) {
												var inlineEnd = data.search("</cc:inline>");
												var inlineContent = data.substring(inlineTag + 11,inlineEnd);
												$("#class_main").html($("#class_main").html() + inlineContent);
											// no tag, display contents
											} else {
												$("#class_main").html(data);
												
											}
											
											apiEventHandler();
										// success end
        								}
										//success end
										
        							});

        						return false;            

    						});
								
						// onload		
							});	
	
	
}





/*
* method - 'post' or 'get'
* action - URL you want to hit
* dataString - bullshit you want to send
*/
function postToAPI(method, action, appID, classID, dataString) {
	dataString = cleanData(dataString);
   
   action = cleanData(action);
   
   return 'appPost.php?type=' + method + '&action=' + escape(action) + '&appID=' + appID + '&classID=' + classID + '&appType=' + appType + '&data=' + escape(dataString);
}




function cleanData(urlStr) {
	if (urlStr != undefined) {
		urlStr = urlStr.replace(/=/g,"(*)");
   	urlStr = urlStr.replace(/&/g,"[*]");
   }
	return urlStr;
}



