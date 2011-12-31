<?php
$evCmd = $this->Command->Parameters[2];
// get cal ID from URL
$calID = $this->Command->Parameters[3];
// data
$data = $this->Command->Parameters[4];

// retrieve cal data
$calData = getCalEntry($calID);
// verify permissions
$pers = determineEventPermissions($calData);

// if we're allowed to view this thing
if ($pers['write'] == true) {

	// calculate the goddamn fucking data
	if ($evCmd == 'shift') {
		$start = strtotime(date("Y-m-d", $calData['start']) . ' +' . $data . ' days');
		$end = strtotime(date("Y-m-d", $calData['end']) . ' +' . $data . ' days');

	} elseif ($evCmd == 'resize') {
		$start = $calData['start'];
		$end = strtotime(date("Y-m-d", $calData['end']) . ' +' . $data . ' days');
		
	}

	writeEvent(2, $start, $end, $calData['type'], $calData['title'], $calData['body'], $calData['shared_with'], $calID);

	echo 1;
}
?>