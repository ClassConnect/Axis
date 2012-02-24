$(".wzdv4").addClass('wizard-crossed');
guiders.createGuider({
  buttons: [{name: "Close this box and start sharing your content!", classString: "danger", onclick: guiders.hideAll }],
  description: "<div style=\"font-size:14px;font-weight:bolder;padding-top:10px\">Click the 'Share' link on a file or folder</div><img class=\"vidView\" src=\"/assets/app/img/wiz/gen/sharing.png\" style=\"margin-top:10px;margin-bottom:20px\" />",
  id: "first",
  width:440,
  title: "Share with students, parents & colleagues!"
}).show();