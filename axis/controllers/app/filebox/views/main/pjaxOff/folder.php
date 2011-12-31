<?php
appHeader($cObj['title'], '<script type="text/javascript" src="/assets/app/js/edit/jquery.tinymce.js"></script><script src="/assets/app/js/filebox.js"></script><script type="text/javascript">currentType = ' . $cObj['type'] . '; currentCon = \'' . $conID . '\'; accessLevel = ' . $perLevel . '; $(document).ready(function() { initFolUI(); });</script><link href="/assets/app/filebox.css" rel="stylesheet">', 2);
// construct the crumbs we're allowed to see
$crumbs = constructCrumbs($permissionObj, $cObj['parents'], array("id" => $cObj['_id'], "title" => $cObj['title']));

// generate our sidebar
$sidebar = createFolBar($cObj, $permissionObj);
// generate main dir list
$main = createDirView($conID, $cObj, $permissionObj, $perLevel);
// generate our nav crumbs
$crumbs = createCrumbs($crumbs);

// display all of the content we generated
echo genTemplate($sidebar, $main, $crumbs, $conID);


appFooter();
?>