<?php
if ($_SESSION['wizViz'][$final] != 1 || $_SESSION['wizardStep'] == 4) {
?>
  guider.createGuider({
      buttons: [{name: "Close"}, {name: "Next"}],
      width: 620,
      description: "Create lectures with rich interactive content like videos, websites, and quizzes. Watch the quick video below!<br /><br /><iframe width=\"600\" height=\"380\" src=\"http://www.youtube.com/embed/nU4mX-jp0sc?HD=1;rel=0;showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>",
      id: "first",
      next: "second",
      overlay: true,
      title: "Create & deliver interactive lectures."
    }).show();

  guider.createGuider({
      buttons: [{name: "Close"}],
      description: "Create an interactive lecture and then head over to a class page to share it in real time with your students. When you're ready to move on, click the 'Getting Started' tab on the right!",
      id: "second",
      title: "Your turn!"
    });
<?php               
// leave this step
$_SESSION['wizardStep'] = 20;
// set that we've viewed this page
$_SESSION['wizViz'][$final] = 1;
}
?>