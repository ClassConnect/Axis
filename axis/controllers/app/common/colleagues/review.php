<?php
// get all pending reqs
$reqs = getFriendReqs();

if (isset($_POST['type']) && in_array($_POST['friendID'], $reqs)) {
  $friendID = escape($_POST['friendID']);
  $usrData = getUser($friendID);
  // accept the friend request
	if ($_POST['type'] == 1) {
    addFriend($friendID);
    $resultText = '<div class="alert-message block-message success" style="margin-right:20px;text-align:center;margin-bottom:-10px">
        You are now colleagues with <strong>' . $usrData['first_name'] . ' ' . $usrData['last_name'] . '</strong>.
      </div>
<script>
amigos = ' . genFriendsJSON() . ';
$(document).ready(function() {
  updateSidebar();
});
</script>';

  } elseif ($_POST['type'] == 2) {
    rmFriend($friendID);
    updateReqs(-1);
    $resultText = '<div class="alert-message block-message error" style="margin-right:20px;text-align:center">
        Denied request from <strong>' . $usrData['first_name'] . ' ' . $usrData['last_name'] . '</strong>.
      </div>';
  }


  echo $resultText;

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
<script type="text/javascript">
function initReq(action, friendID, mObj) {
  finalSwap = $(mObj).parent().parent();
  $(mObj).parent().html('<img src="/assets/app/img/box/miniload.gif" style="margin-left:10px;margin-top:5px" />');

  $.ajax({  
      type: "POST",  
      url: "/app/common/colleagues/review",  
      data: "type=" + action + "&friendID=" + friendID,
      success: function(retData) {
        finalSwap.html(retData);

      }  
  }); 
}

</script>
<div class="form-stacked">
  <fieldset>
    <legend>Colleague Requests (<?= count($reqs); ?>)</legend>
    <div class="clearfix">
    <div id="errorBox">
    </div>


    <?php


    foreach ($reqs as $req) {
      $usr = getUser($req);

      echo '<div style="padding-top:10px;padding-bottom:10px;border-top:1px solid #eee">
      <img src="/assets/app/small.jpg" style="height:50px;width:50px;float:left;margin-right:10px" /> 
      <span class="colNamer" style="font-weight:bolder;font-size:14px">' . $usr['first_name'] . ' ' .$usr['last_name'] . '</span>

      <div class="uniqueBoxer" style="margin-top:8px">
        <button class="btn primary" style="font-size:11px;padding:5px" onclick="initReq(1, ' . $usr['id'] . ', this)">Approve</button>
        <button class="btn" style="font-size:11px;padding:5px" onclick="initReq(2, ' . $usr['id'] . ', this)">Deny</button>
      </div>

      <div style="clear:both"></div>
    </div>';
      

    }

    ?>



    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:0px">
    <div style="float:right">
      <button class="btn" onClick="if (!$('.uniqueBoxer').length) { $('#collPop').remove(); } closeBox(); return false">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</div>