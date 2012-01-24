<?php
if (isset($_POST['submitted'])) {
  deleteContent($_POST['ids']);
  $attempt = 1;
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
  fbFormControl();
});
$('#del-con').submit(function() {
    var delData = '';
    $('.fbprogSel').each(function(index) {
      delData += $(this).attr('id') + ',';
    });
    fbFormSubmitted('#del-con');
    $.ajax({  
      type: "POST",  
      url: "/app/filebox/write/delete/",  
      data: 'submitted=true&ids=<?= $_GET['conIDs']; ?>',  
      success: function(retData) {
        if (retData == '1') {
          initAsyncBar('<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Content deleted successfully</span>', 'yellowBox', 210, 527, 1500);
          softRefresh();
          closeBox();

        } else {
          fbFormRevert('#del-con');
          showFormError(retData);
        }
      }  
      
  });  
    return false;
});
</script>
<form action="#" id="del-con" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <legend>Delete Content</legend>
    <div class="clearfix">
    <div id="errorBox" style="display:none"></div>
     Are you sure you want to delete this content?
    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Confirm Delete</button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>