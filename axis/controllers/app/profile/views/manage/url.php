<?php
$tempUN = $_GET['url'];
$cleanUN = preg_replace('/\W/', '', $tempUN);

// if this user already has a username or isn't logged in
if (user('user_name') != '' || !checkSession()) {
	exit();
}

// if the regex result doens't equal the original input, return an error
if ($tempUN != $cleanUN) {
	echo 3;

} else {
	// we're good to go. lets check if this handle has been taken yet
	if (!getUserByUsername($cleanUN) && $cleanUN != '') {
		// not taken - lets register it
		$myUID = user('id');
		good_query("UPDATE users SET user_name = '$cleanUN' WHERE id = $myUID");
  		getUser($myUID, true);
		echo 1;
	} else {
		// taken, return error 2
		echo 2;
	}
}

?>