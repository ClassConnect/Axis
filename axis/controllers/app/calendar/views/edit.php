<?php
// get cal ID from URL
$calID = $this->Command->Parameters[2];
// retrieve cal data
$calData = getCalEntry($calID);
// verify permissions
$pers = determineEventPermissions($calData);


// if we're allowed to view this thing
if ($pers['write'] == true) {

  // set preselects
  $selCourses = array();
  foreach ($calData['shared_with'] as $datar) {
    $selCourses[] = $datar['shareID'];
  }


if (isset($_POST['submitted'])) {
  // generate our array of permitted shares
  $selCourses = $_POST['courses'];
  $fcourses = array();
  foreach ($selCourses as $course) {
    $tempCrr = array();
    $tempCrr['type'] = 2;
    $tempCrr['shareID'] = (int) $course;
    $fcourses[] = $tempCrr;
  }

  setTempSwap($selCourses, 'calendar');

  $attempt = writeEvent(2, strtotime($_POST['start']), strtotime($_POST['end']), $_POST['entryType'], $_POST['title'], $_POST['body'], $fcourses, $calID);

// set json header
header('Content-type: application/json');

  if ($attempt['success'] == 1) {
    $attempt['data'] = encodeEntry($attempt['data']);
    echo json_encode($attempt);
  } else {

    $nes = '<div class="alert-message warning" style="width:300px">';
    foreach($attempt['data'] as $error) {
      $nes .= '<li>' . say($error) . '</li>';
    }
    $nes .= '</div>';

    $attempt['data'] = $nes;

    echo json_encode($attempt);

  }


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
$(document).ready(function(){

	var dates = $( "#pickStart, #pickEnd" ).datepicker({
		onSelect: function( selectedDate ) {
			var option = this.id == "pickStart" ? "minDate" : "maxDate",
				instance = $( this ).data( "datepicker" ),
				date = $.datepicker.parseDate(
					instance.settings.dateFormat ||
					$.datepicker._defaults.dateFormat,
					selectedDate, instance.settings );
			dates.not( this ).datepicker( "option", option, date );
		}
	});



  fbFormControl('#entryTitle');
});
$('#edit-entry').submit(function() {
   var serData = $("#edit-entry").serialize();
    fbFormSubmitted('#edit-entry');
    $.ajax({  
      type: "POST",  
      url: "/app/calendar/write/edit/<?= $calID; ?>",  
      data: serData,
      dataType: "json",
      success: function(retData) {
        if (retData['success'] == 1) {
          $('#calInit').fullCalendar( 'removeEvents', retData['data']['id']);
          $('#calInit').fullCalendar( 'renderEvent', retData['data']);
          closeBox();
          initAsyncBar('<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Entry updated successfully</span>', 'yellowBox', 195, 610, 1500);

        } else {
          fbFormRevert('#add-entry');
          showFormError(retData['data']);
        }

      }  
      
  });  
    return false;
});
</script>
<form action="#" id="edit-entry" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <legend>Edit Entry</legend>
    <div class="clearfix">
    <div id="errorBox">
    </div>

    <div class="rowBut" style="margin-top:5px">
      <div style="float:left">
        <div style="padding: 0 0 3px 0; color:#666"><?= say('Title'); ?></div>
        <div class="input">
          <input id="entryTitle" name="title" value="<?= $calData['title']; ?>" size="30" maxlength="45" type="text" style="font-size:12px;height:15px">
        </div>
        <div style="<?php if ($calData['body'] != '') { echo 'display:none;'; } ?>font-size:11px;margin-top:5px;margin-left:5px"><a href="#" onClick="$('#descHide').show(); $('#descFoc').focus(); $(this).remove(); return false">Add a description...</a></div>
      </div>

      <div style="margin-left:230px">
      <div style="padding: 0 0 3px 0; color:#666"><?= say('Entry Type'); ?></div>
        <div class="input">
        <select style="width:100px" name="entryType">
          <option value="4" <?php if ($calData['type'] == 4) { echo 'selected'; } ?>><?= say('Event'); ?></option>
          <option value="1" <?php if ($calData['type'] == 1) { echo 'selected'; } ?>><?= say('Assignment'); ?></option>
          <option value="2" <?php if ($calData['type'] == 2) { echo 'selected'; } ?>><?= say('Project'); ?></option>
          <option value="3" <?php if ($calData['type'] == 3) { echo 'selected'; } ?>><?= say('Test / Quiz'); ?></option>
        </select>
        </div>
      </div>

    </div>


    <div id="descHide" style="<?php if ($calData['body'] == '') { echo 'display:none;'; } ?>clear:both">
	    <div style="padding: 0 0 3px 0; color:#666"><?= say('Description'); ?></div>
	    <div class="input">
	    	<textarea style="width:280px" name="body" id="descFoc" rows="3"><?= $calData['body']; ?></textarea>
	    </div>
    </div>

    <div class="rowBut" style="margin-top:20px;clear:both">
	    <div style="float:left">
	      <div style="padding: 0 0 3px 0; color:#666"><?= say('Start Date'); ?></div>
	      <div class="input">
	        <input id="pickStart" value="<?= date("m/d/Y", $calData['start']); ?>" name="start" size="15" type="text" style="font-size:12px;height:15px;width:70px">
	      </div>
	    </div>
	    <div style="margin-left:100px">
	      <div style="padding: 0 0 3px 0; color:#666"><?= say('End Date'); ?></div>
	      <div class="input">
	        <input id="pickEnd" value="<?= date("m/d/Y", $calData['end']); ?>" name="end" size="15" type="text" style="font-size:12px;height:15px;width:70px">
	      </div>
	    </div>
    </div>

<?php
if (user('level') == 3) {
?>
    <div class="rowBut" style="margin-top:20px">
     <div style="padding: 0 0 7px 0; color:#666"><?= say('Choose courses to share with'); ?></div>
      <?= buildCoursePicker($selCourses,0,'','line-height:1.4'); ?>
    </div>
<?php
}
?>


    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Update calendar entry</button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>


<?php

// no permission?
} else {
  echo 'Oops! You cannot edit this.';
}

?>