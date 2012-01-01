<?php
// Location of our JS/CSS server
function scriptServer() {
	return '/assets/ajax/';
}

// Location of our IMG server
function imgServer() {
	return '/assets/site_img/';
}

// Rackspace img server
function iconServer() {
	return 'http://c980053.r53.cf2.rackcdn.com/';
}

// Location of classconnect server
$appServer = 'http://localhost/';

// rackspace cloud files public URL
function cloudServer() {
	return 'http://c819655.r55.cf2.rackcdn.com/';
}


// cloud info
$cloudUser = "ericmsimons"; // username
$cloudKey = "be8dfe902754b75852bfceb3b5c9e2bb"; // api key
// rackspace cloud files main bucket
$cloudBucket = 'axis_storage';
// cloud img bucket
$cloudImgBucket = 'ccStage_img';


$scribd_api_key = "396qj067xxmleexqa5tob";
$scribd_secret = "sec-iazqgbq74f0oqheqwknoe8w8e";


// cloud img location
function cloudImgPub() {
	return 'http://c714621.r21.cf2.rackcdn.com/';
}


function cssServer() {
	return '/assets/ajax/';
}


function image_path($image)
{
  return imgServer() . $image;
}


?>