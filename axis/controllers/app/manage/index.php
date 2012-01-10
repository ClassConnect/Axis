<?php
// if this is to manage courses
if ($this->Command->Parameters[0] == 'courses') {
  require_once('axis/controllers/app/manage/courses/core/main.php');
  // if this is a student
  if (user('level') == 1) {
    require_once('axis/controllers/app/manage/courses/student/index.php');

  // if this is a teacher
  } elseif (user('level') == 3) {
    require_once('axis/controllers/app/manage/courses/teacher/index.php');
  }
 

// if this is settings
} elseif ($this->Command->Parameters[0] == 'settings') {
	require_once('settings/core/main.php');
	// if this is a modify command
	if ($this->Command->Parameters[1] == 'icon') {
		// this is for updating a user or course icon
		require_once('settings/views/icon.php');


	} elseif ($this->Command->Parameters[1] == 'personal') {
		// this is for updating a user or course icon
		require_once('settings/views/personal.php');

	} else {
	  require_once('settings/views/index.php');
	}
}

?>