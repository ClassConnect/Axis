<?php
// clear notis for both
clearNotis();
// if this is a teacher
if (user('level') == 3) {
  require_once('views/teacher.php');

// if this is a student
} elseif (user('level') == 1) {
  require_once('views/student.php');
  
}
?>