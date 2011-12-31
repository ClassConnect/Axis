<?php
// get the current page name
if (isset($wizName)) {
	$final = $wizName;
	$location = 'wizard/' . $final . '.php';

} else {
	// get the current PHP file
	$currentFile = $_SERVER["PHP_SELF"];
	$parts = explode('/', $currentFile);
	$prefinal = $parts[count($parts) - 1];
	$final = substr($prefinal, 0, strlen($prefinal) - 4);
	$location = 'core/ajax/barjax/wizard/' . $final . '.php';

}

// check if a file exists
if (file_exists($location)) {
	require_once($location);
}


?>