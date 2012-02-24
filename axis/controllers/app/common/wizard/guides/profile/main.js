$(".wzdv5").addClass('wizard-crossed');
guiders.createGuider({
  buttons: [{name: "Done with the video? Start collaborating with colleagues!", classString: "danger", onclick: guiders.next }],
  description: "<iframe id=\"rmGuideVid\" width=\"720\" height=\"420\" src=\"http://www.youtube.com/v/h7LWQLk0eVk?autoplay=1&hd=1\" frameborder=\"0\" allowfullscreen></iframe>",
  id: "first",
  next: "second",
  overlay: true,
  width:720,
  title: "Learn how to collaborate & share with colleagues!"
}).show();