<?php
require_once('axis/controllers/app/filebox/core/main.php');

// get & authenticate content
if (empty($this->Command->Parameters[1])){
  $conID = '0';
} else {
  $conID = $this->Command->Parameters[1];
}

echo createPickerView($conID);


?>