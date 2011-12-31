<?php
$conID = $this->Command->Parameters[3];

if (isset($_POST['submitted'])) {
  // example of per array: array('type' => 1, 'shared_id' => 8, 'auth_level' => 1)
  $attempt = updateTitle($conID, $_POST['content_title']);

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


$conData = getContent($conID);
$permissionObj = verifyPermissions($conData, user('id'));
$perLevel = determinePerLevel($conData['_id'], $permissionObj);

if ($perLevel == 2) {
?>
<script type="text/javascript">
$(document).ready(function(){
  fbFormControl('#xlInput3');
});
$('#edit-title').submit(function() {
   var serData = $("#edit-title").serialize();
    fbFormSubmitted('#edit-title');
    $.ajax({  
      type: "POST",  
      url: "/app/filebox/write/edit/title/<?= $conID; ?>",  
      data: serData,  
      success: function(retData) {
        if (retData == '1') {
          initAsyncBar('<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Content updated successfully</span>', 'yellowBox', 210, 527, 1500);
          softRefresh();
          closeBox();

        } else {
          fbFormRevert('#edit-title');
          showFormError(retData);
        }
      }  
      
  });  
    return false;
});
</script>
<form action="#" id="edit-title" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <legend>Edit Content</legend>
    <div class="clearfix">
    <div id="errorBox" style="display:none"></div>
      <div class="input">
        <input class="xlarge span6" id="xlInput3" name="content_title" size="30" maxlength="60" type="text" style="margin-right:20px" value="<?= $conData['title']; ?>">
      </div>
    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Update Title</button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>

<?php
} else {
  echo 'Oops! You don\'t have permission to edit this.';
}
?>