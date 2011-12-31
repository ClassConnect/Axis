guider.createGuider({
      buttons: [{name: "Close"},
                {name: "Next"}],
      description: "FileBox allows you to store & share files, YouTube videos, website bookmarks, and more.",
      id: "first",
      next: "second",
      overlay: true,
      title: "This is your FileBox."
    }).show();

    guider.createGuider({
      attachTo: "#addContenter",
      buttons: [{name: "Close"},
                {name: "Next"}],
      description: "Click the 'Add Content' button to add files and content to FileBox.",
      id: "second",
      next: "third",
      position: 3,
      title: "Adding folders & content.",
      width: 415
    });

<?php 
      if ($level == 3) {
?>
    guider.createGuider({
      attachTo: "#selectable1",
      buttons: [{name: "Close"},
                {name: "Next"}],
      description: "Simply click the folders & files you want to share and then click the 'Share Selected Items' button on the left.<br /><br />After selecting which classes can access these folders & files, your students will have access to them via the class ShareBox.",
      id: "third",
      next: "fourth",
      position: 6,
      title: "Sharing files with your classes.",
      width: 500
    });

    guider.createGuider({
      attachTo: "#selectable1",
      buttons: [{name: "Close"}],
      description: "Simply click the folders & files you want to move / delete and then click either the 'Move Selected Items' button or the 'Delete Selected Items Button'",
      id: "fourth",
      position: 6,
      title: "Moving & deleting files.",
      width: 500
    });

<?php
      } elseif ($level == 1) {
?>
guider.createGuider({
      attachTo: "#selectable1",
      buttons: [{name: "Close"}],
      description: "Simply click the folders & files you want to move / delete and then click either the 'Move Selected Items' button or the 'Delete Selected Items Button'",
      id: "third",
      position: 6,
      title: "Moving & deleting files.",
      width: 500
    });

<?php
      }
 ?>
               