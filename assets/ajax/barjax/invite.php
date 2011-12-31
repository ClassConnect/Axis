<?php
require_once('../../inc/coreInc.php');

function validEmail($email)
{
  return filter_var($email, FILTER_VALIDATE_EMAIL);
}


// if form posted
if (isset($_POST['submitted'])) {
	$message = "Hi there,\n" . $firstname . " " . $lastname . " has invited you to use ClassConnect.\n\n";

	if ($_POST['body'] != '') {
		$message .= $firstname . ' says: "' . $_POST['body'] . '"';
		$message .= "\n\n";
	}
	$message .= "Head over to http://www.classconnect.com to create your free account!\n\nSincerely,\nThe ClassConnect Team";
	//echo $message;
	foreach ($_POST['emails'] as $email) {
		if (validEmail($email)) {
			mail($email, $firstname . ' ' . $lastname . ' has invited you to use ClassConnect', $message, "From: support@classconnect.com");
			$totalEmails .= $email . ', ';
		}
		
	}
	// let vic know of this
	mail('vic@classconnect.com', 'CC: Users Invited Notice', $firstname . ' ' . $lastname . ' (UID: ' . $user_id . ')' . "\n\n" . $totalEmails, "From: support@classconnect.com");
	echo '1';
	exit();
}


echo '<div class="headTitle"><img src="' . $imgServer . 'gen/email_l.png" style="margin-right:5px; margin-top:5px" /><div>&nbsp;Invite your colleagues</div></div>
<div id="content">
<div id="failer" style="display:none"></div>
  <form id="invitecol" accept-charset="utf-8" style="margin:10px">
              <span style="font-size:14px">Use the form below to invite other teachers to use ClassConnect. You can enter as many (or as few) email addresses as you want!</span><br />
              <div>
            <input type="text" name="emails[]" class="regularInput inputPlacers" name="subject" style="width:410px;margin-top:15px" onfocus="optMore(this);" onblur="swapPlace(this);" value="Enter a colleagues\' email address..." />
            <input type="text" name="emails[]" class="regularInput inputPlacers" name="subject" style="width:410px;margin-top:15px" onfocus="optMore(this);" onblur="swapPlace(this);" value="Enter a colleagues\' email address..." />
            <input type="text" name="emails[]" class="regularInput inputPlacers lastput" name="subject" style="width:410px;margin-top:15px" onfocus="optMore(this);" onblur="swapPlace(this);" value="Enter a colleagues\' email address..." />
            </div>
            <br />
            <div id="swapprCmt" style="display:none">
            <span style="font-size:14px;font-weight:bolder">Personal message</span>
            <textarea class="regularInput" name="body" style="width: 410px; height:50px"></textarea>
            </div>
            <div style="font-size:14px;margin-left:2px;margin-top:8px"><a href="#" onclick="$(this).parent().remove();$(\'#swapprCmt\').show();return false;">Add a personal message...</a></div>
            <input type="hidden" name="submitted" value="true" />
        </form>

</div>

<div id="bottom" style="margin-bottom:5px; margin-top:5px; float:right">
<button id="swapBut" type="submit" class="button" onClick="inviteFriends(); return false"><img src="' . $imgServer . 'gen/add_mail_s.png" /> Send invites</button>
<button type="submit" class="button" onClick="closeBox(); return false"><img src="' . $imgServer . 'gen/cross.png" /> Close</button>
</div>';
?>
<script type="text/javascript">
function inviteFriends() {
	dataString = $("#invitecol").serialize();
	$("#content").html('<center><br /><br /><img src=\'/app/core/site_img/loading.gif\' style=\'padding-top:15px\' /><br /><br /><br /></center>');
	$.ajax({
            type: "POST",
            url: "/app/core/ajax/barjax/invite.php",
            data: dataString,
            success: function(data) {
                $('#content').html('<div class="successbox" style="padding:10px;margin:10px;text-align:center">Your colleagues have been invited to use ClassConnect! Thank you :)</div>');

                $('#swapBut').after('<button type="submit" class="button" onClick="openBox(\'/app/core/ajax/barjax/invite.cc\',450);">Send more invites</button>');

                $('#swapBut').remove();

            }

    });
}

// do we need to add another field?
function optMore(obje) {
	$(obje).removeClass('inputPlacers');
	if ($(obje).val() == 'Enter a colleagues\' email address...') {
		$(obje).val('');
	}
	if ($(obje).hasClass('lastput')) {
		$(obje).removeClass('lastput');
		$(obje).parent().append('<input type="text" name="emails[]" class="regularInput lastput inputPlacers" name="subject" style="width:410px;margin-top:15px" onfocus="optMore(this);" onblur="swapPlace(this);" value="Enter a colleagues\' email address..." />');
	}
}


// do we need to add another field?
function swapPlace(obje) {
	if ($(obje).val() == '') {
		$(obje).addClass('inputPlacers')
		$(obje).val('Enter a colleagues\' email address...');
	}
}

</script>