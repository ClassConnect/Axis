<?php
function splitVersionURL($str) {
	$fin = array();
	$tem = explode('-', $str);
	$fin['conID'] = $tem[0];
	$fin['verID'] = $tem[1];
	return $fin;
}
?>