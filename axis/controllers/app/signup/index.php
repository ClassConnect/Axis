<?php
// if this is a teacher signup
if ($this->Command->Parameters[0] == 'teacher') {
	// require the teacher view
	require_once('views/teacher.php');


// if this is a student signup
} elseif ($this->Command->Parameters[0] == 'student') {
	require_once('axis/controllers/app/manage/courses/core/main.php');
	// require the student view
	require_once('views/student.php');

}
?>