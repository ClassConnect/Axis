// floating sidepanel
$(document).ready(function (){ 	
disableSelection(document.getElementById("barRighter"));	
disableSelection(document.getElementById("selectable1"));			
  var thisPage = $(this);
  var panel = $("#fboxLeft");
  var panelTop = panel.offset().top;
  var thisPageTop = 0;
  $(window).bind('scroll', function(){  
    thisPageTop = thisPage.scrollTop();
    if(thisPageTop > (panelTop - 10) && !panel.hasClass('floatingPanel')){
      panel.addClass('floatingPanel'); 
    }
    else if(thisPageTop <= (panelTop - 10) && panel.hasClass('floatingPanel')){ 
      panel.removeClass('floatingPanel');
    }
  });




}); 


function removeByValue(arr, val) {
    for(var i=0; i<arr.length; i++) {
        if(arr[i] == val) {
            arr.splice(i, 1);
            break;
        }
    }
}







function inArray(arr, val) {
    for(var i=0; i<arr.length; i++) {
        if(arr[i] == val) {
            return true;
        }
    }
    
    return false;
}







var allowRM = true;

var activeFiles = new Array();
var activeFolders = new Array();

function getCurrentFolder() {
	if (self.document.location.hash.substring(1) != 0) {
 		return self.document.location.hash.substring(1);
 	} else {
 		return 0;
 	} 
} // end get

 $(document).ready(function(){
 	var fid = getCurrentFolder();
 	updateFbox(fid);
 });

// update folders & crumbs
function updateFbox(dirID, opt) {
	 activeFiles.length = 0;
	 activeFolders.length = 0;
	 parent.location.hash = dirID;
	 $("#selectable1").html('<br /><br /><br /><center><img src="/app/core/site_img/load.gif" /></center>');
    getContent(dirID);
    if (opt != 1) {
    	$("#navCrumber").html($("#navCrumber").html() + '<img src="/app/core/site_img/uploader.gif" style="margin-left:15px"/>');
    	getCrumbs(dirID);
    }
    checkNumSelected();
}


// update folders & crumbs
function getContent(dirID) {
$.ajax({
   type: "GET",
   url: "filebox.cc?n=1&id=" + dirID,
   success: function(msg){
     $("#selectable1").html(msg);
   }
 });

    
}

// update folders & crumbs
function getCrumbs(dirID) {
$.ajax({
   type: "GET",
   url: "filebox.cc?n=2&id=" + dirID,
   success: function(msg){
     $("#boxCrumbs").html(msg);
   }
 });

    
}






function setMe(contType, id) {
// generate string for div
if (contType == 1) {
	var str = 'fo';
} else {
	var str = 'f';
}
var divName = str + id;


if ((contType == 1 && inArray(activeFolders, id)) || (contType == 2 && inArray(activeFiles, id))) {
	return false;
		
		
} else {
	$("#"+divName).addClass("ui-selected");
	if (contType == 1) {
		activeFolders[activeFolders.length + 1] = id;
	} else {
		activeFiles[activeFiles.length + 1] = id;
	}
	allowRM = false;
}

checkNumSelected();
//end
}







function unSetMe(contType, id) {
	// generate string for div
if (contType == 1) {
	var str = 'fo';
} else {
	var str = 'f';
}
var divName = str + id;


if ((contType == 1 && inArray(activeFolders, id)) || (contType == 2 && inArray(activeFiles, id))) {
	
	if (allowRM == true) {
		
	$("#"+divName).removeClass("ui-selected");
	if (contType == 1) {
		removeByValue(activeFolders, id);
	} else {
		removeByValue(activeFiles, id);
	}
	
	} else {
		allowRM = true;
	}	
		
} else {
	return false;
}


checkNumSelected();
// end
}



function addFolder() {
	var fid = getCurrentFolder();
	openBox('filebox.cc?n=3&fid=' + fid, 350);
	
}


function addContent(cType) {
	var fid = getCurrentFolder();
	var width = 100;
	
	if (cType == 1) {
		openBox('filebox.cc?n=7&cType=' + cType + '&fid=' + fid, 300);
		
	} else if (cType == 2) {
		openBox('filebox.cc?n=7&cType=' + cType + '&fid=' + fid, 250);
		
	} else if (cType == 3) {
		openBox('filebox.cc?n=7&cType=' + cType + '&fid=' + fid, 250);
	
	} else if (cType == 4) {
		openBox('filebox.cc?n=7&cType=' + cType + '&fid=' + fid, 250);

	} else if (cType == 8) {
		openBox('filebox.cc?n=7&cType=' + cType + '&fid=' + fid, 250);
		
	}
		
	
}



