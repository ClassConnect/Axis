<?php
$cdata = splitVersionURL($_GET['fid']);
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
if (verifyDataAuth($cdata['verID'], $cObj) && $perLevel >= 1) {
	// get the data
	$data = getContentData($cdata['verID']);
	if ($data['data'] != 'none') {
		echo file_get_contents(cloudServer() . $data['data']);
	}

}

?>