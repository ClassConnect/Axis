guider.createGuider({
      buttons: [{name: "Close"},
                {name: "Next"}],
      description: "This page shows upcoming tests & assignments, your agenda for the day, as well as the latest updates from your classes.",
      id: "first",
      next: "second",
      overlay: true,
      title: "This is your homepage."
    }).show();

    guider.createGuider({
      attachTo: "#home-left",
      buttons: [{name: "Close"},
                {name: "Next"}],
      description: "The left sidebar shows you tests and projects that take place within the next week. You can hover over a test or quiz to view more information about it.",
      id: "second",
      next: "third",
      position: 2,
      title: "Upcoming tests & projects at a glance.",
      width: 415
    });


    guider.createGuider({
      attachTo: "#home-main",
      buttons: [{name: "Close"},
                {name: "Next"}],
      description: "Our agenda table gives you a quick look at everything going on today including tests, assignments, projects and events. You can hover over an entry to view more information about it.",
      id: "third",
      next: "fourth",
      position: 6,
      title: "View your agenda for today.",
      width: 500
    });

    guider.createGuider({
      attachTo: "#home-right",
      buttons: [{name: "Close"}],
      description: "<?php 
      if ($level == 3) {
      	echo 'The right sidebar shows the latest updates you have posted to your classes. You can also update multiple classes at once by clicking the \"Add\" link at the top right corner.';
      } elseif ($level == 1) {
      	echo 'The right sidebar shows the latest updates your teachers have posted to your classes. Hover over an update to view more; click to go to its class page.';
      }


      ?>",
      id: "fourth",
      next: "fifth",
      position: 10,
      title: "Latest updates from your classes.",
      width: 500
    });
               