<?php

function getHandoutChildren($contentID, $courseID) {

	global $mdb;

	// select a collection (analogous to a relational database's table)
	$collection = $mdb->fbox_content;

	// if this is the root directory
	if ($contentID == '0') {

		$params = array('permissions.type'=>2, 'permissions.shared_id'=>(int) $courseID);
		$data = $collection->find($params);

	} else {
		$data = $collection->find(array('parent.id'=>$contentID));
	}

	$folArr = array();
	$filArr = array();

	foreach ($data as $obe) {
		if ($obe['type'] == 1) {
			$folArr[] = $obe;
		} elseif ($obe['type'] == 2) {
			$filArr[] = $obe;
		}
	}

	$folArr = sort2d ($folArr, 'title', 'asc', true); 
	$filArr = sort2d ($filArr, 'title', 'asc', true);
	$final = array_merge($folArr, $filArr);

	return $final;
	
}




// create the HTML for the dir list
function createHandoutDirView($conID, $conObj, $secID) {

	// show description area if it's not the home or shared folder
	if ($conID == '0') {
		// do nothing
	} else {

		
		if ($conObj['body'] !== '') {
			$list .= '<div class="descMain"><div class="descText">' . $conObj['body'] . '</div></div>';
		}
		
	}


	// okay, now lets pull in the directory
	$children = getHandoutChildren($conID, $secID);
	$count = 0;
	foreach ($children as $child) {
	    $count++;
	    //$list .= '<div style="border-bottom:1px solid #ccc;padding:7px;font-size:18px"><a class="js-pjax" href="/app/filebox/' . $child['_id'] . '">' . $child['title'] . ' - (' . $child['versions'][count($child['versions']) - 1]['timestamp'] . ')</a></div>';

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
	    		<a class="js-pjax" href="/app/course/' . $secID . '/handout/' . $child['_id'] . '">' . createConTitle($child) . '</a>
	    		</div>
	    		<div class="conlast">
	    		Updated ' . $lastMod . '</a>
	    		</div>
	    	</div>
	    </div>
	    </div>';

	}

	  if ($count == 0) {
	  		$list .= '<div style="margin-top:20px;font-weight:bolder;font-size:20px;color:#666;text-align:center">This folder is empty.
	    </div>';
	    
	  }


	  return $list;
}

?>