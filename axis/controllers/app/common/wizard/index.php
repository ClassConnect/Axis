<?php
// this is an ajax request
if ($this->Command->Parameters[1] == 'ajax') {
	echo fireWizard($_GET['loc'], $_GET['step']);
}

//echo fireWizard('/app/filebox');

//echo $_SESSION['wizData']['completed'][1];
?>