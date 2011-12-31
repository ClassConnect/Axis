<?php
// get cal ID from URL
$calID = $this->Command->Parameters[1];
// retrieve cal data
$calData = getCalEntry($calID);
// verify permissions
$pers = determineEventPermissions($calData);

// if we're allowed to view this thing
if ($pers['read'] == true) {
	$tdata = determineEvType($calData['type']);
?>
<input type="hidden" name="submitted" value="true" />
  <fieldset style="margin-left:20px;margin-right:20px">
    <legend style="padding-left:0">
	    <div class="evRounder typeDist" style="background:<?= $tdata['color']; ?>">
	    	<?= $tdata['title']; ?>
	    </div>
	    <?= $calData['title']; ?>
    </legend>
    <div class="clearfix">

    <div class="evRounder" style="padding:10px;border:1px solid #eee">
    <?php
    if ($calData['body'] != '') {
    	echo $calData['body'];
    } else {
      echo '<div style="color:#aaa;text-align:center">No description found for this entry.</div>';
    }


    // generate clean
    $cleand = cleanShareList($calData['shared_with']);

    if (!empty($cleand)) {
      echo '<div style="border-top:1px solid #eee;margin-top:10px;margin-left:-10px;margin-right:-10px">
      <div style="margin:10px;margin-bottom:-20px">

      <div style="width:160px;float:left">
      <strong>Shared with:</strong><br />';

      // generate shared list
      $cleand = cleanShareList($calData['shared_with']);
      $courses = array();
      $initArray = array();
      foreach ($cleand as $ent) {
        if ($ent['type'] == 2) {
          $cdata = getSection($ent['shareID']);
          $courses[$cdata['course_link']][] = $ent['shareID'];
          $initArray[] = $cdata['course_link'];

        }
      }


      echo '<ul>';
      foreach ($courses as $key=>$course) {
        $courseData = getCourse($key);
        echo '<li>' . $courseData['title'] . '</li>

        <ul style="list-style-type: circle;">';

        foreach ($course as $sec) {
          $secData = getSection($sec);
          echo '<li>' . $secData['title'] . '</li>';
        }

        echo '</ul>';
      }
      echo '</ul>';


      echo '</div>
      <div style="width:140px;float:right;text-align:center;font-size:14px;">
        <div style="font-weight:bolder;margin-bottom:4px">' . date('m/d/Y', $calData['start']) . '</div>
        to
        <div style="font-weight:bolder;margin-top:4px">' . date('m/d/Y', $calData['end']) . '</div>

      </div>

      <div style="clear:both"></div>


      </div>
      </div>';
    }
    ?>
    </div>




    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:0px;padding-left:0px">
    <div style="float:right">
    <?php
    if ($pers['write'] == true) {
    ?>
      <button class="btn danger" onClick="jQuery.facebox({ 
    ajax: '/app/calendar/write/delete/<?= $calID; ?>'
  });
  return false;"><img src="/assets/app/img/box/del.png" style="float:left;margin-top:4px;margin-right:5px;height:10px" />Delete</button>

      <button class="btn" onClick="jQuery.facebox({ 
    ajax: '/app/calendar/write/edit/<?= $calID; ?>'
  });
  return false;"><img src="/assets/app/img/temp/keywords.png" style="float:left;margin-top:4px;margin-right:5px;height:10px" /> Edit</button>

  <?php
    }
  ?>

      <button class="btn" onClick="closeBox();">Close</button>

    </div>
    <div style="clear:both"></div>
  </div>








<?php
// otherwise, throw an error
} else {
	echo 'ruh-roh!';
	
}

?>