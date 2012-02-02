<?php
function insertContent($uid, $parent, $type, $title, $body, $permissions, $tags, $standards, $format, $content) {
	// init return array
	$retArr = array();

	// substr title to max 60
	$title = substr($title, 0, 60);

	// load parent information, authenticate our permissions
	$parentData = getContent($parent);
	$permissionObj = verifyPermissions($parentData, $uid);
	$perLevel = determinePerLevel($parentData['_id'], $permissionObj);


	if ($perLevel == 2) {

		// initialize the update query array
		$updateParams = array();

		// if this is content (not a folder)
		if (isset($format)) {
			$updateParams['files'] = 1;

			$content['format'] = $format;
			$dataID = insertData($content);
			$dataID = (string) $dataID;
			$retArr['verID'] = $dataID;
			if ($format == 1) {
				$extData = $content['ext'];
				$sizeData = (int) $content['size'];


				$updateParams['total_size'] = (int) $content['size'];

			} else {
				$extData = '';
				$sizeData = 0;
			}

		} else {
			// no format, this is a folder
			$updateParams['folders'] = 1;

			$format = 0;
			$dataID = 0;
			$extData = '';
			$sizeData = 0;
		}

		global $mdb;

		// select a collection (analogous to a relational database's table)
		$collection = $mdb->fbox_content;

		//$permissions[] = array('type' => '1', 'shared_id' => '9', 'auth_level' => '2');
		//$permissions[] = array('type' => '1', 'shared_id' => '10', 'auth_level' => '2');

		// if we don't own this, set the owner as the real guy
		if ($permissionObj['isOwner'] != 1 && $parent != 0) {
			$owner_id = $parentData['owner_id'];
		} else {
			$owner_id = $uid;
		}


		$parentPermissions = $parentData['parentPermissions'];

		// cycle through our parent's permissions and put them in parent permissions
		foreach ($parentData['permissions'] as $pdata) {
			// type 1 = person, type 2 = class, type 3 = network (coming soon)
			$parentPermissions[] = array('type' => $pdata['type'], 'folder_id' => (string) $parentData['_id'], 'shared_id' => $pdata['shared_id'], 'auth_level' => $pdata['auth_level']);
			// 2 = r/w
			// 1 = r only
		}



		$parent = array("id" => $parent, "title" => $parentData['title']);
		$parents = $parentData['parents'];
		$parents[] = $parent;
		if (empty($parentData['parentTags'])) {
			$parentTags = $parentData['tags'];
		} else {
			if (empty($parentData['tags'])) {
				$parentTags = $parentData['parentTags'];
			} else {
				$parentTags = array_merge($parentData['parentTags'], $parentData['tags']);
			}
		}

		
		$obj = array(
			"owner_id" => $owner_id, // owner of this content
			"type" => $type, // 1 = folder, 2 = file
			"parent" => $parent, // parent folder
			"parents" => $parents, // all parents
			"parentPermissions" => $parentPermissions, // permissions from parents
			"permissions" => $permissions, // permissions for this content object (unique)
			"parentTags" => $parentTags, // tags from parents (id = userid, title = tagtitle, type 1-grade level, 2-state standard (commoncore))
			"tags" => $tags, // tags for this content object (unique)
			"title" => htmlspecialchars($title), // content title
			"body" => htmlspecialchars($body), // content description/body
			"format" => $format, // format (file, web link, etc)
			"versions" => array(array("id" => $dataID, "uid" => $uid, "timestamp" => date("U"), "comments_priv" => array(), "comments_pub" => array(), "public" => 0, "size" => $sizeData, "ext" => $extData)), // versions
			"last_update" => date("U"), // for easy access, show last update
			"last_update_by" => $uid, // person who last updated
			"folders" => 0, // number of folders in here
			"files" => 0, // number of files in here
			"total_size" => $sizeData, // total size in bytes
			"forkedFrom" => '0', // origin fork contentID hash
			"forkHash" => '0', // origin fork DS hash
			"forkStamp" => 0 // timestamp of fork

		);

		$collection->insert($obj);



		// object inserted successfully. lets update the parents.
		$setData = array("last_update" => date("U"), "last_update_by" => $uid);

		$rent_ids = array();
		foreach ($parentData['parents'] as $rentData) {
			if ($rentData['id'] != '0' || $rentData['id'] != 0) {
				$rent_ids[] = array('_id' => new MongoId($rentData['id']));
			}
		}
		// also update our parent
		$rent_ids[] = array('_id' => new MongoId($parentData['_id']));
		$final = array('$or' => $rent_ids);

		updateParents($final, $updateParams, $setData);

		// insert the feed
		insertFboxNoti(1, $permissions, $parentPermissions, $owner_id, $obj);


		// finally, lets return the new object ID
		$retArr['conID'] = (string) $obj['_id'];
		return $retArr;

	// if per level == 2
	}

}


// update a content's title
function updateTitle($conID, $title, $uid) {

	if (!isset($uid)) {
		$uid = user('id');
	}


	// make sure there is a title
	if ($title != '') {
		// make sure the title is less than 60 chars
		if (strlen($title) > 60) {
			return array('The name you entered is too long.');
		}
	} else {
		return array('You forgot to enter a new name.');
	}

	$conData = getContent($conID);
	$permissionObj = verifyPermissions($conData, $uid);
	$perLevel = determinePerLevel($conData['_id'], $permissionObj);

	if ($perLevel == 2) {

		global $mdb;
	  	$collection = $mdb->fbox_content;

		// substr title to max 60
		$title = substr($title, 0, 60);

		$title = htmlspecialchars($title);

		// update local
		$up = array();
		$up["title"] = $title;
		// update this
		$collection->update(array('_id' => new MongoId($conID)), array('$set' => $up));


		// if this is a folder, update descendants
		if ($conData['type'] == 1) {

			// update all parent datas
			$up = array();
			$up["parent.title"] = $title;
			// update this
			$collection->update(array('parent.id' => $conID), array('$set' => $up), array("multiple" => true));


			// update all parents datas
			$numd = count($conData['parents']);
			$titleStr = 'parents.' . $numd . '.title';
			$idStr = 'parents.' . $numd . '.id';
			$up = array();
			$up[$titleStr] = $title;
			// update this
			$collection->update(array($idStr => $conID), array('$set' => $up), array("multiple" => true));


		}


		return 1;


	// perlevel == 2
	} else {
		return array("You don't have permission to edit this.");
	}


	
}


// update parents function
function updateParents($parents, $incs, $sets) {

	global $mdb;

	// select a collection (analogous to a relational database's table)
	$collection = $mdb->fbox_content;

	$updates = array();

	if (isset($incs)) {
		$updates['$inc'] = $incs;
	}

	if (isset($sets)) {
		$updates['$set'] = $sets;
	}

	$collection->update($parents, $updates, array("multiple" => true));
	
}


// update descendants
function updateDescendantForks($batchObj, $num, $single) {
	global $mdb;

	// select a collection (analogous to a relational database's table)
	$collection = $mdb->fbox_content;

	$rent_ids = array();

	foreach ($batchObj as $tObj) {
		$rent_ids[] = array('_id' => new MongoId((string) $tObj['_id']));

		if (!isset($single)) {
			$rent_ids[] = array('parent.id' => (string) $tObj['_id']);
			$rent_ids[] = array('parents.id' => (string) $tObj['_id']);
		}
	}

	$finalq = array('$or' => $rent_ids);

	$updateParams = array();
	// if this is a folder

	$updateParams['versions.0.forkTotal'] = $num;

	$updates['$inc'] = $updateParams;

	$collection->update($finalq, $updates, array("multiple" => true));
}


// insert data into the fbox_datastore
function insertData($content) {
	global $mdb;

	$content['last_update'] = date("U");

	// select a collection (analogous to a relational database's table)
	$collection = $mdb->fbox_datastore;


	$ins = $collection->insert($content);

	return $content['_id'];

}



function moveContent($target, $conIDs, $uid) {
	global $mdb;
	$collection = $mdb->fbox_content;

	// if target == 0, set up fake parents and shit

	// init passthrough variable
	$clear = true;

	// if the uid isn't present, grab the current session id
	if (!isset($uid)) {
		$uid = user('id');
	}

	// load parent information, authenticate our permissions
	$targData = getContent($target);
	$newOwner = $targData['owner_id'];
	if ($target == '0') {
		$newOwner = $uid;
	}
	$permissionObj = verifyPermissions($targData, $uid);
	$perLevel = determinePerLevel($targData['_id'], $permissionObj);
	// make sure that this is a folder
	if (($targData['type'] != 1 || $perLevel != 2) && $target != '0')  {
		$clear = false;
	}


	// get the data for the content about to be moved
	$batchObj = getBatchContent($conIDs);
	$batchPer = verifyBatchPermissions($batchObj, $uid);
	$batchLevel = determinePerLevel('temp', $batchPer);

	// calculate total size of stuff being moved
	$placeID = 0;
	$sizeTot = 0;
	$folTot = 0;
	$filTot = 0;
	$donSub = array();
	foreach ($batchObj as $cObj) {
		// check if we're putting a folder in a folder
		foreach ($targData['parents'] as $parChk) {
			if ($cObj['_id'] == $parChk['id'] || $targData['_id'] == $cObj['_id']) {
				return array("You cannot put a folder within itself.");
			}
		}

		// public -> private
		if (verifyPublic($cObj) && !verifyPublic($targData)) {
			// verify that there's enough room to do this
			if ($cObj['owner_id'] == $targData['owner_id']) {
				if (!checkStorage($cObj['total_size'], $cObj['owner_id'])) {
					return array("There is not enough room in the specified folder.");
				}
				// double the storage to add
				incStorage($cObj['total_size'], $targData['owner_id']);

			} else {
				if (!checkStorage($cObj['total_size'], $targData['owner_id'])) {
					return array("There is not enough room in the specified folder.");
				}

				// dont subract
				$donSub[] = (string) $cObj['_id'];
			}

		// if this is public -> public
		} elseif (verifyPublic($cObj) && verifyPublic($targData)) {
			// verify that there's enough room to do this
			if ($cObj['owner_id'] != $targData['owner_id']) {
				// dont subract
				$donSub[] = (string) $cObj['_id'];
				// subtract from new parent to compensate
				incStorage(-$cObj['total_size'], $targData['owner_id']);


			}

		// if this is private to public (fuck this dude)
		} elseif (!verifyPublic($cObj) && verifyPublic($targData)) {
			// if same owner
			if ($cObj['owner_id'] == $targData['owner_id']) {
				$pubtot = 0;
				$curID = (string) $cObj['_id'];
				$curDesc = getDescendants($curID);
				foreach ($curDesc as $dec1) {
					if (verifyPublic($dec1) && $dec1['type'] == 2) {
						$pubtot += $dec1['total_size'];
					}
				}

				$totsub = ($cObj['total_size'] - $pubtot);
				incStorage(-$totsub, $targData['owner_id']);

			// different owner, offset add
			} else {
				// subtract from new user
				incStorage(-$cObj['total_size'], $targData['owner_id']);
			}


		}

		// set userid, increment size, folder and file count
		$placeID = $cObj['owner_id'];
		$sizeTot += $cObj['total_size'];
		$folTot += $cObj['folders'];
		$filTot += $cObj['files'];

		// increment by 1 depending on this content type
		if ($cObj['type'] == 1) {
			$folTot++;
		} elseif ($cObj['type'] == 2) {
			$filTot++;
		}
	}

	// not the same person? size check
	if ($targData['owner_id'] != $placeID) {
		if (!checkStorage($sizeTot, $newOwner)) {
			return array("There is not enough room in the specified folder.");
		}
	}

	// format permissions
	foreach ($targData['permissions'] as $per) {
		$per['folder_id'] = (string) $targData['_id'];
		$targData['parentPermissions'][] = $per;
	}


	// user needs to have r/w to both of these
	if ($batchLevel == 2 && $clear != false) {
		// increment user's storage
		incStorage($sizeTot, $newOwner);

		// send notifications
		insertFboxNoti(2, $targData['permissions'], $targData['parentPermissions'], $targData['owner_id'], $targData);

		foreach ($batchObj as $conObj) {

			// lets update our current parents (subtract)
			// objects deleted successfully. lets update the parents.

			$rent_ids = array();
			foreach ($conObj['parents'] as $rentData) {
				if ($rentData['id'] != '0' || $rentData['id'] != 0) {
					$rent_ids[] = array('_id' => new MongoId($rentData['id']));
				}
			}
			// also update our parent
			$rent_ids[] = array('_id' => new MongoId($conObj['parent']['id']));
			$finalq = array('$or' => $rent_ids);

			$updateParams = array();
			// if this is a folder
			if ($conObj['type'] == 1) {
				$conObj['folders']++;
			} else {
				$conObj['files']++;
			}

			$updateParams['files'] = -$conObj['files'];
			$updateParams['folders'] = -$conObj['folders'];
			$updateParams['total_size'] = -$conObj['total_size'];

			// update this user's total storage if no sub exists
			if (!in_array((string) $conObj['_id'], $donSub)) {
				incStorage($updateParams['total_size'], $conObj['owner_id']);
			}
			// update the parents
  			updateParents($finalq, $updateParams);


  			if ($target == '0') {
  				$targData['_id'] = '0';
  			}


			// format crumb data
			$leftArr = $targData['parents'];
			$parentdat = array("id" => (string) $targData['_id'], "title" => $targData['title']);
			$leftArr[] = $parentdat;

			// format tag data (target)
			if (empty($targData['parentTags'])) {
				$parentTags = $targData['tags'];
			} else {
				if (empty($targData['tags'])) {
					$parentTags = $targData['parentTags'];
				} else {
					$parentTags = array_merge($targData['parentTags'], $targData['tags']);
				}
			}


			// format tag data (current object)
			if (empty($conObj['parentTags'])) {
				$conTags = $conObj['tags'];
			} else {
				if (empty($targData['tags'])) {
					$conTags = $conObj['parentTags'];
				} else {
					$conTags = array_merge($conObj['parentTags'], $conObj['tags']);
				}
			}

			$up = array();
			$up["parents"] = $leftArr;
			$up["parent"] = $parentdat;
			$up["parentTags"] = $parentTags;
			$up["owner_id"] = $newOwner;
			$up["parentPermissions"] = $targData['parentPermissions'];
			$up["permissions"] = array();

			// if this is the home folder
			if ($target == '0') {
				$up["owner_id"] = $uid;
				$up["parent"] = array("id" => "0", "title" => '');
				$up["parents"] = array(array("id" => "0", "title" => ''));
			}

			$fOwn = $up["owner_id"];

			// update this
			$collection->update(array('_id' => new MongoId($conObj['_id'])), array('$set' => $up));



			// if this content object is a folder...
			if ($conObj['type'] == 1) {
				// get array index of the con object
				$arrDex = count($conObj['parents']) + 1;

				// grab all descendants
				$lilVikas = getDescendants($conObj['_id']);
				$fArr = array();
				$crossArr = array();

				// set orig tags
				if (!empty($parentTags) && !empty($conObj['tags'])) {
					$crossArr[(string) $conObj['_id']] = array_merge($parentTags, $conObj['tags']);

				} else {
					if (!empty($parentTags)) {
						$crossArr[(string) $conObj['_id']] = $parentTags;

					} elseif (!empty($conObj['tags'])) {
						$crossArr[(string) $conObj['_id']] = $conObj['tags'];

					}
				}


				// sort folders & files
				foreach ($lilVikas as $vic) {
					$vic['countTotal'] = count($vic['parents']);
					$fArr[] = $vic;
				}

				// sort array based on count total
				$fArr = sort2d($fArr, 'countTotal', 'asc', true);

				// iterate descendants
				foreach ($fArr as $vic) {
					if (!empty($crossArr[$vic['parent']['id']])) {
						$parTags = $crossArr[$vic['parent']['id']];
					}

					// set the tags for this item
					if (!empty($parTags) && !empty($vic['tags'])) {
						$crossArr[(string) $vic['_id']] = array_merge($parTags, $vic['tags']);
					} else {
						if (!empty($vic['tags'])) {
							$crossArr[(string) $vic['_id']] = $vic['tags'];
						} elseif (!empty($parTags)) {
							$crossArr[(string) $vic['_id']] = $parTags;
						}
					}

					$newRents = array();
					$rightArr = array();
					$rightArr = array_slice($vic['parents'], $arrDex - 1);
					//return $rightArr;
					$newRents = array_merge($leftArr, $rightArr);

					$up = array();
					$up["parents"] = $newRents;
					$up["parentTags"] = $parTags;
					$up["owner_id"] = $fOwn;
					$up["parentPermissions"] = $targData['parentPermissions'];
					$up["permissions"] = array();


					$collection->update(array('_id' => new MongoId($vic['_id'])), array('$set' => $up));
				}

			}
		}



		// update our new parents
		$rent_ids = array();
		foreach ($targData['parents'] as $rentData) {
			if ($rentData['id'] != '0' || $rentData['id'] != 0) {
				$rent_ids[] = array('_id' => new MongoId($rentData['id']));
			}
		}
		// also update our parent and target
		$rent_ids[] = array('_id' => new MongoId($targData['_id']));
		$finalq = array('$or' => $rent_ids);


		$updateParams = array();

		$updateParams['files'] = $filTot;
		$updateParams['folders'] = $folTot;
		$updateParams['total_size'] = $sizeTot;

		$sets = array("last_update" => date("U"), "last_update_by" => $uid);

		// update the parents
		updateParents($finalq, $updateParams, $sets);

		// success
		return 1;

	} else {
		return array("You don't have permission to move content here.");
	}
	
}



