<?php
function genSharedParams() {
	$params = array();
	$params[] = array('permissions.type'=>3, 'permissions.shared_id'=>1);
	if (checkSession()) {
		// add all of our courses to the array
		if (user('level') == 1) {
			foreach (getSections() as $secd) {
				$params[] = array('permissions.type'=>2, 'permissions.shared_id'=>(int) $secd['section_id']);
			}
		}

		// add our UID to the permissions list
		$params[] = array('permissions.type'=>1, 'permissions.shared_id'=>(int) user("id"));
	}

	return $params;
}


function getSharedNumber($owner) {
	global $mdb;

	// select a collection (analogous to a relational database's table)
	$collection = $mdb->fbox_content;

	$params = genSharedParams();
	$finalq = array('$or' => $params, "owner_id" => (string) $owner);

	$data = $collection->count($finalq);

	return $data;
}


// this pulls all files shared publicly and/or shared with the logged in user
function getSharedChildren($owner) {

	global $mdb;

	// select a collection (analogous to a relational database's table)
	$collection = $mdb->fbox_content;

	$params = genSharedParams();

	$finalq = array('$or' => $params, "owner_id" => (string) $owner);
	$data = $collection->find($finalq)->sort(array('last_update'=>-1));

	$finalArr = array();
	// if we're logged in, split up the shared items into public and private arrays
	if (checkSession()) {
		foreach($data as $d1) {
			// if this isn't public, it can only be private
			if (!verifyPublic($d1)) {
				$finalArr['private'][] = $d1;
			} else {
				$finalArr['public'][] = $d1;
			}
		}

	} else {
		$finalArr['public'] = $data;
		$finalArr['private'] = array();
	}

	return $finalArr;
	
}




// create the HTML for the dir list
function createSharedDirView($owner) {

	$list = '';

	// okay, now lets pull in the directory
	$children = getSharedChildren($owner);

	if (!empty($children['private'])) {
		$list .= '<div style="margin-top:20px;margin-left:10px;margin-bottom:5px">
	<span class="commentbox-label selecterd" style="font-size:12px;">Shared with you</span>
	</div>';

		foreach ($children['private'] as $child) {
			$list .= genDirBar($child);
		}
			
	}


	if (!empty($children['public'])) {
		$list .= '<div style="margin-top:20px;margin-left:10px;margin-bottom:5px">
		<span class="commentbox-label selecterd" style="font-size:12px;">Shared publicly</span>
		</div>';
		foreach ($children['public'] as $child) {
			$list .= genDirBar($child);
		}
	}

	  if (empty($children['public']) && empty($children['private'])) {
	  		$list .= '<div style="margin-top:20px;font-weight:bolder;font-size:18px;color:#666;text-align:center">' . dispUser($owner, 'first_name') . ' ' . dispUser($owner, 'last_name') . ' hasn\'t shared anything...yet.
	    </div>';
	    
	  }


	  return $list;
}


function genDirBar($child) {
	$list = '';
	if ($child['type'] == 1) {
	    	$class = "fboxFolder";
	    	$icon = '<img src="/assets/app/img/box/type/folder.png" class="conicon" />';
	    } else {
	    	$class = "fboxContent";
	    	$icon = createConIcon($child);
	    }

	    $lastMod = date('F jS, Y', $child['last_update']);
	    $lastModder = $child['last_update_by'];

	    $list .= '<div id="' . $child['_id'] . '" class="fboxElement ' . $class . '">
	    ' . $icon  . '
	    <div class="conmain" style="margin-left:45px">
	    	<div class="optarea">
	    		<div style="margin-top:17px;margin-left:50px">
	    			
	    		</div>
	    	</div>
	    	<div class="mainarea">
	    		<div class="contitle">
	    		<a class="js-pjax" href="/app/filebox/' . $child['_id'] . '">' . createConTitle($child) . '</a>
	    		</div>
	    		<div class="conlast">
	    		Updated ' . $lastMod . '</a>
	    		</div>
	    	</div>
	    </div>
	    </div>';
	return $list;
}

?>