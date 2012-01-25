<?php
appHeader($cObj['title'], '<script type="text/javascript" src="/assets/app/js/edit/jquery.tinymce.js"></script><script src="/assets/app/js/filebox.js"></script><script type="text/javascript">currentType = ' . $cObj['type'] . '; currentCon = \'' . $conID . '\'; $(document).ready(function() { initFilUI(); });</script><link href="/assets/app/filebox.css" rel="stylesheet">', 2);
// construct the crumbs we're allowed to see
$crumbs = constructCrumbs($permissionObj, $cObj['parents'], array("id" => '-1', "title" => 'skip'));


// generate our sidebar
$sidebar = createFilBar($cObj, $permissionObj);
// generate main area
$main = createContentView($conID, $cObj, $permissionObj, $perLevel, $dataID) . createFilUI() . createCommentView($conID, $cObj, $permissionObj, $perLevel, $dataID);
// generate our nav crumbs
$crumbs = createCrumbs($crumbs);

// display all of the content we generated
echo genTemplate($sidebar, $main, $crumbs, $conID);


appFooter();
?>