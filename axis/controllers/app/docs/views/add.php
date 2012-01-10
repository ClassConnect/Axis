<?php
if (isset($_POST['submitted'])) {
  $attempt = addDSFile(6, $_POST['chosenOne'], $_POST['docTitle']);


// set json header
header('Content-type: application/json');
$final = array();
  if (isset($attempt['verID'])) {

    $final['success'] = 1;
    $final['data'] = $attempt;
    echo json_encode($final);
  } else {
    $etext = '<div class="alert-message warning" style="width:300px">';
    foreach($attempt as $error) {
      $etext .= '<li>' . say($error) . '</li>';
    }
    $etext .= '</div>';
    $final['success'] = 2;
    $final['data'] = $etext;
    echo json_encode($final);

  }

  exit();
}
?>
<script type="text/javascript">
$(document).ready(function(){
  fbFormControl("#docTitle");
});
$('#create-doc').submit(function() {
   var serData = $('#create-doc').serialize();
    fbFormSubmitted('#create-doc');
    $.ajax({  
      type: "POST",  
      url: "/app/docs/create",  
      data: serData,
      dataType: "json",
      success: function(retData) {
        if (retData['success'] == 1) {
          window.location = "/app/docs/edit/" + retData['data']['conID'] + "/" + retData['data']['verID'];

        } else {
          fbFormRevert('#create-doc');
          showFormError(retData['data']);
        }
      }  
      
  });  
    return false;
});
</script>
<form action="#" id="create-doc" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <legend>Create Document</legend>
    <div class="clearfix">
    <div id="errorBox" style="display:none"></div>

<div style="font-size:12px; font-weight:bolder; color:#666; margin-bottom:3px">Document Title</div>
    <div class="input" style="margin-bottom:20px">
      <input id="docTitle" name="docTitle" size="60" maxlength="60" type="text" style="width:320px">
    </div>

<div style="font-size:12px; font-weight:bolder; color:#666; margin-bottom:3px">Create document in this folder:</div>
<?= createPickerWrap('0'); ?>

    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Create Document</button>&nbsp;<button class="btn" onClick="closeBox();return false">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>