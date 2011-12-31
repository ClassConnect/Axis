<?php
if ($this->Command->Parameters[1] == 'enroll') {
	// if we're adding a course
	require_once('enroll.php');


} else {
	require_once('default.php');	
}
?>