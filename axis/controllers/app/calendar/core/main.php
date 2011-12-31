<?php
function insertEvent($uid, $start, $end, $type, $title, $body, $sharedWith) {

	global $mdb;

	// select a collection (analogous to a relational database's table)
	$collection = $mdb->calendar_entries;


	// make sure we have permission to insert this thing
	foreach ($sharedWith as $skey=>$share) {
		if ($share['type'] == 2) {
			if (authSection($share['shareID']) && user('level') == 3) {
				// do nothing, we're good
			} else {
				// remove this, not allowed
				unset($sharedWith[$skey]);
			}
		}
	}



	$obj = array(
		"title" => $title, // owner of this content
		"start" => $start, // unix timestamp
		"end" => $end, // unix timestamp
		"type" => $type, // 1 = assignment, 2 = project, 3 = test/quiz, 4 = event
		"body" => $body, // nothing special
		"uid" => $uid, // creators UID
		"shared_with" => $sharedWith // array of key value (type (1-person, 2-course), shareID)
	);

	$collection->insert($obj);

	return $obj;

}




// update an event
function updateEvent($uid, $eid, $start, $end, $type, $title, $body, $sharedWith) {
	global $mdb;

	// select a collection (analogous to a relational database's table)
	$collection = $mdb->calendar_entries;


	// make sure we have permission to insert this thing
	foreach ($sharedWith as $skey=>$share) {
		if ($share['type'] == 2) {
			if (authSection($share['shareID']) && user('level') == 3) {
				// do nothing, we're good
			} else {
				// remove this, not allowed
				unset($sharedWith[$skey]);
			}
		}
	}



	$up = array(
		"title" => $title, // owner of this content
		"start" => $start, // unix timestamp
		"end" => $end, // unix timestamp
		"type" => $type, // 1 = assignment, 2 = project, 3 = test/quiz, 4 = event
		"body" => $body, // nothing special
		"uid" => $uid, // creators UID
		"shared_with" => $sharedWith // array of key value (type (1-person, 2-course), shareID)
	);

	$collection->update(array('_id' => new MongoId($eid)), array('$set' => $up), array("upsert" => true));

	$up['_id'] = $eid;
	return $up;
}



function writeEvent($writeType, $start, $end, $type, $title, $body, $sharedWith, $eid, $uid) {

	// writeType (1 = insert, 2 = update). eid only applies to 2

	if (!isset($uid)) {
		$uid = user('id');
	}

	$errors = array();

	// make sure there is a title
	if ($title != '') {
		// make sure the title is less than 60 chars
		if (strlen($title) > 60) {
			$errors[] = 'The title you entered is too long.';
		}
	} else {
		$errors[] = 'You forgot to enter a title.';
	}



	// make sure there is a start & end
	if (!is_numeric($start) || !is_numeric($end)) {
		$errors[] = 'You need to enter a start and end date.';
	} elseif ($end < $start) {
		$errors[] = 'End date must be after start date.';
	}


	// if there are no errors, return success
	if (empty($errors)) {
		// insert?
		if ($writeType == 1) {
			$newID = insertEvent($uid, $start, $end, $type, $title, $body, $sharedWith);

		// update?
		} elseif ($writeType == 2) {
			$newID = updateEvent($uid, $eid, $start, $end, $type, $title, $body, $sharedWith, $eid);
		}

		return array('success' => 1, 'data' => $newID);
	} else {
		// fail
		return array('success' => 2, 'data' => $errors);
	}
	
}



// delete an event
function deleteEvent($eventID) {
	global $mdb;
	$collection = $mdb->calendar_entries;
	$collection->remove(array('_id' => new MongoId($eventID)), array('safe' => true));
	return 1;
}



// get a single calendar entry
function getCalEntry($calID) {
	global $mdb;
	// select a collection (analogous to a relational database's table)
	$collection = $mdb->calendar_entries;

	$data = $collection->findOne(array('_id' => new MongoId($calID)));

	return $data;
}


