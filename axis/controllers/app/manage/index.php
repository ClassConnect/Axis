<?php
// if this is to manage courses
if ($this->Command->Parameters[0] == 'courses') {
  require_once('axis/controllers/app/manage/courses/core/main.php');
  // if this is a student
  if (user('level') == 1) {
    require_once('axis/controllers/app/manage/courses/student/index.php');

  // if this is a teacher
  } elseif (user('level') == 3) {
    require_once('axis/controllers/app/manage/courses/teacher/index.php');
  }
 
}

?>