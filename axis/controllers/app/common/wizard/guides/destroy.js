// remove the wizard
$("#wizthing").remove();
$("#wizpnel").remove();

guiders.createGuider({
  attachTo: "#habla_topbar_div",
  id: "hablalertaassd",
  title: "If you ever need help...",
  description: "...feel free to send us a message! :)",
  width:250,
  position: 12,
  buttons: []
}).show();

setTimeout('guiders.hideAll();',3000);