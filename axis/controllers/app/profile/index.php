<?php
// $this->Command->Name
// $this->Command->Function
// get user data
if ($this->Command->Name != 'app') {
	// pull using $this->Command->Name
	$usr1 = getUserByUsername($this->Command->Name);
	// add the current stuff to the paramaters array
	array_unshift($this->Command->Parameters, $this->Command->Name, $this->Command->Function);
	$un = true;

// this is being done on /app/profile
} else {
	// get the ID from the URL
	$usr1 = getUser($this->Command->Parameters[0]);
	$un = false;
}

// if this user doesn't exist
if ($usr1 == false) {
	showError();
	exit();
}


// two different routing blocks
// this one is for profile by id
require_once('axis/controllers/app/profile/core/main.php');

	if ($this->Command->Parameters[1] == 'latest' || $this->Command->Parameters[1] == '') {
	$cappID = 1;
	require_once('views/latest/index.php');
		
	} elseif ($this->Command->Parameters[1] == 'shared') {
		$cappID = 2;
		require_once('axis/controllers/app/filebox/core/main.php');
		require_once('views/shared/core/main.php');
		require_once('views/shared/index.php');

	} elseif ($this->Command->Parameters[1] == 'manage') {
		if ($this->Command->Parameters[2] == 'icon') {
			require_once('views/manage/icon.php');
		}


	// default view (?)
	}

?>