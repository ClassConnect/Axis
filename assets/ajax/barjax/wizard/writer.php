<?php
if ($_SESSION['wizViz'][$final] != 1 || $_SESSION['wizardStep'] == 5) {
?>
  guider.createGuider({
      buttons: [{name: "Close"}, {name: "Next"}],
      width: 620,
      description: "No need to download or re-upload your documents - you can edit and save them right inside of ClassConnect. Watch the quick video below!<br /><br /><iframe width=\"600\" height=\"380\" src=\"http://www.youtube.com/embed/EEix4nWGh8Y?HD=1;rel=0;showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>",
      id: "first",
      next: "second",
      overlay: true,
      title: "Create & edit documents."
    }).show();

  guider.createGuider({
      buttons: [{name: "Close"}],
      description: "Create (or open) a document and save it to FileBox. When you're ready to move on, click the 'Getting Started' tab on the right!",
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