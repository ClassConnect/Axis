<?php
if (isset($_POST['pass1'])) {

if (($_POST['pass1'] == $_POST['pass2']) && (strlen($_POST['pass1']) > 1)) {
$password = escape($_POST['pass1']);
setPassword($user_id, $password);
sendPasswordEmail($user_id, $password);
echo '1';

} else {
    // error msg here
    echo '<div class="errorbox" style="text-align:center">Your passwords do not match.</div>';
			
}
exit();
}



                echo '<div class="headTitle"><img src="' . $imgServer . 'gen/update.png" style="margin-right:5px;margin-top:5px" /><div>Reset Password</div></div>
<div id="failer" style="display:none;margin-top:1px;margin-left:1px;margin-right:1px;margin-bottom:5px"></div>
<div id="content" style="margin:5px">

<form method="POST" id="update-pswd" style="font-size:14px; padding-top:3px">
<strong>New Password</strong><br />
<input type="password" name="pass1" style="width:215px" /><br /><br />
<strong>Confirm New Password</strong><br />
<input type="password" name="pass2" style="width:215px" /><br />
<div style="display:none"><input type="password" name="saget" value="sag" /></div>
</form>


</div>

<div id="bottom" style="clear:both;float:right;margin-top:10px;margin-bottom:5px"><button class="button" type="submit" onClick="closeBox();" style="float:right"><img src="' . $imgServer . 'gen/cross.png" />Close</button><button class="button" type="submit" onClick="resetPass();" style="float:right"><img src="' . $imgServer . 'gen/tick.png" />Reset Password</button></div>



<script type="text/javascript">
// function for submitting the school signup form
function resetPass() {
		  dataString = $("#update-pswd").serialize();
        $.ajax({
        type: "POST",
        url: "settings.cc?n=2",
        data: dataString,
        success: function(data) {
        	if (data == 1) {
                $("#failer").html(\'\');
        		$("#content").html(\'<div class="successbox" style="text-align:center; font-weight: bolder">Password Reset Successfully!</div>\');
        		$("#bottom").html(\'<button class="button" type="submit" onClick="closeBox();" style="float:right"><img src="' . $imgServer . 'gen/cross.png" />Close</button>\');
        	} else {
      $("#failer").html(data).slideDown(300);
}

       }

        });




}
</script>';

?>
