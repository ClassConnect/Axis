<?php
require_once('../../../core/inc/coreInc.php');
require_once('../../../core/inc/func/app/fileBox/main.php');

// main file type
$type = escape($_GET['type']);
$typeArr = explode(',', $type);

// subfile type (not necessarily required
$addType = escape($_GET['addType']);
$addArr = explode(',', $addType);

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
   }

   if ($curDir != 0) {
    echo '<div class="folderPicker" onClick="swapDir(' . $parent . ');"><img src="' . $imgServer . 'gen/arrow_up.png" style="float:left; margin-right:4px" />
           Parent Folder
            </div>';
   }

    $dirList = read_dirFolders($curDir, $user_id);
    foreach ($dirList as $dir) {
        echo '<div class="folderPicker" onClick="swapDir(' . $dir['id'] . ');"><img src="' . $imgServer . 'fileBox/folder.png" style="float:left; margin-right:4px" />
            '. $dir['name'] . '
            </div>';
    }
$count = 0;
    $fileList = read_dirFiles($curDir, $user_id);
    foreach ($fileList as $file) {
        if (in_array($file['format'], $typeArr)) {
            if ($file['format'] == 1) {
                if (($addType != '') && in_array($file['ext'], $addArr)) {
                    $allow = true;
                    $file['name'] = $file['name'] . '.' . $file['ext'];
                } else {
                    $allow = false;
                }
                
            } else {
                $allow = true;
            }
        if ($allow) {
$count++;
        echo '<div class="folderPicker" onClick="selectMe($(this));"><img src="' . $imgServer . 'fileBox/formats/' . $file['icon'] . '.png" style="float:left; margin-right:4px" />
            <input type="radio" class="folderRad" name="target" value="' . $file['id'] . '" style="display:none" />
            '. $file['name'] . '
            </div>';

        }


        }

    }

    if (empty($dirList) && $count == 0) {
        echo '<div class="folderPicker">No content found.</div>';
    }




}


exit();
}
// issett ID
?>