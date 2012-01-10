<?php
// if we're creating a document
if ($this->Command->Parameters[0] == 'create') {
	require_once('views/add.php');

// if we're editing a document
} elseif ($this->Command->Parameters[0] == 'edit') {
	require_once('views/edit.php');

// if we're saving a document
} elseif ($this->Command->Parameters[0] == 'save') {
	require_once('views/save.php');

// this is the main page for docs
} else {
  require_once('views/index.php');
}

?>