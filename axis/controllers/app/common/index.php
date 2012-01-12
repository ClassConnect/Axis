<?php
// if we're pulling down contacts
if ($this->Command->Parameters[0] == 'colleagues' && checkSession()) {
	if ($this->Command->Parameters[1] == 'add') {
		require_once('colleagues/add.php');

	} elseif ($this->Command->Parameters[1] == 'review') {
		require_once('colleagues/review.php');

	} elseif ($this->Command->Parameters[1] == 'remove') {
		require_once('colleagues/remove.php');

	}


// feed api
} elseif ($this->Command->Parameters[0] == 'feed' && checkSession()) {
	if ($this->Command->Parameters[1] == 'remove') {
		require_once('feed/remove.php');

	} elseif ($this->Command->Parameters[1] == 'retrieve') {
		require_once('feed/retrieve.php');

	} else {
		// display feed
	}


// if we're accessing the file picker
} elseif ($this->Command->Parameters[0] == 'picker' && checkSession()) {
  require_once('picker/index.php');



// if we're accessing the wizard
} elseif ($this->Command->Parameters[0] == 'wizard' && checkSession()) {
  require_once('wizard/core/main.php');
  require_once('wizard/index.php');


// lets assume that this is a piece of filebox content (or homepage)
} else {
  showError();
}

?>