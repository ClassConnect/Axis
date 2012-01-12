<?php
// this is an ajax request
if ($this->Command->Parameters[1] == 'ajax') {
	var_dump(urlToArray($_GET['loc']));
}
exit();
var_dump(urlToArray($_SERVER['REQUEST_URI']));

echo fireWizard('/app/filebox');

echo $_SESSION['wizData']['completed'][1];
?>