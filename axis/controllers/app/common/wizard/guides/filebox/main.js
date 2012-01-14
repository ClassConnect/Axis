$(".wzdv1").addClass('wizard-crossed');
guiders.createGuider({
  buttons: [{name: "Done with the video? Start using your FileBox!", classString: "danger", onclick: guiders.next }],
  description: "<iframe id=\"rmGuideVid\" width=\"720\" height=\"420\" src=\"http://www.youtube.com/v/sC_tDs5bdM8?autoplay=1&hd=1\" frameborder=\"0\" allowfullscreen></iframe>",
  id: "first",
  next: "second",
  overlay: true,
  width:720,
  title: "Learn about your FileBox!"
}).show();

guiders.createGuider({
  buttons: [{name: "Close & start using FileBox", onclick: guiders.hideAll }],
  description: "Start adding & organizing your content into FileBox! Once you're ready to move on, click the 'Getting Started' tab on the right.",
  id: "second",
  title: "Your turn!"
});