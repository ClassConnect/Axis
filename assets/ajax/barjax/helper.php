<?php
// main include
require_once('../../inc/coreInc.php');
requireSession();


// if we receive a clean page name (been processed, now need to execute)
if (isset($_GET['ep'])) {
	if (file_exists('guides/' . $_GET['ep'] . '.php')) {
		echo '<script type="text/javascript" src="' . $scriptServer . 'guider.js"></script>';
		echo '<script>

$(document).ready(function() {
    $("#helper a:first").click();
});

';
// include specific guide
include('guides/' . $_GET['ep'] . '.php');

echo '</script>';

	}


	exit();
}


// clean out the name
$arr = explode('/', $_GET['p']);
$name = $arr[count($arr) - 1];
$name = substr($name, 0, strpos($name, '.'));

if (file_exists('guides/' . $name . '.php')) {
	echo '<button class="button" type="submit" onClick="initTutorial(\'' . $name . '\')" style="margin-bottom:10px; margin-top:10px; margin-left:10px">Learn How This Page Works</button>';
} else {
	echo '<div style="font-size:14px; color:#666; margin-top:10px; margin-left:5px; margin-bottom:10px">No tutorial found for this page.<div style="font-size:12px; color:#333; text-align:left; margin-top:7px">Have a question? Click the feedback button on the bottom bar or you can call us at (866) 844-5250.</div></div>';
}
?>