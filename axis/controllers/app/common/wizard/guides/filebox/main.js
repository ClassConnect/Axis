$(".wzdv1").addClass('wizard-crossed');
guiders.createGuider({
  xButton: true,
  buttons: [],
  attachTo: "#addBtn",
  position: 3,
  id: "first",
  title: "Click the \"Add Files\" button!"
}).show();

// only do this if it hasnt been initialized before
if (typeof addbuttontog=="undefined") {
  addbuttontog = true;
  $('#addBtn').click(function() {
    guiders.hideAll();
  }); 
}