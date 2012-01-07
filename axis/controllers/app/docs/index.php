<?php
// if we're creating a document
if ($this->Command->Parameters[0] == 'create') {
	require_once('views/add.php');

// if we're editing a document
} elseif ($this->Command->Parameters[0] == 'edit') {


// this is the main page for docs
} else {
  require_once('views/index.php');
}

?>