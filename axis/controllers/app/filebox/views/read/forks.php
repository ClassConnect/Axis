<?php
$conID = $this->Command->Parameters[2];
$dataID = $this->Command->Parameters[3];

// get the data for this content
$cObj = getContent($conID);
$permissionObj = verifyPermissions($cObj, user('id'));
$perLevel = determinePerLevel($cObj['_id'], $permissionObj);

if ($perLevel == 0) {
	echo '<p style="margin:10px;font-size:14px;font-weight:bolder;text-align:center">Oops! You don\'t have permission to view this.</p>
  <button class="btn" onClick="closeBox(); return false" style="float:right;margin:15px">Close</button>';
  exit();
}


// if we're good to go, get all forks
$forkers = getAllForks($conID, $dataID);
$finForkers = array();
foreach ($forkers as $fork) {
	$finForkers[$fork['owner_id']][] = $fork;
}

foreach ($finForkers as $uid=>$forkarr) {
	$numFork = count($forkarr);
	if ($numFork == 1) {
		$fword = 'time';
	} else {
		$fword = 'times';
	}
	echo '<div style="margin: 0px 10px 0px 10px; border-bottom:1px solid #eee;padding-top:5px">
	<img src="' . iconServer() . '50_' . dispUser($uid, 'prof_icon') . '" style="float:left;margin:0px 10px 5px 0px" />
	<div style="font-size:14px;width:290px;float:right"><a href="#" style="font-weight:bolder">' . dispUser($uid, 'first_name') . ' ' . dispUser($uid, 'last_name') . '</a> used this <strong>' . $numFork . ' ' . $fword . '</strong>';

	foreach ($forkarr as $conData) {
		$permissionObj = verifyPermissions($conData, user('id'));
		$perLevel = determinePerLevel($conData['_id'], $permissionObj);
		if ($perLevel > 0) {
			echo '<li style="font-size:12px;margin:3px"><a href="/app/filebox/' . $conData['_id'] . '">' . $conData['title'] . '</a></li>';
		} else {
			echo '<li style="font-size:12px;margin:3px">Used privately</li>';
		}
	}


	echo '</div>
<div style="clear:both"></div>
	</div>';
}


if (empty($finForkers)) {
	if ($cObj['owner_id'] == user('id')) {
		echo '<div style="font-size:14px;font-weight:bolder;text-align:center">Looks like you\'re the only one using this right now!</div>
	<div style="margin: 10px 10px 0 10px">Make sure that you have <a href="#" onClick="jQuery.facebox({ 
    ajax: \'/app/filebox/write/share/?conIDs=' . $conID . '\'
  }); return false;">shared this publicly and/or with your colleagues</a> to ensure that others can use it!
  </div>

  <button class="btn" onClick="closeBox(); return false" style="float:right;margin:15px 15px 15px 0px">Close</button>';
		
	} else {
		echo '<div style="font-size:14px;font-weight:bolder;text-align:center">Looks like no one else has used this...yet!</div>
	<div style="margin: 10px 10px 0 10px">
	Be the first person to use this! If you like it, be sure to click the "Recommend" button!
  </div>


  <button class="btn" onClick="closeBox(); return false" style="float:right;margin:15px 15px 15px 0px">Close</button>

  <button class="btn primary" style="font-weight:bolder;float:right;margin:15px 5px 15px 0px" onClick="jQuery.facebox({ 
    ajax: \'/app/filebox/write/copy/?conIDs=' . $conID . '\'
  }); return false;"><img src="/assets/app/img/box/addfile.png" style="height:14px;float:left;margin-top:2px;margin-right:8px" /> Add to your Filebox</button>';
	}

} else {
	echo '<button class="btn" onClick="closeBox(); return false" style="float:right;margin:10px 10px 5px 0px">Close</button>';
}
?>