// delete selected content
function deleteContent($conIDs, $uid) {
	global $mdb;
	$collection = $mdb->fbox_content;

	// if the uid isn't present, grab the current session id
	if (!isset($uid)) {
		$uid = user('id');
	}


	$finForks = array();
	$finIds = array();


	// get the data for the content about to be moved
	$batchObj = getBatchContent($conIDs);
	$batchPer = verifyBatchPermissions($batchObj, $uid);

	// user must be the owner and have cleared the target verification
	if ($batchPer['localAuth'] == 2 || $batchPer['isOwner'] == 1) {
		foreach ($batchObj as $conObj) {
			$finIds[] = array("data.id" => (string) $conObj['_id']);
			if (isset($conObj['forkedFrom']) && $conObj['forkedFrom'] != 0 && $conObj['forkedFrom'] != '0') {
				$finForks[] = array("_id" => $conObj['forkedFrom']);
			}

			$curDescs = getDescendants($conObj['_id']);
			foreach ($curDescs as $play) {
				$finIds[] = array("data.id" => (string) $play['_id']);
				if (isset($play['forkedFrom']) && $play['forkedFrom'] != 0 && $play['forkedFrom'] != '0') {
					$finForks[] = array("_id" => $play['forkedFrom']);
				}
			}

			$isPub = verifyPublic($conObj);

			// delete this content
  			$collection->remove(array('_id' => new MongoId($conObj['_id'])), array('safe' => true));
  			// delete descendants
  			$collection->remove(array('parents.id' => (string) $conObj['_id']), array('safe' => true));


  			// objects deleted successfully. lets update the parents.
			$sets = array("last_update" => date("U"), "last_update_by" => $uid);

			$rent_ids = array();
			foreach ($conObj['parents'] as $rentData) {
				if ($rentData['id'] != '0' || $rentData['id'] != 0) {
					$rent_ids[] = array('_id' => new MongoId($rentData['id']));
				}
			}
			// also update our parent
			$rent_ids[] = array('_id' => new MongoId($conObj['_id']));
			$final = array('$or' => $rent_ids);

			$updateParams = array();
			// if this is a folder
			if ($conObj['type'] == 1) {
				$conObj['folders']++;
			} else {
				$conObj['files']++;
			}

			$updateParams['files'] = -$conObj['files'];
			$updateParams['folders'] = -$conObj['folders'];
			$updateParams['total_size'] = -$conObj['total_size'];

			if (!$isPub) {
				// update this user's total storage
				incStorage($updateParams['total_size'], $conObj['owner_id']);
			}
			// update the parents
  			updateParents($final, $updateParams, $sets);
  		}




  		// update forks
  		updateDescendantForks($finForks, -1, true);
	}


	$feed_collection = $mdb->feed;
	$feed_collection->remove(array('$or' => $finIds), array("multiple" => true, 'safe' => true));


}



// copy selected content to new directory
function copyContent($target, $conIDs, $uid) {
	global $mdb;
	$collection = $mdb->fbox_content;

	// if target == 0, set up fake parents and shit

	// init passthrough variable
	$clear = true;

	// if the uid isn't present, grab the current session id
	if (!isset($uid)) {
		$uid = user('id');
	}

	// load parent information, authenticate our permissions
	$targData = getContent($target);
	$permissionObj = verifyPermissions($targData, $uid);
	$perLevel = determinePerLevel($targData['_id'], $permissionObj);
	// make sure that this is a folder
	if (($targData['type'] != 1 || $perLevel != 2) && $target != '0')  {
		$clear = false;
	}


	// get the data for the content about to be moved
	$batchObj = getBatchContent($conIDs);
	$batchPer = verifyBatchPermissions($batchObj, $uid);
	$batchLevel = determinePerLevel('temp', $batchPer);

	// calculate total size of stuff being moved
	$placeID = 0;
	$sizeTot = 0;
	$folTot = 0;
	$filTot = 0;
	foreach ($batchObj as $cObj) {
		// check if we're putting a folder in a folder
		foreach ($targData['parents'] as $parChk) {
			if ($cObj['_id'] == $parChk['id'] || $targData['_id'] == $cObj['_id']) {
				return array("You cannot copy a folder within itself.");
			}
		}

		// set userid, increment size, folder and file count
		$placeID = $cObj['owner_id'];
		$sizeTot += $cObj['total_size'];
		$folTot += $cObj['folders'];
		$filTot += $cObj['files'];

		// increment by 1 depending on this content type
		if ($cObj['type'] == 1) {
			$folTot++;
		} elseif ($cObj['type'] == 2) {
			$filTot++;
		}
	}

	// size check
	if (!checkStorage($sizeTot, $newOwner)) {
		return array("There is not enough room in the specified folder.");
	}

	// format permissions
	foreach ($targData['permissions'] as $per) {
		$per['folder_id'] = (string) $targData['_id'];
		$targData['parentPermissions'][] = $per;
	}

	if ($target == '0') {
		$targData["_id"] = '0';
		$newOwner = $uid;
	} else {
		$newOwner = $targData['owner_id'];
	}

	// user must be the owner and have cleared the target verification
	if ($batchLevel >= 1 && $clear != false) {
		// if this isn't shared publicly, increase storage
		if (!verifyPublic($targData)) {
			// increase user's storage
			incStorage($sizeTot, $newOwner);
		}

		// if new owner, increase fork
		if ($newOwner != $placeID) {
			updateDescendantForks($batchObj, 1);
		}

		// send update
		insertFboxNoti(2, $targData['permissions'], $targData['parentPermissions'], $targData['owner_id'], $targData);
		// key=> value for old ids and new ids
		$idSwap = array();
		// init the "left" side of our parents
		$leftArr = $targData['parents'];
		$parentdat = array("id" => (string) $targData['_id'], "title" => $targData['title']);
		$leftArr[] = $parentdat;

		foreach ($batchObj as $conObj) {
			// array index
			$arrDex = count($conObj['parents']) + 1;
			// store folders to process
			$folArr = array();
			// store files to process
			$filArr = array();
			// current objectID
			$currentOBID = (string) $conObj['_id'];
			// tag swap
			$tagSwapper = array();
			// insert content HERE
			unset($conObj['_id']);
			// set new parent data
			if (empty($parentData['parentTags'])) {
				$parentTags = $targData['tags'];
			} else {
				if (empty($targData['tags'])) {
					$parentTags = $targData['parentTags'];
				} else {
					$parentTags = array_merge($targData['parentTags'], $targData['tags']);
				}
			}

			// determine contags
			if (empty($conObj['parentTags'])) {
				$locTags = $conObj['tags'];
			} else {
				if (empty($conObj['tags'])) {
					$locTags = $conObj['parentTags'];
				} else {
					$locTags = array_merge($conObj['parentTags'], $conObj['tags']);
				}
			}


			// final rents
			if (empty($locTags)) {
				$finTags = $parentTags;
			} else {
				if (empty($parentTags)) {
					$finTags = $locTags;
				} else {
					$finTags = array_merge($locTags, $parentTags);
				}
			}





			$conObj['parent'] = $parentdat;
			$conObj['parents'] = $leftArr;
			$conObj['parentTags'] = $parentTags;
			$conObj['tags'] = $locTags;
			$conObj['permissions'] = array();
			$conObj['parentPermissions'] = $targData['parentPermissions'];
			$conObj['owner_id'] = $newOwner;

			// new owner? this is a fork, set the forkfrom and forkhash
			if ($newOwner != $placeID) {
				// when versioning comes out, make this an opt override
				$conObj["forkedFrom"] = $currentOBID; // origin fork contentID hash
				$conObj["forkHash"] = verifyDataAuth('0', $conObj); // origin fork DS hash
				$conObj["forkStamp"] = $conObj["last_update"]; // timestamp of fork
			} else {
				$conObj["forkedFrom"] = '';
				$conObj["forkHash"] = '';
				$conObj["forkStamp"] = 0;
			}

			$conObj["last_update"] = date("U");


			// if this is the home folder
			if ($target == '0') {
				$conObj["parent"] = array("id" => "0", "title" => '');
				$conObj["parents"] = array(array("id" => "0", "title" => ''));
			}

			// clean the object (comments, etc)
			$conObj = cleanCopyObj($conObj);
			// insert object
			$collection->insert($conObj);
			// set ID swap for $current OBID
			$idSwap[$currentOBID] = (string) $conObj['_id'];
			// set orig tags for this obj
			$tagSwapper[(string) $conObj['_id']] = $finTags;

			$vikas = getDescendants($currentOBID);
			// sort folders & files
			foreach ($vikas as $vic) {
				$vic['countTotal'] = count($vic['parents']);
				$folArr[] = $vic;
			}

			// sort array based on count total
			$foldArr = sort2d($folArr, 'countTotal', 'asc', true);
			foreach ($foldArr as $prim => $fold) {
				$newRents = array();
				$rightArr = array();
				// init new array for this obj
				$final = array();

				$curTempID = (string)$fold['_id'];

				// iterate through each key, make sure we want to copy it over
				foreach ($fold as $key => $value) {
					// add it if it isn't naughty
					if ($key != 'countTotal' && $key != '_id') {
						$final[$key] = $value;
					}
				}


				$rightArr = array_slice($fold['parents'], $arrDex - 1);
				// clean array
				foreach ($rightArr as $rkey => $rval) {
					$rightArr[$rkey]['id'] = $idSwap[$rightArr[$rkey]['id']];
				}
				$newRents = array_merge($leftArr, $rightArr);

				$newRentID = $idSwap[$final['parent']['id']];
				$final['parent']['id'] = $newRentID;

				$final['parents'] = $newRents;

				$final['parentTags'] = $tagSwapper[$final['parent']['id']];

				$conObj['permissions'] = array();

				$final['parentPermissions'] = $targData['parentPermissions'];

				$final['owner_id'] = $newOwner;

				// new owner? this is a fork, set the forkfrom and forkhash
				if ($newOwner != $placeID) {
					// when versioning comes out, make this an opt override
					$final["forkedFrom"] = $curTempID; // origin fork contentID hash
					$final["forkHash"] = verifyDataAuth('0', $final); // origin fork DS hash
					$final["forkStamp"] = $final["last_update"]; // timestamp of fork
				} else {
					$final["forkedFrom"] = '';
					$final["forkHash"] = '';
					$final["forkStamp"] = 0;
				}

				$final["last_update"] = date("U");

				// clean final object (comments, etc)
				$final = cleanCopyObj($final);
				// insert final
				$collection->insert($final);
				// set ID swap for $current OBID
				$idSwap[(string) $fold['_id']] = (string) $final['_id'];


				// set tags
				if (!empty($final['tags']) && !empty($tagSwapper[$final['parent']['id']])) {
					$tagSwapper[(string) $final['_id']] = array_merge($final['tags'], $tagSwapper[$final['parent']['id']]);
				} else {
					if (empty($final['tags'])) {
						$tagSwapper[(string) $final['_id']] = $tagSwapper[$final['parent']['id']];

					} else {
						$tagSwapper[(string) $final['_id']] = $final['tags'];
					}

				}

				//$collection->insert($final);
				//echo $final['_id'] . '<br />';
			}



		}


		// update our new parents
		$rent_ids = array();
		foreach ($targData['parents'] as $rentData) {
			if ($rentData['id'] != '0' || $rentData['id'] != 0) {
				$rent_ids[] = array('_id' => new MongoId($rentData['id']));
			}
		}
		// also update our parent and target
		$rent_ids[] = array('_id' => new MongoId($targData['_id']));
		$finalq = array('$or' => $rent_ids);


		$updateParams = array();

		$updateParams['files'] = $filTot;
		$updateParams['folders'] = $folTot;
		$updateParams['total_size'] = $sizeTot;

		$sets = array("last_update" => date("U"), "last_update_by" => $uid);

		// update the parents
		updateParents($finalq, $updateParams, $sets);

		// success
		return 1;
	} else {
		return array("You don't have permission to copy content here.");
	}

}


// helper function for copy that removes all comments and chooses primary version
function cleanCopyObj($copObj) {
	// just remove comments for now
	foreach ($copObj['versions'] as $vkey=>$ver) {
		$ver['comments_pub'] = array();
		$ver['comments_priv'] = array();
		$ver['comments_course'] = array();
		$ver['recIDs'] = array();
		$ver['recs'] = 0;
		$ver['forkTotal'] = 0;
		$copObj['versions'][$vkey] = $ver;
	}

	return $copObj;
}



// update tags of single/multiple objects
function updateTags($conIDs, $tags, $uid) {
	global $mdb;
	$collection = $mdb->fbox_content;

	// if target == 0, set up fake parents and shit

	// if the uid isn't present, grab the current session id
	if (!isset($uid)) {
		$uid = user('id');
	}

	// get the data for the content about to be moved
	$batchObj = getBatchContent($conIDs);
	$batchPer = verifyBatchPermissions($batchObj, $uid);


	// user must be the owner and have cleared the target verification
	if ($batchPer['localAuth'] >= 1 || $batchPer['isOwner'] == 1 || $batchPer['publicAuth'] == 1) {
		// initialize our delete and add arrays
		$add = array();
		$del = array();
		// get the original tags
		$orig = getSharedTags($batchObj);

		// detect which items will be added
		foreach ($tags as $tag) {
			$pass = true;
			// check each of the original entries for it's existence
			foreach ($orig as $dad) {
				// we have a hit, this is not an "add"
				if ($dad['title'] == $tag['title'] && $dad['type'] == $tag['type'] && $dad['loc'] == 2) {
					$pass = false;
				}
			}

			// if this didn't fail, insert into the add array
			if ($pass == true) {
				$add[] = $tag;
			}
			
		}
		// okay, now lets check which items need to be deleted
		foreach ($orig as $curr) {
			$pass = false;
			// check each tag and ensure it exists
			foreach ($tags as $tog) {
				// we have a hit, we're not deleting this
				if ($tog['title'] == $curr['title'] && $tog['type'] == $curr['type']) {
					$pass = true;
				}
			}

			if ($pass == false) {
				$del[] = $curr;
			}
			
		}

		// okay, so now we have our add and delete arrays. lets update our content
		foreach ($batchObj as $obj) {
			// first, modify local tags
			$curRents = $obj['parentTags'];
			$curLocals = $obj['tags'];
			$finLocals = array();
			$curAdd = $add;
			$curDel = $del;

			// crosscheck parents
			// reset array for direct in_array comparison
			foreach ($curRents as $rKey=>$rent) {
				$curRents[$rKey] = array("title"=>$rent['title'], "type"=>$rent['type']);
			}
			// okay, now lets get our curAdd array set up with the final values
			foreach ($curRents as $rKey=>$rent) {
				if (in_array($rent, $curAdd)) {
					// it's already in the parents array. we're not adding it.
					$delKey = array_search($rent, $curAdd);
					// remove this from current add
					unset($curAdd[$delKey]);
				}
			}

			// crosscheck locals
			// delete all entries that are in the curDel array
			foreach ($curLocals as $lKey=>$local) {
				foreach ($curDel as $delv) {
					if ($local['title'] == $delv['title'] && $local['type'] == $delv['type']) {
						unset($curLocals[$lKey]);
					}
				}
			}

			// okay, our curAdd array and curlocals have been cleaned & set.
			// time to iterate curAdd into curLocals
			foreach ($curAdd as $item) {
				$curLocals[] = array("title"=>$item['title'],"owner"=>$uid,"type"=>$item['type']);
			}

			// $curLocals is now complete. update back into the database...
			// second, update this content
			$collection->update(array('_id' => new MongoId($obj['_id'])), array('$set' => array("tags" => $curLocals)));

			// third, get all descendents and loop
			$children = getDescendants($obj['_id']);
			foreach ($children as $child) {
				// get parent tags
				$childPar = $child['parentTags'];
				// get local tags
				$childLoc = $child['tags'];

				// fourth, modify parents & locals
				// iterate parents, delete if hit
				foreach ($childPar as $pKey=>$par) {
					foreach ($curDel as $delt) {
						// if theres a hit on this for deletion...
						if ($par['title'] == $delt['title'] && $par['type'] == $delt['type']) {
							// remove this from the parents array
							unset($childPar[$pKey]);
						}
					}
				}

				// okay, now lets add our new guys into the parents array
				foreach ($curAdd as $cadd) {
					$doIt = true;
					foreach ($childPar as $ptag) {
						// if this already exists in the array, set to false
						if ($cadd['title'] == $ptag['title'] && $cadd['type'] == $ptag['type']) {
							$doIt = false;
						}
					}

					// if there weren't any tags detected, add this to the parents array
					if ($doIt == true) {
						$childPar[] = $cadd;
					}

					// check local tags, delete if necessary
					foreach ($childLoc as $locKey=>$loctag) {
						// we have a hit
						if ($cadd['title'] == $loctag['title'] && $cadd['type'] == $loctag['type']) {
							// remove this from child
							unset($childLoc[$locKey]);
						}
					}
					
				}


				// fifth, update this content
				$collection->update(array('_id' => new MongoId($child['_id'])), array('$set' => array("tags" => $childLoc, "parentTags" => $childPar)));

			}

		}

	}
	
}


