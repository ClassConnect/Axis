<?php
// $this->Command->Name
// $this->Command->Function
// get user data
if ($this->Command->Name != 'app') {
	// pull using $this->Command->Name
	$usr1 = getUserByUsername($this->Command->Name);

// this is being done on /app/profile
} else {
	// get the ID from the URL
	$usr1 = getUser($this->Command->Parameters[0]);
}

// if this user doesn't exist
if ($usr1 == false) {
	showError();
}

echo $usr1['first_name'];
?>