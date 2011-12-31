<?php
$_SESSION['wizViz']['filebox'] = 1;
?>
guider.createGuider({
      attachTo: "#appsHover",
      buttons: [],
      description: "",
      id: "third",
      next: "fourth",
      position: 6,
      title: "Hover over the 'Apps' tab"
    }).show();

$("#appsHover").hover(
  function () {
    guider.hideAll();

    guider.createGuider({
      attachTo: "#fboxappHover",
      buttons: [],
      description: "This is where we will upload & organize class content.",
      id: "fourth",
      position: 3,
      title: "Click on 'FileBox'"
    }).show();
  },
  function () {
  guider.hideAll();
  guider.createGuider({
      attachTo: "#appsHover",
      buttons: [],
      description: "",
      id: "third",
      next: "fourth",
      position: 6,
      title: "Hover over the 'Apps' tab"
    }).show();
  }
);