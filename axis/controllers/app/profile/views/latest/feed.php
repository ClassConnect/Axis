<?php
$queryData = buildSharingQuery($usr1['id']);

$offset = $_GET['off'];
$limit = $_GET['limit'];

$result = retrieveFeedItems($queryData, $offset, $limit);

$rcount = 0;
foreach ($result as $res) {
	$rcount++;
}

if ($rcount == 0) {
	$final['empty'] = true;
} else {
	$final['empty'] = false;
}

$final['result'] = genFeedItem($result, array("type" => 10, "shareID" => (int) $usr1['id']));
echo json_encode($final);

?>