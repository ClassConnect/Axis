guider.createGuider({
      buttons: [{name: "Close"},
                {name: "Next"}],
      description: "SearchBox allows you to search for videos, websites, images, documents, e-books, presentations, and more.",
      id: "first",
      next: "second",
      overlay: true,
      title: "Search the web for educational content.",
      width:430
    }).show();

    guider.createGuider({
      attachTo: "#updateBox",
      buttons: [{name: "Close"}],
      description: "Enter a keyword into the search box to find content.<br /><br />Click on the content to preview. Click the 'Save To FileBox' button to save this content to your FileBox.",
      id: "second",
      position: 6,
      title: "Search for content.",
      width: 415
    });
               