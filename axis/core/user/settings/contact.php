<?php
if (isset($_POST['submitted'])) {
$userData = getUser($user_id);
$email = escape($_POST['email']);
$estatus = escape($_POST['email_swap']);
$cell = escape($_POST['cell']);
$nstatus = escape($_POST['cell_swap']);
$test = updateUser($user_id, $userData['prof_icon'], $cell, $nstatus, $email, $estatus);

if ($test == 1) {
    echo '1';

} else {
    // error msg here
    echo '<div class="errorbox">';
		foreach ($test as $error) {
			echo '<li>' . $error . '</li>';
		}
		echo '</div>';

}
exit();
}

?>
