<?php
require_once('../../../core/inc/coreInc.php');
require_once('../../../core/inc/func/app/fileBox/main.php');

if (isset($_GET['id'])) {


// get current folder id
if ($_GET['id'] != 0) {
    $curDir = escape($_GET['id']);

} else {
    $curDir = 0;

}


// make sure we can authorize this...
if (auth_dir($curDir, $user_id) == true) {


   if ($curDir != 0) {
       $current_folder = get_dir($curDir);
       $parent = $current_folder['parent_id'];
   } else {
       $parent = -1;
   }

   if ($curDir != 0) {
    echo '<div class="folderPicker" onClick="swapDir(' . $parent . ');"><img src="' . $imgServer . 'gen/arrow_up.png" style="float:left; margin-right:4px" />
           Parent Folder
            </div>';
   }

    $dirList = read_dirFolders($curDir, $user_id);
    foreach ($dirList as $dir) {
        echo '<div class="folderPicker" onClick="selectMe($(this));" onDblClick="swapDir(' . $dir['id'] . ');"><img src="' . $imgServer . 'fileBox/folder.png" style="float:left; margin-right:4px" />
            <input type="radio" class="folderRad" name="target" value="' . $dir['id'] . '" style="display:none" />
            '. $dir['name'] . '
            </div>';
    }

    if (empty($dirList)) {
        echo '<div class="folderPicker">No folders found.</div>';
    }



echo '<input type="radio" name="target" value="' . $curDir . '" checked="checked" style="display:none" />';
}


exit();
}
// issett ID
?>