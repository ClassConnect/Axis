guider.createGuider({
      buttons: [{name: "Close"},
                {name: "Next"}],
      description: "Docs allows you to write and save documents directly to FileBox. It can also open .doc files you have uploaded to FileBox.",
      id: "first",
      next: "second",
      overlay: true,
      title: "Create, open and edit documents.",
      width:430
    }).show();

    guider.createGuider({
      attachTo: "#createDoc",
      buttons: [{name: "Close"},
                {name: "Next"}],
      description: "Simply click 'Create Document', choose a file name and location, and you can start writing a new document.",
      id: "second",
      next: "third",
      position: 6,
      title: "Create a new document.",
      width: 415
    });

    guider.createGuider({
      attachTo: "#openDoc",
      buttons: [{name: "Close"}],
      description: "Simply click 'Open Document' and choose the the document you wish to open from FileBox.<br /><br />If you are opening a .doc file for the first time, we have to convert it to a ClassConnect Doc before you can edit it.",
      id: "third",
      position: 6,
      title: "Open an existing document.",
      width: 415
    });