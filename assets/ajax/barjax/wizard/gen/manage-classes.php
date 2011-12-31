guider.createGuider({
      attachTo: "#settingsHover",
      buttons: [],
      description: "",
      id: "third",
      next: "fourth",
      position: 6,
      title: "Hover over the 'Settings' tab"
    }).show();

$("#settingsHover").hover(
  function () {
    guider.hideAll();

    guider.createGuider({
      attachTo: "#manageclassesHover",
      buttons: [],
      description: "This is the page where we will create your classes!",
      id: "fourth",
      position: 3,
      title: "Click on 'Manage Classes'"
    }).show();
  },
  function () {
  guider.hideAll();
  guider.createGuider({
      attachTo: "#settingsHover",
      buttons: [],
      description: "",
      id: "third",
      next: "fourth",
      position: 6,
      title: "Hover over the 'Settings' tab"
    }).show();
  }
);