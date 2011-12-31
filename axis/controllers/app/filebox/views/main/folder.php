<?php
// only show header if there is not pjax attr
if (!isset($_GET['_pjax'])) {
  require_once('pjaxOff/folder.php');
} else {
  require_once('pjaxOn/folder.php');
}
?>