$(".wzdv4").addClass('wizard-crossed');

if ($('.shareLinkClasser').length) {
	guiders.createGuider({
	  xButton: true,
	  buttons: [],
	  attachTo: ".shareLinkClasser",
	  position: 9,
	  width:300,
	  id: "temp_id",
	  title: "<div style='line-height:1.2em'>Click the \"Share\" link on a file or folder!</div>"
	}).show();


	$('.shareLinkClasser').click(function() {
		$('.shareLinkClasser').unbind('click');
		guiders.hideAll();
	});
	
} else if ($("#shurred").length) {
	guiders.createGuider({
	  xButton: true,
	  buttons: [],
	  attachTo: "#shurred",
	  position: 3,
	  width:300,
	  id: "temp_id",
	  title: "<div style='line-height:1.2em'>Click the \"Share this with others\" button!</div>"
	}).show();


	$('#shurred').click(function() {
		$('#shurred').unbind('click');
		guiders.hideAll();
	});

}

