$(".wzdv2").addClass('wizard-crossed');
guiders.createGuider({
  buttons: [{name: "Close this box and start tagging your content!", classString: "danger", onclick: guiders.hideAll }],
  description: "<div style=\"margin-top:20px;font-size:14px;font-weight:bolder\">On a file or folder, click the <a href=\"#\" class=\"btn\" style=\"margin-bottom:-4px;height:15px;padding:0\"><img src=\"/assets/app/img/gen/arrDown.png\" /></a> icon and then click \"Tag\".</div><img class=\"vidView\" src=\"/assets/app/img/wiz/gen/tag.png\" style=\"margin-top:10px;margin-bottom:20px\" />",
  id: "first",
  width:500,
  title: "Add tags & Common Core to your content!"
}).show();