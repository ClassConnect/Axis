<?php

if ($this->Command->Parameters[0] == 'commoncore') {
	require_once('views/commoncore.php');
} else {
	require_once('views/index.php');
}

?>