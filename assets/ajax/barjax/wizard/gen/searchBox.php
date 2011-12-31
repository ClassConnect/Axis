<?php
$_SESSION['wizViz']['searchBox'] = 1;
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
      attachTo: "#sboxappHover",
      buttons: [],
      description: "This is where we will find content.",
      id: "fourth",
      position: 3,
      title: "Click on 'SearchBox'"
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