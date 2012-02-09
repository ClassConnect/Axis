<?php
global $mdb;
$collection = $mdb->fbox_content;
$data = $collection->findOne(array('_id' => new MongoId('4f335646498fe2ab1000000c')));

$docID = (string) $data['_id'];
unset($data['_id']);


$client = new Elastica_Client();
$index = $client->getIndex('mongotest');

$type = $index->getType('nocluebro');

$doc = new Elastica_Document($docID, $data);
$type->addDocument($doc);

$index->refresh();



$queryTerm = new Elastica_Query_Terms();
$queryTerm->setTerms('title', array('reddit2'));

$filter1 = new Elastica_Filter_Term();
$filter1->setTerm('type', 1);


$queryFinal = new Elastica_Query_Filtered($queryTerm, $filter1);

$query = Elastica_Query::create($queryFinal);
$query->setSize(10)->setFrom(0);
$query->setSort(array('versions.forkTotal' => array('order' => 'desc'), 'versions.recs' => array('order' => 'desc')));

$resultSet = $type->search($query);

var_dump($resultSet);
foreach ($resultSet as $result) 
{ 
  $result = $result->getData();
  echo '<br /><br />';
  //var_dump($result);
} 


/*
$this->assertEquals(2, $resultSet->count());

$query->addTerm('ruflin');
$resultSet = $type->search($query);

$this->assertEquals(3, $resultSet->count());

*/

?>