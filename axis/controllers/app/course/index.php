<?php
$sectionID = $this->Command->Parameters[0];

// if we can access this section
if (authSection($sectionID)) {

	// get section & course data
	$secData = getSection($sectionID);
	$courseData = getCourse($secData['course_link']);

	if ($this->Command->Parameters[1] == 'latest' || $this->Command->Parameters[1] == '') {
		$cappID = 1;
		require_once('views/latest/index.php');


	} elseif ($this->Command->Parameters[1] == 'calendar') {
		$cappID = 2;
		require_once('views/calendar/index.php');

		
	} elseif ($this->Command->Parameters[1] == 'handout') {
		$cappID = 3;
		require_once('axis/controllers/app/filebox/core/main.php');
		require_once('views/handout/core/main.php');
		require_once('views/handout/index.php');

	} elseif ($this->Command->Parameters[1] == 'manage') {
		if ($this->Command->Parameters[2] == 'icon') {
			require_once('views/manage/icon.php');
		}


	// default view (?)
	}

// add else to show error
}

?>