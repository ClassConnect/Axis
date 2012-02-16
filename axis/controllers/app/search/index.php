<?php

if ($this->Command->Parameters[0] == 'commoncore') {
	require_once('views/commoncore.php');

} elseif ($this->Command->Parameters[0] == 'retrieve') {
	require_once('views/retrieve.php');
} else {
	require_once('views/index.php');
}

?>