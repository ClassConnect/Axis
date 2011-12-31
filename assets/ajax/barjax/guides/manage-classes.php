guider.createGuider({
      buttons: [{name: "Close"},
                {name: "Next"}],
      description: "<?php 
      if ($level == 3) {
        echo 'This page allows you to create, end and view your classes.';
      } elseif ($level == 1) {
        echo 'This page allows you to enroll & view your classes.';
      }
      ?>",
      id: "first",
      next: "second",
      overlay: true,
      title: "Manage your classes<?php 
      if ($level == 3) {
        echo ' & schools';
      } ?>."
    }).show();


    <?php 
      if ($level == 3) {
        // TEACHERS ONLY
?>
    guider.createGuider({
      attachTo: "#manageRighter",
      buttons: [{name: "Close"}],
      description: "To create a class, click the 'Create New Class' button.",
      id: "second",
      next: "third",
      position: 9,
      title: "Create your classes.",
      width: 415
    });

<?php
      } elseif ($level == 1) {
        // STUDENTS ONLY
?>
    guider.createGuider({
      attachTo: "#manageRighter",
      buttons: [{name: "Close"}],
      description: "If you want to enroll in a class, click the 'Enroll In A Class' button. You will need to enter the class code provided by your teacher.",
      id: "second",
      next: "third",
      position: 9,
      title: "Enroll in a class.",
      width: 415
    });
<?php
      }
      ?>
