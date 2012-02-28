<?php
require_once('../coreInc.php');
require_once('../../controllers/app/search/core/main.php');


$client = initGearmanClient();
$client->doBackground("pushDoc", '4f02bd05498fe20b11008597');


exit();
global $mdb;
$collection = $mdb->fbox_content;
$data = $collection->find();

foreach ($data as $obj) {
	$docID = (string) $obj["_id"];
	$client = initGearmanClient();
	$client->doBackground("pushDoc", $docID);

	echo "Pushed " . $docID . "\n";
}

?>