// returns tags that are shared between multiple objects
function getSharedTags($conObjs) {
	$parentTags = array();
	$localTags = array();
	$result = array();

	// how many objects?
	$start = 0;
	foreach ($conObjs as $conKey=>$temps) {
       $start++;
    }
    // cool, thanks bro.


    // if we only have one object, return the parent & local tags immediately
	if ($start == 1) {
		// loop this array once
		foreach ($conObjs as $cj) {
			// dump parent tags to all array
			foreach ($cj['parentTags'] as $tag) {
				$result[$tag['title'] . $tag['type']] = array("title" => $tag['title'], "owner" => $tag['owner'], "type" => $tag['type'], "loc" => 1);
			}
			// dump local tags to all array
			foreach ($cj['tags'] as $tag) {
				$result[$tag['title'] . $tag['type']] = array("title" => $tag['title'], "owner" => $tag['owner'], "type" => $tag['type'], "loc" => 2);
			}

				
		}

		return $result;

	// if this is more than 1 object, determine shared
	} else {
		// id = userid, title = tagtitle, type 1-grade level, 2-subject, 3-state standards, 4-keywords
		// array of cleared items
		$cleared = array();
		$flag = 0;
		foreach ($conObjs as $cj) {
			// only grab the first object (only one is reqd to check)
			if ($flag < 1) {
				$all = array();
				// dump parent tags to all array
				foreach ($cj['parentTags'] as $tag) {
					$all[sha1($tag['title'] . $tag['type'])] = array("title" => $tag['title'], "owner" => $tag['owner'], "type" => $tag['type'], "loc" => 1);
				}
				// dump local tags to all array
				foreach ($cj['tags'] as $tag) {
					$all[sha1($tag['title'] . $tag['type'])] = array("title" => $tag['title'], "owner" => $tag['owner'], "type" => $tag['type'], "loc" => 2);
				}
				
			}
			$flag++;
		}

		// ok, so now we have an array of all the tags in this object.
		// we need to cycle through all of the objects and see if we get a hit.
		foreach ($conObjs as $coj) {
			// dump all locals to temp array
			$temp = array();
			foreach ($coj['parentTags'] as $tag) {
				$temp[sha1($tag['title'] . $tag['type'])] = array("title" => $tag['title'], "owner" => $tag['owner'], "type" => $tag['type'], "loc" => 1);
			}
			// dump local tags to all array
			foreach ($coj['tags'] as $tag) {
				$temp[sha1($tag['title'] . $tag['type'])] = array("title" => $tag['title'], "owner" => $tag['owner'], "type" => $tag['type'], "loc" => 2);
			}


			// check if each element in all has matching values in temp
			foreach ($all as $keyd => $val) {
				if (array_key_exists($keyd, $temp)) {
					if (($temp[$keyd]['loc'] > $all[$keyd]['loc']) && ($all[$keyd]['loc'] != 0)) {
						$all[$keyd]['loc'] = $temp[$keyd]['loc'];
					}
				} else {
					$all[$keyd]['loc'] = 0;
				}
			}

		}


	}

	// clean out array all if the loc is = 0
	foreach ($all as $key => $tagArr) {
		if ($tagArr['loc'] == 0) {
			unset($all[$key]);
		}
	}

	return $all;
}



// update tags of single/multiple objects
function updatePermissions($conIDs, $pers, $uid) {
	global $mdb;
	$collection = $mdb->fbox_content;

	// if target == 0, set up fake parents and shit

	// if the uid isn't present, grab the current session id
	if (!isset($uid)) {
		$uid = user('id');
	}

	// get the data for the content about to be moved
	$batchObj = getBatchContent($conIDs);
	$batchPer = verifyBatchPermissions($batchObj, $uid);


	// user must be the owner and have cleared the target verification
	if ($batchPer['localAuth'] >= 1 || $batchPer['isOwner'] == 1 || $batchPer['publicAuth'] == 1) {
		// initialize our delete and add arrays
		$add = array();
		$del = array();
		// get the original tags
		$orig = getSharedPermissions($batchObj);

		// detect which items will be added
		foreach ($pers as $per) {
			$pass = true;
			// check each of the original entries for it's existence
			foreach ($orig as $dad) {
				// we have a hit, this is not an "add"
				if ($dad['shared_id'] == $per['shared_id'] && $dad['auth_level'] == $per['auth_level'] && $dad['type'] == $per['type'] && $dad['loc'] == 2) {
					$pass = false;
				}
			}

			// if this didn't fail, insert into the add array
			if ($pass == true) {
				$add[] = $per;

				// check if this is a public share
				if ($per['type'] == 3 && $per['shared_id'] == 1) {
					$addPub = true;
				}
			}
			
		}
		// okay, now lets check which items need to be deleted
		foreach ($orig as $curr) {
			$pass = false;
			// check each tag and ensure it exists
			foreach ($pers as $tog) {
				// we have a hit, we're not deleting this
				if ($tog['shared_id'] == $curr['shared_id'] && $tog['auth_level'] == $curr['auth_level'] && $tog['type'] == $curr['type']) {
					$pass = true;
				}
			}

			if ($pass == false) {
				$del[] = $curr;

				// check if this is a public share
				if ($curr['type'] == 3 && $curr['shared_id'] == 1) {
					$subPub = true;
				}
			}
			
		}


		// if we are adding or subtracting pubs
		if ($addPub || $subPub) {
			// calculate total size of this
			$placeID = 0;
			$sizeTot = 0;
			$folTot = 0;
			$filTot = 0;
			foreach ($batchObj as $cObj) {

				// set userid, increment size, folder and file count
				$placeID = $cObj['owner_id'];
				$sizeTot += $cObj['total_size'];
				$folTot += $cObj['folders'];
				$filTot += $cObj['files'];
			}

			// if we're subtracting, make sure we have enough storage to do so
			if ($subPub) {
				if (!checkStorage($sizeTot, $placeID)) {
					return array("We can't remove public permissions from this content because you don't have enough storage space!");
				}

			// if we're adding public, subtract from total
			} else {
				$sizeTot = -$sizeTot;
			}

			// increment user's storage
			incStorage($sizeTot, $placeID);
		}





		// okay, so now we have our add and delete arrays. lets update our content
		foreach ($batchObj as $obj) {
			// first, modify local tags
			$curRents = $obj['parentPermissions'];
			$curLocals = $obj['permissions'];
			$finLocals = array();
			$curAdd = $add;
			$curDel = $del;

			// crosscheck parents
			// reset array for direct in_array comparison
			foreach ($curRents as $rKey=>$rent) {
				$curRents[$rKey] = array("shared_id"=>$rent['shared_id'], "type"=>$rent['type'], "auth_level"=>$rent['auth_level']);
			}
			// okay, now lets get our curAdd array set up with the final values
			foreach ($curRents as $rKey=>$rent) {
				if (in_array($rent, $curAdd)) {
					// it's already in the parents array. we're not adding it.
					$delKey = array_search($rent, $curAdd);
					// remove this from current add
					unset($curAdd[$delKey]);
				}
			}

			// crosscheck locals
			// delete all entries that are in the curDel array
			foreach ($curLocals as $lKey=>$local) {
				foreach ($curDel as $delv) {
					if ($local['shared_id'] == $delv['shared_id'] && $local['type'] == $delv['type'] && $local['auth_level'] == $delv['auth_level']) {
						unset($curLocals[$lKey]);
					}
				}
			}

			// remove anything from curAdd that already exists here
			foreach ($curLocals as $lKey=>$local) {
				foreach ($curAdd as $delv) {
					if ($local['shared_id'] == $delv['shared_id'] && $local['type'] == $delv['type']) {
						unset($curLocals[$lKey]);
					}
				}
			}

			// okay, our curAdd array and curlocals have been cleaned & set.
			// time to iterate curAdd into curLocals
			foreach ($curAdd as $item) {
				$curLocals[] = array("type"=>$item['type'],"shared_id"=>$item['shared_id'],"auth_level"=>$item['auth_level']);
			}

			$curLocals = array_values($curLocals);


			// $curLocals is now complete. update back into the database...
			// second, update this content
			$collection->update(array('_id' => new MongoId($obj['_id'])), array('$set' => array("permissions" => $curLocals)));


			// update our curAdds
			insertFboxNoti(3, $curAdd, null, $uid, $obj);

			// third, get all descendents and loop
			$children = getDescendants($obj['_id']);
			foreach ($children as $child) {
				// get parent permissions
				$childPar = $child['parentPermissions'];
				// get local permissions
				$childLoc = $child['permissions'];

				// fourth, modify parents & locals
				// iterate parents, delete if hit
				foreach ($childPar as $pKey=>$par) {
					foreach ($curDel as $delt) {
						// if theres a hit on this for deletion...
						if ($par['shared_id'] == $delt['shared_id'] && $par['type'] == $delt['type'] && $par['auth_level'] == $delt['auth_level']) {
							// remove this from the parents array
							unset($childPar[$pKey]);
						}
					}
				}

				// okay, now lets add our new guys into the parents array
				foreach ($curAdd as $cadd) {
					$cadd['folder_id'] = (string) $obj['_id'];
					$doIt = true;
					foreach ($childPar as $parKey=>$ptag) {
						// if this already exists in the array, set to false
						if ($cadd['shared_id'] == $ptag['shared_id'] && $cadd['type'] == $ptag['type']) {
							unset($childPar[$parKey]);
						}
					}

					// if there weren't any tags detected, add this to the parents array
					if ($doIt == true) {
						$childPar[] = $cadd;
					}

					// check local tags, delete if necessary
					foreach ($childLoc as $locKey=>$loctag) {
						// we have a hit
						if ($cadd['shared_id'] == $loctag['shared_id'] && $cadd['type'] == $loctag['type'] && $cadd['auth_level'] == $loctag['auth_level']) {
							// remove this from child
							unset($childLoc[$locKey]);
						}
					}
					
				}

				$childLoc = array_values($childLoc);
				$childPar = array_values($childPar);

				// fifth, update this content
				$collection->update(array('_id' => new MongoId($child['_id'])), array('$set' => array("permissions" => $childLoc, "parentPermissions" => $childPar)));

			}

		}

	}


}




// returns permissions that are shared between multiple objects
function getSharedPermissions($conObjs) {
	// how many objects?
	$start = 0;
	foreach ($conObjs as $conKey=>$temps) {
       $start++;
    }
    // cool, thanks bro.
	// if we only have one object, return the parent & local tags immediately
	if ($start == 1) {
		$result = array();
		// loop this array once
		foreach ($conObjs as $cj) {
			// dump parent tags to all array
			foreach ($cj['parentPermissions'] as $per) {
				$result[sha1($per['shared_id'] . '-' . $per['auth_level'] . '-' . $per['type'])] = array("shared_id" => $per['shared_id'], "auth_level" => $per['auth_level'], "type" => $per['type'], "loc" => 1);
			}
			// dump local tags to all array
			foreach ($cj['permissions'] as $per) {
				$result[sha1($per['shared_id'] . '-' . $per['auth_level'] . '-' . $per['type'])] = array("shared_id" => $per['shared_id'], "auth_level" => $per['auth_level'], "type" => $per['type'], "loc" => 2);
			}

				
		}

		return $result;

	// if this is more than 1 object, determine shared
	} else {
		// id = userid, title = tagtitle, type 1-grade level, 2-subject, 3-state standards, 4-keywords
		// array of cleared items
		$cleared = array();
		$flag = 0;
		foreach ($conObjs as $cj) {
			// only grab the first object (only one is reqd to check)
			if ($flag < 1) {
				$all = array();
				// dump parent tags to all array
				foreach ($cj['parentPermissions'] as $per) {
					$all[sha1($per['shared_id'] . '-' . $per['auth_level'] . '-' . $per['type'])] = array("shared_id" => $per['shared_id'], "auth_level" => $per['auth_level'], "type" => $per['type'], "loc" => 1);
				}
				// dump local tags to all array
				foreach ($cj['permissions'] as $per) {
					$all[sha1($per['shared_id'] . '-' . $per['auth_level'] . '-' . $per['type'])] = array("shared_id" => $per['shared_id'], "auth_level" => $per['auth_level'], "type" => $per['type'], "loc" => 2);
				}
				
			}
			$flag++;
		}

		// ok, so now we have an array of all the tags in this object.
		// we need to cycle through all of the objects and see if we get a hit.
		foreach ($conObjs as $coj) {
			// dump all locals to temp array
			$temp = array();
			foreach ($coj['parentPermissions'] as $per) {
				$temp[sha1($per['shared_id'] . '-' . $per['auth_level'] . '-' . $per['type'])] = array("shared_id" => $per['shared_id'], "auth_level" => $per['auth_level'], "type" => $per['type'], "loc" => 1);
			}
			// dump local tags to all array
			foreach ($coj['permissions'] as $per) {
				$temp[sha1($per['shared_id'] . '-' . $per['auth_level'] . '-' . $per['type'])] = array("shared_id" => $per['shared_id'], "auth_level" => $per['auth_level'], "type" => $per['type'], "loc" => 2);
			}


			// check if each element in all has matching values in temp
			foreach ($all as $keyd => $val) {
				if (array_key_exists($keyd, $temp)) {
					if (($temp[$keyd]['loc'] > $all[$keyd]['loc']) && ($all[$keyd]['loc'] != 0)) {
						$all[$keyd]['loc'] = $temp[$keyd]['loc'];
					}
				} else {
					$all[$keyd]['loc'] = 0;
				}
			}

		}


	}

	// clean out array all if the loc is = 0
	foreach ($all as $key => $tagArr) {
		if ($tagArr['loc'] == 0) {
			unset($all[$key]);
		}
	}

	return $all;

}







// content specific functions
function addFolder($parent, $title, $body, $uid, $permissions, $tags, $standards) {
	if (isset($uid)) {
		$uid = (string) $uid;
	} else {
		$uid = (string) user('id');
	}


	$errors = array();

	// make sure there is a title
	if ($title != '') {
		// make sure the title is less than 60 chars
		if (strlen($title) > 60) {
			$errors[] = 'The folder name you entered is too long.';
		}
	} else {
		$errors[] = 'You forgot to enter a folder name.';
	}


	// make sure our parent folder is here
	if (!isset($parent) || $parent == '') {
		$errors[] = 'No parent folder detected.';
	}


	// if there are no errors, return success
	if (empty($errors)) {
		insertContent($uid, $parent, 1, $title, $body);
		return 1;	
	} else {
		return $errors;
	}


}



// add web content
function addWebContent($parent, $title, $body, $content, $type, $uid) {
	// type 1 = URL, type 2 = embed code

	if (isset($uid)) {
		$uid = (string) $uid;
	} else {
		$uid = (string) user('id');
	}

	$pass = false;
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


	// detect content
	if ($content == '') {
		$errors[] = 'You forgot to enter the URL.';
	} else {

		// if this is an embed
		if ($type == 2) {
			$webType = 3; // embed
			$finalCon = array(
				"data"=> $content
			);

		// if this is a URL or video (NOT EMBED)
		} else {
			// determine if this is a video
			$vidCheck = linkToVideo($content);
			
			if ($vidCheck != false) {
				$webType = 4; // video
				$finalCon = array(
					"vidType" => $vidCheck['vidType'],
					"data"=> $vidCheck['data'],
					"origData" => formatURL($content)
				);
				$pass = true;
			}
			

			if ($pass == false) {
				// if this is a google doc
				if ($type == 5) {
					$webType = 5;
				} else {
					$webType = 2; // url
				}
				$data = formatURL($content); // clean & curate the url

				$finalCon = array(
					"data"=>$data
				);
				
			}

		} // embed elseif

		// add the body
		$finalCon['body'] = $body;

	} // content empty check


	// make sure our parent folder is here
	if (!isset($parent) || $parent == '') {
		$errors[] = 'No parent folder detected.';
	}


	// if there are no errors, return success
	if (empty($errors)) {
		insertContent($uid, $parent, 2, $title, $body, $permissions, $tags, $standards, $webType, $finalCon);
		return 1;	
	} else {
		return $errors;
	}


}




// determine if link is video
function linkToVideo($content) {
	if (strpos($content, 'youtube.com/watch?v=')) {
			$temp =  substr($content, strpos($content, 'youtube.com/watch?v=') + 20, 11);
			if ($temp != '') {
				$finalCon = array(
					"vidType" => 1, // youtube
					"data" => $temp
				);
				$pass = true;
			}
			
		// if this is a youtube short link
		} elseif (strpos($content, 'youtu.be/')) {
			$temp =  substr($content, strpos($content, 'youtu.be/') + 9, 11);
			if ($temp != '') {
				$finalCon = array(
					"vidType" => 1, // youtube
					"data" => $temp
				);
				$pass = true;
			}


		// if this is a schooltube video
		} elseif (strpos($content, 'schooltube.com/video/')) {
			$temp =  substr($content, strpos($content, 'schooltube.com/video/') + 21, 20);
			if ($temp != '') {
				$finalCon = array(
					"vidType" => 2, // schooltube
					"data" => $temp
				);
				$pass = true;
			}

		// if this is a teachertube video
		} elseif (strpos($content, 'teachertube.com/viewVideo.php?video_id=')) {
			if (strpos($content, '&')) {
				$end = strpos($content, '&') - (strpos($content, 'teachertube.com/viewVideo.php?video_id=') + 39);
			} else {
				$end = strlen($content);
			}
			
			$temp =  substr($content, strpos($content, 'teachertube.com/viewVideo.php?video_id=') + 39, $end);
			if ($temp != '') {
				$finalCon = array(
					"vidType" => 3, // teachertube
					"data" => $temp
				);
				$pass = true;
			}

		// this is just a regular old fashioned URL
		}


		if (!isset($finalCon)) {
			return false;
		} else {
			return $finalCon;
		}

}



