<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

// okay, so lets fetch the type & share data
if (isset($_GET['me'])) {
	$me = true;
} else {
	$me = false;
}

$start = $_GET['start'];
$end = $_GET['end'];
$courses = $_GET['courses'];

$data = getCalEntries($start, $end, $me, $courses);

$final = array();

foreach ($data as $datas) {
	$final[] = encodeEntry($datas);
}

echo json_encode($final);

?>