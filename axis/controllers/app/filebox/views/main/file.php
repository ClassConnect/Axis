<?php
// gget the dataID (if any)
if (empty($this->Command->Parameters[1])){
  $dataID = '0';
} else {
  $dataID = $this->Command->Parameters[1];
}

// check if this exists, return false if not
$dataID = verifyDataAuth($dataID, $cObj);

// if this is a download request
if ($this->Command->Parameters[2] == 'download') {
	require_once('download/download.php');
	exit();
}

// only show header if there is not pjax attr
if (!isset($_GET['_pjax'])) {
  require_once('pjaxOff/file.php');
} else {
  require_once('pjaxOn/file.php');
}
?>