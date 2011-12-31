<?php
if (isset($_POST['submitted'])) {
  // example of per array: array('type' => 1, 'shared_id' => 8, 'auth_level' => 1)
  $attempt = studAddCourse($_POST['code']);

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
$('#enroll-section').submit(function() {

  var serData = $("#enroll-section").serialize();
  fbFormSubmitted('#enroll-section');
    $.ajax({  
      type: "POST",  
      url: "/app/manage/courses/enroll",  
      data: serData,  
      success: function(retData) {
        if (retData == '1') {
          softFresh();
          closeBox();

        } else {
          fbFormRevert('#enroll-section');
          showFormError(retData);
        }
      }  
      
  });  
    return false;
});
</script>
<form action="#" id="enroll-section" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <legend>Enroll in a course</legend>
    <div class="clearfix">
    <div id="errorBox" style="display:none"></div>
      <div class="input">
      <div style="color:#666;padding:4px;font-weight:bolder">Course Code</div>
        <input class="xlarge span6" id="secTitle" name="code" size="30" type="text" style="margin-right:20px">
      </div>
      <div style="font-size:10px;color:#999;margin-top:8px;margin-bottom:-12px;font-style:italic"><strong>Example:</strong> qx6sfd792s</div><br />

    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Enroll in course</button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>