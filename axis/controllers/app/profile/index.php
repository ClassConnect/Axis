<?php
// $this->Command->Name
// $this->Command->Function
// get user data
if ($this->Command->Name != 'app') {
	// pull using $this->Command->Name
	$usr1 = getUserByUsername($this->Command->Name);
	// add the current stuff to the paramaters array
	array_unshift($this->Command->Parameters, 'app', $this->Command->Function);
	$un = true;
	$rootURL = '/' . $usr1['user_name'] . '/';

// this is being done on /app/profile
} else {
	// get the ID from the URL
	$usr1 = getUser($this->Command->Parameters[0]);
	$un = false;
	$rootURL = '/app/profile/' . $usr1['id'] . '/';
}

// if this user doesn't exist
if ($usr1 == false) {
	showError();
	exit();
}

$curUID = $usr1['id'];

// two different routing blocks
// this one is for profile by id
require_once('axis/controllers/app/profile/core/main.php');

	if ($this->Command->Parameters[1] == 'latest' || $this->Command->Parameters[1] == '') {
	$cappID = 1;
	require_once('axis/controllers/app/profile/views/latest/index.php');
		
	} elseif ($this->Command->Parameters[1] == 'shared') {
		$cappID = 2;
		require_once('axis/controllers/app/filebox/core/main.php');
		require_once('axis/controllers/app/profile/views/shared/core/main.php');
		require_once('axis/controllers/app/profile/views/shared/index.php');

	} elseif ($this->Command->Parameters[1] == 'manage') {
		if ($this->Command->Parameters[2] == 'icon') {
			require_once('axis/controllers/app/profile/views/manage/icon.php');

		} elseif ($this->Command->Parameters[2] == 'about') {
			require_once('axis/controllers/app/profile/views/manage/about.php');
		}


	// default view (?)
	} else {
		showError();
	}
?>