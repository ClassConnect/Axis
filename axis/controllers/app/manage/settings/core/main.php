<?php
// if we're updating data from 
function updatePersonalData($uid, $title, $firstname, $lastname, $email, $pass1, $pass2) {
	if (!isset($uid)) {
        $uid = user('id');
    }

	// init errors array
	$errors = array();

	if ($title != '') {
		$title = escape($title);
	}

	if ($firstname != '') {
		$firstname = escape($firstname);
	} else {
		$errors[] = 'You forgot to enter a first name.';
	}

	if ($lastname != '') {
		$lastname = escape($lastname);
	} else {
		$errors[] = 'You forgot to enter a last name.';
	}

	$email = escape($email);

	// we are checking passwords son
	if ($pass1 != '' && $pass1 != 'New password') {
		if ($pass1 != $pass2) {
			$errors[] = 'Your passwords do not match.';
		} elseif (strlen($pass1) < 5) {
			$errors[] = 'Your password needs to be at least 5 characters long.';
		
		// we're clear son
		} else {
			$newPass = escape($pass1);
			$insertPass = ", pass = SHA1('$newPass')";
		}
	}

	if (empty($errors)) {
		good_query("UPDATE users SET pre_name = '$title', first_name = '$firstname', last_name = '$lastname', e_mail = '$email'$insertPass WHERE id = $uid");
		// update memcached
		getUser($uid, true);
		return 1;
	} else {
		return $errors;
	}
	
}
?>