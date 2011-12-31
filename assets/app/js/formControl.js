actionSet = '';
asyncActive = false;
function fbFormControl(focusFirst) {
	$(focusFirst).focus();
	actionSet = $("#fbActions").html();
}

function fbFormActLoader(title) {
	if (!title) {
		title = 'submitting...';
	}
	$("input").blur();
	$("#fbActions").html('<div style="float:right"><div style="float:right; color:#999; margin-left:5px">' + title + '</div><img src="/assets/app/img/box/sub.gif" /></div><div style="clear:both"></div>');
}

function fbFormActRevert() {
	$("#fbActions").html(actionSet);
}

function fbFormDisable(formID) {
	$(formID + ' :input').attr('disabled', true);
 
	$(formID + ' :submit').attr('disabled', true);
}

function fbFormEnable(formID) {
	$(formID + ' :input').attr('disabled', false);
 
	$(formID + ' :submit').attr('disabled', false);
}

function fbFormSubmitted(formID) {
	fbFormActLoader();
	fbFormDisable(formID);
}

function fbFormRevert(formID) {
	fbFormActRevert();
	fbFormEnable(formID);
}

function showFormError(data) {
	$("#errorBox").html(data).show();
}



function pickCourse(iObj) {
	var bool1 = false;
	if ($(iObj).is(':checked')) {
		bool1 = true;
	}
	var mObj = $(iObj).parent().parent().find('.coursePickerSections').find('input:checkbox');

	$(mObj).each(function(index) {
		if ($(this).is(':disabled')) {
			// do nothing
		} else {
			$(this).prop('checked', bool1);
		}
	});
}

function pickShowSections(iObj) {
	var mObj = $(iObj).parent().find('.coursePickerSections');
	if (mObj.is(':hidden')) {
		mObj.parent().find('.arrSwap').attr("src", '/assets/app/img/gen/arrUp.png');
		$(mObj).css('opacity', 0).slideDown(100).animate({ opacity: 1 },{ queue: false, duration: 100});
	} else {
		mObj.parent().find('.arrSwap').attr("src", '/assets/app/img/gen/arrDown.png');
		$(mObj).css('opacity', 1).slideUp(100).animate({ opacity: 0 },{ queue: false, duration: 100});
	}
}



function initAsyncBar(data, cssClass, width, left, autotimer) {
	asyncActive = true;
	// remove any instances of the async bar
	$('.asyncLoader').remove();

	if (left == null) {
		left = 500;
	}
	if (width == null) {
		width = 300;
	}
	$('body').append('<div class="asyncLoader">' + data + '</div>');
	$('.asyncLoader').addClass(cssClass);
	$('.asyncLoader').css('width', width);
	$('.asyncLoader').css('left', left);
	$('.asyncLoader').css('opacity', 0).slideDown(150).animate({ opacity: 1 },{ queue: false, duration: 150});
	if (autotimer != null) {
		$('.asyncLoader').delay(autotimer).queue(function(){ destroyAsyncBar(); });
	}
}

function destroyAsyncBar() {
	asyncActive = false;
	$('.asyncLoader').css('opacity', 1).slideUp(150, function() { $(this).remove(); }).animate({ opacity: 0 },{ queue: false, duration: 150});
}



function sortNest(prop, arr) {
    prop = prop.split('.');
    var len = prop.length;

    arr.sort(function (a, b) {
        var i = 0;
        while( i < len ) { a = a[prop[i]]; b = b[prop[i]]; i++; }
        if (a < b) {
            return -1;
        } else if (a > b) {
            return 1;
        } else {
            return 0;
        }
    });
    return arr;
}




function togglePicker(tObj) {
	if (tObj.parent().find('.pickPane').is(":visible")) {
		tObj.parent().find('.pickPane').hide();
	} else {
		tObj.parent().find('.pickPane').show();
	}
}

function togglePickFolder(fObj) {
	var childs = $(fObj).parent().find('.dirWrap:first');
	// if we've loaded the children already
	if (childs.html()) {

		// if it's visible, hide it
		if (childs.is(":visible")) {
			var src = $(fObj).attr("src").replace("arrDown", "arrRight");
            $(fObj).attr("src", src);
            childs.hide();
		} else {
			var src = $(fObj).attr("src").replace("arrRight", "arrDown");
            $(fObj).attr("src", src);
            childs.show();
		}

	// load the children
	} else {
		$(fObj).parent().find(".dirWrap").html('Loading...');
		var src = $(fObj).attr("src").replace("arrRight", "arrDown");
        $(fObj).attr("src", src);
		$.ajax({
			type: "GET",
			url: "/app/common/picker/" + $(fObj).parent().attr('folid'),
			success: function(data) {
				$(fObj).parent().find(".dirWrap").html(data);
			}
		});
		
	}
	
}

function selectPickFolder(fObj) {
	var folid = $(fObj).parent().attr('folid');
	var text = $(fObj).text();
	$(fObj).closest(".fboxPicker").find('.titleTexter').html(text);
	$(fObj).closest(".fboxPicker").find('.chosenOne').val(folid);
	$(fObj).closest(".pickPane").hide();

}