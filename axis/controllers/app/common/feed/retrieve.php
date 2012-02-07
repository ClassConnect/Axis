<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

// fetch offset & limit (REQUIRED)
$offset = $_GET['off'];
$limit = $_GET['limit'];

// figure out if we have a primary
if (isset($_GET['primType']) && isset($_GET['primID'])) {
	$primary = array("type" => (int) $_GET['primType'], "shareID" => (int) $_GET['primID']);
}


$idArr = array();
// lets retrieve all share types
// created by one of these userIDs (no support yet)
//$owners = explode(',', $_GET['o']);

// a user's notistream (t/f, pulls uid from session)
$t1 = $_GET['t1'];
if ($t1 == 1) {
	$idArr[] = array('shared_with.shareID' => (int) user('id'), 'shared_with.type' => 1);
}


if (isset($_GET['t2'])) {
	
}


// take idarray and turn into mongo or statement
$params = array('$or' => $idArr);
// this is a force request
if ($_GET['primType'] == 10) {
	$params['uid'] = (int) $_GET['primID'];
}


$result = retrieveFeedItems($params, $offset, $limit);
$rcount = 0;
foreach ($result as $res) {
$rcount++;
}

if ($rcount == 0) {
	$final['empty'] = true;
} else {
	$final['empty'] = false;
}

$final['result'] = genFeedItem($result, $primary);
echo json_encode($final);


?>