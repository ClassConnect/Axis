<?php
if (isset($_POST['identity'])) {
	$attempt = resetPassStream($_POST['identity']);
	if ($attempt == true) {
		$loginError = 2;
	} else {
		$loginError = 1;
	}
}
appHeader('Reset Password');
?>

<div class="content">
	<div class="row"> 
<?php 
// if we have a login error
if ($loginError == 1) {
	echo '<div class="alert-message error" style="margin-left:20px;text-align:center;font-family:\'Varela Round\', sans-serif;">
        <p><strong>Oops!</strong> We couldn\'t find that email address. Try again!</p>
      </div>';

// if this is success
} elseif ($loginError == 2){
	echo '<div class="alert-message success" style="margin-left:20px;text-align:center;font-family:\'Varela Round\', sans-serif;">
        <p><strong>We reset your password successfully!</strong> Check your email :)</p>
      </div>';

} else {
	echo '<div class="alert-message warning" style="margin-left:20px;text-align:center;font-family:\'Varela Round\', sans-serif;">
        <p><strong>If you are a student and do not have an email address linked with your ClassConnect account,</strong><br />you need to have your teacher reset your password.</p>
      </div>';
}
?>
          <div class="span10" style="margin-left:200px;">
            <form method="POST" action="/app/resetpassword" style="padding-left:20px; padding-top:15px">
            <div style="color:#777;margin-left:75px;margin-bottom:15px">Enter the email address that you used to sign up for ClassConnect.</div>
				<div style="margin-left:100px">
					<input id="idFirst" type="text" name="identity" placeholder="Email address" value="<?= $_POST['identity']; ?>" />
					<button type="submit" class="btn primary" style="margin-left:5px">Reset Password</button>
				</div>
			</form>
          </div>
          
          <div style="clear:both;margin-top:120px;"></div>

	</div> 
</div>

<?php
appFooter();
?>