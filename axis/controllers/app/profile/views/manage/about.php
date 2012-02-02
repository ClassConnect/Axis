<?php
$settings = cleanSettings($usr1['settings']);
if (isset($_POST['submitted'])) {

  // clear out existing array
  $settings['profile_data'] = array();

  $settings['profile_data']['title'] = escape($_POST['title']); // teacher, lib, admin

  $settings['profile_data']['country'] = escape($_POST['country']);

  if ($_POST['state'] != 'State name' && $_POST['state'] != '') {
    $settings['profile_data']['state'] = escape($_POST['state']);
  }

  if ($_POST['city'] != 'City name') {
    $settings['profile_data']['city'] = escape($_POST['city']);
  }

  if ($_POST['mywebsite'] != 'http:// <website URL>') {
    if ($_POST['mywebsite'] != '') {
      $finSite = formatURL(escape($_POST['mywebsite']));
    } else {
      $finSite = '';
    }
     $settings['profile_data']['website'] = $finSite; // personal site
  }

  foreach ($_POST['grades'] as $grade) {
    $settings['profile_data']['grades'][] = escape($grade);
  }

  foreach ($_POST['subjects'] as $subj) {
    $settings['profile_data']['subjects'][] = escape($subj);
  }

  $settings = json_encode($settings);

  $myUID = user('id');
  good_query("UPDATE users SET settings = '$settings' WHERE id = $myUID");
  $udata = getUser($myUID, true);
  echo buildProfOneliner($udata);
  exit();
}
?>
<script type= "text/javascript" src = "/assets/app/js/profile/countries.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  postState = '<?= $settings['profile_data']['state']; ?>';
  postCountry = '<?= $settings['profile_data']['country']; ?>';
  initCountry('US');
  fbFormControl('#xlInput3');
});
$('#update-about').submit(function() {
   var serData = $("#update-about").serialize();
    fbFormSubmitted('#update-about');
    $.ajax({  
      type: "POST",  
      url: preURL + "manage/about",  
      data: serData,  
      success: function(retData) {

          initAsyncBar('<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Profile updated successfully</span>', 'yellowBox', 200, 542, 1500);
          $('#miniDescer').replaceWith(retData);
          closeBox();

      }  
      
  });  
    return false;
});
</script>
<form action="#" id="update-about" class="form-stacked" style="width:570px">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <legend>Edit Profile</legend>
    <div class="clearfix">
    <div id="errorBox" style="display:none"></div>


    <div style="float:left;margin-right:20px">
      <div style="clear:both">
        <div style="color:#666;font-weight:bolder;margin-top:6px;margin-bottom:10px">
          You are a
        </div>

        <select id="title" name="title">
        <?php
        if (isset($settings['profile_data']['title']) && $settings['profile_data']['title'] != '') {
          echo '<option>' . $settings['profile_data']['title'] . '</option>';
        }
        ?>
          <option>Teacher</option>
          <option>Librarian</option>
          <option>Administrator</option>
        </select>
      </div>

      <div style="color:#666;font-weight:bolder;margin-top:20px;margin-bottom:10px">
        You are currently located in
      </div>
<select id='countrySelect' name="country" onchange='populateState()' style="margin-bottom:5px"></select><br />
<div id="#stateDrop"><select id='stateSelect' name="state" style="margin-bottom:5px"></select></div>
<input type="text" name="city" placeholder="City name" value="<?= $settings['profile_data']['city']; ?>" style="width:200px" />

      <div style="color:#666;font-weight:bolder;margin-top:20px;margin-bottom:10px">
        Your personal website / blog is
      </div>
      <input type="text" name="mywebsite" placeholder="http:// <website URL>" value="<?= $settings['profile_data']['website']; ?>" style="width:200px" />


      <br /><br /><br /><br /><br />
    </div>






<div id="addGradeSub">
  <div style="color:#666;font-weight:bolder;margin-top:6px;margin-bottom:10px">
    You teach and / or specialize in
  </div>
        <div style="width:150px;border-right:1px solid #ccc;float:left">
          <div class="tagTitleGradeSub">
          Grade Levels
          </div>
          <div>
          <?php
          $grades = 'PS,PK,K,1,2,3,4,5,6,7,8,9,10,11,12,Prep,BS/BA,Masters,PhD,Post-Doc';
          $inGrades = explode(",", $grades);
          foreach ($inGrades as $grade) {
            if (in_array($grade, $settings['profile_data']['grades'])) {
              $attr = 'checked';
            } else {
              $attr = '';
            }
            echo '<input id="grade-' . $grade . '" type="checkbox" name="grades[]" value="' . $grade . '" ' . $attr . ' /> <span style="font-size:11px;color:#666">' . $grade . '</span><br />';
          }


          ?>
          </div>
        </div>
        <div style="width:168px;float:left">
          <div class="tagTitleGradeSub" style="border-right:1px solid #ccc;">
          Subjects
          </div>
          <div style="padding-left:3px">
          <?php
          $subjects = 'Math,Science,Social Studies,English / Language Arts,Foreign Language,Music,Physical Education,Health,Dramatic Arts,Visual Arts,Special Education,Technology and Engineering';
          $inSub = explode(",", $subjects);
          foreach ($inSub as $subject) {
            if (in_array($subject, $settings['profile_data']['subjects'])) {
              $attr = 'checked';
            } else {
              $attr = '';
            }
            echo '<input id="sub-' . $subject . '" type="checkbox" name="subjects[]" value="' . $subject . '" ' .$attr . ' /> <span style="font-size:11px;color:#666">' . $subject . '</span><br />';
          }


          ?>
          </div>
        </div>
        <div style="clear:both"></div>
      </div>












    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger" style="font-weight:bolder">Update Profile</button>&nbsp;<button  class="btn" onClick="closeBox(); return false">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>