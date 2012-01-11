<?php

if (isset($_POST['submitted'])) {

	$attempt = updateLocales(user('id'), $_POST['tz']);
	echo 1;
}


?>