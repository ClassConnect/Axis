<?php
$string = file_get_contents("standards.json");
$json_a=json_decode($string,true);

$finarr = array();

foreach ($json_a as $subj) {
	foreach ($subj as $grade) {
		foreach ($grade as $stands) {

			foreach($stands as $stand) {

				$finarr[] = array("title" => $stand['title'], "label" => "<div style='text-align:left;line-height:1.2;padding:7px'><strong> " . $stand['title'] . " </strong><br />" . $stand['body'] . "</div>", "category" => "Common Core");

			}

		}
	}
}

echo json_encode($finarr);
?>