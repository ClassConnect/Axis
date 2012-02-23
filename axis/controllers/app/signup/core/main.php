<?php
function createUser($email, $username, $firstName, $lastName, $password, $password2, $level, $title, $code) {
	global $dbc;
	// create errors array	
	$errors = array();



	// check for first name
	if (isFilled($firstName) && strlen($firstName) > 1) {
		$firstName = escape($firstName);
	} else {
		$errors[] = say('You forgot to enter your first name.');
	}


	// check for last name
	if (isFilled($lastName)) {
		$lastName = escape($lastName);
	} else {
		$errors[] = say('You forgot to enter your last name.');
	}


	// check for email
	if (isFilled($email)) {
		// if email present, lets validate it
		$email = escape($email);
		if (filter_var($email, FILTER_VALIDATE_EMAIL) != true) {
			$errors[] = say('The email address you entered is not valid.');
		} else {
			$checkMail = getUserByEmail($email);
			if ($checkMail != false) {
				// if this is an invited account
				if ($checkMail['pass'] == 'temp-user') {
					$upsert = true;
				} else {
					$errors[] = say('This email address has already been used.');
				}
			}

		} // valid email else
	} else {
		// only report this error if it's not a student
		if ($level != 1) {
			$errors[] = say('No email address was entered.');
		}
	}


	// check for last name
	if (isFilled($password)) {
		$password = escape($password);
		$password2 = escape($password2);
		if ($password != $password2) {
			$errors[] = say('Your passwords do not match.');
		} else {
			if (strlen($password) < 5) {
				$errors[] = say('Your password is too short.');
			}
		}
	} else {
		$errors[] = say('No password was entered.');
	}


	// check for level
	if (isFilled($level) && is_numeric($level)) {
		$level = escape($level);
	} else {
		$errors[] = 'No user level was given.';
	}



	// check for username
	if (isFilled($username)) {
		// if email present, lets validate it
		$username = escape($username);
		if (strlen($username) <= 4) {
			$errors[] = say('Username must be more than 4 characters.');
		} else {
			$checkName = good_query_assoc("SELECT * FROM users WHERE user_name = '$username' LIMIT 1");
			if ($checkName != false) {	
				$errors[] = say('This username has already been taken.');
			}
		} // valid email else	
	} else {
		// only require UN if student
		if ($level == 1) {
			$errors[] = say('You forgot to enter a username.');
		}
	}



	// check for title
	if ($level == 3) {
		if (isFilled($title)) {
			$title = escape($title);
		} else {
			$errors[] = say('You forgot to choose a title.');
		}

	}


	if ($level == 1) {
		$codeData = authCourseCode($code);
		if ($codeData != false) {
			// do nothing
		} else {
			$errors[] = say('The code you entered is not valid.');
		}
	}

	$now = date('U');
	$unhash = sha1(rand(1, 9999) . 'user' . uniqid());

	if(empty($errors)) {

		if ($upsert == true) {
			$userID = $checkMail['id'];
			rewardInvite(1, $userID);
			good_query("UPDATE users SET pre_name = '$title', first_name = '$firstName', last_name = '$lastName', user_name = '$username', level = $level, pass = SHA1('$password'), e_mail = '$email', reg_date = $now, mehash = '$unhash' WHERE id = $userID");
			// set new user data
			getUser($userID, true);

			// okay, lets go thank the ppl who invited this person
			$finFriends = array();
			$ppl = getFriends(false, $userID);
			foreach ($ppl as $friend) {
				rewardInvite(1, $friend);
				$finFriends[] = array("type"=>1, "shareID"=>$friend);
			}
			$notiData = array("friend_id" => (int) $userID);
			insertFeedItem(3, 2, $finFriends, $notiData);

		} else {
			// insert user into db, return user id
			$insertUser = @mysqli_query($dbc, "INSERT INTO users (pre_name, first_name, last_name, user_name, level, pass, e_mail, reg_date, mehash) VALUES ('$title', '$firstName', '$lastName', '$username', $level, SHA1('$password'), '$email', $now, '$unhash')");

			$userID = $dbc->insert_id;

		}

		if ($level == 1) {
			addStudToSection($codeData, $userID);
		}



		// any session reference?
		if (isset($_SESSION['uref'])) {
			$udata = getUserByHash($_SESSION['uref']);
			if ($udata != false) {
				rewardInvite(1, $udata['id']);
				rewardInvite(1, $userID);
				addFriend($udata['id'], $userID);
			}
		}




		
		return $userID;

	} else {
		return $errors;
	}
}

?>