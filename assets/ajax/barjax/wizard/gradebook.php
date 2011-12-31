<?php
if ($_SESSION['wizViz'][$final] != 1) {
?>
  guider.createGuider({
      buttons: [{name: "Close"}, {name: "Next"}],
      width: 620,
      description: "ClassConnect's gradebook is beautiful, powerful and simple. Watch the video below to get started!<br /><br /><iframe width=\"600\" height=\"380\" src=\"http://www.youtube.com/embed/X1VeQP0IpOU?HD=1;rel=0;showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>",
      id: "first",
      next: "second",
      overlay: true,
      title: "Grading is no longer a chore."
    }).show();

  guider.createGuider({
      buttons: [{name: "Close"}],
      description: "Try out ClassConnect's gradebook! When you're ready to move on, click the 'Getting Started' tab on the right!",
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