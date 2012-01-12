<?php
// get url
// route it to the correct file
// check if it's being forced by the session
// output file

// split apart a url
function urlToArray($url) {
	if (strpos($url, '?') != false) {
		$url = substr($url, 0, (strpos($url, '?')));
	}

	// first lets get the location of /app/ and slice it
	$start = strpos($url, '/app/') + 5;
	$fin = substr($url, $start, strlen($url) - $start);
	$finarr = explode('/', $fin);
	foreach ($finarr as $fkey => $fval) {
		if ($fval == '') {
			unset($finarr[$fkey]);
		}
	}
	$finarr = array_values($finarr);
	return $finarr;
	
}


// generic fire func
function fireWizard($curLoc, $step) {
	if (!isset($step)) {
		$step = $_SESSION['wizData']['target'];
	} else {
		$forceElse = true;
		if (is_numeric($step)) {
			// set the step in the session
			$_SESSION['wizData']['target'] = $step;
		}
	}


	$locData = urlToArray($curLoc);

	// session 1
	if ($_SESSION['wizData']['target'] == 1) {
		if ($locData[0] == 'filebox') {
			echo 'dataloaded';
			// reset target
			$_SESSION['wizData']['target'] = 0;
			// set the session completion
			$_SESSION['wizData']['completed'][1] = true;

		} else {
			if ($forceElse) {
				echo 'nope';
			}
		}

	// session 2
	} elseif ($_SESSION['wizData']['target'] == 2) {
		
	}


}




// display cross if completed
function dispWizComplete($num) {
	if ($_SESSION['wizData']['completed'][$num]) {
		return ' style="text-decoration: line-through;"';
	}
}
?>