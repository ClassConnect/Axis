<?php
// if the form has been submitted
if (isset($_POST['submitted'])) {
	$attempt = createUser($_POST['email'], '', $_POST['firstname'], $_POST['lastname'], $_POST['pass1'], $_POST['pass2'], 3, $_POST['title']);
	if (!is_numeric($attempt)) {
		echo '<div class="alert-message warning" style="width:300px">';
		foreach ($attempt as $error) {
			echo '<li>' . $error . '</li>';
		}
		echo '</div>';
	} else {
   // set the session
   setSession($attempt);
   echo 1; 
  }
	exit();
}
?>
<style type="text/css">
.rowPut {
	margin-top:5px;
}
.pendDesc {
	width:80px;
	float:left;
	margin-top:4px;
	margin-right:5px;
	text-align:right;
	color:#666;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
  fbFormControl('#firstname');
});
function removeSpaces(string) {
 return string.split(' ').join('');
}
$('#add-teacher').submit(function() {
	var errors = new Array();
	var output = '';
	// check for first name
	if (removeSpaces($('#firstname').val()).length == 0) {
		errors[0] = '<?= say('You forgot to enter your first name.'); ?>';

	}
	// check for last name
	if (removeSpaces($('#lastname').val()).length == 0) {
		errors[1] = '<?= say('You forgot to enter your last name.'); ?>';
	}
	// check for email (valid)
	var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	if (removeSpaces($('#email').val()).length == 0 || $('#email').val().search(emailRegEx) == -1) {
		errors[2] = '<?= say('The email address you entered is not valid.'); ?>';
	}
	// make sure the password is 5 or more
	if ($('#pass1').val().length < 5) {
		errors[3] = '<?= say('Your password is too short.'); ?>';
	} else if ($("#pass1").val() != $("#pass2").val() || removeSpaces($('#pass1').val()).length == 0) {
		errors[3] = '<?= say('Your passwords do not match.'); ?>';
	}

	// iterate array
	for(var i in errors) {
		output += '<li>' + errors[i] + '</li>';
	}
	if (output != '') {
		output = '<div class="alert-message warning" style="width:300px">' + output + '</div>';
		$("#errorBox").html(output).show();
		return false;
	}
   var serData = $("#add-teacher").serialize();
    fbFormSubmitted('#add-teacher');
    $.ajax({  
      type: "POST",  
      url: "/app/signup/teacher",  
      data: serData,  
      success: function(retData) {
        if (retData == '1') {
          window.location = location.pathname;

        } else {
          fbFormRevert('#add-teacher');
          showFormError(retData);
        }
      }  
      
  });  
    return false;
});
</script>
<form action="#" id="add-teacher" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <legend><?= say('Teacher Sign Up'); ?></legend>
    <div class="clearfix">
    <div id="errorBox" style="display:none"></div>

    <div class="rowBut" style="margin-top:10px">
    	<div class="pendDesc"><?= say('First Name:'); ?></div>
      <div class="input">
        <input id="firstname" name="firstname" size="30" maxlength="20" type="text" style="font-size:12px;height:15px">
      </div>
    </div>

    <div class="rowBut" style="margin-top:10px">
    	<div class="pendDesc"><?= say('Last Name:'); ?></div>
      <div class="input">
        <input id="lastname" name="lastname" size="30" maxlength="30" type="text" style="font-size:12px;height:15px">
      </div>
    </div>

    <div class="rowBut" style="margin-top:10px">
    	<div class="pendDesc"><?= say('Email:'); ?></div>
      <div class="input">
        <input id="email" name="email" size="100" maxlength="30" type="text" style="font-size:12px;height:15px">
      </div>
    </div>

    <div class="rowBut" style="margin-top:10px">
    	<div class="pendDesc"><?= say('Title:'); ?></div>
      <div class="input">
      <select id="title" name="title" class="small">
        <option><?= say('Mr.'); ?></option>
        <option><?= say('Mrs.'); ?></option>
        <option><?= say('Ms.'); ?></option>
        <option><?= say('Dr.'); ?></option>
      </select>
      </div>
    </div>

    <div class="rowBut" style="margin-top:10px">
    	<div class="pendDesc"><?= say('Password:'); ?></div>
      <div class="input">
        <input id="pass1" name="pass1" size="100" maxlength="30" type="password" style="font-size:12px;height:15px">
      </div>
    </div>

    <div class="rowBut" style="margin-top:10px">
    	<div class="pendDesc"><?= say('Password:'); ?><div style="font-size:10px;color:#999">(<?= say('confirm'); ?>)</div></div>
      <div class="input">
        <input id="pass2" name="pass2" size="100" maxlength="30" type="password" style="font-size:12px;height:15px">
      </div>
    </div>
    <input type="hidden" name="submitted" value="true" />
    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Create Account</button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Cancel</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>