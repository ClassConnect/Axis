<?php
$tempUN = $_GET['url'];
$cleanUN = preg_replace('/\W/', '', $tempUN);

// if the regex result doens't equal the original input, return an error
if ($tempUN != $cleanUN) {
	echo 3;

} else {
	// we're good to go. lets check if this handle has been taken yet
	if (!getUserByUsername($cleanUN) && $cleanUN != '') {
		// not taken - lets register it
		echo 1;
	} else {
		// taken, return error 2
		echo 2;
	}
}

?>