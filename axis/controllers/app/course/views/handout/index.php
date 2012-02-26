<?php
$conID = $this->Command->Parameters[2];

$conData = getContent($conID);

$mysecs = array($sectionID);

$permissionObj = verifyPermissions($conData, -1, $mysecs);
$perLevel = determinePerLevel($conData['_id'], $permissionObj);

$realPermissionObj = verifyPermissions($conData, user('id'), $mysecs);
$realPerLevel = determinePerLevel($conData['_id'], $realPermissionObj);


if (!isset($conID) || $conID == '') {
  $conID = '0';
  $conData['type'] = 1;
  $conData['_id'] = '0';
}

// if it's a folder, gen crumbs
if ($conData['type'] == 1) {
  // construct the crumbs we're allowed to see
  $crumbs = constructCrumbs($permissionObj, $conData['parents'], array("id" => $conData['_id'], "title" => $conData['title'])); 
} elseif ($conData['type'] == 2) {
  // construct the crumbs we're allowed to see
  $crumbs = constructCrumbs($permissionObj, $conData['parents'], array("id" => '-1', "title" => 'skip'));
}

$crumbs = createCrumbs($crumbs);

// if this is root
if (strlen($crumbs) == 1957) {
  // if this is a teacher
  if (user('level') == 3) {
    $crumbs = '<div class="alert-message warning" style="margin-right:15px">
  <p>Below is the content you have shared with this course via <a href="/app/filebox/">My Files</a>.</p>
</div>';
  } elseif (user('level') == 1) {
    $crumbs = '<div class="alert-message warning" style="margin-right:15px">
  <p>Below is the content your teacher has shared with this course.</p>
</div>';
  }

// if this is the top level
} elseif ($conData['type'] == 2 && strlen($crumbs) == 1910) {
  $crumbs = '<div class="fboxNavEl"><a id="fol0" href="/app/course/' . $sectionID . '/handout" class="fboxCrumb js-pjax" onClick="chooseCrumb($(this));"><img src="/assets/app/img/box/root.png" style="height:16px;float:left" /></a></div>';
} else {
  // clean out shared
  $crumbs = str_replace('<a id="folshared" href="/app/filebox/shared?_nav=true" class="fboxCrumb js-pjax" onClick="chooseCrumb($(this));"><img src="/assets/app/img/box/share.png" style="height:16px;float:left;margin-right:4px" /> Shared</a><img src="/assets/app/img/box/arrow.png" class="fbArr" />', '', $crumbs);
  // change all URLs
  $crumbs = str_replace('/app/filebox', '/app/course/' . $sectionID . '/handout', $crumbs);
  $crumbs = str_replace('?_nav=true', '', $crumbs);
}
// <div class="fboxNavEl"></div>
//<img src="/assets/app/img/box/arrow.png" class="fbArr" />

$rightCont .= '<div style="margin-left:14px;height:35px;margin-top:10px">' . $crumbs . '</div>';

if ($conData['type'] == 1) {
  $rightCont .= '<div style="margin-left:7px;margin-top:0px">' . createHandoutDirView($conID, $conData, $sectionID) . '</div>';
} elseif ($conData['type'] == 2) {
  $version = verifyDataAuth('0', $conData);
  $rightCont .= '<div style="margin-right:15px;margin-top:0px">' . createContentView($conID, $conData, $permissionObj, $perLevel, $version) . createCommentView($conID, $conData, $realPermissionObj, $realPerLevel, $version, array("type" => 3, "optID" => $sectionID)) . '</div>';
}

// execute init
$rightCont .= '
<script>
$(document).ready(function() {
   initHandout();
});
</script>';

if ($perLevel < 1) {
  $rightCont = '<br /><br /><center>Oops! You do not have permission to view this.</center>';
}

// show main annoucements
genCoursePage($secData, $courseData, $rightCont, $cappID, 'Hand-out');


?>