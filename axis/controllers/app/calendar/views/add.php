<?php
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

  $starter = strtotime($_POST['start']);
  $ender = strtotime($_POST['end']);

  $attempt = writeEvent(1, $starter, $ender, $_POST['entryType'], $_POST['title'], $_POST['body'], $fcourses);


// set json header
header('Content-type: application/json');

  if ($attempt['success'] == 1) {
    // data for our noti array
    $notiData = array("id" => (string) $attempt['data']['_id'], "type" => $attempt['data']['type'], "title" => $attempt['data']['title']);

    // fire off a notification
    insertFeedItem(2, 1, $fcourses, $notiData);


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
$('#add-entry').submit(function() {
   var serData = $("#add-entry").serialize();
    fbFormSubmitted('#add-entry');
    $.ajax({  
      type: "POST",  
      url: "/app/calendar/write/add/",  
      data: serData,
      dataType: "json",
      success: function(retData) {
        if (retData['success'] == 1) {
          $('#calInit').fullCalendar( 'renderEvent', retData['data']);
          closeBox();
          initAsyncBar('<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Entry added successfully</span>', 'yellowBox', 180, 625, 1500);

        } else {
          fbFormRevert('#add-entry');
          showFormError(retData['data']);
        }

      }  
      
  });  
    return false;
});
</script>
<form action="#" id="add-entry" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <legend>Add Entry</legend>
    <div class="clearfix">
    <div id="errorBox">
    <?php
    if (!isset($_GET['start'])) {
    ?>
    <div class="alert-message info" style="width:300px"><p><strong>Tip:</strong> You can add entries much more quickly by just clicking (and dragging) dates on the calendar!</p></div>
    <?php
    }
    ?>
    </div>

    <div class="rowBut" style="margin-top:5px">
      <div style="float:left">
        <div style="padding: 0 0 3px 0; color:#666"><?= say('Title'); ?></div>
        <div class="input">
          <input id="entryTitle" name="title" size="30" maxlength="45" type="text" style="font-size:12px;height:15px">
        </div>
        <div style="font-size:11px;margin-top:5px;margin-left:5px"><a href="#" onClick="$('#descHide').show(); $('#descFoc').focus(); $(this).remove(); return false">Add a description...</a></div>
      </div>

      <div style="margin-left:230px">
      <div style="padding: 0 0 3px 0; color:#666"><?= say('Entry Type'); ?></div>
        <div class="input">
        <select style="width:100px" name="entryType">
          <option value="4"><?= say('Event'); ?></option>
          <option value="1"><?= say('Assignment'); ?></option>
          <option value="2"><?= say('Project'); ?></option>
          <option value="3"><?= say('Test / Quiz'); ?></option>
        </select>
        </div>
      </div>

    </div>


    <div id="descHide" style="display:none;clear:both">
	    <div style="padding: 0 0 3px 0; color:#666"><?= say('Description'); ?></div>
	    <div class="input">
	    	<textarea style="width:280px" name="body" id="descFoc" rows="3"></textarea>
	    </div>
    </div>

    <div class="rowBut" style="margin-top:20px;clear:both">
	    <div style="float:left">
	      <div style="padding: 0 0 3px 0; color:#666"><?= say('Start Date'); ?></div>
	      <div class="input">
	        <input id="pickStart" value="<?php
          if (isset($_GET['start'])) {
            echo date("m/d/Y", strtotime(urldecode($_GET['start'])));
          }
          ?>" name="start" size="15" type="text" style="font-size:12px;height:15px;width:70px">
	      </div>
	    </div>
	    <div style="margin-left:100px">
	      <div style="padding: 0 0 3px 0; color:#666"><?= say('End Date'); ?></div>
	      <div class="input">
	        <input id="pickEnd" value="<?php
          if (isset($_GET['end'])) {
            echo date("m/d/Y", strtotime(urldecode($_GET['end'])));
          }
          ?>" name="end" size="15" type="text" style="font-size:12px;height:15px;width:70px">
	      </div>
	    </div>
    </div>

<?php
if (user('level') == 3) {
?>
    <div class="rowBut" style="margin-top:20px">
     <div style="padding: 0 0 7px 0; color:#666"><?= say('Choose courses to share with'); ?></div>
      <?= buildCoursePicker(getTempSwap('calendar'),0,'','line-height:1.4'); ?>
    </div>
<?php
}
?>



    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Add entry to calendar</button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>