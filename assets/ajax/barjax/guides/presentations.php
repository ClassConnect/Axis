guider.createGuider({
      buttons: [{name: "Close"},
                {name: "Next"}],
      description: "Presentations allows you to write and save presentations directly to FileBox. It can also open .ppt files you have uploaded to FileBox.<br /><br />The presentations you create can be used to host LiveLectures for your classes.",
      id: "first",
      next: "second",
      overlay: true,
      title: "Create, open and edit presentations.",
      width:430
    }).show();

    guider.createGuider({
      attachTo: "#createPres",
      buttons: [{name: "Close"},
                {name: "Next"}],
      description: "Simply click 'Create Presentation', choose a file name and location, and you can start creating a new presentation.",
      id: "second",
      next: "third",
      position: 6,
      title: "Create a new presentation.",
      width: 415
    });

    guider.createGuider({
      attachTo: "#openPres",
      buttons: [{name: "Close"}],
      description: "Simply click 'Open Presentation' and choose the the presentation you wish to open from FileBox.<br /><br />If you are opening a .ppt file for the first time, we have to convert it to a ClassConnect Presentation before you can edit it. We only copy over text from your presentations due to incompatibilities.",
      id: "third",
      position: 6,
      title: "Open an existing presentation.",
      width: 415
    });