<?php
if (isset($_POST['target'])) {
  $attempt = moveContent($_POST['target'], $_POST['conIDs']);

  if ($attempt == 1) {
    echo 1;
  } else {
    echo '<div class="alert-message warning" style="width:300px">';
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
  fbFormControl();
});
$('#move-con').submit(function() {
   var serData = 'target=' + $('.chosenOne').val() + '&conIDs=<?= $_GET['conIDs']; ?>';
    fbFormSubmitted('#move-con');
    $.ajax({  
      type: "POST",  
      url: "/app/filebox/write/move/",  
      data: serData,  
      success: function(retData) {
        if (retData == '1') {
          initAsyncBar('<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Content moved successfully</span>', 'yellowBox', 200, 517, 1500);
          softRefresh();
          closeBox();

        } else {
          fbFormRevert('#move-con');
          showFormError(retData);
        }
      }  
      
  });  
    return false;
});
</script>
<form action="#" id="move-con" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
  <legend>Move Content</legend>
    <div class="clearfix">
    <div id="errorBox" style="display:none"></div>
<div style="font-size:12px; font-weight:bolder; color:#666; margin-bottom:3px">Move selected content to this folder:</div>
<?= createPickerWrap('0'); ?>

    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Move to selected folder</button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>