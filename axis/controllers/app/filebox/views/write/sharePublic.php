<?php

$batchCon = getBatchContent($_GET['conID']);
$permissions = getSharedPermissions($batchCon);
$batchPer = verifyBatchPermissions($batchCon);
$permLev = determinePerLevel(123, $batchPer);
if ($permLev != 2) {
  echo 'false';
  exit();
}

updatePermissions($_GET['conID'], array(array("shared_id"=> 1, "type"=>3, "auth_level"=>1)));
$retObj = array();
$retObj['success'] = 1;

$cdata = getContent($_GET['conID']);
$permissionObj = verifyPermissions($cdata, user('id'));
if ($_GET['current'] == '0') {
  $cdata['_id'] = 0;
  $cdata['type'] = 1;
  $cdata['title'] = 'FileBox';
}

if ($cdata['type'] == 1) {
  $retObj['sidebar'] = createFolBar($cdata, $permissionObj);
} elseif ($cdata['type'] == 2) {
  $retObj['sidebar'] = createFilBar($cdata, $permissionObj);
}

header('Content-type: application/json');
echo json_encode($retObj);


  exit();

?>