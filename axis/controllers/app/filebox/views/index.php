<?php
// get & authenticate content
if (empty($this->Command->Parameters[0])){
  $conID = '0';
} else {
  $conID = $this->Command->Parameters[0];
}


// detect if we're not logged in and on a gen page (home or shared)
if (!checkSession() && ($conID == 'shared' || $conID == '0')) {
	// they need to be logged in
	showLogin();
	exit();
}


// get the data for this content
$cObj = getContent($conID);

// make sure this object exists
if (is_null($cObj) && $conID != 'shared' && $conID != '0') {
	if (checkSession()) {
		// this person must have hit an error
		showError();
	} else {
		// they need to be logged in
		showLogin();
	}
	exit();
}

// generate sections to pass along
$mySecs = array();
foreach (getSections() as $secd) {
	$mySecs[] = (int) $secd['section_id'];
}

// if we're good to go, lets get the permissions
$permissionObj = verifyPermissions($cObj, user('id'), $mySecs);
$perLevel = determinePerLevel($cObj['_id'], $permissionObj);

// if this user has some permission to view this content...
if ($perLevel > 0) {
	if ($conID == '0') {
	  $cObj['_id'] = 0;
	  $cObj['type'] = 1;
	  $cObj['title'] = 'My Files';
	}
	// override if this is the share view
	if ($conID == 'shared') {
	  $cObj['_id'] = 'shared';
	  $cObj['title'] = 'Shared';
	  $cObj['type'] = 1;
	  $perLevel = 1;
	}
	// if this content object is a folder...
	if ($cObj['type'] == 1) {
	  require_once('main/folder.php');
	} elseif ($cObj['type'] == 2) {
	  require_once('main/file.php');
	}

// IF WE DON'T HAVE ANY PERMISSIONS
} else {
	// if this person is logged in
	if (checkSession()) {
		// this person must have hit an error
		showError();
	} else {
		// they need to be logged in
		showLogin();
	}
}
?>