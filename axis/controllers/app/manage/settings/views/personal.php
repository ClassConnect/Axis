<?php
//title=Dr.&first_name=Eric&last_name=Simons&e_mail=ericsimons%40es40.net&pass1=saget&pass2=saget1

// okay, so this person is trying to update stuff
if (isset($_POST['submitted'])) {
	$attempt = updatePersonalData(user('id'), $_POST['title'], $_POST['first_name'], $_POST['last_name'], $_POST['e_mail'], $_POST['pass1'], $_POST['pass2']);

	if ($attempt == 1) {
		echo 1;
	} else {
		echo '<div class="alert-message warning" style="width:600px">';
	    foreach($attempt as $error) {
	      echo '<li>' . $error . '</li>';
	    }
	    echo '</div>';
	}
}

?>