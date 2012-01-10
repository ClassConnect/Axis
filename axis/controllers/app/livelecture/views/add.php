<?php
if (isset($_POST['submitted'])) {
  $attempt = addDSFile(7, $_POST['chosenOne'], $_POST['lecTitle']);


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
  fbFormControl("#lecTitle");
});
$('#create-lec').submit(function() {
   var serData = $('#create-lec').serialize();
    fbFormSubmitted('#create-lec');
    $.ajax({  
      type: "POST",  
      url: "/app/livelecture/create",  
      data: serData,
      dataType: "json",
      success: function(retData) {
        if (retData['success'] == 1) {
          window.location = "/app/livelecture/edit/?fid=" + retData['data']['conID'] + "-" + retData['data']['verID'];

        } else {
          fbFormRevert('#create-lec');
          showFormError(retData['data']);
        }
      }  
      
  });  
    return false;
});
</script>
<form action="#" id="create-lec" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <legend>Create LiveLecture</legend>
    <div class="clearfix">
    <div id="errorBox" style="display:none"></div>

<div style="font-size:12px; font-weight:bolder; color:#666; margin-bottom:3px">LiveLecture Title</div>
    <div class="input" style="margin-bottom:20px">
      <input id="lecTitle" name="lecTitle" size="60" maxlength="60" type="text" style="width:320px">
    </div>

<div style="font-size:12px; font-weight:bolder; color:#666; margin-bottom:3px">Create LiveLecture in this folder:</div>
<?= createPickerWrap('0'); ?>

    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Create LiveLecture</button>&nbsp;<button class="btn" onClick="closeBox();return false">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>