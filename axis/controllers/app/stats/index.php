<?php
$totTeachers = count(good_query_table("SELECT * FROM users WHERE level = 3 OR level = 0"));
$today = strtotime(date("d-m-Y"));

$totToday = count(good_query_table("SELECT * FROM users WHERE reg_date >= $today AND (level = 3 OR level = 0)"));

echo 'SU today: ' . $totToday . '<br /><br />Since init: ' . ($totTeachers - 2000) . '<br /><br />Since origin: ' . $totTeachers;


// crunch FBox nums

global $mdb;

// select a collection (analogous to a relational database's table)
$collection = $mdb->fbox_content;
$data = $collection->count();

$params[] = array('permissions.type'=>3);
$params[] = array('parentPermissions.type'=>3);
$finalq = array('$or' => $params);
$pubs = $collection->find($finalq);
$totpubs = 0;
$pubarr = array();
foreach ($pubs as $pubber) {
	$totpubs++;
	$pubarr[$pubber['owner_id']] = 1;
}



echo '<br /><br /><br /><br />fbox total: ' . $data . '<br /><br />pub total: ' . $totpubs . '<br /><br />tot pubs auths: ' . count($pubarr);
?>