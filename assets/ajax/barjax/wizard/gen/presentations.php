<?php
$_SESSION['wizViz']['presentations'] = 1;
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
      attachTo: "#lectureappHover",
      buttons: [],
      description: "This is where we will create interactive lectures.",
      id: "fourth",
      position: 3,
      title: "Click on 'Lectures'"
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