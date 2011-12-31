<?php
// get cal ID from URL
$calID = $this->Command->Parameters[2];
// retrieve cal data
$calData = getCalEntry($calID);
// verify permissions
$pers = determineEventPermissions($calData);


// if we're allowed to view this thing
if ($pers['write'] == true) {


// submitted? delete it.
if (isset($_POST['submitted'])) {

  $attempt = deleteEvent($calID);
  echo 1;

  exit();
}
?>
<style type="text/css">
.rowPut {
  margin-top:5px;
}
.ui-datepicker {
  width:auto;
  font-size:1.0em;
}
</style>
<script type="text/javascript">
$('#del-entry').submit(function() {
   var serData = $("#del-entry").serialize();
    fbFormSubmitted('#del-entry');
    $.ajax({  
      type: "POST",  
      url: "/app/calendar/write/delete/<?= $calID; ?>",  
      data: serData,
      success: function(retData) {
        if (retData == 1) {
          $('#calInit').fullCalendar('removeEvents', '<?= $calID; ?>');
          closeBox();
          initAsyncBar('<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Entry deleted successfully</span>', 'yellowBox', 195, 610, 1500);

        }
      }  
      
  });  
    return false;
});
</script>
<form action="#" id="del-entry" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <legend>Delete Entry</legend>
    <div class="clearfix">
    <div id="errorBox">
    </div>

    Are you sure you want to delete this event?



    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Delete calendar entry</button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>


<?php

// no permission?
} else {
  echo 'Oops! You cannot delete this.';
}

?>