// retrieve calendar entries
function getCalEntries($start, $end, $personal, $courses, $colleagues, $networks, $uid) {

	if (!isset($uid)) {
		$uid = user('id');
	}

	global $mdb;

	// select a collection (analogous to a relational database's table)
	$collection = $mdb->calendar_entries;


	$idArr = array();

	// if we want personal things only
	if ($personal == true) {
		$idArr[] = array('uid' => $uid, 'shared_with' => array());
	}

	// get courses
	$courses = explode(',', $courses);
	// if we have courses
	if (count($courses) > 0) {
		foreach ($courses as $cid) {
			if (authSection($cid)) {
				$idArr[] = array('shared_with.shareID' => (int) $cid, 'shared_with.type' => 2);
			}	
		}
	}

	$params = array('$or' => $idArr);
	$data = $collection->find($params);

	return $data;
	
}




// encode cal entry
function encodeEntry($datas) {
	$pers = determineEventPermissions($datas);
	$typed = determineEvType($datas['type']);
	// create the hover over bubble
	$bubble = createBubble($datas);


	$tempr = array();
	$tempr['id'] = (string) $datas['_id'];
	$tempr['title'] = $datas['title'];
	$tempr['start'] = $datas['start'];
	$tempr['end'] = $datas['end'];
	$tempr['body'] = $bubble;
	$tempr['color'] = $typed['color'];
	$tempr['editable'] = $pers['write'];
	return $tempr;
}


// determine an event's color
function determineEvType($type) {
	// if this is an event
	if ($type == 4) {
		return array('title' => 'Event', 'color' => '#2952A2');
	
	// if this is an assignment
	} elseif ($type == 1) {
		return array('title' => 'Assignment', 'color' => '#2FA325');

	// if this is a project
	} elseif ($type == 2) {
		return array('title' => 'Project', 'color' => '#D2CF00');
	
	// if this is a test/quiz
	} elseif ($type == 3) {
		return array('title' => 'Test/Quiz', 'color' => '#A22828');
		
	}
}


// determine if we can edit this
function determineEventPermissions($eventData, $uid) {
	if (!isset($uid)) {
		$uid = user('id');
	}

	$result = array();
	$result['write'] = false;
	$result['read'] = false;

	if ($uid == $eventData['uid']) {
		$result['write'] = true;
		$result['read'] = true;

	// they aren't the immediate owner. check the shares
	} else {
		// for the time being we only need to check classes.
		// in the future, add network & colleague support.
		foreach ($eventData['shared_with'] as $share) {
			// if were in this class, allow us to read it
			if (authSection($share['shareID'])) {
				// read!
				$result['read'] = true;

				// if were a teacher, let us write to this
				if (user('level') == 3) {
					// write!
					$result['write'] = true;
				}
			}
		}
	}


	// return final result
	return $result;
}



// creates a list of classes, people, etc (only classes for now) that we can view
function cleanShareList($shareList) {
	foreach ($shareList as $skey=>$share) {
		if (!authSection($share['shareID'])) {
			unset($shareList[$skey]);
		}
	}

	return $shareList;
}



// create the hover tip bubble
function createBubble($datas) {
	$cleand = cleanShareList($datas['shared_with']);
	$courseText = '';
	$initArray = array();
	foreach ($cleand as $ent) {
		if ($ent['type'] == 2) {
			$cdata = getSection($ent['shareID']);
			if (!in_array($cdata['course_link'], $initArray)) {
				$courseData = getCourse($cdata['course_link']);
				$courseText .= $courseData['title'] . ', ';
				$initArray[] = $cdata['course_link'];
				
			}
		}
	}
	$courseText = substr($courseText, 0, strlen($courseText) - 2);

	if ($courseText == '') {
		$courseText = 'You';
	}
	$bubble = '<div class="evRounder" style="font-size:11px;background:#222;padding:3px"><strong>' . $courseText . '</strong></div>

	<div style="margin-top:5px;font-size:10px;line-height:1.4">';
	if ($datas['body'] != '') {
		$bubble .= substr($datas['body'], 0, 100) . '...';
	}
	$bubble .= ' <span style="color:#fff;font-weight:bolder">(click to view)</span></div>';


	return $bubble;
}

?>