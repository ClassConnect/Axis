<?php
// if this is a modify command (usually done via fbox)
if ($this->Command->Parameters[0] == 'write') {

  // if we're adding content into filebox
  if ($this->Command->Parameters[1] == 'add') {

    // if we're adding a folder
    if ($this->Command->Parameters[2] == 'comment') {
      require_once('views/write/addComment.php');

    } elseif ($this->Command->Parameters[2] == 'rec') {
      require_once('views/write/addRec.php');

    // if we're adding a folder
    } elseif ($this->Command->Parameters[2] == 'folder') {
      require_once('views/write/addFolder.php');
    
    // if we're adding web content
    } elseif ($this->Command->Parameters[2] == 'web') {
      require_once('views/write/addWebContent.php');

    // if we're adding a google doc
    } elseif ($this->Command->Parameters[2] == 'gdoc') {
      require_once('views/write/addGdoc.php');

    // if we're uploading files
    } elseif ($this->Command->Parameters[2] == 'file') {
      require_once('views/write/uploadFiles.php');


    }


  // if we're moving content...
  } elseif ($this->Command->Parameters[1] == 'move') {
	  // if we're moving via drag
	  if ($this->Command->Parameters[2] == 'drag') {
		  require_once('views/write/dragMove.php');
    //if it's a regular move request (facebox)
	  } else {
     require_once('views/write/move.php'); 
    }

  // if we're copying content...
  } elseif ($this->Command->Parameters[1] == 'copy') {
    require_once('views/write/copy.php');

  // if we're tagging content...
  } elseif ($this->Command->Parameters[1] == 'tags') {
    // if we're hitting the common core
    if ($this->Command->Parameters[2] == 'commoncore') {
      require_once('views/write/commoncore.php');
    } else {
      // no second paramenter? give the default tag file
      require_once('views/write/tags.php');
    }


  // if we're sharing content...
  } elseif ($this->Command->Parameters[1] == 'share') {
    require_once('views/write/share.php');


  // if we're adding a description...
  } elseif ($this->Command->Parameters[1] == 'desc') {
    require_once('views/write/desc.php');


  // if we're deleting content...
  } elseif ($this->Command->Parameters[1] == 'delete') {
    require_once('views/write/delete.php');


  // if we're deleting a comment...
  } elseif ($this->Command->Parameters[1] == 'rm') {
    if ($this->Command->Parameters[2] == 'comment') {
      require_once('views/write/delComment.php');
      
    } elseif ($this->Command->Parameters[2] == 'rec') {
      require_once('views/write/delRec.php');
    }


  // if we're editing content...
  } elseif ($this->Command->Parameters[1] == 'edit') {
    if ($this->Command->Parameters[2] == 'title') {
      require_once('views/write/editTitle.php');
    }

  }



// lets assume that this is a piece of filebox content (or homepage)
} else {
  require_once('views/index.php');
}

?>