<?php
if (isset($_POST['submitted'])) {
  deleteContent($_POST['ids']);
  $attempt = 1;
  $retObj = array();
  $retObj['success'] = $attempt;
  $retObj['items'] = explode(',', $_POST['ids']);
  header('Content-type: application/json');
  if ($attempt == 1) {
    $cdata = getContent($_POST['current']);
    $permissionObj = verifyPermissions($cdata, user('id'));
    if ($_POST['current'] == '0') {
      $cdata['_id'] = 0;
      $cdata['type'] = 1;
      $cdata['title'] = 'FileBox';
    }

    if ($cdata['type'] == 1) {
      $retObj['sidebar'] = createFolBar($cdata, $permissionObj);
    } elseif ($cdata['type'] == 2) {
      $retObj['sidebar'] = createFilBar($cdata, $permissionObj);
    }
    echo json_encode($retObj);
  } else {
    $errDat = '<div class="alert-message warning" style="width:310px">';
    foreach($attempt as $error) {
      $errDat .= '<li>' . say($error) . '</li>';
    }
    $errDat .= '</div>';
    $retObj['text'] = $errDat;
    echo json_encode($retObj);

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
      data: 'submitted=true&ids=<?= $_GET['conIDs']; ?>&current=' + currentCon, 
      dataType: "json",
      success: function(retData) {
        if (retData['success'] == 1) {
          // if this is a dir view
          if (currentType == 1) {
            initAsyncBar('<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Content deleted successfully</span>', 'yellowBox', 210, 527, 1500);
            for (dataID in retData['items']) {
              $("#" + retData['items'][dataID]).css('opacity', 1).slideUp('fast').animate({ opacity: 0 },{ queue: false, duration: 300});
            }

            sideBarSwap = retData['sidebar'];
            setTimeout("restartFolUI(sideBarSwap)",300);
            closeBox();
          }

        } else {
          fbFormRevert('#del-con');
          showFormError(retData['text']);
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