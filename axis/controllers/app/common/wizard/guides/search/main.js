$(".wzdv3").addClass('wizard-crossed');
guiders.createGuider({
  attachTo: "#leftBox",
  position: 3,
  buttons: [{name: "Close", classString: "danger", onclick: guiders.hideAll }],
  description: "Using the filters to the left, you can find exactly what you're looking for. You can even find resources that are aligned with Common Core standards!",
  id: "first",
  width:500,
  title: "Filter your search!"
}).show();