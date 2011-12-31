guider.createGuider({
      buttons: [{name: "Close"},
                {name: "Next"}],
      description: "This page shows the latest updates from this school as well as the school's contact information.",
      id: "first",
      next: "second",
      overlay: true,
      title: "This is a school page."
    }).show();

    guider.createGuider({
      attachTo: "#app1",
      buttons: [{name: "Close"},
                {name: "Next"}],
      description: "<?php 
      if ($level == 3) {
        echo 'School administrators can post updates to this school. All students and teachers in this school will be notified when new updates are posted.';
      } elseif ($level == 1) {
        echo 'This is where this school\'s updates are shown.';
      }


      ?>",
      id: "second",
      next: "third",
      position: 7,
      title: "Your school's updates.",
      width: 415
    });


    guider.createGuider({
      attachTo: "#app10",
      buttons: [{name: "Close"}],
      description: "View contact information that your school has posted to ClassConnect including the school's address, phone number, and more.",
      id: "third",
      position: 7,
      title: "School contact information.",
      width: 415
    });
       