// datastore file types (docs & livelectures)
function addDSFile($format, $parent, $title, $body, $content, $uid) {
	if (isset($uid)) {
		$uid = (string) $uid;
	} else {
		$uid = (string) user('id');
	}


	// make sure there is a title
	if ($title != '') {
		// make sure the title is less than 60 chars
		if (strlen($title) > 60) {
			$errors[] = 'The title you entered is too long.';
		}
	} else {
		$errors[] = 'You forgot to enter a title.';
	}


	// make sure our parent folder is here
	if (!isset($parent) || $parent == '') {
		$errors[] = 'No parent folder detected.';
	}


	if (empty($content)) {
		$content = array();
		$content['data'] = 'none';
	}


	// if there are no errors, return success
	if (empty($errors)) {
		$newID = insertContent($uid, $parent, 2, $title, $body, $permissions, $tags, $standards, $format, $content);
		return $newID;	
	} else {
		return $errors;
	}

}


// update a doc or livelecture
function pushDSFile($fileID, $versionID, $content, $uid) {
	if (isset($uid)) {
		$uid = (string) $uid;
	} else {
		$uid = (string) user('id');
	}

	global $cloudUser;
	global $cloudKey;
	global $cloudBucket;

	// make sure we're cleared to do this (verify permissions)
	$tdata = getContent($fileID);
	$permissionObj = verifyPermissions($tdata, $uid);
	$perLevel = determinePerLevel($tdata['_id'], $permissionObj);

	// if verified, obtain file & version ID
	if ($perLevel == 2 && verifyDataAuth($versionID, $tdata)) {
		$encname = gen_encName($uid, $versionID);
		// Connect to Rackspace
		$auth = new CF_Authentication($cloudUser, $cloudKey);
		$auth->authenticate();
		$conn = new CF_Connection($auth);
		// Get the container we want to use
		$container = $conn->get_container($cloudBucket);


		$store_data = getContentData($versionID);
		// remove old cloud file (if not none)
		if ($store_data['data'] != 'none') {
			$container->delete_object($store_data['data']);
		}


		// upload new cloud file
		// create object
		$object = $container->create_object($encname);

		$tmpfname = tempnam("swap", "dswap");

		$handle = fopen($tmpfname, "w");
		fwrite($handle, $content);
		fclose($handle);

		// upload file to Rackspace
		$object->load_from_filename($tmpfname);

		// do here something
		unlink($tmpfname);

		// update datastore with new data
		global $mdb;
		$collection = $mdb->fbox_datastore;
		$collection->update(array('_id' => new MongoId($versionID)), array('$set' => array("data" => $encname)));

	}
}


// main add file function
function addFile($parent, $fileLoc, $title, $body, $content, $uid) {
	// type 1 = URL, type 2 = embed code

	if (isset($uid)) {
		$uid = (string) $uid;
	} else {
		$uid = (string) user('id');
	}

	$errors = array();


	// get this file's extension
	$ext = strtolower(substr($title, strrpos($title, '.') + 1));

	// detect if image
	$imgTypes = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
	if (in_array($ext, $imgTypes)) {
		$formatType = 2;
		list($iwidth, $iheight, $itype, $iattr) = getimagesize($fileLoc);
		$content['width'] = $iwidth;
		$content['height'] = $iheight;

	} else {
		$formatType = 1;
	}


	// determine if we should send this to scribd
	$docTypes = array('pdf', 'ps', 'doc', 'docx', 'ppt', 'pps', 'pptx', 'xls', 'xlsx', 'odt', 'sxw', 'odp', 'sxi', 'ods', 'sxc', 'txt', 'rtf');
	if (in_array($ext, $docTypes)) {
		// ok, time to upload
		global $scribd_api_key;
		global $scribd_secret;
		$scribd = new Scribd($scribd_api_key, $scribd_secret);
		$data = $scribd->upload($fileLoc, $ext, 'private', null);
		$content['scribd_doc'] = $data['doc_id'];
		$content['scribd_key'] = $data['access_key'];
		$content['scribd_pass'] = $data['secret_password'];
	}

	$title = substr($title, 0, strrpos($title, '.'));

	// make sure there is a title
	if ($title != '') {
		// make sure the title is less than 60 chars
		if (strlen($title) > 57) {
			$title = substr($title, 0, 57);
		}
	} else {
		$title = 'untitled';
	}


	// detect content
	if (empty($content)) {
		$errors[] = 'No file data received.';
	}


	// check file loc
	if (empty($fileLoc)) {
		$errors[] = 'No file data received.';
	}


	// make sure our parent folder is here
	if (!isset($parent) || $parent == '') {
		$errors[] = 'No parent folder detected.';
	}


	// if there are no errors, return success
	if (empty($errors)) {
		$encname = gen_encName($uid, $title);
		$finalCon = $content;
		$finalCon['formatType'] = $formatType; // 1 file, 2 image
		$finalCon['ext'] = $ext;
		$finalCon['data'] = $encname;

		$tdata = getContent($parent);
		$permissionObj = verifyPermissions($tdata, $uid);
		$perLevel = determinePerLevel($tdata['_id'], $permissionObj);

		if ($parent == '0') {
			$tdata['owner_id'] = $uid;
		}

		// verify that we can upload this
		if ($perLevel == 2) {
			// public? allow it
			if (verifyPublic()) {
				$pass1 = true;
			} else {
				if (checkStorage($content['size'], $tdata['owner_id'])) {
					$pass1 = true;
				} else {
					$pass1 = false;
				}
			}
		} else {
			$pass1 = false;
		}

		// if we're allowed to be here and have enough storage
		if ($pass1) {
			uploadCloudFile($fileLoc, $encname, $formatType, $ext);
			insertContent($uid, $parent, 2, $title, $body, $permissions, $tags, $standards, 1, $finalCon);

			// if this isn't shared publicly, increment storage
			if (!verifyPublic($tdata)) {
				incStorage($finalCon['size'], $uid);
			}
			return true;
		} else {
			return false;
		}

	} else {
		return $errors;
	}

}





// upload a file to rackspace
function uploadCloudFile($localfile, $enc_name, $type, $ext) {
	global $cloudUser;
	global $cloudKey;
	global $cloudBucket;
	global $cloudImgBucket;
	global $cloudImgPub;

		// Connect to Rackspace
	$auth = new CF_Authentication($cloudUser, $cloudKey);
	$auth->authenticate();
	$conn = new CF_Connection($auth);
	 
	if ($type == 1) {
		// Get the container we want to use
		$container = $conn->get_container($cloudBucket);
		// create object
		$object = $container->create_object($enc_name);
	} elseif ($type == 2) {
		// choose img container
		$container = $conn->get_container($cloudImgBucket);
		// create object
		$object = $container->create_object($enc_name . '.' . $ext);
	}

	 
	// upload file to Rackspace
	$object->load_from_filename($localfile);

}







// get a piece of content
function getContent($contentID) {
	global $mdb;
	// select a collection (analogous to a relational database's table)
	$collection = $mdb->fbox_content;
	$data = $collection->findOne(array('_id' => new MongoId($contentID)));

	return $data;
}



// get a piece of content from the datastore
function getContentData($contentID) {
	global $mdb;
	// select a collection (analogous to a relational database's table)
	$collection = $mdb->fbox_datastore;
	$data = $collection->findOne(array('_id' => new MongoId($contentID)));

	return $data;
}



// get a piece of content
function getBatchContent($contentIDs) {

	$pieces = explode(",", $contentIDs);
	$idArr = array();
	foreach ($pieces as $piece) {
		$idArr[] = array('_id' =>new MongoId($piece));

	}

	global $mdb;
	// select a collection (analogous to a relational database's table)
	$collection = $mdb->fbox_content;

	$params = array('$or' => $idArr);
	$data = $collection->find($params);

	return $data;
}



// get all descendants of a given folder
function getDescendants($contentID) {
	if ($contentID != 0) {
		global $mdb;
		$collection = $mdb->fbox_content;
		$params[] = array('parents.id'=>(string) $contentID);
		$final = array('$or' => $params);

		$data = $collection->find($final);
		return $data;
	} else {
		return array();
	}
}




