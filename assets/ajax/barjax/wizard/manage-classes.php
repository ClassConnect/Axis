<?php
if ($_SESSION['wizardStep'] == 1 && empty($myClasses)) {
?>
guider.createGuider({
      buttons: [{name: "Close"},{name: "Next"}],
      
      description: "Let's create your classes. Click next to continue.",
      id: "second",
      next: "third",
      title: "This page allows you to manage your classes."
    }).show();

guider.createGuider({
      attachTo: "#createAclass",
      
      buttons: [{name: "Close"}],
      description: "",
      id: "third",
      next: "fourth",
      position: 9,
      title: "Click 'Create New Class'"
    });

$("#createAclass").click(
  function () {
    
  },
  function () {
  guider.hideAll();

    guider.createGuider({
      attachTo: "#dialogBox",
      buttons: [{name: "Close"}],
      
      description: "Simply enter the class name and click 'Create Class'.<br /><br />If you want to create more than one class, click the 'add another class' link after you create your class.",
      id: "fourth",
      position: 3,
      title: "Fill in your class information."
    }).show();
  }
);
<?php
} elseif (!empty($myClasses)) {

if ($_SESSION['wizardStep'] == 1) {
?>
guider.createGuider({
      buttons: [],
      buttonCustomHTML: "<br /><a class=\"guider_button\" style=\"float:right\" onClick=\"guider.hideAll();tempEr();\">Let's keep going!</a><a class=\"guider_button\" style=\"float:right\" onClick=\"guider.hideAll();\">I need to create more classes.</a>",
      description: "",
      id: "x",
      next: "x",
      title: "Your classes have been created!"
    }).show();

<?php
}
?>


<?php
}
?>