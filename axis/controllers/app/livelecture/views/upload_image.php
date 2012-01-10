<?php
header('Content-type: application/json');
//set array of allowed image types
$imgTypes = array('jpg', 'jpeg', 'png', 'gif','bmp');
$ret = array();

if (isset($_FILES['file'])) {
if ($_FILES['file']['error'] > 0) {
  echo '{"url":""}';
  exit();
}

$ext = strtolower(substr($_FILES['file']['name'], strrpos($_FILES['file']['name'], '.') + 1));
$enc_name = gen_encName($user_id, $_FILES['file']['name']);

  if (in_array(strtolower($ext), $imgTypes)) {
    $isImg = true;
  } else {
    $isImg = false;
  }



if ($isImg) {

	//uploadCloudFile($_FILES['file']['tmp_name'], $enc_name, 2, $ext);

	echo '{"url":"' . cloudImgPub() . $enc_name . '.' . $ext . '"}';


	exit();


// isimg check
} else {
		// throw an error in LL
		echo '{"url":""}';
}


} elseif (!isset($_FILES['file']) && isset($_GET['u'])) {
  echo '{"name":"Error. This file might be too big.","type":"0","size":"0"}';
  exit();
}

?>