function getChildren($contentID, $userid) {
	if (isset($userid)) {
		$uid = (string) $userid;
	} else {
		$uid = (string) user('id');
	}


	global $mdb;

	// select a collection (analogous to a relational database's table)
	$collection = $mdb->fbox_content;

	// if this is the root directory
	if ($contentID == '0') {

		$params = array('parent.id'=>$contentID, 'owner_id'=>$uid);

		$data = $collection->find($params);

	} elseif ($contentID === 'shared') {
		$params = array('permissions.type'=>1, 'permissions.shared_id'=>(int) $uid);
		$data = $collection->find($params)->sort(array('last_update'=>-1));
		return $data;

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



// get latest 20 files
function getLatestFiles($format, $uid) {
	if (isset($uid)) {
		$uid = (string) $uid;
	} else {
		$uid = (string) user('id');
	}
	global $mdb;

	// select a collection (analogous to a relational database's table)
	$collection = $mdb->fbox_content;


	$params = array('format'=>$format, 'owner_id'=>$uid);

	$data = $collection->find($params)->sort(array('last_update'=>-1))->limit(20);

	return $data;
}


// get all forks of a content & dataID
function getAllForks($conID, $dataID) {
	global $mdb;

	// select a collection (analogous to a relational database's table)
	$collection = $mdb->fbox_content;


	$params = array('forkedFrom'=>$conID, 'forkHash'=>$dataID);

	$data = $collection->find($params)->sort(array('last_update'=>-1));

	return $data;
}



function verifyPermissions($conObj, $uid, $courses) {
	// lets initialize our return values
	$isOwner = 0;
	$localAuth = 0;
	$folderLoc = 0;
	$publicAuth = 0;



	// lets check if we're the owner
	if ($conObj['owner_id'] == $uid){
		// this user has read/write privs
		// SET VAR AS OWNER!
		$isOwner = 1;

	} else {
		// cycle through parent permissions and see if we have a hit
		foreach ($conObj['parentPermissions'] as $pper) {
			// if this permission is for a person
			if ($pper['type'] == 1) {
				// is this shared with my uid?
				if ($pper['shared_id'] == $uid) {
					// winner winner, chicken dinner
					// lets see if they have r/w or just r access
					if ($pper['auth_level'] > $localAuth) {
						$localAuth = $pper['auth_level'];

						$findex = getIndex($conObj['parents'], $pper['folder_id']);
						// get location of folder
						if ($folderLoc < $findex) {
	      					$folderLoc = $findex;
	      				}
					}

				}


			// check if we're auth via a class
			} elseif ($pper['type'] == 2) {
				// check if this exists
				if (in_array($pper['shared_id'], $courses)) {
					// winner winner, chicken dinner
					// lets see if they have r/w or just r access
					if ($pper['auth_level'] > $localAuth) {
						$localAuth = $pper['auth_level'];

						$findex = getIndex($conObj['parents'], $pper['folder_id']);
						// get location of folder
						if ($folderLoc < $findex) {
	      					$folderLoc = $findex;
	      				}
					}
				}


			// check if this is shared publicly
			} elseif ($pper['type'] == 3) {
				// generic public on/off switch
				$publicAuth = 1;
				// get the parent location
				$findex = getIndex($conObj['parents'], $pper['folder_id']);
				// get location of folder
				if ($folderLoc < $findex) {
  					$folderLoc = $findex;
  				}
			}

		}



		// no permissions found from the parents/owner?
		// lets check out our local permissions...
		foreach ($conObj['permissions'] as $pper) {
			// if this permission is for a person
			if ($pper['type'] == 1) {
				// is this shared with my uid?
				if ($pper['shared_id'] == $uid) {
					// winner winner, chicken dinner
					// lets see if they have r/w or just r access
					if ($pper['auth_level'] > $localAuth) {
						$localAuth = $pper['auth_level'];

						$findex = count($conObj['parents']);
						// get location of folder
						if ($folderLoc < $findex) {
		  					$folderLoc = $findex;
		  				}
					}
				}

			// if this is shared with a course
			} elseif ($pper['type'] == 2) {
				// check if this exists
				if (in_array($pper['shared_id'], $courses)) {
					// winner winner, chicken dinner
					// lets see if they have r/w or just r access
					if ($pper['auth_level'] > $localAuth) {
						$localAuth = $pper['auth_level'];

						$findex = count($conObj['parents']);
						// get location of folder
						if ($folderLoc < $findex) {
	      					$folderLoc = $findex;
	      				}
					}
				}

			// we need to add a public option in the next update
			} elseif ($pper['type'] == 3) {
				$publicAuth = 1;

				if ($pper['auth_level'] > $localAuth) {
					$localAuth = $pper['auth_level'];

					$findex = count($conObj['parents']);
					// get location of folder
					if ($folderLoc < $findex) {
	  					$folderLoc = $findex;
	  				}
				}
				
			}
		}


	
	// end of isowner t/f if and else
	}


	// okay, to recap, here is all of the data we sifted through
	// oh yeah, forgot to mention, fuck this fucking function.
	$result = array();
	$result['isOwner'] = $isOwner;
	$result['localAuth'] = $localAuth;
	$result['folderLoc'] = $folderLoc;
	$result['publicAuth'] = $publicAuth;

	return $result;

// end of function
}



// check if something is available publicly
function verifyPublic($conObj) {
	// cycle through parent permissions and see if we have a hit
		foreach ($conObj['parentPermissions'] as $pper) {
			// if this permission is public
			if ($pper['type'] == 3) {
				return true;
			}

		}

		// no permissions found from the parents/owner?
		// lets check out our local permissions...
		foreach ($conObj['permissions'] as $pper) {
			// if this permission is public
			if ($pper['type'] == 3) {
				return true;
			}
		}


		return false;
}


// return batch permissions
function verifyBatchPermissions($conObjs, $uid) {
	if (!isset($uid)) {
		$uid = user('id');
	}
	// lets initialize our return values
	$isOwner = 1;
	$localAuth = 2;
	$publicAuth = 1;

	foreach ($conObjs as $obj) {
		$pers = verifyPermissions($obj, $uid);
		// are they the owner?
		if ($pers['isOwner'] < $isOwner) {
			$isOwner = $pers['isOwner'];
		}
		// what is our auth?
		if ($pers['localAuth'] < $localAuth) {
			$localAuth = $pers['localAuth'];
		}
		// shared publicly?
		if ($pers['publicAuth'] < $isOwner) {
			$publicAuth = $pers['publicAuth'];
		}
	}


	$result = array();
	$result['isOwner'] = $isOwner;
	$result['localAuth'] = $localAuth;
	$result['publicAuth'] = $publicAuth;

	return $result;
}


// determine permission level for this object
function determinePerLevel($objID, $perObj) {

	 if ($perObj['isOwner'] == 1) {
	 	// r/w
	 	$level = 2;
	 } elseif ($perObj['localAuth'] != 0) {
	 	// determine whether they have r or r/w
	 	if ($perObj['localAuth']== 2) {
	 		// r/w
	 		$level = 2;
	 	} elseif ($perObj['localAuth']== 1) {
	 		// r only
	 		$level = 1;
	 	}
	 } elseif ($perObj['publicAuth'] == 1) {
	 	// viewing this publicly
	 	$level = 1;
	 } else {
	 	// no permissions at all
	 	$level = 0;
	 }


	 if ($objID == 0) {
	 	$level = 2;
	 }

	 return $level;
}


// find the folder index location
function getIndex($parents, $folderID) {
	// get the position of the folder
	foreach ($parents as $key => $tempPar) {
	    if ($tempPar['id'] == $folderID) {
	      return $key;
	    }
	}
	return false;
}



// assemble breadcrumbs
function constructCrumbs($authObj, $parents, $current) {
	// if it's the owner, skip the index info and just return all parents
	if ($authObj['isOwner'] == 1) {

		// if current id is not content
		if ($current['id'] != '-1') {
			$parents[] = $current;
		}

	// otherwise get the highest shared index
	} else {
		// iterate through array of parents
		foreach ($parents as $key => $folder) {
			// if this key is less than the index, wipe it
			if ($key < $authObj['folderLoc']) {
				unset($parents[$key]);
			}
		}

		// if current id is not content
		if ($current['id'] != '-1') {
			$parents[] = $current;
		}

		if ($current['id'] !== 'shared' && $current['id'] !== 0) {
			$tempr = array();
			$tempr[] = array("id"=>"shared","title"=>"Shared");
			$parents = array_merge($tempr, $parents);
		}

		// if this is not home (but it's shared)
		if ($current['id'] !== 0) {
			$tempr = array();
			$tempr[] = array("id"=>"0","title"=>"none");
			$parents = array_merge($tempr, $parents);
		}

	}
	

	return $parents;
}


// update content description
function updateDesc($conID, $desc, $uid) {
	global $mdb;
	$collection = $mdb->fbox_content;

	if (!isset($uid)) {
		$uid = user('id');
	}
	// load parent information, authenticate our permissions
	$targData = getContent($conID);
	$permissionObj = verifyPermissions($targData, $uid);
	$perLevel = determinePerLevel($targData['_id'], $permissionObj);
	// make sure we have permission to edit this
	if ($perLevel == 2) {
		$collection->update(array('_id' => new MongoId($conID)), array('$set' => array("body" => $desc)));
	}
	

}


// verify a data object is in a content object
function verifyDataAuth($dataID, $cObj) {
	if ($dataID == '0') {
		$dex = count($cObj['versions']) - 1;
		$dataID = $cObj['versions'][$dex]['id'];
	}

	$result = false;
	foreach ($cObj['versions'] as $vd) {
		if ($vd['id'] == $dataID) {
			$result = $dataID;
		}
	}

	return $result;
}

// get index of data object
function getDataIndex($dataID, $cObj) {

	$result = false;
	foreach ($cObj['versions'] as $vkey=>$vd) {
		if ($vd['id'] == $dataID) {
			$result = $vkey;
		}
	}

	return $result;
}



function gen_encName($uid, $name) {
	$enc_name = SHA1(date('m/d/Y/i/s') . $uid . $name . createUnID());
	return $enc_name;
}



// create unique IDs
function createUnID() {
	$identifier = uniqid(rand(1, 999999));
	return $identifier;
}


function cleanOutJS($str) {
	$str = str_replace("\n", '', $str);
	return $str;
}

















































// TEMPLATING FUNCTIONS
////////////////////////////////////////////////////////////////////////////////
////////// GENERIC VIEW ////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

// construct the HTML
function createCrumbs($crumbs) {
	// max width of the crumbs
	$max = 210;
	// current width of the crumbs
	$current = 0;
	// flag for triggering the stop
	$flag1 = 0;
	// flag for inserting the ".."
	$flag2 = 0;

	// we're always gonna have a root directory
	$prepend .= '<div class="fboxNavEl"><a id="fol0" href="/app/filebox?_nav=true" class="fboxCrumb js-pjax" onClick="chooseCrumb($(this));"><img src="/assets/app/img/box/root.png" style="height:16px;float:left" /></a>';

    // define our root HTML handling
	if (count($crumbs) == 1) {
		$prepend .= '</div>';
	} else {
		$prepend .= '<img src="/assets/app/img/box/arrow.png" class="fbArr" /></div>';
	}


	// reverse crumbs array
	$crumbs = array_reverse($crumbs);
	// initialize append str
	$append = '';

	foreach ($crumbs as $cKey => $crumb) {
		$temp = '';

		if ($crumb['id'] !== '0' && $crumb['id'] !== 0) {
		  // calculate current total
		  $temptot = 21 + (strlen($crumb['title']) * 2);
		  // if this unit exceeds the max limit, flag it
		  if (($temptot + $current) > $max) {
		  	$flag1 = 1;
		  } else {
		  	$current = $current + $temptot;
		  }

		  if ($flag1 == 1) {
		  	if ($flag2 == 0) {
		  		// show the ".." crumb
		  		$temp .= '<div class="fboxNavEl">
			  <a href="/app/filebox/' . $crumb['id'] . '" class="fboxCrumb js-pjax" onClick="chooseCrumb($(this));">..</a><img src="/assets/app/img/box/arrow.png" class="fbArr" /></div>';
			  	$flag2 = 1;
		  		
		  	} else {
		  		// do nothing
		  	}

		  	$append = $temp . $append;
		  } else {
		  	// if we're still under the max, keep going

		  	// if this is the shared icon, show it
		  	if ($crumb['id'] == 'shared') {
		  		$crumb['title'] = '<img src="/assets/app/img/box/share.png" style="height:16px;float:left;margin-right:4px" /> Shared';
		  	}
		  	// if this is the last, auto select it
			  if ($cKey == 0) {
			  	$selr = ' selectedr';
			  } else {
			  	$selr = '';
			  }

			  $temp .= '<div class="fboxNavEl">
			  <a id="fol' . $crumb['id'] . '" href="/app/filebox/' . $crumb['id'] . '?_nav=true" class="fboxCrumb js-pjax' . $selr . '" onClick="chooseCrumb($(this));">' . $crumb['title'] . '</a>';

			  if ($cKey != 0) {
			    $temp .= '<img src="/assets/app/img/box/arrow.png" class="fbArr" />';
			  }
			  $temp .= '</div>';

			  $append = $temp . $append;
		  }

		  
		}
	}

	return $prepend . $append;
}


// generate the basic filebox template
function genTemplate($sidebar, $main, $crumbs) {
	$template = '<div id="mainBlocker" class="content">

 <div id="leftBox">
 	<div id="leftSwap">
 		' . $sidebar . '
 	</div>
 	<div style="width:225px">&nbsp;</div>
 </div>

 <div id="mainBox">
   <div id="crumbNav" class="fboxFloater">
   <div style="float:right"></div>
      ' . $crumbs . '
   </div>

   <div id="mainSwap" style="min-height:500px;height:auto !important;">
   ' . $main . '
   </div>


 </div>





  <div style="clear:both"></div>
</div>


<div id="jaxecute" style=""></div>';

	return $template;
}








////////////////////////////////////////////////////////////////////////////////
////////// FOLDER VIEW! ////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
function createPickerWrap($conID) {
	return '<div style="width: 330px;" class="fboxPicker">
      <div class="titleBar" onclick="togglePicker($(this));">
        <img src="/assets/app/img/gen/arrDown.png" style="float:right;margin-top:2px;margin-right:5px" />
        <div class="titleTexter" style="font-weight:bolder;width:290px;height:18px;overflow:hidden">Home</div>
        <input type="hidden" name="chosenOne" class="chosenOne" value="0" />
      </div>
      <div class="pickPane">
        ' . createPickerView($conID) . '
      </div>
    </div>';
}
function createPickerView($conID) {
	// get the data for this content
	$cObj = getContent($conID);
	$permissionObj = verifyPermissions($cObj, user('id'));
	$perLevel = determinePerLevel($cObj['_id'], $permissionObj);

	if ($perLevel > 0) {
		if ($conID == '0') {
		  $cObj['_id'] = 0;
		  $cObj['type'] = 1;
		}
		// override if this is the share view
		if ($conID == 'shared') {
		  $cObj['_id'] = 'shared';
		  $cObj['title'] = 'Shared';
		  $cObj['type'] = 1;
		  $perLevel = 1;
		}
		// if this content object is not a folder...
		if ($cObj['type'] != 1) {
			return false;
		}


	}


	$list = '';
	// okay, now lets pull in the directory
	$children = getChildren($conID);
	if ($conID === '0') {
		$list .= '<div class="dir" folid="shared">
          <img src="/assets/app/img/gen/arrRight.png" class="arrImg arrdown" onClick="togglePickFolder(this);" />
          <span>
            <img src="/assets/app/img/box/type/shareFolder.png" class="conImg" />
            Shared with you
          </span>

          <div class="dirWrap"></div>
          </div>';
	}
	$count = 0;
	foreach ($children as $child) {
	    //$list .= '<div style="border-bottom:1px solid #ccc;padding:7px;font-size:18px"><a class="js-pjax" href="/app/filebox/' . $child['_id'] . '">' . $child['title'] . ' - (' . $child['versions'][count($child['versions']) - 1]['timestamp'] . ')</a></div>';
	    $permissionObj = verifyPermissions($child, user('id'));
		$perLevel = determinePerLevel($child['_id'], $permissionObj);

		if ($child['type'] == 1) {

		    $list .= '<div class="dir" folid="' . $child['_id'] . '">
	          <img src="/assets/app/img/gen/arrRight.png" class="arrImg arrdown" onClick="togglePickFolder(this);" />
	          <span onclick="selectPickFolder(this)">
	            <img src="/assets/app/img/box/type/folder.png" class="conImg" />
	            ' . $child['title'] . '
	          </span>

	          <div class="dirWrap"></div>
	          </div>';
	          $count++;
      	}
	}

	  if ($count == 0) {
	  	if ($conID == '0') {
	  		$list .= '<div class="dir" style="margin-left:20px">
          <span>
            No folders found.
          </span>

          <div class="dirWrap"></div>
          </div>';
	  	} else {
	  		$list .= 'No folders found.';
	  	}
	  }

	  return $list;
}


// generate proper content title
function createConTitle($obj) {
	if ($obj['format'] == 1 && $obj['versions'][count($obj['versions']) - 1]['ext'] != 'folder') {
		return $obj['title'] . '.' . $obj['versions'][count($obj['versions']) - 1]['ext'];
	} else {
		return $obj['title'];
	}


}



// generate content icon
function createConIcon($obj) {
	// if this is a file
	if ($obj['format'] == 1) {
		$ext = $obj['versions'][count($obj['versions']) - 1]['ext'];
		$text = strtoupper($ext);

		if ($ext == 'folder') {
			$class = 'conWeb';
			$img ='folder.png';
			$text = 'Folder';
		} elseif ($ext == 'pdf') {
			$class = 'conPDF';
			$img = 'pdf.png';
		} elseif ($ext == 'ppt' || $ext == 'pps' || $ext == 'pptx' || $ext == 'odp' || $ext == 'sxi') {
			$class = 'conPPT';
			$img = 'ppt.png';

		} elseif ($ext == 'doc' || $ext == 'docx' || $ext == 'odt' || $ext == 'sxw' || $ext == 'rtf') {
			$class = 'conDOC';
			$img = 'doc.png';

		} elseif ($ext == 'xls' || $ext == 'xlsx' || $ext == 'ods' || $ext == 'sxc') {
			$class = 'conXLS';
			$img = 'xls.png';

		} elseif ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $ext == 'bmp') {
			$class = 'conWeb';
			$img = 'picture.png';
				
		} else {
			$class = 'conWeb';
			$img ='gen.png';
		}

	// website
	} elseif ($obj['format'] == 2) {
		$class = 'conWeb';
		$img ='web.png';
		$text = 'Web';
		
	// embed
	} elseif ($obj['format'] == 3) {
		$class = 'conWeb';
		$img ='embed.png';
		$text = 'Embed';

	// video
	} elseif ($obj['format'] == 4) {
		$class = 'conWeb';
		$img ='video.png';
		$text = 'Video';


	// google doc
	} elseif ($obj['format'] == 5) {
		$class = 'conWeb';
		$img ='gdoc.png';
		$text = 'Doc';

	
	// web document
	} elseif ($obj['format'] == 6) {
		$class = 'conWeb';
		$img ='document.png';
		$text = 'Doc';


	// livelecture
	} elseif ($obj['format'] == 7) {
		$class = 'conWeb';
		$img ='lecture.png';
		$text = 'Lecture';


	}


	// if we have a hit, return the data
	if (isset($text)) {
		return '<div class="conIcon">
				  <div class="dynConBox ' . $class . '">
				    <div class="peel">
				      <img src="/assets/peelfin.png" />
				    </div>

				    <img src="/assets/app/img/box/type/file/' . $img . '" class="typeLogo" />

				    <div class="typeText">
				    ' . $text . '
				    </div>
				  </div>
				</div>';	
	} else {
		return false;
	}
}




// create the HTML for the dir list
function createDirView($conID, $conObj, $perObj, $perLev) {
	// this is for the smooth scrolling (offset the 30px lost when floating)
	$list = '<div id="padset" style="height:30px;width:650px;display:none">&nbsp;</div>';

	// show description area if it's not the home or shared folder
	if ($conID === '0' || $conID === 'shared') {
		// do nothing
	} else {
		// if we have read write
		if ($perLev == 2) {
			if ($conObj['body'] === '') {
			$hideText = ' style="display:none"';
		} else {
			$hidePlacer = ' style="display:none"';
			$placeText = $conObj['body'];
		}

		$list .= '<div class="descMain">

		<div class="descPlacer descTip" data-original-title="Click to edit" onClick="swapDesc()"' . $hidePlacer . '>Add a description for this folder...</div>

		<div class="descText descTip" data-original-title="Click to edit" onClick="swapDesc()"' . $hideText . '>' . $placeText . '</div>

		<div class="descHold" style="margin-bottom:-18px">
		<textarea id="' . uniqid() . '" name="desc" rows="15" cols="80" style="width: 712px" class="descBox">' . htmlspecialchars($placeText) . '</textarea>
		<div class="actions" style="border-top:none;border-bottom:1px solid #ccc;margin-top:0px;width:703px;padding:0; padding-top:4px; padding-bottom:4px;padding-right:8px;background:#dddddd">
    <div style="float:right">
      <button type="submit" class="btn danger" style="font-size:10px;font-weight:bolder" onClick="saveDesc();">Save Description</button>&nbsp;<button type="reset" class="btn" onclick="swapDesc();" style="font-size:10px">Cancel</button>
    </div>
    <div style="clear:both"></div>
  </div>


		</div>

		</div>';
		} else {
			if ($conObj['body'] !== '') {
				$list .= '<div class="descMain"><div class="descText">' . $conObj['body'] . '</div></div>';
			}
		}
	}


	// okay, now lets pull in the directory
	$children = getChildren($conID);
	if ($conID === '0') {
	$list .= '<div id="shared" class="sharedEl">
		<div class="optBox">&nbsp;</div>
	    <img src="/assets/app/img/box/type/shareFolder.png" class="conicon" />
	    <div class="conmain">
	    	<div class="mainarea">
	    		<div class="contitle">
	    		<a class="js-pjax" href="/app/filebox/shared">Shared with you</a>
	    		</div>
	    		<div class="conlast">
	    		Content shared with you by your ' .  dispOnly('colleagues', 3) .  dispOnly('classmates and teachers', 1) .'.
	    		</div>
	    	</div>
	    </div>
	    </div>';
	}
	$count = 0;
	foreach ($children as $child) {
	    $count++;
	    //$list .= '<div style="border-bottom:1px solid #ccc;padding:7px;font-size:18px"><a class="js-pjax" href="/app/filebox/' . $child['_id'] . '">' . $child['title'] . ' - (' . $child['versions'][count($child['versions']) - 1]['timestamp'] . ')</a></div>';

	    $list .= genConStripe($child, $perLev);
	    

	    // debug opt ' . $child['folders'] . ' folders, ' . $child['files'] . ' files (' . $child['total_size'] . ')
	}

	  if ($count == 0) {
	  	if ($perLev == 2) {
	  		$list .= '<img src="/assets/app/img/box/nocon.png" style="margin-top:10px;margin-left:10px;float:left" />
	    <div style="font-size:20px;color:#444;font-weight:bolder;margin-top:42px">This folder is empty.
	    <div style="font-weight:normal;color:#666;margin-top:6px">Click "Add Content" to add files, websites, videos and more.</div>
	    </div>';
	  	} else {
	  		$list .= '<div style="margin-top:20px;font-weight:bolder;font-size:20px;color:#666;text-align:center">This folder is empty.
	    </div>';
	  	}
	    
	  }


	  return $list;
}


// dir view helper function
function genConStripe($child, $perLev) {
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
	    <div class="optBox"><div class="checkBoxy cboxNorm" onClick="checkBox($(this));"></div></div>
	    ' . $icon  . '
	    <div class="conmain">
	    	<div class="optarea">
	    		<div style="margin-top:17px;">
	    		<div style="float:right;margin-right:34px;height:10px"></div>
	    			';

	    if ($perLev == 2) {
	    		$list .= '<div class="optArDef">
	    		<div class="btn opterBtner" onclick="toggleOptPanel(this);">
		    		<img src="/assets/app/img/gen/arrDown.png" style="margin-top:-3px" />

		    		<div class="optListers">
			    		<div style="float:right;border-top:1px solid #fff; margin-top:-1px;width:20px;height:1px;margin-right:2px">
			    		</div>
			    		<div class="listElem" onClick="jQuery.facebox({ 
    ajax: \'/app/filebox/write/edit/title/' . $child['_id'] . '\'
  }); return false;">
			    		<img src="/assets/app/img/box/editcon.png" style="float:left;margin-right:3px;height:12px;margin-top:0px;margin-right:5px" />
			    		Rename
			    		</div>
			    		<div class="listElem" onClick="jQuery.facebox({ 
    ajax: \'/app/filebox/write/tags/?conIDs=' . $child['_id'] . '\'
  }); return false;">
			    		<img src="/assets/app/img/box/tag.png" style="float:left;margin-right:3px;height:12px;margin-top:0px;margin-right:5px" />
			    		Tag
			    		</div>
			    		<div class="listElem" onClick="jQuery.facebox({ 
    ajax: \'/app/filebox/write/move/?conIDs=' . $child['_id'] . '\'
  }); return false;">
			    		<img src="/assets/app/img/box/move.png" style="float:left;margin-right:3px;height:12px;margin-top:0px;margin-right:5px" />
			    		Move
			    		</div>
			    		<div class="listElem" onClick="jQuery.facebox({ 
    ajax: \'/app/filebox/write/copy/?conIDs=' . $child['_id'] . '\'
  }); return false;">
			    		<img src="/assets/app/img/box/copy.png" style="float:left;margin-right:3px;height:12px;margin-top:0px;margin-right:5px" />
			    		Copy
			    		</div>
			    		<div class="listElem" onClick="jQuery.facebox({ 
    ajax: \'/app/filebox/write/delete/?conIDs=' . $child['_id'] . '\'
  }); return false;">
			    		<img src="/assets/app/img/box/del.png" style="float:left;margin-right:3px;height:12px;margin-top:0px;margin-right:5px" />
			    		Delete
			    		</div>
		    		</div>

	    		</div>';
	    	}

	 if (verifyPublic($child) && checkSession()) {
	    	$list .= '<img src="/assets/app/img/box/sharelink.png" onClick="jQuery.facebox({ 
    ajax: \'/app/filebox/write/share/?conIDs=' . $child['_id'] . '\'
  }); return false;" class="topDesc" title="<strong>This is shared publicly</strong><br />Anyone with the link can access it<br /><span style=\'color:#bbb;font-size:9px\'>(click to view the link)</span>" style="float:right;margin-left:4px;margin-top:2px;height:14px;width:14px">';
	}

  if ($perLev == 2) {
	    $list .= '<a style="float:right" class="textTogg" onClick="jQuery.facebox({ 
    ajax: \'/app/filebox/write/share/?conIDs=' . $child['_id'] . '\'
  }); return false;">Share</a>

	    		</div>';
	    	}

	    $list .= '</div>
	    	</div>
	    	<div class="mainarea">
	    		<div class="contitle">
	    		<a class="js-pjax" href="/app/filebox/' . $child['_id'] . '">' . createConTitle($child) . '</a>
	    		</div>
	    		<div class="conlast">
	    		Updated ' . $lastMod . ' by <a href="' . userURL($lastModder) . '" class="textTogg">' . dispUser($lastModder, 'first_name') . ' ' . dispUser($lastModder, 'last_name') . '</a>';


	    		if ($child['files'] > 0 && $child['type'] == 1) {
	    			$sizeData = sizeToText($child['total_size']);
	    			$list .= '<span class="topDesc rollFalse" title="' . $child['files'] . ' files, ' . $sizeData['data'] . ' ' . $sizeData['fix'] .'"><img src="/assets/app/img/box/mini/file.png" style="margin-left:6px;margin-right:-1px;" /> ' . $child['files'] . '</span>';
	    		}


	    		//if this has tags, show the mini button thing
	    		$totTags = (count($child['tags']) + count($child['parentTags']));
	    		if ($totTags > 0) {
	    			$list .= '<span class="topDesc rollFalse" title="' . $totTags . ' tags" onClick="jQuery.facebox({ 
    ajax: \'/app/filebox/write/tags/?conIDs=' . $child['_id'] . '\'
  });$(\'.twipsy\').remove();"><img src="/assets/app/img/box/mini/tag.png" style="margin-left:6px;margin-right:-1px;" /> ' . $totTags . '</span>';
	    		}

	    		//if this has tags, show the mini button thing
	    		$totShares = (count($child['permissions']) + count($child['parentPermissions']));
	    		if ($totShares > 0 && $perLev == 2) {
	    			$list .= '<span class="topDesc rollFalse" title="Shared with ' . $totShares . '" onClick="jQuery.facebox({ 
    ajax: \'/app/filebox/write/share/?conIDs=' . $child['_id'] . '\'
  });$(\'.twipsy\').remove();"><img src="/assets/app/img/box/mini/user.png" style="margin-left:6px;margin-right:-1px;" /> ' . $totShares . '</span>';
	    		}

	    $list .= '</div>
	    	</div>
	    </div>
	    </div>';
	    return $list;
}









