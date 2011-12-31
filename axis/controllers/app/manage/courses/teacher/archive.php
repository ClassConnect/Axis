<?php
if (isset($_GET['submitted'])) {
  // example of per array: array('type' => 1, 'shared_id' => 8, 'auth_level' => 1)
  if (isset($_GET['cid'])) {
  	$attempt = archiveCourse($_GET['cid']);
  } elseif (isset($_GET['sid'])) {
  	$attempt = archiveSection($_GET['sid']);
  }

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

if (isset($_GET['cid'])) {
	// this is a course archive
	$dent = $_GET['cid'];
	$title = 'Course';
	$stitle = 'course (including all current sections)';
	$par = 'cid=';
} elseif (isset($_GET['sid'])) {
	// this is a course archive
	$dent = $_GET['sid'];
	$title = 'Section';
	$stitle = 'section';
	$par = 'sid=';
}
?>
<script type="text/javascript">
$('#archiveObj').submit(function() {

  fbFormSubmitted('#archiveObj');
    $.ajax({  
      type: "GET",  
      url: "/app/manage/courses/archive/",
      data: "submitted=true&<?= $par . $dent; ?>",  
      success: function(retData) {
        if (retData == '1') {
          softFresh();
          closeBox();

        } else {
          fbFormRevert('#archiveObj');
          showFormError(retData);
        }
      }  
      
  });  
    return false;
});
</script>
<form action="#" id="archiveObj" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <legend>Archive <?= $title; ?></legend>
    <div class="clearfix">
    <div id="errorBox" style="display:none"></div>
     Are you sure you want to archive this <?= $stitle; ?>?

    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Archive <?= $title; ?></button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>