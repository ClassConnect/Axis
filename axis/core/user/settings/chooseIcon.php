<?php


if (isset($_POST['id'])) {

$url = str_replace(' ', '%20', $_POST['img']);
$pid = strip_tags($_POST['id']);
if (!file_exists('extensions/classPage/temp/' . $_POST['id'] . '.png')) {
$ch = curl_init($url);
$fp = fopen('extensions/classPage/temp/' . $pid . '.png', 'wb');
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch);
fclose($fp);


// cloud info
$cloudUser = "ericmsimons"; // username
$cloudKey = "be8dfe902754b75852bfceb3b5c9e2bb"; // api key


	// Connect to Rackspace
$auth = new CF_Authentication($cloudUser, $cloudKey);
$auth->authenticate();
$conn = new CF_Connection($auth);

// Get the container we want to use
$container = $conn->get_container('cc4_img');


// $container->delete_object('JB_blog.odt');
// upload file to Rackspace
$object = $container->create_object($pid . '.png');
$object->load_from_filename('extensions/classPage/temp/' . $pid . '.png');

}

$userData = getUser($user_id);
updateUser($user_id, $pid, $userData['cell'], $userData['cell_active'], $userData['e_mail'], $userData['email_active']);

$_SESSION['prof_icon'] = $pid;

echo '<script>
$(document).ready(function(){
	$("#changeImg").attr(\'style\', \'background: url(' . $iconServer . $pid . '.png)\');
	closeBox();
});
</script>';
exit();
}




// if query
if (isset($_POST['query'])) {

	echo '<script>$(document).ready(function(){
	$("#resultsPane form").submit(function(){
		dataString = $(this).serialize();
                $("#resultsPane").html("<br /><br /><br /><center><img src=\"core/site_img/loading.gif\" /></center>");
        $.ajax({
        type: "POST",
        data: dataString,
        url: "settings.cc?n=1",
        success: function(data) {
        		$("#resultsPane").html(data);
       }

        });
		return false;
	});
});
</script>';
	$errorText = '<div style="text-align:center; font-size:14px; color: #666; padding-top:75px; font-weight:bolder">
No results found! Try another keyword.
</div>';
	$url = 'http://www.iconfinder.net/xml/search/?q=' . $_POST['query'] . '&c=50&p=0&min=64&max=128&api_key=e9dc2547f8092d2a70433350e527ae90';
$xml = simplexml_load_file($url);

       foreach($xml->iconmatches->icon as $matches){
		$id = $matches->id;
		$img = $matches->image;
		$mod_img = str_replace('%25', '%', $img);
        echo '<form action="#" method="POST"><input type="hidden" name="id" value="' . urlencode($id) . '" /><input type="hidden" name="img" value="' . $img . '" /><input type="image" src="' . $img . '" width="60" height="60" style="padding:8px;border:1px solid #999; float:left" /></form>';
	  $flag = 1;
       }
	   if ($flag != 1) {
		   echo $errorText;
	   }

	   exit();
}



echo '<div class="headTitle"><img src="' . $imgServer . 'gen/edit.png" style="margin-right:5px;margin-top:5px" /><div>Update Your Icon</div></div>
<div id="failer" style="display:none;margin-top:1px;margin-left:1px;margin-right:1px;margin-bottom:5px"></div>
<div id="content" style="margin:5px">
<form method="POST" id="icon-search" style="font-size:14px">
<button class="button" type="submit" style="float:right"><img src="' . $imgServer . 'gen/search.png" />Search For Icons</button><input type="text" name="query" style="width:320px; margin-top:3px" value="' . $classData['name'] . '" /><br /><br />

</form>

<div id="resultsPane" style="height:200px; overflow:auto">

<div style="text-align:center; font-size:14px; color: #666; padding-top:75px">
Enter a keyword in the field above to find an icon that fits you!<br />(Exampes: music, computer, car, sport)
</div>

</div>


</div>

<div id="bottom" style="clear:both;float:right;margin-top:10px;margin-bottom:5px"><button class="button" type="submit" onClick="closeBox();" style="float:right"><img src="' . $imgServer . 'gen/cross.png" />Close</button></div>

<script>

$(document).ready(function(){
	$("#icon-search").submit(function(){
		$("#resultsPane").html("<br /><br /><br /><center><img src=\"core/site_img/loading.gif\" /></center>");
		searchIcons();
		return false;
	});
});

function searchIcons() {
        dataString = $("#icon-search").serialize();
        $.ajax({
        type: "POST",
        data: dataString,
        url: "settings.cc?n=1",
        success: function(data) {
        	$("#resultsPane").html(data);

       }

        });
}

</script>';
?>