function updateContent(cType, conID) {
	
	var width = 100;
	
	if (cType == 1) {
		openBox('filebox.cc?n=8&content_id=' + conID, 250);
		
	} else if (cType == 2) {
		openBox('filebox.cc?n=8&content_id=' + conID, 250);
		
	} else if (cType == 3) {
		openBox('filebox.cc?n=8&content_id=' + conID, 250);
		
	} else if (cType == 4) {
		openBox('filebox.cc?n=8&content_id=' + conID, 250);
		
	} else if (cType == 5) {
		openBox('filebox.cc?n=8&content_id=' + conID, 250);
		
	} else {
                                    openBox('filebox.cc?n=8&content_id=' + conID, 250);
                   }
	
}



function viewContent(cType, contentID) {
	var width = 100;
	
	if (cType == 1) {
		openBox('filebox.cc?n=10&content_id=' + contentID, 300);
		
	} else if (cType == 2) {
		window.location = 'filebox.cc?n=10&content_id=' + contentID;
		
	} else if (cType == 3) {
		openBox('filebox.cc?n=10&content_id=' + contentID, 480, 1);
		
	} else if (cType == 4) {
		openEmbed('filebox.cc?n=10&content_id=' + contentID);
		
	} else if (cType == 5) {
		openBox('filebox.cc?n=10&content_id=' + contentID, 480, 1);
		
	} else if (cType == 6) {
		window.location = 'writer.cc?n=2&doc_id=' + contentID;

	} else if (cType == 7) {
		window.location = '/app/livelecture/Editor/index.php?fid=' + contentID;

	} else if (cType == 8) {
		window.location = 'filebox.cc?n=10&content_id=' + contentID;

	} else if (cType == 9) {
		openBox('filebox.cc?n=10&content_id=' + contentID, 300);
	
	}

	
}




function sdContent() {
	if ($("#addOpt").is(':hidden')) {
		$("#addOpt").slideDown(200);
		$('#leftDesc').slideUp(200);
	} else {
			$("#addOpt").slideUp(200);
		if ($("#subMove").is(':hidden')) {
			$('#leftDesc').slideDown(200);
		}
	}
	
}




function disableSelection(target){
if (typeof target.onselectstart!="undefined") //IE route
	target.onselectstart=function(){return false}
else if (typeof target.style.MozUserSelect!="undefined") //Firefox route
	target.style.MozUserSelect="none"
else //All other route (ie: Opera)
	target.onmousedown=function(){return false}
target.style.cursor = "default"
}
	
	
	
	
function checkNumSelected() {
	
	var totalStr = '';
	var undef;
		for(var i=0; i<activeFolders.length; i++) {
			if (activeFolders[i] === undef) {
      		// do nothing
      	} else {
      		totalStr += activeFolders[i] + ',';
      	}
    	}
    	
    var totalCon = ',';
		for(var i=0; i<activeFiles.length; i++) {
			if (activeFiles[i] === undef) {
      		// do nothing
      	} else {
      		totalCon += activeFiles[i] + ',';
      	}
    	}
    	
    	
  if ((totalStr == '') && ((totalCon == '') || (totalCon == ','))) {
		$('#subMove').hide();
		$('#subDel').hide();
		$('#subShare').hide();
		if ($("#addOpt").is(':hidden')) {
			$('#leftDesc').slideDown(200);
		}
  } else {
  		$('#subShare').show();
  		$('#subMove').show();
		$('#subDel').show();
		$('#leftDesc').hide();
  }

// end num selected
}	






function sharingBox() {
	var origFolder = getCurrentFolder();
				var totalStr = '';
				for(var i=0; i<activeFolders.length; i++) {
        			totalStr += activeFolders[i] + ',';
    			}
    			var totalCon = '';
				for(var i=0; i<activeFiles.length; i++) {
        			totalCon += activeFiles[i] + ',';
    			}

  openBox("filebox.cc?n=13&parent=" + origFolder + "&ids=" + totalStr + "&cids=" + totalCon, 350);

}