// create the folder sidebar
function createFolBar($conObj, $perObj) {
	$barML = '';
	$barML .= authorUI($conObj, $perObj);
	$barML .= actionUI($conObj, $perObj);
	$barML .= forkNotice($conObj, $perObj);
	$barML .= assocUI($conObj, $perObj);

	$bar = '<div class="folderBar fboxFloater">' . $barML . '</div>';
    return $bar;
}




// show user data
function authorUI($conObj, $perObj) {
	// detect if this is home/shared
	if ($conObj['_id'] == 0) {
		$ownerID = user('id');
	} else {
		$ownerID = $conObj['owner_id'];
	}


	// if we own this content, show our bar
	if ($ownerID == user('id')) {
		$sData = dispStorageInfo();

		// determine color to display
		if ($sData['percentage'] < 60) {
			$class = 'stage1';
		} elseif ($sData['percentage'] >= 60 && $sData['percentage'] < 75) {
			$class = 'stage2';
		} elseif ($sData['percentage'] >= 75) {
			$class = 'stage3';
		}


		// determine text pos and color
		if ($sData['percentage'] < 42) {
			$attr = 'margin-left:42px; color:#000';
		} elseif ($sData['percentage'] >= 42) {
			$num = $sData['percentage'] - 42 + 2;
			$attr = 'margin-left:' . $num . 'px';
		}


 return '<div style="clear:both">
	 	<a href="' . userURL($ownerID) . '"><img src="' . iconServer() . '50_' . dispUser($ownerID, 'prof_icon') . '" style="float:left; width:48px; height:48px; margin-left:10px; margin-bottom:3px;background:#fff" class="vidView" /></a>
		 	<div style="margin-left:70px;padding-bottom:5px">
		 		<div style="padding-top:1px">

		 			<div id="storagebar" class="' . $class . '">
		 				<div class="hud" style="' . $attr . '">
		 				<strong>' . $sData['percentage'] . '%</strong> full
		 				</div>
		 			</div>
		 			<div id="storageval" style="display:none">' . $sData['percentage'] . '</div>

		 			<div style="color:#666;font-size:10px;margin-left:3px">Using ' . $sData['used']['data'] . ' ' . $sData['used']['fix'] . ' of your ' . $sData['available']['data'] . ' ' . $sData['available']['fix'] . '</div>
		 			<div style="margin-top:-5px;font-size:10px">' .  dispOnly('<a href="#" onClick="jQuery.facebox({ 
    ajax: \'/app/common/colleagues/add\'
  });
  return false;">Invite colleagues, <strong>get storage!</strong></a>', 3) . '</div>

		 		</div>
		 	</div>
	 	</div>';



	} else {

	 return '<div style="clear:both;margin-bottom:17px">
	 	<a href="' . userURL($ownerID) . '"><img src="' . iconServer() . '50_' . dispUser($ownerID, 'prof_icon') . '" style="float:left; width:48px; height:48px; margin-left:10px; margin-bottom:8px;background:#fff" class="vidView" /></a>
		 	<div style="margin-left:70px">
		 		<div style="padding-top:5px;font-size:14px">
		 			<a href="' . userURL($ownerID) . '">' . dispUser($ownerID, 'first_name') . ' ' . dispUser($ownerID, 'last_name') . '</a>
		 		</div>
		 		<div style="padding-top:2px;font-size:12px">
		 			is the owner of this folder.
		 		</div>
		 	</div>
	 	</div>';
	}
	
}


// show control panel UI
function actionUI($conObj, $perObj) {

	// if this person owns the folder
	$owner = '<div id="addBtn" class="btn success addButtonBox" onClick="">

	<div class="boxTitle"><img src="/assets/app/img/box/add.png" style="height:16px;margin-right:7px;margin-bottom:-3px;" />' . say('Add Content') . '</div>

	<div class="contentPanel">
		<div class="contentItem" style="border-top:none" onClick="addContent(\'folder\');">
			<img src="/assets/app/img/box/addFolder.png" style="height:14px;float:left;margin-left:5px;margin-right:5px" />
			Add Folder
		</div>
		<div class="contentItem" onClick="addContent(\'file\');">
		<img src="/assets/app/img/box/upload.png" style="height:14px;float:left;margin-left:5px;margin-right:5px;margin-top:-1px" />
			Upload Files
		</div>
		<div class="contentItem" onClick="addContent(\'web\');">
		<img src="/assets/app/img/box/web.png" style="height:14px;float:left;margin-left:5px;margin-right:5px;margin-top:-1px" />
			Add URL / Video / Embed
		</div>
		<div class="contentItem" onClick="addContent(\'gdoc\');">
		<img src="/assets/app/img/box/google.png" style="height:14px;float:left;margin-left:5px;margin-right:5px;margin-top:-1px" />
			Add Google Doc
		</div>
	</div>
	

	</div>








      <button id="copyBtn" class="btn actBtn actBtnNoRight actBtnMarg" style="margin-left:10px;margin-top:40px" disabled><img src="/assets/app/img/box/copy.png" style="height:12px;margin-right:5px;margin-bottom:-2px;" />' . say('Copy') . '</button>

      <button id="moveBtn" class="btn actBtn actBtnMiddle actBtnMarg" disabled><img src="/assets/app/img/box/move.png" style="height:12px;margin-right:5px;margin-bottom:-2px;" />' . say('Move') . '</button>

      <button id="delBtn" class="btn actBtn actBtnNoLeft actBtnMarg" disabled><img src="/assets/app/img/box/del.png" style="height:12px;margin-right:5px;margin-bottom:-2px;" />' . say('Delete') . '</button>

      <button id="shareBtn" class="btn actBtn actBtnNoRight" style="margin-left:40px" disabled><img src="/assets/app/img/box/share.png" style="float:left; height:16px;margin-right:5px;margin-top:-2px;margin-bottom:-2px" />' . say('Share') . '</button>

      <button id="tagBtn" class="btn actBtn actBtnNoLeft actBtnMarg" disabled><img src="/assets/app/img/box/tag.png" style="height:12px;margin-right:5px;margin-bottom:-2px;" />' . say('Tag') . '</button>';

     // if this person has read/write permission
    /* $readwrite = '<button id="addBtn" class="btn"onClick="jQuery.facebox({ ajax: \'/app/filebox/write/add/folder/\' });return false">' . say('Add Content') . '</button><br />
		<button id="copyBtn" class="btn" disabled>' . say('Copy') . '</button>
		<button id="moveBtn" class="btn" disabled>' . say('Move') . '</button>
		<button id="delBtn" class="btn" disabled>' . say('Delete') . '</button>';*/

	// if this is shared as read-only or publicly
	$readonly = '<button id="copyBtn" style="margin-left:20px" class="btn actBtn" disabled><img src="/assets/app/img/box/copy.png" style="height:12px;margin-right:5px;margin-bottom:-2px;" />' . say('Copy selected to your FileBox') . '</button>';

	// if we own this OR this is home/shared
	if ($perObj['isOwner'] == 1 || $conObj['_id'] === 0) {
		$barML = $owner;
	}

	// shared view is readonly
	if ($conObj['_id'] === 'shared') {
		$barML = $readonly;
	}

	// if we actually have permission to view this
	if ($perObj['localAuth'] != 0) {

		if ($perObj['localAuth'] == 2) {
			$barML = $owner;
		

		} elseif ($perObj['localAuth'] == 1) {
			$barML = $readonly;

		}

	// if this is shared publicly
	} elseif ($perObj['publicAuth'] == 1) {
	 	// viewing this publicly
	 	$barML = $readonly;

	 }

	 if (isset($barML)) {
	 	$barML = '<div style="border-top:1px solid #ddd;clear:both;padding-top:10px">' . $barML . '</div>';
	 }

	 return $barML;
	
}



// show forks notice (if applicable)
function forkNotice($conObj, $perObj) {
	if ($conObj['forkedFrom'] != 0 && $conObj['forkedFrom'] != '0') {
		$cObj = getContent($conObj['forkedFrom']);
		if (!is_null($cObj)) {
			$permissionObj = verifyPermissions($cObj, user('id'), $mySecs);
			$perLevel = determinePerLevel($cObj['_id'], $permissionObj);
			$result = '<div class="alert-message block-message warning" style="font-size:11px;margin:10px 10px 0 10px;padding:4px 4px 4px 4px">
			<img src="/assets/app/img/box/fork.png" style="float:left;margin:3px 5px 5px 0px" />
			Used from <a href="#">' . dispUser($cObj['owner_id'], 'first_name') . ' ' . dispUser($cObj['owner_id'], 'last_name') . '</a><br />';

			if ($perLevel > 0) {
				$result .= '<center><a href="/app/filebox/' . $conObj['forkedFrom'] . '" style="font-weight:bolder">Click here to view the original</a></center>';
			}

			$result .= '</div>';

			return $result;
			
		} else {
			return '';
		}
	}
}


// show tags & sharing info
function assocUI($conObj, $perObj) {
	  if ($conObj['_id'] != 0) {

	  // generate pemissions
	  if (empty($conObj['permissions'])) {
	  	$shares = $conObj['parentPermissions'];
	  } else {
	  	if (empty($conObj['parentPermissions'])) {
	  		$shares = $conObj['permissions'];
	  	} else {
	  		$shares = array_merge($conObj['permissions'], $conObj['parentPermissions']);
	  	}
	  }

	  foreach ($shares as $share) {
	  	if ($share['type'] == 1) {
	  		$fshar[1][] = $share;
	  		$colAttr .= dispUser($share['shared_id'], 'first_name') . ' ' . dispUser($share['shared_id'], 'last_name') . '<br />';
	  	} elseif ($share['type'] == 2 && authSection($share['shared_id'])) {
	  		$fshar[2][] = $share;
	  		$sdata = getSection($share['shared_id']);
	  		$courAttr .= $sdata['title'] . '<br />';
	  	} elseif ($share['type'] == 3) {
	  		$fshar[3][] = $share;
	  	}
	  }

	  if (count($fshar[1]) > 0) {
	  	if (count($fshar[1]) > 1) {
	  		$colText = 'colleagues';
	  	} else {
	  		$colText = 'colleague';
	  	}
	  	$sharePend .= '<a href="#" rel="sharedWith" data-original-title="' . $colAttr . '<span style=\'color:#ccc;font-size:9px\'>(click to edit)</span>" onClick="shareCurrent();return false">
	      	<img src="/assets/app/img/temp/shared.png" style="float:left;margin-right:5px;margin-top:2px;height:12px" /> ' . count($fshar[1]) . ' ' . $colText . '
	      </a>';
	  }

	  if (count($fshar[2]) > 0) {
	  	if (count($fshar[2]) > 1) {
	  		$courText = 'courses';
	  	} else {
	  		$courText = 'course';
	  	}
	  	if ($sharePend != '') {
	  		$sharePend .= '<br />';
	  	}
	  	$sharePend .= '<a href="#" rel="sharedWith" data-original-title="' . $courAttr . '<span style=\'color:#ccc;font-size:9px\'>(click to edit)</span>" onClick="shareCurrent();return false">
	      	<img src="/assets/app/img/temp/course.png" style="float:left;margin-right:5px;margin-top:2px;height:12px" /> ' . count($fshar[2]) . ' ' . $courText . '
	      </a>';
	  }

	  if (count($fshar[3]) > 0) {
	  	if ($sharePend != '') {
	  		$sharePend .= '<br />';
	  	} else {
	  		if (determinePerLevel($conObj['_id'], $perObj) == 2) {
	  			$sharePend = '<div style="font-size:10px;color:#333">Not shared with colleagues or courses...<a href="#" onClick="shareCurrent();return false">yet.</a></div>';
	  		}
	  	}


	  	if (!checkSession()) {
	  		$sharePend = '';
	  	}
	  	/*$sharePend .= '<a href="#" rel="sharedWith" data-original-title="<strong>This is shared publicly</strong><br />Anyone with the link can access it<br /><span style=\'color:#bbb;font-size:9px\'>(click to view the link)</span>" onClick="shareCurrent();return false">
	      	<img src="/assets/app/img/temp/globe.png" style="float:left;margin-left:-2px;margin-right:5px;margin-top:2px;height:12px" /> Public
	      </a>';*/

	      $sharePend .= '<div class="btn primary" style="padding:3px 3px 3px 3px;font-size:10px;margin-top:5px">
			<span style="color:#fff;font-weight:bolder"><img src="/assets/app/img/box/sharelink.png" style="float:left;margin-right:4px;margin-top:0px;height:12px" /> Public link (click then copy & paste)</span><br />
          <input type="text" style="margin-top:4px;font-size:11px;width:180px;padding-top:2px;padding-bottom:2px;border:2px solid #999;cursor:pointer" onclick="this.select();" value="http://www.classconnect.com/app/filebox/' . $conObj['_id'] . '/" readonly />
		</div>';
	  }


	  if (empty($fshar)) {
	  	$sharePend = '<div style="font-size:10px;color:#333">Not shared with anyone...<a href="#" onClick="shareCurrent();return false">yet.</a></div>';
	  }

      $barML .= '<div style="border-top:1px solid #ddd;clear:both;padding-top:5px; margin-top:10px;padding-left:10px">
	      <div style="font-size:12px; font-weight:bolder; color:#666">
	      Sharing
	      </div>
	      ' . $sharePend . '
      </div>';
      
      	
      

      $tags = getSharedTags(array($conObj));
      // cycle through and sort tags
		foreach ($tags as $tag) {
		   if ($tag['type'] == 1) {
		     $finalArr[1][] = $tag;
		   } elseif ($tag['type'] == 2) {
		    $finalArr[2][] = $tag;
		  } elseif ($tag['type'] == 3) {
		    $finalArr[3][] = $tag;
		  } elseif ($tag['type'] == 4) {
		    $finalArr[4][] = $tag;
		  }
		}

      $barML .= '<div style="border-top:1px solid #ddd;clear:both;padding-top:5px; margin-top:10px; padding-left:10px">
	      <div style="font-size:12px; font-weight:bolder; color:#666">
	      Tags
	      </div>';

	      // determine grade / subject combo
	      if (!empty($finalArr[1]) || !empty($finalArr[2])) {
		      $gtext = '';
		      if (!empty($finalArr[1])) {
		      	$gtext .= '<strong>Grade Levels</strong><br />';
		      	foreach ($finalArr[1] as $gradeSub) {
		      		$gtext .= $gradeSub['title'] . ', ';
		      	}
		      	$gtext = substr($gtext, 0, strlen($gtext) -2) . '<br />';
		      }

		      if (!empty($finalArr[2])) {
		      	$gtext .= '<strong>Subjects</strong><br />';
		      	foreach ($finalArr[2] as $gradeSub) {
		      		$gtext .= $gradeSub['title'] . '<br />';
		      	}
		      	
		      }
		      $gtext .= '<span style=\'color:#ccc;font-size:9px\'>(click to edit)</span>';

		      // format the verbage
		      $displayText = '';
		      if (!empty($finalArr[1])) {
		      	$displayText .= count($finalArr[1]) . ' grade';
		      	if (count($finalArr[1]) > 1) {
		      		$displayText .= 's';
		      	}

		      	if (!empty($finalArr[2])) {
		      		$displayText .= ' & ';
		      	}

		      	
		      }
		      if (!empty($finalArr[2])) {
		      	$displayText .= count($finalArr[2]) . ' subject';
		      	if (count($finalArr[2]) > 1) {
		      		$displayText .= 's';
		      	}
		      }

		      $barML .= '<a href="#" rel="sharedWith" data-original-title="' . $gtext . '" onClick="tagCurrent();return false">
		      	<img src="/assets/app/img/temp/course.png" style="float:left;margin-right:5px;margin-top:2px;height:12px" /> ' . $displayText . '</a><br />';
		  // on to the next type of tag...STANDARDS!
	      }
	      if (!empty($finalArr[3])) {
	      	$standText = '';
	      	$gtext = '';
	      	foreach ($finalArr[3] as $gradeSub) {
		      	$gtext .= $gradeSub['title'] . '<br />';
		    }
		    $gtext .= '<span style=\'color:#ccc;font-size:9px\'>(click to edit)</span>';
	      	$standText .= count($finalArr[3]) . ' standard';
	      	if (count($finalArr[3]) > 1) {
	      		$standText .= 's';
	      	}
	      	$barML .= '<a href="#" rel="sharedWith" data-original-title="' . $gtext . '" onClick="tagCurrent();return false">
		      	<img src="/assets/app/img/temp/standard.png" style="float:left;margin-right:5px;margin-top:2px;height:12px" /> ' . $standText . '</a><br />';
	      }
	      // last, bt not least, keywords.
	      if (!empty($finalArr[4])) {
	      	$keyText = '';
	      	$gtext = '';
	      	foreach ($finalArr[4] as $gradeSub) {
		      	$gtext .= $gradeSub['title'] . '<br />';
		    }
		    $gtext .= '<span style=\'color:#ccc;font-size:9px\'>(click to edit)</span>';
	      	$keyText .= count($finalArr[4]) . ' keyword';
	      	if (count($finalArr[4]) > 1) {
	      		$keyText .= 's';
	      	}
	      	$barML .= '<a href="#" rel="sharedWith" data-original-title="' . $gtext . '" onClick="tagCurrent();return false">
		      	<img src="/assets/app/img/temp/keywords.png" style="float:left;margin-right:5px;margin-top:2px;height:12px" /> ' . $keyText . '</a><br />';
	      }



	      // no tags at all?
	      if (empty($finalArr)) {
	      	$barML .= '<div style="font-size:10px;color:#333">There are no tags here...<a href="#" onClick="tagCurrent();return false">yet.</a></div>';
	      }
	      
      $barML .= '</div>';

  }


  return $barML;
	
}





