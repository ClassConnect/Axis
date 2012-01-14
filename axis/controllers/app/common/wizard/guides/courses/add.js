$(".wzdv4").addClass('wizard-crossed');
guiders.createGuider({
  buttons: [{name: "Done with the video? Create your courses!", classString: "danger", onclick: guiders.next }],
  description: "<iframe id=\"rmGuideVid\" width=\"720\" height=\"420\" src=\"http://www.youtube.com/v/flLKd0j-_-c?autoplay=1&hd=1\" frameborder=\"0\" allowfullscreen></iframe>",
  id: "first",
  next: "second",
  overlay: true,
  width:720,
  title: "Learn how to create your courses and share with them!"
}).show();

guiders.createGuider({
  buttons: [{name: "Close & start creating your courses", onclick: guiders.hideAll }],
  description: "Create your courses and share with them! Once you're ready to move on, click the 'Getting Started' tab on the right.",
  id: "second",
  title: "Your turn!"
});