<?php
require_once('/var/www/axis/core/coreInc.php');
require_once('/var/www/axis/controllers/app/search/core/main.php');
$worker = initGearmanWorker();
$worker->addFunction("pushDoc", "pushDocument");
$worker->addFunction("delDoc", "deleteDocument");
$worker->addFunction("pushNewsletter", "pushToNewsletter");
while ($worker->work());


function deleteDocument($docID)
{
	global $search_index;
	global $search_type;
	$docID = $docID->workload();
	$client = initElastica();
	$index = $client->getIndex($search_index);
	//$index->clearCache();

	$type = $index->getType($search_type);
	$type->deleteById($docID);
}






function pushDocument($docID) {
	global $search_index;
	global $search_type;
	$docID = $docID->workload();
	global $mdb;
	$collection = $mdb->fbox_content;
	$data = $collection->findOne(array('_id' => new MongoId($docID)));


	// document has been pushed
	if ($data) {
		$docID = (string) $data['_id'];
		unset($data['_id']);
		$data['body'] = strip_tags($data['body']);

		$newTags = array();
		foreach($data['tags'] as $tkey => $tag) {
			if ($tag['type'] == 1) {
				$newTags[] = convGradeToString($tag['title']);
				
			} elseif ($tag['type'] == 3) {
				$newTags[] = convStandardToString($tag['title']);

			} else {
				$newTags[] = strtolower(str_replace(" ", "", $tag['title']));
			}
		}

		foreach($data['parentTags'] as $tkey => $tag) {
			if ($tag['type'] == 1) {
				$newTags[] = convGradeToString($tag['title']);
				
			} elseif ($tag['type'] == 3) {
				$newTags[] = convStandardToString($tag['title']);

			} else {
				$newTags[] = strtolower(str_replace(" ", "", $tag['title']));
			}
		}


		$data['tagstore'] = $newTags;

		$data['uniqid'] = uniqid();


		$client = initElastica();
		$index = $client->getIndex($search_index);
		//$index->clearCache();

		$type = $index->getType($search_type);

		// temp for now, delete by docID
		$type->deleteById($docID);

		
		$doc = new Elastica_Document($docID, $data);
		$type->addDocument($doc);

		//$index->refresh();
	}	
}




function pushToNewsletter($arrdat) {
	$arrdat = $arrdat->workload();
	$userID = $arrdat['userID'];
	$letterID = $arrdat['letterID'];
	$udata = getUser($userID);
    // main teachers newsletter
    if ($letterID == 1) {
        $list = 'CC-Teachers';
    }


    $hiturl = 'https://sendgrid.com/api/newsletter/lists/email/add.json';
    $fields_string = 'api_user=classconnectinc&api_key=cc221g7tx&list=' . $list . '&data={"email":"' . $udata['e_mail'] . '","name":"' . $udata['first_name'] . ' ' . $udata['last_name'] . '"}';

    //open connection
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL,$hiturl);
    curl_setopt($ch,CURLOPT_POST,4);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);
}
?>