// create the content sidebar view
function createFilBar($conObj, $perObj) {
	$barML = '';
	$barML .= authorUI($conObj, $perObj);
	$barML .= forkNotice($conObj, $perObj);
	$barML .= assocUI($conObj, $perObj);

	$bar = '<div class="folderBar fboxFloater">' . $barML . '</div>';
    return $bar;
	
}



// display a piece of content (main view)
function createContentView($conID, $cObj, $permissionObj, $perLevel, $dataID) {
	if ($dataID == false) {
		return '<div style="font-weight:bolder;font-size:24px;color:#666;text-align:center;margin-top:20px">Oops! We couldn\'t find that!</div>';
	}
		// get the data
		$data = getContentData($dataID);
		$view = displayContent($cObj, $data);

		$result = '<div id="padset" style="height:30px;width:650px;display:none">&nbsp;</div>
		<div class="contentView">
		<div class="contentTitle"><span class="contentTitleSwap">' . createConTitle($cObj) . '</span>';

		// if editable, show rename button
		if ($perLevel == 2) {
			$result .= '<button class="btn topDesc" style="padding-left:5px;padding-right:0px;padding-top:2px;padding-bottom:4px;position:absolute;margin-left:7px;margin-top:-3px" title="Rename this content" onClick="jQuery.facebox({ 
    ajax: \'/app/filebox/write/edit/title/' . $conID . '\'
  }); $(\'.twipsy\').remove(); return false;"><img src="/assets/app/img/box/editcon.png" style="height:14px;float:left;margin-top:2px;margin-right:4px" /></button>';
		}


		$result .= '</div>
		' . $view . '</div>';

		// if we have read write
		if ($perLevel == 2) {
			if ($cObj['body'] === '') {
			$hideText = ';display:none';
		} else {
			$hidePlacer = ';display:none';
			$placeText = $cObj['body'];
		}

		$result .= '<div class="descMain" style="margin-left:10px">

		<div class="descPlacer descTip" data-original-title="Click to edit" onClick="swapDesc()"style="width:660px' . $hidePlacer . '">Add a description for this content...</div>

		<div class="descText descTip" data-original-title="Click to edit" onClick="swapDesc()" style="width:660px' . $hideText . '">' . $placeText . '</div>

		<div class="descHold" style="margin-bottom:-18px;margin-left:10px">
		<textarea id="' . uniqid() . '" name="desc" rows="15" cols="80" style="width: 668px" class="descBox">' . htmlspecialchars($placeText) . '</textarea>
		<div class="actions" style="border-top:none;border-bottom:1px solid #ccc;margin-top:0px;width:660px;padding:0; padding-top:4px; padding-bottom:4px;padding-right:8px;background:#dddddd">
    <div style="float:right">
      <button type="submit" class="btn danger" style="font-size:10px;font-weight:bolder" onClick="saveDesc();">Save Description</button>&nbsp;<button type="reset" class="btn" onclick="swapDesc();" style="font-size:10px">Cancel</button>
    </div>
    <div style="clear:both"></div>
  </div>


		</div>

		</div>';
	} else {
		if ($cObj['body'] !== '') {
			$result .= '<div class="descMain" style="margin-left:10px"><div class="descText" style="width:660px">' . $cObj['body'] . '</div></div>';
		}
	}

		return $result;
	
}



// create options view
function createFilUI($conID, $cObj, $permissionObj, $perLevel, $dataID) {
	$ret = '<div style="float:right;margin-top:15px">';

	// if this is accessible publicly, show the share link
	if (verifyPublic($cObj)) {
		$ret .= '<div style="font-size:11px;float:left;margin-right:15px;margin-top:6px;cursor:pointer" onclick="jQuery.facebox({ div: \'#shareBoxer\' });"><a href="#" onclick="return false">Share</a> <img src="/assets/app/img/box/sharelink.png" style="margin-bottom:-3px;margin-left:2px" /></div>


		<div id="shareBoxer" style="display:none">



		<div class="btn primary" style="padding-left:7px;padding-right:7px;margin-left:45px">
			<span style="color:#fff;font-weight:bolder"><img src="/assets/app/img/box/sharelink.png" style="float:left;margin-right:4px;margin-top:0px" /> Link (click then copy & paste)</span><br />
          <input type="text" style="margin-top:4px;font-size:11px;width:253px;padding-top:2px;padding-bottom:2px;border:2px solid #999;cursor:pointer" onclick="this.select();" value="http://www.classconnect.com/app/filebox/' . $conID . '/' . $dataID . '" readonly />
		</div>

		<div style="margin-bottom:-10px;margin-left:90px;margin-top:10px">
			<iframe src="//www.facebook.com/plugins/like.php?href=' . urlencode('http://www.classconnect.com/app/filebox/' . $conID . '/' . $dataID) . '&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=90&amp;appId=213954741999891" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:50px; height:70px; margin-right:15px" allowTransparency="true"></iframe>

	        <iframe allowtransparency="true" frameborder="0" marginwidth="0" scrolling="no" src="https://plusone.google.com/_/+1/fastbutton?url=' . urlencode('http://www.classconnect.com/app/filebox/' . $conID . '/' . $dataID) . '&amp;size=tall&amp;count=true&amp;annotation=&amp;width=120&amp;hl=en-US&amp;jsh=m%3B%2F_%2Fapps-static%2F_%2Fjs%2Fwidget%2F__features__%2Frt%3Dj%2Fver%3DSXEYxs5FO0c.en_US.%2Fsv%3D1%2Fam%3D!KW4lzGmbF_KIhSW8Og%2Fd%3D1%2F#id=I1_1327178530968&amp;parent=' . urlencode('http://www.classconnect.com/app/filebox/' . $conID . '/' . $dataID) . '&amp;rpctoken=350075819&amp;_methods=onPlusOne%2C_ready%2C_close%2C_open%2C_resizeMe" style="border:none; overflow:hidden; width:50px; height:70px;margin-bottom:-1px;margin-right:15px" title="+1"></iframe>

	        <iframe allowtransparency="true" frameborder="0" scrolling="no"
	        src="//platform.twitter.com/widgets/tweet_button.html?count=vertical&text=' . $cObj['title'] . ' is awesome! %23UnitedWeTeach&via=ClassConnectInc&url=' . urlencode('http://www.classconnect.com/app/filebox/' . $conID . '/' . $dataID) . '"
	        style="width:55px; height:70px;"></iframe>
		</div>

		<button class="btn" style="float:right; margin:10px" onclick="closeBox();">Close<button>

		</div>';
	}

	// if this is accessible publicly, show the share link
	if ($permissionObj['isOwner'] == 1) {
		$icon = 'copy';
		$text = 'Copy';
	} else {
		$icon = 'addfile';
		$text = 'Add';
	}
	$ret .= '<button class="btn fboxFilUIbtn primary" style="font-weight:bolder" onClick="jQuery.facebox({ 
    ajax: \'/app/filebox/write/copy/?conIDs=' . $conID . '\'
  }); return false;"><img src="/assets/app/img/box/' . $icon . '.png" style="height:14px;float:left;margin-top:2px;margin-right:4px" /> ' . $text . ' to your Filebox</button>';

  	if (didRecommend($cObj, $dataID)) {
		$class = ' fboxFilUIbtnSel';
		$text = 'Un-recommend this';
	} else {
		$text = 'Recommend this';
	}

	if (!checkSession()) {
		$text = 'Login to recommend this';
		$func = 'logPopper()';
	} else {
		$func = 'recommendThis(this, \'' . $conID . '\', \'' . $dataID . '\')';
	}

	$numForks = genNumForks($cObj, $dataID);

	$ret .= '<button class="btn fboxFilUIbtn topDesc' . $class . '" onClick="' . $func . '"  title="' . $text . '"><img src="/assets/app/img/box/thumbup.png" style="height:14px;float:left;margin-top:2px;margin-right:4px" /> <span class="label numbero" style="background:#666;text-shadow:none">' . genNumLikes($cObj, $dataID) . '</span></button>

	<button class="btn fboxFilUIbtn topDesc" style="margin-right:0px" title="This has been used ' . $numForks . ' times<br /><span style=\'font-size:9px;color:#ccc\'>(click to view)</span>" onClick="jQuery.facebox({ 
    ajax: \'/app/filebox/read/forks/' . $conID . '/' . $dataID . '\'
  }); return false;"><img src="/assets/app/img/box/fork.png" style="height:14px;float:left;margin-top:2px;margin-right:4px" /> <span class="label copynumbero" style="background:#666;text-shadow:none">' . $numForks . '</span></button>';


	$ret .= '</div>';


	return $ret;
}


function genNumLikes($cObj, $dataID) {
	foreach ($cObj['versions'] as $vkey=>$vd) {
		if ($vd['id'] == $dataID) {
			return (int) $vd['recs'] + 1;
		}
	}
	
}

function genNumForks($cObj, $dataID) {
	foreach ($cObj['versions'] as $vkey=>$vd) {
		if ($vd['id'] == $dataID) {
			return (int) $vd['forkTotal'] + 1;
		}
	}
	
}


function addRecommendation($conID, $dataID, $uid) {
	if (!isset($uid)) {
		$uid = user('id');
	}

	// get the data for this content
	$cObj = getContent($conID);
	// if we're good to go, lets get the permissions
	$permissionObj = verifyPermissions($cObj, $uid);
	$perLevel = determinePerLevel($cObj['_id'], $permissionObj);

	$dataID = verifyDataAuth($dataID, $cObj);

	// if this dataID exists
	if ($dataID != false && $perLevel > 0) {
		// update the thing
		// update local
		$up = array();

		foreach ($cObj['versions'] as $vkey=>$vd) {
			if ($vd['id'] == $dataID) {
				$countIndex = 'versions.' . $vkey . '.recs';
				$arrIndex = 'versions.' . $vkey . '.recIDs';
				$up[$countIndex] = $vd['recs'];
				$up[$arrIndex] = $vd['recIDs'];
			}
		}

		$uid = (int) $uid;

		if (!in_array($uid, $up[$arrIndex])) {
			$up[$arrIndex][] = $uid;
			$up[$countIndex] = (int) $up[$countIndex];
			$up[$countIndex] = $up[$countIndex] + 1;

			global $mdb;
		  	$collection = $mdb->fbox_content;
			// update this
			$collection->update(array('_id' => new MongoId($conID)), array('$set' => $up));

			$share_permissions = array();
			// remove all entities that are courses
			foreach ($cObj['permissions'] as $pkey=>$per) {
				if ($per['type'] != 2) {
					$share_permissions[] = $cObj['permissions'][$pkey];
				}
			}
			foreach ($cObj['parentPermissions'] as $pkey=>$per) {
				if ($per['type'] != 2) {
					$share_permissions[] = $cObj['parentPermissions'][$pkey];
				}
			}

			insertFboxNoti(5, $share_permissions, array(), $cObj['owner_id'], $cObj, array("dataID" => $dataID), null, $uid);


			return true;
		} else {
			return false;
		}

	} else {
		return false;
	}
}


function delRecommendation($conID, $dataID, $uid) {
	if (!isset($uid)) {
		$uid = user('id');
	}

	// get the data for this content
	$cObj = getContent($conID);
	// if we're good to go, lets get the permissions
	$permissionObj = verifyPermissions($cObj, $uid);
	$perLevel = determinePerLevel($cObj['_id'], $permissionObj);

	$dataID = verifyDataAuth($dataID, $cObj);

	// if this dataID exists
	if ($dataID != false && $perLevel > 0) {
		// update the thing
		// update local
		$up = array();

		foreach ($cObj['versions'] as $vkey=>$vd) {
			if ($vd['id'] == $dataID) {
				$countIndex = 'versions.' . $vkey . '.recs';
				$arrIndex = 'versions.' . $vkey . '.recIDs';
				$up[$countIndex] = $vd['recs'];
				$up[$arrIndex] = $vd['recIDs'];
			}
		}

		$uid = (int) $uid;

		if (in_array($uid, $up[$arrIndex])) {
			foreach ($up[$arrIndex] as $ik=>$val) {
				if ($val == $uid) {
					unset($up[$arrIndex][$ik]);
				}
			}
			array_values($up[$arrIndex]);
			$up[$countIndex] = (int) $up[$countIndex];
			if ($up[$countIndex] > 0) {
				$up[$countIndex] = $up[$countIndex] - 1;
			}

			global $mdb;
		  	$collection = $mdb->fbox_content;
			// update this
			$collection->update(array('_id' => new MongoId($conID)), array('$set' => $up));

			$feed_collection = $mdb->feed;
			$feed_collection->remove(array('appType' => 1, 'notiType' => 5, 'uid' => $uid, 'data.0.id' => $conID, 'data.0.dataID' => $dataID), array('safe' => true));
			return true;
		} else {
			return false;
		}

	} else {
		return false;
	}
}


function didRecommend($cObj, $dataID, $uid) {
	if (!isset($uid)) {
		$uid = user('id');
	}

	$up = array();

	foreach ($cObj['versions'] as $vkey=>$vd) {
		if ($vd['id'] == $dataID) {
			$up[$countIndex] = $vd['recs'];
			$up[$arrIndex] = $vd['recIDs'];
		}
	}

	$uid = (int) $uid;

	if (in_array($uid, $up[$arrIndex])) {
		return true;
	} else {
		return false;
	}
	
}



