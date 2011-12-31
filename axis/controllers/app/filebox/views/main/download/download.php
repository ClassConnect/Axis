<?php
if ($dataID === '0') {
	$dataID = $cObj['versions'][count($cObj['versions']) - 1]['id'];
}

// if we're allowed to download this file
if (verifyDataAuth($dataID, $cObj) && $cObj['format'] == 1) {
	// kill object cleaning, it messes with the download
	ob_get_clean();

	$fileData = getContentData($dataID);

	header("content-type: " . $fileData['file_type']);

	header('Content-Disposition: attachment; filename="' . $cObj['title'] . '.' . $fileData['ext'] . '"');

	header('content-length: ' . $fileData['size']);

	readfile(cloudServer() . $fileData['data']);

	exit();
}

?>