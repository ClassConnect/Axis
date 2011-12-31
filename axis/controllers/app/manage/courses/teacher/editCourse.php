<?php
if (isset($_GET['cid'])) {
  $cid = $_GET['cid'];
} elseif (isset($_POST['cid'])) {
  $cid = $_POST['cid'];
}

// make sure we're allowed to see this
if (checkCourseOwner($cid) == false) {
  echo 'You don\'t have permission to edit this.';
  exit();
}


if (isset($_POST['submitted'])) {
  $attempt = updateCourse($cid, $_POST['title'], $_POST['grade'], $_POST['subject']);

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

$courseData = getCourse($cid);
?>
<script type="text/javascript">
$(document).ready(function(){
  fbFormControl('#courseTitle');
});
$('#edit-course').submit(function() {

  var serData = $("#edit-course").serialize() + '&cid=<?= $cid; ?>';
  fbFormSubmitted('#edit-course');
    $.ajax({  
      type: "POST",  
      url: "/app/manage/courses/edit/course",  
      data: serData,  
      success: function(retData) {
        if (retData == '1') {
          softFresh();
          closeBox();

        } else {
          fbFormRevert('#edit-course');
          showFormError(retData);
        }
      }  
      
  });  
    return false;
});
</script>
<form action="#" id="edit-course" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <legend>Create Course</legend>
    <div class="clearfix">
    <div id="errorBox" style="display:none"></div>
      <div class="input">
        <input class="xlarge span6" id="courseTitle" name="title" size="30" type="text" value="<?= $courseData['title']; ?>" style="margin-right:20px">
      </div>
      <div style="font-size:10px;color:#999;margin-top:8px;font-style:italic"><strong>Examples:</strong> Algebra 1, Chemistry, American History, etc...</div><br />
      <select id="gradeLevel" name="grade" style="width:160px">
          <option value="<?= $courseData['grade_level']; ?>"><?= $courseData['grade_level']; ?></option>
          <option value="PK">Prekindergarten</option>
          <option value="K">Kindergarten</option>
          <option value="1">1st</option>
          <option value="2">2nd</option>
          <option value="3">3rd</option>
          <option value="4">4th</option>
          <option value="5">5th</option>
          <option value="6">6th</option>
          <option value="7">7th</option>
          <option value="8">8th</option>
          <option value="9">9th</option>
          <option value="10">10th</option>
          <option value="11">11th</option>
          <option value="12">12th</option>
          <option value="Prep">Prep</option>
          <option value="BS/BA">BS/BA</option>
          <option value="Masters">Masters</option>
          <option value="PhD">PhD</option>
          <option value="Post-Doc">Post-Doc</option>
      </select>

      <select id="subjectSel" name="subject" style="width:175px">
          <option value="<?= $courseData['subject']; ?>"><?= $courseData['subject']; ?></option>
          <option value="Math">Math</option>
          <option value="Science">Science</option>
          <option value="Social Studies">Social Studies</option>
          <option value="English / Language Arts">English / Language Arts</option>
          <option value="Foreign Language">Foreign Language</option>
          <option value="Music">Music</option>
          <option value="Physical Education">Physical Education</option>
          <option value="Health">Health</option>
          <option value="Dramatic Arts">Dramatic Arts</option>
          <option value="Visual Arts">Visual Arts</option>
          <option value="Special Education">Special Education</option>
          <option value="Technology and Engineering">Technology and Engineering</option>
      </select>

    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Update Course</button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>