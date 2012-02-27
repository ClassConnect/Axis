<?php
if (isset($_POST['submitted'])) {
	$reqCount = 0;
	$friendCount = 0;
  foreach ($_POST['emails'] as $email) {
  	// if the email is not empty
  	if ($email != '') {
  		$check = getUserByEmail($email);
  		// if this user exists
  		if ($check != false) {
  			// if this is a "fake" account
  			if ($check['pass'] == 'temp-user') {
  				// add as a contact
  				addFriend($check['id'], null, true);
  				$friendCount++;
  			} else {
  				// request as a friend
  				addFriend($check['id']);
  				$reqCount++;
  			}

  		// this email address isn't in our DB. lets create a fake account for it.
  		} else {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) == true) {
          $newID = createTempUser($email);
          addFriend($newID, null, true);
          $friendCount++;
        }
  		}
  	}
  }


if ($reqCount > 0 && $friendCount > 0) {
	echo '<div class="alert-message block-message success" style="margin-right:20px;text-align:center">
        <strong>Success!</strong><br />You requested ' . $reqCount . ' colleague(s) and invited ' . $friendCount . ' colleague(s).
      </div>';
} elseif ($reqCount > 0 && $friendCount == 0) {
	echo '<div class="alert-message block-message success" style="margin-right:20px;text-align:center">
        <strong>Success!</strong><br />You requested ' . $reqCount . ' colleague(s).
      </div>';
	
} elseif ($reqCount == 0 && $friendCount > 0) {
	echo '<div class="alert-message block-message success" style="margin-right:20px;text-align:center">
        <strong>Success!</strong><br />You invited ' . $friendCount . ' colleague(s).
      </div>';
} else {
	echo '<div class="alert-message block-message error" style="margin-right:20px;text-align:center">
        <strong>Oops!</strong> We weren\'t able to invite / add anybody.
      </div>';
}

  echo '<script>
amigos = ' . genFriendsJSON() . ';
$(document).ready(function() {
  if(typeof window.updateSidebar == \'function\') {
    updateSidebar();
  }
  setTimeout(closeBox, 1500);
});
</script>';

  exit();
}
?>
<style type="text/css">
.rowPut {
    margin-top:5px;
}
.ui-datepicker {
    width:auto;
    font-size:1.0em;
}
</style>
<script type="text/javascript" src="/assets/app/js/clip/jquery.zclip.min.js"></script>
<script type="text/javascript">
// do we need to add another field?
function optMore(obje) {
    $(obje).removeClass('inputPlacers');

    if ($(obje).hasClass('lastput')) {
        $(obje).removeClass('lastput');
        $(obje).parent().append('<input type="text" name="emails[]" class="inputPlacers lastput" style="width:320px;margin-top:15px" onfocus="optMore(this);" onblur="swapPlace(this);" placeholder="Enter a colleagues\' email address..." />');
    }
}


// do we need to add another field?
function swapPlace(obje) {
    if ($(obje).val() == '') {
        $(obje).addClass('inputPlacers')
    }
}



$('#add-colleagues').submit(function() {
   var serData = $("#add-colleagues").serialize();
    fbFormSubmitted('#add-colleagues');
    $.ajax({  
      type: "POST",  
      url: "/app/common/colleagues/add",  
      data: serData,
      success: function(retData) {
        $("#add-colleagues").html(retData);

      }  
      
  });  
    return false;
});


$(".sharePop").twipsy({
  live: true,
  placement: 'above',
  html: true
});
$(document).ready(function(){
  $('.linkClicker').zclip({
      path:'/assets/app/js/clip/ZeroClipboard.swf',
      copy:'http://www.classconnect.com/app/?uref=<?= dispUser(user('id'), 'mehash'); ?>'
  });
});
</script>
<style>
.networkShares {
  height:25px;
  float:left;
  padding-left:10px;
}

</style>
<form action="#" id="add-colleagues" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
<fieldset>

    <legend>Share via social networks or URL</legend>

    <div style="color:#888;font-size:11px;margin-bottom:10px;line-height:1.2em">
     Share your personal link with colleagues. When they click the link and sign up, you both will be rewarded with 500mb of free storage.
     </div>

    <div class="clearfix">

    <div class="sharePop linkClicker" data-original-title="Click to copy link" style="float:left; padding-left:0px;font-weight:bolder;cursor:pointer">
          <img src="/assets/app/img/colleagues/link.png" style="margin-right:5px;margin-top:3px;height:18px;float:left" /> 
          <div style="padding-top:5px;float:left"><a href="#" onclick="return false">Copy URL to clipboard</a></div>
      </div>

      <a href="http://twitter.com/intent/tweet?text=Come collaborate with me on ClassConnect! http://www.classconnect.com/app/?uref=<?= dispUser(user('id'), 'mehash'); ?> %23UnitedWeTeach" onClick="window.open('http://twitter.com/intent/tweet?text=Come collaborate with me on ClassConnect! http://www.classconnect.com/app/?uref=<?= dispUser(user('id'), 'mehash'); ?> %23UnitedWeTeach', 'mywindow','location=1,status=1,scrollbars=1, width=400,height=300'); return false"><img src="/assets/app/img/colleagues/tw.png" data-original-title="Share with colleagues on Twitter" class="networkShares sharePop" /></a>

      <a href="http://www.facebook.com/share.php?u=http://www.classconnect.com/app/?uref=<?= dispUser(user('id'), 'mehash'); ?>" onClick="window.open('http://www.facebook.com/share.php?u=http://www.classconnect.com/app/?uref=<?= dispUser(user('id'), 'mehash'); ?>', 'mywindow','location=1,status=1,scrollbars=1, width=400,height=300'); return false"><img src="/assets/app/img/colleagues/fb.png" data-original-title="Share with colleagues on Facebook" class="networkShares sharePop" /></a>

<div style="clear:both"></div>
    </div>
</fieldset>



  <fieldset>

    <legend>Enter the emails of your colleagues</legend>

    <div style="color:#888;font-size:11px;margin-bottom:10px;line-height:1.2em">
     Enter the emails of your colleagues below. If they're already on ClassConnect, they will be added to your colleagues list. If they aren't, when they sign up you both will be rewarded with 500mb of free storage.
    </div>

    <div class="clearfix">

    <div>
    <input type="text" name="emails[]" class="inputPlacers" style="width:320px;margin-top:15px" onfocus="optMore(this);" onblur="swapPlace(this);" placeholder="Enter a colleagues' email address..." />

    <input type="text" name="emails[]" class="inputPlacers" style="width:320px;margin-top:15px" onfocus="optMore(this);" onblur="swapPlace(this);" placeholder="Enter a colleagues' email address..." />

    <input type="text" name="emails[]" class="inputPlacers lastput" style="width:320px;margin-top:15px" onfocus="optMore(this);" onblur="swapPlace(this);" placeholder="Enter a colleagues' email address..." />

    </div>





    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Add / Invite Colleagues</button>&nbsp;<button class="btn" onClick="closeBox(); return false">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>