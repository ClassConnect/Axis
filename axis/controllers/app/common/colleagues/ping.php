<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
	addFriend($_GET['id']);
	echo 1;
} else {
	echo 0;
}
?>