// create the comment view
function createCommentView($conID, $cObj, $permissionObj, $perLevel, $dataID, $override) {
	if ($dataID == false) {
		return '';
	}

	$vdex = getDataIndex($dataID, $cObj);

	// there is no override, show for main fbox view
	if (!isset($override)) {
		// private viewing
		if ($perLevel == 2) {
			$comLabels = '<span class="commentbox-label editor-true selecterd"><span class="commentcount">' . count($cObj['versions'][$vdex]['comments_priv']) . '</span> editor comments</span>&nbsp;&nbsp;&nbsp;
		<span class="commentbox-label viewer-true"><span class="commentcount">' . count($cObj['versions'][$vdex]['comments_pub']) . '</span> viewer comments</span>';

			$comDatas = '<div class="commentData editor-comments">' . genCommentFeed($cObj['versions'][$vdex]['comments_priv'], $conID, $dataID, $permissionObj, $perLevel) . '</div>
		<div class="commentData viewer-comments" style="display:none">' . genCommentFeed($cObj['versions'][$vdex]['comments_pub'], $conID, $dataID, $permissionObj, $perLevel) . '</div>';

			$comLev = 2;

		// public viewing
		} elseif ($perLevel == 1) {
			$comLabels = '<span class="commentbox-label pub-true selecterd"><span class="commentcount">' . count($cObj['versions'][$vdex]['comments_pub']) . '</span> viewer comments</span>';

			$comDatas = '<div class="commentData pub-comments">' . genCommentFeed($cObj['versions'][$vdex]['comments_pub'], $conID, $dataID, $permissionObj, $perLevel) . '</div>';

			$comLev = 1;
			
		}

	// override is set, display
	} else {
		// if this is a course
		if ($override['type'] == 3) {
			$courments = array();
			foreach ($cObj['versions'][$vdex]['comments_course'] as $cmt) {
				if ($cmt['optID'] == $override['optID']) {
					$courments[] = $cmt;
				}
			}


			$comLabels = '<span class="commentbox-label pub-true selecterd"><span class="commentcount">' . count($courments) . '</span> course comments</span>';

			$comDatas = '<div class="commentData pub-comments">' . genCommentFeed($courments, $conID, $dataID, $permissionObj, $perLevel) . '</div>';
			$comLev = 3;
			$optID = $override['optID'];
		}
		
	}

	// format the comments section
	$result .= '
<div class="commentbox-wrapper">


	<div class="commentbox-contain-label">
		' . $comLabels . '
	</div>


	<div class="commentBoxTopper"></div>
	<div class="commentBox">
		' . $comDatas . '

		<form action="#" class="commentBar">';
		// show the comment bar if they're logged in
		if (checkSession()) {
			$result .= '<input type="hidden" class="comlevel" name="comlevel" value="' . $comLev . '" />
			<input type="hidden" class="comlevel" name="optID" value="' . $optID . '" />
			<input type="hidden" name="conid" value="' . $conID . '" />
			<input type="hidden" name="dataid" value="' . $dataID . '" />
			<img src="' . iconServer() . '50_' . dispUser(user('id'), 'prof_icon') . '" class="proImgr" style="display:none" /><textarea class="commentBarInput" name="comment_text" placeholder=" Add a comment..." rows="3" style="width: 640px; resize: none; height: 20px; "></textarea>
			<div class="commentBarBtn" style="float:right;margin-top:10px;display:none">
				<button class="btn">Add Comment</button>
			</div>
			<div style="clear:both"></div>';
		// otherwise, show a generic error message
		} else {
			$result .= '<span style="color:#777"><a href="#" style="font-weight:bolder" onClick="logPopper();return false">Login or Sign up</a> to leave a comment!</span>';
		}
		$result .= '</form>
	</div>


</div>';

	return $result;
}


// function to display a comment feed
function genCommentFeed($comments, $conID, $dataID, $permissionObj, $perLevel, $optID, $uid) {
	if (!isset($uid)) {
		$uid = user('id');
	}

	$comments = sort2d($comments, 'posted', 'asc', true);

	$finDat = '';
	foreach ($comments as $comment) {
		$finDat .= '<div class="commentEntry" id="com-' . $comment['id'] . '">';
		if ($permissionObj['isOwner'] == 1 || ($comment['uid'] == $uid && dispUser($uid, 'level') != 1)) {
			// show the delete button
			$finDat .= '<img src="/assets/app/img/colleagues/del.png" class="deleter" title="Remove" onClick="jQuery.facebox({ ajax: \'/app/filebox/write/rm/comment/' . $conID . '/' . $dataID . '/' . $comment['id'] . '\' }); return false;" />';
		}

		$finDat .= '<a href="' . userURL($comment['uid']) . '"><img src="' . iconServer() . '50_' . dispUser($comment['uid'], 'prof_icon') . '" class="proImgr" style="margin-bottom:5px" /></a>
		<div class="commentText"><a href="' . userURL($comment['uid']) . '">' . dispUser($comment['uid'], 'first_name') . ' ' . dispUser($comment['uid'], 'last_name') . '</a><br />' . spit($comment['text']) . '</div>

		<div style="clear:both"></div>
		</div>';
	}

	return $finDat;
}


// adding a comment to a piece of content
function addConComment($conID, $dataID, $target, $text, $optID, $uid) {
	if (!isset($uid)) {
		$uid = user('id');
	}

	$text = strip_tags($text);
	$allow = false;
	$cObj = getContent($conID);
	$permissionObj = verifyPermissions($cObj, $uid, $mySecs);
	$perLevel = determinePerLevel($cObj['_id'], $permissionObj);
	$share_permissions = array();

	if ($target == 1) {
		$arDex = 'comments_pub';
		if ($perLevel >= 1) {
			$allow = true;
		}

		// remove all entities that are courses
		foreach ($cObj['permissions'] as $pkey=>$per) {
			if ($per['type'] != 2) {
				$share_permissions[] = $cObj['permissions'][$pkey];
			}
		}
		foreach ($cObj['parentPermissions'] as $pkey=>$per) {
			if ($per['type'] != 2) {
				$share_permissions[] = $cObj['parentPermissions'][$pkey];
			}
		}


		// set bogus share to ID this noti
		$share_permissions[] = array("type" => 100, "shared_id" => $dataID, "auth_level" => 1);
		$optID = '';

	} elseif ($target == 2) {
		$arDex = 'comments_priv';
		if ($perLevel == 2) {
			$allow = true;
		}

		// remove all entities without r/w access
		foreach ($cObj['permissions'] as $pkey=>$per) {
			if ($per['auth_level'] == 2) {
				$share_permissions[] = $cObj['permissions'][$pkey];
			}
		}
		foreach ($cObj['parentPermissions'] as $pkey=>$per) {
			if ($per['auth_level'] == 2) {
				$share_permissions[] = $cObj['parentPermissions'][$pkey];
			}
		}


		// set bogus share to ID this noti
		$share_permissions[] = array("type" => 101, "shared_id" => $dataID, "auth_level" => 2);

	// this is a course
	} elseif ($target == 3) {
		$arDex = 'comments_course';
		//auth section here
		if (authSection($optID, $uid)) {
			$allow = true;
		}

		$cObj['permissions'] = array();
		$cObj['parentPermissions'] = array();

		// if this is a teacher
		if (dispUser($uid, 'level') == 3) {
			$share_permissions[] = array("type" => 2, "shared_id" => $optID, "auth_level" => 1);
		// this is a student comment (notify teachers)
		} else {
			$teachers = getSectionTeachers($optID);
			foreach ($teachers as $row) {
				$share_permissions[] = array("type" => 1, "shared_id" => (int) $row['teach_id'], "auth_level" => 1);
			}

			// set bogus share to ID this noti
			$share_permissions[] = array("type" => 102, "shared_id" => $dataID, "auth_level" => 3);
		}
	}

	$dataID = verifyDataAuth($dataID, $cObj);
	if ($dataID != false && $allow) {
		global $mdb;
	  	$collection = $mdb->fbox_content;

		// update local
		$up = array();

		foreach ($cObj['versions'] as $vkey=>$vd) {
			if ($vd['id'] == $dataID) {
				$finIndex = 'versions.' . $vkey . '.' . $arDex;
				$up[$finIndex] = $vd[$arDex];
			}
		}

		$cmtID = uniqid(rand(1, 999999));
		$retVal = array("id" => $cmtID, "text" => $text, "uid" => $uid, "optID" => $optID, "posted" => (int) date("U"));
		$up[$finIndex][] = $retVal;
		// update this
		$collection->update(array('_id' => new MongoId($conID)), array('$set' => $up));

		// fire off a noti
		insertFboxNoti(4, $share_permissions, array(), $cObj['owner_id'], $cObj, array("target" => $target, "optID" => $optID), 604800, $uid);

		return array("data" => $retVal, "perLevel" => $perLevel, "permissionObj" => $permissionObj, "conID" => $conID, "dataID" => $dataID);
	} else {
		return false;
	}
}



// delete a comment
function delConComment($conID, $dataID, $comID, $uid) {
	if (!isset($uid)) {
		$uid = user('id');
	}

	$cObj = getContent($conID);
	$permissionObj = verifyPermissions($cObj, $uid, $mySecs);
	$perLevel = determinePerLevel($cObj['_id'], $permissionObj);
	$dataID = verifyDataAuth($dataID, $cObj);
	
	$vkey = getDataIndex($dataID, $cObj);

	foreach ($cObj['versions'][$vkey]['comments_priv'] as $ckey=>$cval) {
		if ($cval['id'] == $comID) {
			$comment = $cval;
			$commentKey = $ckey;
			$arrKey = 'comments_priv';
		}
	}

	foreach ($cObj['versions'][$vkey]['comments_pub'] as $ckey=>$cval) {
		if ($cval['id'] == $comID) {
			$comment = $cval;
			$commentKey = $ckey;
			$arrKey = 'comments_pub';
		}
	}

	foreach ($cObj['versions'][$vkey]['comments_course'] as $ckey=>$cval) {
		if ($cval['id'] == $comID) {
			$comment = $cval;
			$commentKey = $ckey;
			$arrKey = 'comments_course';
		}
	}

	if ($permissionObj['isOwner'] == 1 || ($comment['uid'] == $uid && dispUser($uid, 'level') != 1)) {
		global $mdb;
	  	$collection = $mdb->fbox_content;

		// remove the comment
		unset($cObj['versions'][$vkey][$arrKey][$commentKey]);
		array_values($cObj['versions'][$vkey][$arrKey]);

		$up = array();
		$up['versions.' . $vkey . '.' . $arrKey] = $cObj['versions'][$vkey][$arrKey];

		$collection->update(array('_id' => new MongoId($conID)), array('$set' => $up));
	}


}


// display a given piece of content
function displayContent($cObj, $cData) {
	// if this is a file
	if ($cObj['format'] == 1) {
		if (isset($cData['scribd_doc'])) {
			return '<div><iframe class="webConLoader" src="http://www.scribd.com/embeds/' . $cData['scribd_doc'] . '/content?start_page=1&view_mode=list&access_key=' . $cData['scribd_key'] . '&secret_password=' . $cData['scribd_pass'] . '" data-auto-height="true" data-aspect-ratio="0.772727272727273" scrolling="no" id="doc_41452" width="100%" height="550" frameborder="0"></iframe>
			<div style="margin-top:-45px;margin-left:140px">
			<a href="/app/filebox/' . $cObj['_id'] . '/' . $cData['_id'] . '/download/" target="_blank" class="btn large success" style="font-weight:bolder"><img src="/assets/app/img/box/dl.png" style="float:left;margin-right:10px;height:18px" />Download this document</a>
			</div>

			</div>';
		} else {
			// display images properly
			$imgTypes = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
			if (in_array($cData['ext'], $imgTypes)) {
				if ($cData['width'] > 600) {
					$cData['height'] = (600/$cData['width']) * $cData['height'];
					$cData['width'] = 600;
				}
				return '<center><a href="' . cloudImgPub() . $cData['data'] . '.' . $cData['ext'] . '" target="_blank"><img class="vidView" src="' . cloudImgPub() . $cData['data'] . '.' . $cData['ext'] . '" style="width:' . $cData['width'] . 'px;height:' . $cData['height'] . 'px" /></a></center>';
			}

			// return regular download file button
			return '<center><a href="/app/filebox/' . $cObj['_id'] . '/' . $cData['_id'] . '/download/" target="_blank" class="btn large success" style="font-weight:bolder"><img src="/assets/app/img/box/dl.png" style="float:left;margin-right:10px;height:18px" />Download this file</a></center>';
		}

	// if this is a URL
	} elseif ($cObj['format'] == 2) {
		return '<center><a href="' . $cData['data'] . '" class="btn large primary" style="font-weight:bolder" onclick="displayWebContent(2, \'' . str_replace("'", "\'", $cObj['title']) . '\', \'' . $cData['data'] . '\'); return false"><img src="/assets/app/img/box/globe.png" style="float:left;margin-right:10px;height:18px" />View bookmarked website</a></center>';

	// if this is an embed
	} elseif ($cObj['format'] == 3) {
		return '<center><button class="btn large primary" style="font-weight:bolder" onclick="displayWebContent(3, \'' . str_replace("'", "\'",$cObj['title']) . '\', \'' . str_replace("'", "\'",htmlspecialchars($cData['data'])) . '\'); return false"><img src="/assets/app/img/box/content.png" style="float:left;margin-right:10px;height:18px" />View embeddable content</button></center>';

	// if this is a video
	} elseif ($cObj['format'] == 4) {
		// if it's youtube
		if ($cData['vidType'] == 1) {
			return '<iframe width="670" height="375" src="http://www.youtube.com/embed/' . $cData['data'] . '?wmode=transparent" frameborder="0" wmode="Opaque" class="vidView" allowfullscreen></iframe>';

		// if it's schooltube
		} elseif ($cData['vidType'] == 2) {
			return '<iframe width="670" height="375" src="http://www.schooltube.com/embed/' . $cData['data'] . '" frameborder="0" class="vidView"></iframe>';

		// if it's teachertube
		} elseif ($cData['vidType'] == 3) {
			return '<embed src="http://teachertube.com/embed/player.swf"  width="670"  height="375"  bgcolor="undefined"  allowscriptaccess="always"  allowfullscreen="true" flashvars="file=http://teachertube.com/embedFLV.php?pg=video_' . $cData['data'] . '&menu=false&frontcolor=ffffff&lightcolor=FF0000&logo=http://teachertube.com/www3/images/greylogo.swf&skin=http://teachertube.com/embed/overlay.swf&volume=80&controlbar=over&displayclick=link&viral.link=http://www.teachertube.com/viewVideo.php?video_id=' . $cData['data'] . '&stretching=exactfit&plugins=viral-2&viral.callout=none&viral.onpause=false" class="vidView" />';

		}


	// if this is a google doc
	} elseif ($cObj['format'] == 5) {
		return '<center><a href="' . $cData['data'] . '" class="btn large primary" style="font-weight:bolder" onclick="displayWebContent(5, \'' . $cObj['title'] . '\', \'' . $cData['data'] . '\'); return false"><img src="/assets/app/img/box/goog.png" style="float:left;margin-right:10px;height:18px" />Open Google Document</a></center>';



	// temp doc catch
	} elseif ($cObj['format'] == 6) {
		return '<center><a href="/app/docs/edit/' . $cObj['_id'] . '/' . $cData['_id'] . '" class="btn large primary" style="font-weight:bolder">Open this document</a></center>';


	// temp lecture catch
	} elseif ($cObj['format'] == 7) {
		return '<center><a href="/app/livelecture/edit/?fid=' . $cObj['_id'] . '-' . $cData['_id'] . '" class="btn large primary" style="font-weight:bolder">Open this lecture</a></center>';

	}
}











// format notification inserts
function insertFboxNoti($type, $pers, $parentPers, $owner_id, $conObj, $addData, $force, $uid) {
	// set the user id
	if (!isset($uid)) {
		$uid = user('id');
	}

	if (!isset($force)) {
		$forceNew = true;
		$timeLimit = null;
	} else {
		$forceNew = false;
		$timeLimit = $force;
	}

	// make sure that type & owner are ints
	$type = (int) $type;

	// init our shared with array, a mix of $pers and $parentPers
	$sharedWith = array();
	// we need to retrieve local & parents
	foreach ($pers as $per) {
		$sharedWith[] = array("type" => $per['type'], "shareID" => $per['shared_id']);
	}
	foreach ($parentPers as $per1) {
		$sharedWith[] = array("type" => $per1['type'], "shareID" => $per1['shared_id']);
	}

	// if our UID != owner id, add the owner to this list
	if ($uid != $owner_id) {
		$sharedWith[] = array("type" => 1, "shareID" => $owner_id);
		// also cross check & remove uid from current share list
		foreach ($sharedWith as $skey=>$share) {
			if ($share['type'] == 1 && $share['shareID'] == $uid) {
				unset($sharedWith[$skey]);
				array_values($sharedWith);
			}
		}
	}

	// okay, sweet. we have all of the shares to be notified.
	// now lets elseif the type data and send noti data accordingly (if there are any shared_withs)
	if (!empty($sharedWith)) {

		
		$notiData = array("id" => (string) $conObj['_id'], "format" => (int) $conObj['format'], "title" => $conObj['title']);

		if ($conObj['format'] == 1) {
			$notiData['ext'] = $conObj['versions'][0]['ext'];
		}

		// if extra data needs to be appended
		if (isset($addData)) {
			$notiData = array_merge($notiData, $addData);
		}


	    // fire off a notification
	    insertFeedItem(1, $type, $sharedWith, $notiData, $timeLimit, $forceNew);

	// end of "if we have entities to share this with"
	}


}
?>