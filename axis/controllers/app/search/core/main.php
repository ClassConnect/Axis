<?php

function performSearch($keyQuery, $required_params, $limit, $offset) {

	if (!isset($limit)) {
		$limit = 20;
	}

	if (!isset($offset)) {
		$offset = 0;
	}

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

	// add filter terms here
	$filter1 = new Elastica_Filter_Term();
	$filter1->setTerm('type', 2);

	// or filter
	$orFilt = new Elastica_Filter_Or();
	$orFilt->addFilter($filter1);

	$finFilt = new Elastica_Filter_And();
	$finFilt->addFilter($orFilt);


	$queryFinal = new Elastica_Query_Filtered($queryTerm, $finFilt);

	$query = Elastica_Query::create($queryFinal);
	$query->setSize($limit)->setFrom($offset);
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
		  $finTxt .= $result['title'];
		} 
		$finTxt .= '<br /><br />' . $resultSet->count();
	}

	return $finTxt;
}













function genFilterBtns($filtArr) {
	$fin = '';
	foreach ($filtArr as $res) {
		if ($res != '') {
			$fin .= '<div class="label filterItem">'  . $res . '<img src="/assets/app/img/box/rem.png" style="height:12px;float:right;cursor:pointer;margin-top:4px" onclick="removeFilter(\'' . $res . '\')" /></div>';
		}
	}

	return $fin;
}
?>