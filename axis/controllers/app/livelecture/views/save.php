<?php
header('Content-type: application/json');
$ret = array();
// not logged in? return error
if (!checkSession()) {
	$ret['success'] = false;
	$ret['needsLogin'] = true;
	echo json_encode($ret);
	exit();
}

$cdata = splitVersionURL($_POST['fid']);
//$cdata['conID'], $cdata['verID'];
// generate sections to pass along
$mySecs = array();
foreach (getSections() as $secd) {
	$mySecs[] = (int) $secd['section_id'];
}

$cObj = getContent($cdata['conID']);
// if we're good to go, lets get the permissions
$permissionObj = verifyPermissions($cObj, user('id'), $mySecs);
$perLevel = determinePerLevel($cObj['_id'], $permissionObj);

// verify that we have all needed permissions
if (verifyDataAuth($cdata['verID'], $cObj) && $perLevel == 2) {
	// save the data
	pushDSFile($cdata['conID'], $cdata['verID'], $_POST['data']);

	$ret['success'] = true;


} else {
	$ret['success'] = false;
	$ret['errorString'] = 'You do not have permission to modify this file';
}

// output final
echo json_encode($ret);

?>