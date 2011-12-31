<?php

$query = $_GET['query'];
if (str_replace(' ', '', $query) != '') {
$APPID = "2B58A36CC379B4E201BBEC225DF184E381B00ED5";

		// BING IMAGES SEARCH
	$request1 = 'http://api.search.live.net/json.aspx?Appid=' . $APPID . '&sources=image&query=' . urlencode( $query) . '&Image.Count=24&Image.Offset=0&SafeSearchOptions=Strict';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $request1 );       
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$response1 = curl_exec( $ch );
	curl_close($ch);
	$jsonobj1  = json_decode($response1);
	if ($jsonobj1->SearchResponse->Image->Total != 0){
    echo '<div style="margin-left: 15px">';
	foreach($jsonobj1->SearchResponse->Image->Results as $value)
	{
	echo('<div style="float:left; width:110px; height:140px; padding-top: 5px; padding-bottom: 5px;margin-right:10px; margin-bottom:5px; border:1px solid #ccc"><center><a href="' . $value->MediaUrl. '" target="_blank"><img src="' . $value->MediaUrl. '" width="100" border="0" /></a></center></div>');
}
	echo '</div>';
	} else {
		echo '<br /><br /><center>No results found. Try another search query.</center>';
	}

} // empty query
?>