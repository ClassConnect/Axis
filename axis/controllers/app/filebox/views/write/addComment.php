<?php
$cmtData = addConComment($_POST['conid'], $_POST['dataid'], $_POST['comlevel'], $_POST['comment_text'], $_POST['optID']);

echo genCommentFeed(array($cmtData['data']), $cmtData['conID'], $cmtData['dataID'], $cmtData['permissionObj'], $cmtData['perLevel']);
?>