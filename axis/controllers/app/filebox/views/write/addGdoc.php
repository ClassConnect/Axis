<?php
if (isset($_POST['submitted'])) {
  // we need to determine if this is a url or an embed
  $parent = $_POST['parent'];

    // set vars
    $title = $_POST['docTitle'];
    $data = $_POST['docURL'];
    if ($_POST['docDesc'] != 'Description') {
      $desc = $_POST['docDesc'];
    }


  // lets insert this
  $attempt = addWebContent($parent, $title, $desc, $data, 5);


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
<style type="text/css">
.pendDesc {
  width:40px;
  float:left;
  margin-top:7px;
  margin-right:8px;
  text-align:right;
  color:#666;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
  fbFormControl();
});
$('#add-doc').submit(function() {
   var serData = $("#add-doc").serialize() + '&parent=' + currentCon;
    fbFormSubmitted('#add-doc');
    $.ajax({  
      type: "POST",  
      url: "/app/filebox/write/add/gdoc/",  
      data: serData,
      success: function(retData) {
        if (retData == '1') {
          initAsyncBar('<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Google Doc added successfully</span>', 'yellowBox', 225, 532, 1500);
          softRefresh();
          closeBox();

        } else {
          fbFormRevert('#add-doc');
          showFormError(retData);
        }
      }  
      
  });  
    return false;
});
</script>
<form action="#" id="add-doc" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
  <legend>Add Google Doc</legend>
    <div class="clearfix">
    <div id="errorBox"><div class="alert-message info" style="width:310px"><p>Make sure this document is publicly accessible otherwise your students & colleagues might not be able to view it.</p></div></div>
        

        <div class="input" style="margin-bottom:10px">
          <input class="xlarge span6" id="urlTitle" name="docTitle" placeholder="Title" size="30" maxlength="60" type="text" style="margin-right:20px">
        </div>
      
        <div class="input" style="margin-bottom:10px">
          <input class="xlarge span6" id="urlLoc" name="docURL" placeholder="http:// (document URL)" size="30" type="text" style="margin-right:20px">
        </div>

        <div style="font-size:11px;margin-top:5px;margin-left:5px"><a href="#" onclick="$('#descHide').show(); $('#urlDesc').focus(); $(this).remove(); return false">Add a description...</a></div>

        <div id="descHide" style="display:none">
          <div class="input"><textarea class="xlarge span6" style="margin-right:20px" placeholder="Description" name="urlDesc" id="urlDesc" rows="3"></textarea></div>
        </div>


    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Add Google Doc</button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>