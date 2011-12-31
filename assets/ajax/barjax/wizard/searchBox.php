<?php
if ($_SESSION['wizViz'][$final] != 1 || $_SESSION['wizardStep'] == 6) {
?>
  guider.createGuider({
      buttons: [{name: "Close"}, {name: "Next"}],
      width: 620,
      description: "SearchBox has access to over 400 million pieces of educational content. Watch the quick video below!<br /><br /><iframe width=\"600\" height=\"380\" src=\"http://www.youtube.com/embed/ERpNWANHK9w?HD=1;rel=0;showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>",
      id: "first",
      next: "second",
      overlay: true,
      title: "Find & save educational content."
    }).show();

  guider.createGuider({
      buttons: [{name: "Close"}],
      description: "Search for content and then save it directly to FileBox. When you're ready to move on, click the 'Getting Started' tab on the right!",
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