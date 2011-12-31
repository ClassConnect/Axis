<?php
$_SESSION['wizViz']['class'] = 1;
?>
guider.createGuider({
      attachTo: "#myclassesHover",
      buttons: [],
      description: "",
      id: "third",
      next: "fourth",
      position: 6,
      title: "Hover over the 'Classes' tab"
    }).show();

$("#myclassesHover").hover(
  function () {
    guider.hideAll();

    guider.createGuider({
      attachTo: "#classLister",
      buttons: [],
      description: "",
      id: "fourth",
      position: 3,
      title: "Click on one of your classes"
    }).show();
  },
  function () {
  guider.hideAll();
  guider.createGuider({
      attachTo: "#myclassesHover",
      buttons: [],
      description: "",
      id: "third",
      next: "fourth",
      position: 6,
      title: "Hover over the 'Classes' tab"
    }).show();
  }
);