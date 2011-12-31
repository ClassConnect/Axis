<?php
if (isset($_POST['submitted'])) {
  // example of per array: array('type' => 1, 'shared_id' => 8, 'auth_level' => 1)
  $attempt = addSection($_POST['cid'], $_POST['title']);

  if (is_numeric($attempt)) {
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
  fbFormControl('#secTitle');
});
$('#add-section').submit(function() {

  var serData = $("#add-section").serialize() + '&cid=' + <?= $_GET['cid']; ?>;
  fbFormSubmitted('#add-section');
    $.ajax({  
      type: "POST",  
      url: "/app/manage/courses/add/section",  
      data: serData,  
      success: function(retData) {
        if (retData == '1') {
          softFresh();
          closeBox();

        } else {
          fbFormRevert('#add-section');
          showFormError(retData);
        }
      }  
      
  });  
    return false;
});
</script>
<form action="#" id="add-section" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <legend>Add Section</legend>
    <div class="clearfix">
    <div id="errorBox" style="display:none"></div>
      <div class="input">
        <input class="xlarge span6" id="secTitle" name="title" size="30" type="text" style="margin-right:20px">
      </div>
      <div style="font-size:10px;color:#999;margin-top:8px;margin-bottom:-12px;font-style:italic"><strong>Examples:</strong> Period 1, Block 4, 440, etc...</div><br />

    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Add Section</button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>