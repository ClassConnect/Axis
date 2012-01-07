<?php
// if we're creating a lecture
if ($this->Command->Parameters[0] == 'create') {
	require_once('views/add.php');


// if we're editing a lecture
} elseif ($this->Command->Parameters[0] == 'edit') {
	require_once('views/edit.php');

// if we're loading a lecture
} elseif ($this->Command->Parameters[0] == 'load') {


// if we're saving a lecture
} elseif ($this->Command->Parameters[0] == 'save') {


// this is the main page for livelecture
} else {
  require_once('views/index.php');
}

?>