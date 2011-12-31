<?php
// set the ttle of the page
echo '<title>' . $cObj['title'] . '</title>';

// construct the crumbs we're allowed to see
$crumbs = constructCrumbs($permissionObj, $cObj['parents'], array("id" => '-1', "title" => 'skip'));


// generate our sidebar
$sidebar = cleanOutJS(createFilBar($cObj, $permissionObj));
// generate main dir list
$main = cleanOutJS(createContentView($conID, $cObj, $permissionObj, $perLevel, $dataID));

if (isset($_GET['_nav'])) {
  $crumbs = 0;
} else {
  // generate our nav crumbs
  $crumbs = cleanOutJS(createCrumbs($crumbs));
}
?>
<script type="text/javascript">
currentCon = '<?= $conID; ?>';
$(document).ready(function() {
  dispFilview();
});
</script>

<div id="tempSidebar">
<?= $sidebar; ?>
</div>

<div id="tempMain">
<?= $main; ?>
</div>

<div id="tempCrumbs">
<?= $crumbs; ?>
</div>