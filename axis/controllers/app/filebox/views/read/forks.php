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
	echo '<div style="margin: 0px 10px 0px 10px">
	<img src="' . iconServer() . '50_' . dispUser($fork['owner_id'], 'prof_icon') . '" style="float:left;margin:0px 10px 5px 0px" />
	<span style="font-size:14px"><a href="#" style="font-weight:bolder">' . dispUser($fork['owner_id'], 'first_name') . ' ' . dispUser($fork['owner_id'], 'last_name') . '</a> used this <strong>' . count($forkarr) . ' times.</strong></span>


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

}
?>