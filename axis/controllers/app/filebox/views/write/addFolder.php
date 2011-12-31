<?php
if (isset($_POST['submitted'])) {
  // example of per array: array('type' => 1, 'shared_id' => 8, 'auth_level' => 1)
  $attempt = addFolder($_POST['parent'], $_POST['filebox_title'], $body);

  if ($attempt == 1) {
    echo 1;
  } else {
    echo '<div class="alert-message warning" style="width:310px">';
    foreach($attempt as $error) {
      echo '<li>' . say($error) . '</li>';
    }
    echo '</div>';

  }


  exit();
}
?>
<script type="text/javascript">
$(document).ready(function(){
  fbFormControl('#xlInput3');
});
$('#add-folder').submit(function() {
   var serData = $("#add-folder").serialize() + '&parent=' + currentCon;
    fbFormSubmitted('#add-folder');
    $.ajax({  
      type: "POST",  
      url: "/app/filebox/write/add/folder/",  
      data: serData,  
      success: function(retData) {
        if (retData == '1') {
          initAsyncBar('<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Folder created successfully</span>', 'yellowBox', 195, 542, 1500);
          softRefresh();
          closeBox();

        } else {
          fbFormRevert('#add-folder');
          showFormError(retData);
        }
      }  
      
  });  
    return false;
});
</script>
<form action="#" id="add-folder" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <legend>Add Folder</legend>
    <div class="clearfix">
    <div id="errorBox" style="display:none"></div>
      <div class="input">
        <input class="xlarge span6" id="xlInput3" name="filebox_title" size="30" maxlength="60" type="text" style="margin-right:20px">
      </div>
    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Create Folder</button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>