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

	$url = str_replace('#', '', $url);

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
	// set the root of the guiders
	$groot = 'axis/controllers/app/common/wizard/guides/';


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


	// destroy the wizard
	if ($_SESSION['wizData']['target'] == -1) {
		// reset the session
		destroyWizard();
		return loadWizJS($groot . 'destroy.js');

	// first init
	} elseif ($_SESSION['wizData']['target'] == 0 && !isset($_SESSION['wizData']['completed'][0])) {
		$_SESSION['wizData']['completed'][0] = true;
		return loadWizJS($groot . 'first.js');

	// main filebox
	} elseif ($_SESSION['wizData']['target'] == 1) {
		if ($locData[0] == 'filebox') {
			// reset target
			$_SESSION['wizData']['target'] = 0;
			// set the session completion
			$_SESSION['wizData']['completed'][1] = true;

			return loadWizJS($groot . 'filebox/main.js');

		} else {
			// only return this if we initialized this via JS
			if ($forceElse) {
				return loadWizJS($groot . 'filebox/direct.js');
			}
		}

	// adding tags
	} elseif ($_SESSION['wizData']['target'] == 2) {
		if ($locData[0] == 'filebox') {
			// reset target
			$_SESSION['wizData']['target'] = 0;
			// set the session completion
			$_SESSION['wizData']['completed'][2] = true;

			return loadWizJS($groot . 'filebox/standards.js');

		} else {
			// only return this if we initialized this via JS
			if ($forceElse) {
				return loadWizJS($groot . 'filebox/direct.js');
			}
		}


	// sharing with colleagues
	} elseif ($_SESSION['wizData']['target'] == 3) {
		if ($locData[0] == 'filebox') {
			// reset target
			$_SESSION['wizData']['target'] = 0;
			// set the session completion
			$_SESSION['wizData']['completed'][3] = true;

			return loadWizJS($groot . 'filebox/collaborate.js');

		} else {
			// only return this if we initialized this via JS
			if ($forceElse) {
				return loadWizJS($groot . 'filebox/direct.js');
			}
		}


	// adding/sharing with courses
	} elseif ($_SESSION['wizData']['target'] == 4) {
		if ($locData[0] == 'manage' && $locData[1] == 'courses') {
			// reset target
			$_SESSION['wizData']['target'] = 0;
			// set the session completion
			$_SESSION['wizData']['completed'][4] = true;

			return loadWizJS($groot . 'courses/add.js');

		} else {
			// only return this if we initialized this via JS
			if ($forceElse) {
				return loadWizJS($groot . 'courses/direct.js');
			}
		}


		
	}


}



// load a file and put script tags around it
function loadWizJS($file) {
	$fin = file_get_contents($file);
	$fin = '<script>guiders.hideAll();' . $fin . '</script>';
	return $fin;
}


// display cross if completed
function dispWizComplete($num) {
	if ($_SESSION['wizData']['completed'][$num]) {
		return 'wizard-crossed';
	}
}
?>