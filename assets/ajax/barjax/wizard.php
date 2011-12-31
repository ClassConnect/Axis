<?php
// main include
require_once('../../inc/coreInc.php');
requireSession();

if ($_GET['c']) {
	$_SESSION['wizard'] = 2;
	$_SESSION['wizardStep'] = 0;
	$_SESSION['wizViz'] = array();
	exit();
}

if ($_SESSION['wizard'] != 1) {
	$_SESSION['wizard'] = 1;
	$_SESSION['wizardStep'] = 0;
	$_SESSION['wizViz'] = array();
}

$step = escape($_GET['step']);
$_SESSION['wizardStep'] = $step;


// clean out the name
$arr = explode('/', $_GET['p']);
$name = $arr[count($arr) - 1];
$name = substr($name, 0, strpos($name, '.'));


	echo '<script type="text/javascript" src="' . $scriptServer . 'guider.js"></script>';
	echo '<script>';

	$wizName = $name;

	if ($_SESSION['wizardStep'] == 1 && $name != 'manage-classes') {
		require_once('wizard/gen/manage-classes.php');

	} elseif ($_SESSION['wizardStep'] == 2 && $name != 'filebox') {
		require_once('wizard/gen/filebox.php');

	} elseif ($_SESSION['wizardStep'] == 3 && $name != 'class') {
		require_once('wizard/gen/class.php');

	} elseif ($_SESSION['wizardStep'] == 4 && $name != 'presentations') {
		require_once('wizard/gen/presentations.php');

	} elseif ($_SESSION['wizardStep'] == 5 && $name != 'writer') {
		require_once('wizard/gen/writer.php');

	} elseif ($_SESSION['wizardStep'] == 6 && $name != 'searchBox') {
		require_once('wizard/gen/searchBox.php');

	} else {
		// include specific guide
		require_once('wizard/main.php');

	}

	echo '</script>';


?>
