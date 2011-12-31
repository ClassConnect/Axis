<?php
$_SESSION['wizViz']['writer'] = 1;
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
      attachTo: "#docsappHover",
      buttons: [],
      description: "This is where we will create and edit documents.",
      id: "fourth",
      position: 3,
      title: "Click on 'Docs'"
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