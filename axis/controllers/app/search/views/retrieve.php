<?php
$num = (int) $_GET['marker'] * 20;

// prepare our preset filters (if any)
$gradeArray = array_filter(explode(',', $_GET['grades']));
$subjArray = array_filter(explode(',', $_GET['subjs']));
$commonArray = array_filter(explode(',', $_GET['commonstand']));
$filesArray = array_filter(explode(',', $_GET['filetypes']));
$instArray = array_filter(explode(',', $_GET['instypes']));

$reqPars = array("grades" => $gradeArray, "subjects" => $subjArray, "commoncore" => $commonArray, "filetypes" => $filesArray, "instructionaltypes" => $instArray);

$keyQuery = $_GET['query'];
$resultSet = performSearch($keyQuery, $reqPars, $num);
$genQuery = genResFeed($resultSet);

echo $genQuery;

?>