$(".wzdv3").addClass('wizard-crossed');
guiders.createGuider({
  buttons: [{name: "Your turn - start tagging your content!", classString: "danger", onclick: guiders.hideAll }],
  description: "On a file or folder, click the <a href=\"#\" class=\"btn\" style=\"margin-bottom:-4px;height:15px;padding:0\"><img src=\"/assets/app/img/gen/arrDown.png\" /></a> icon and then click \"Tag\".<img src=\"/assets/app/img/wiz/gen/share.png\" style=\"margin-top:10px\" />",
  id: "first",
  width:500,
  title: "Add tags & Common Core to your content!"
}).show();