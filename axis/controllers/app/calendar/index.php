<?php
// if this is a modify command
if ($this->Command->Parameters[0] == 'write') {

  // if we're adding an entry
  if ($this->Command->Parameters[1] == 'add') {
    require_once('views/add.php');
  
  // if we're editing an entry
  } elseif ($this->Command->Parameters[1] == 'edit') {
  	require_once('views/edit.php');

  // if we're deleting an entry
  } elseif ($this->Command->Parameters[1] == 'delete') {
  	require_once('views/delete.php');

  // if we're deleting an entry
  } elseif ($this->Command->Parameters[1] == 'malleable') {
  	require_once('views/malleable.php');

  }


// if we're viewing an event
} elseif ($this->Command->Parameters[0] == 'view') {
	require_once('views/view.php');


// if we're viewing a calendar feed
} elseif ($this->Command->Parameters[0] == 'feed') {
	require_once('views/feed.php');


// this is the main page for the calendar
} else {
  require_once('views/index.php');
}

?>