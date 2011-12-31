<?php
// set the ttle of the page
echo '<title>' . $cObj['title'] . '</title>';

// construct the crumbs we're allowed to see
$crumbs = constructCrumbs($permissionObj, $cObj['parents'], array("id" => $cObj['_id'], "title" => $cObj['title']));

// generate our sidebar
$sidebar = cleanOutJS(createFolBar($cObj, $permissionObj));
// generate main dir list
$main = cleanOutJS(createDirView($conID, $cObj, $permissionObj, $perLevel));

if (isset($_GET['_nav'])) {
  $showCrumbs = 0;
} else {
  $showCrumbs = 1;
}

// generate our nav crumbs
$crumbs = cleanOutJS(createCrumbs($crumbs));

?>
<script type="text/javascript">
currentCon = '<?= $conID; ?>';
accessLevel = <?= $perLevel; ?>;
$(document).ready(function() {
  dispFolview('<?= $showCrumbs; ?>');
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