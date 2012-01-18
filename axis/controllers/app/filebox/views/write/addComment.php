<?php
$cmtData = addConComment($_POST['conid'], $_POST['dataid'], $_POST['comlevel'], $_POST['comment_text']);

echo genCommentFeed(array($cmtData['data']), $cmtData['permissionObj'], $cmtData['perLevel']);
?>