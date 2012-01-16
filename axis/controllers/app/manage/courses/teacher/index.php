<?php
if ($this->Command->Parameters[1] == 'add') {
	// if we're adding a course
	if ($this->Command->Parameters[2] == 'course') {
		require_once('addCourse.php');
	// if we're adding a section
	} elseif ($this->Command->Parameters[2] == 'section') {
		require_once('addSection.php');
	}


// if we're editing something
} elseif ($this->Command->Parameters[1] == 'edit') {
	// if we're editing a course
	if ($this->Command->Parameters[2] == 'course') {
		require_once('editCourse.php');
	// if we're adding a section
	} elseif ($this->Command->Parameters[2] == 'section') {
		require_once('editSection.php');
	// if we're editing students
	} elseif ($this->Command->Parameters[2] == 'students') {
		require_once('editStudents.php');
	}


// if we're archiving something
} elseif ($this->Command->Parameters[1] == 'archive') {
	require_once('archive.php');


// if we're resetting a section access code
} elseif ($this->Command->Parameters[1] == 'reset_code') {
	require_once('resetCode.php');


} else {
	require_once('default.php');	
}
?>