<?php
require_once('../coreInc.php');
require_once('../../controllers/app/search/core/main.php');
$worker = initGearmanWorker();
$worker->addFunction("pushDoc", "pushDocument");
$worker->addFunction("delDoc", "deleteDocument");
while ($worker->work());


function deleteDocument($docID)
{
	$docID = $docID->workload();
	$client = initElastica();
	$index = $client->getIndex('msh');
	//$index->clearCache();

	$type = $index->getType('fbx');
	$type->deleteById($docID);
}






function pushDocument($docID) {
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


		$client = initElastica();
		$index = $client->getIndex('msh');
		//$index->clearCache();

		$type = $index->getType('fbx');

		
		$doc = new Elastica_Document($docID, $data);
		$type->addDocument($doc);

		//$index->refresh();
	}	
}
?>