guiders.createGuider({
  attachTo: "#courses-tab",
  id: "dirtocourse",
  title: "Click 'Courses'",
  position: 6,
  width: 170,
  xButton: true,
  buttons: []
}).show();
// only do this if it hasnt been initialized before
if (typeof coursesinitcheck=="undefined") {
	coursesinitcheck = true;
	$('#courses-tab').click(function() {
		guiders.hideAll();
		setTimeout('guiders.createGuider({
		  attachTo: "#manage-courses-tab",
		  id: "manage-courseer",
		  title: "Click \'add / manage courses\'",
		  position: 3,
		  width: 320,
		  xButton: true,
		  buttons: []
		}).show();',200);
	});	
}