<?php
if (isset($_POST['data'])) {
  $settings = cleanSettings($curData['settings']);
  $settings['timezone'] = escape($tz);
  $settings = json_encode($settings);

  good_query("UPDATE users SET settings = '$settings' WHERE id = $uid");
  getUser($uid, true);
  exit();
}
?>

<script type="text/javascript">
$(document).ready(function(){
  fbFormControl('#xlInput3');
});
$('#update-about').submit(function() {
   var serData = $("#update-about").serialize() + '&parent=' + currentCon;
    fbFormSubmitted('#update-about');
    $.ajax({  
      type: "POST",  
      url: "",  
      data: serData,  
      success: function(retData) {
        if (retData == '1') {
          initAsyncBar('<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Folder created successfully</span>', 'yellowBox', 195, 542, 1500);
          softRefresh();
          closeBox();

        } else {
          fbFormRevert('#update-about');
          showFormError(retData);
        }
      }  
      
  });  
    return false;
});
</script>
<form action="#" id="update-about" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <legend>Edit Profile</legend>
    <div class="clearfix">
    <div id="errorBox" style="display:none"></div>
      <div class="input">
        <input class="xlarge span6" id="xlInput3" name="filebox_title" size="30" maxlength="60" type="text" style="margin-right:20px">
      </div>
    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Update Profile</button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>