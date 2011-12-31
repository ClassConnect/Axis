<?php
$file = file_get_contents('dump.php');
$data = str_replace("\n", '', $file);
$data = str_replace("\t", '', $data);
echo $data;

?>