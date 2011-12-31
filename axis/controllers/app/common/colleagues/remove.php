<?php
$rmID = $this->Command->Parameters[2];

// get all pending reqs
$reqs = getFriends();


if (!in_array($rmID, $reqs)) {
  exit();
}

if (isset($_POST['submitted'])) {

  // accept the friend request
    rmFriend($rmID);
    echo '<script>
amigos = ' . genFriendsJSON() . ';
$(document).ready(function() {
  updateSidebar();
  closeBox();
  initAsyncBar(\'<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Colleague removed successfully</span>\', \'yellowBox\', 225, 540, 1500);
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
<script type="text/javascript">
function deleteCol() {
  fbFormSubmitted('#rm-col');
    $.ajax({  
      type: "POST",  
      url: "/app/common/colleagues/remove/<?= $rmID; ?>",  
      data: 'submitted=true',
      success: function(retData) {
        $("#errorBox").html(retData);
      }  
      
  });  
    return false;
}

</script>
<form action="#" id="rm-col" class="form-stacked">
  <fieldset>
    <div class="clearfix">
    <div id="errorBox">
    </div>
<span style="font-size:14px;line-height:1.3">Are you sure you want to remove <strong><?= dispUser($rmID, 'first_name') . ' ' . dispUser($rmID, 'last_name'); ?></strong> from your colleagues?</span>



    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button class="btn danger" onClick="deleteCol(); return false">Yes, remove <?= dispUser($rmID, 'first_name') . ' ' . dispUser($rmID, 'last_name'); ?></button>
      <button class="btn" onClick="closeBox(); return false">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>