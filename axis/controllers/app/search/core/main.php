<?php

function performSearch($keyQuery, $required_params) {

	//clean our query
	$keyQuery = preg_replace("/[^a-zA-Z0-9\s]/", "", strip_tags($keyQuery));

	global $mdb;
	$collection = $mdb->fbox_content;
	$data = $collection->findOne(array('_id' => new MongoId('4f337b79498fe23117000003')));

	$docID = (string) $data['_id'];
	unset($data['_id']);
	$data['body'] = strip_tags($data['body']);


	$client = new Elastica_Client();
	$index = $client->getIndex('mongotest');

	$type = $index->getType('nocluebro');

	/*
	$doc = new Elastica_Document($docID, $data);
	$type->addDocument($doc);

	$index->refresh();
	*/



	if ($keyQuery == '') {
		return false;
	}


	$queryTerm = new Elastica_Query_QueryString($keyQuery);
	$queryTerm->setFuzzyMinSim(0.5);


	$filter1 = new Elastica_Filter_Term();
	$filter1->setTerm('type', 2);


	$queryFinal = new Elastica_Query_Filtered($queryTerm, $filter1);

	$query = Elastica_Query::create($queryFinal);
	$query->setSize(10)->setFrom(0);
	$query->setSort(array('versions.forkTotal' => array('order' => 'desc'), 'versions.recs' => array('order' => 'desc')));

	$resultSet = $type->search($query);

	return $resultSet;

	/*
	$this->assertEquals(2, $resultSet->count());

	$query->addTerm('ruflin');
	$resultSet = $type->search($query);

	$this->assertEquals(3, $resultSet->count());

	*/
	
}



function genResults($resultSet) {
	$finTxt = '';

	if ($resultSet == false || $resultSet->count() == 0) {
		$finTxt .= 'No matches found for that query.';
	} else {
		foreach ($resultSet as $result) 
		{ 
		  $result = $result->getData();
		  $finTxt .= '<br /><br />';
		  $finTxt .= var_dump($result);
		} 
		$finTxt .= '<br /><br />' . $resultSet->count();
	}

	return $finTxt;
}


?>