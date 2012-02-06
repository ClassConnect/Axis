$(".wzdv2").addClass('wizard-crossed');
guiders.createGuider({
  buttons: [{name: "Done with the video? Start tagging your content!", classString: "danger", onclick: guiders.next }],
  description: "<iframe id=\"rmGuideVid\" width=\"720\" height=\"420\" src=\"http://www.youtube.com/v/_4oX2TW-YVU?autoplay=1&hd=1\" frameborder=\"0\" allowfullscreen></iframe>",
  id: "first",
  next: "second",
  overlay: true,
  width:720,
  title: "Learn how to add tags to your content!"
}).show();

guiders.createGuider({
  buttons: [{name: "Close & start tagging content", onclick: guiders.hideAll }],
  description: "Start tagging your content! Once you're ready to move on, click the 'Getting Started' tab on the right.",
  id: "second",
  title: "Your turn!"
});