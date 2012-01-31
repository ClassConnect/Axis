<?php
$cmdParam = $this->Command->Parameters[2];
// if we're adding something to the feed
if ($cmdParam == 'add') {
  // ensure that we're a teacher
  if (user('level') == 3) {
    $courses = explode(',', $_POST['courses']);
    $fin = array();
    foreach ($courses as $course) {
      $temp = array();
      $temp['type'] = 2;
      $temp['shareID'] = $course;
      if (authSection($course)) {
        $fin[] = $temp;
      }
    }
    $data = array(
            "status" => htmlentities($_POST['status'])
        );
    $feedID = insertFeedItem(3, 1, $fin, $data, false, true);


    $item = array();
    $item['_id'] = $feedID;
    $item['appType'] = 3;
    $item['notiType'] = 1;
    $item['uid'] = user('id');
    $item['shared_with'] = $fin;
    $item['data'][] = $data;
    $item['sent_at'] = (int) date("U");




    echo genFeedItem(array($item), array("type" => 2, "shareID" => (int) $sectionID));

  }
  

// main announcements view
} else {


  $rightCont = '';
  // if we are a teacher, show the add status box
  if (user('level') == 3) {
  $rightCont = '<div class="addStatusBox">
                <textarea id="status" name="status" placeholder=" Post an announcement to this section..." rows="3" style="width:665px;height:20px;resize: none;"></textarea>

                <div class="statActions" style="float:right;margin-top:5px;display:none">
                  <div class="alsoAdd" onClick="$(this).find(\'.hidPicker\').show();">
                    <span style="font-style:italic;color:#666">Also post to...</span>

                    <div class="hidPicker" style="display:none;position:absolute;margin-left:-113px;margin-top:5px">
                      ' . buildCoursePicker(array($sectionID),0,'','') . '
                    </div>

                  </div>
                  <button id="statSub" class="btn danger" style="font-weight:bolder">Post Update</button>
                </div>

                <div style="clear:both"></div>
              </div>';
  }

  $rightCont .= '<div id="course_feed">';


  $queryData = array("shared_with.type" => 2, "shared_with.shareID" => (int) $sectionID);

  $result = retrieveFeedItems($queryData);

  $rcount = 0;
  foreach ($result as $res) {
    $rcount++;
  }
  if ($rcount == 0) {
    $rightCont .= '<p id="noneRM" style="text-align:center;color:#666">No announcements found for this section...yet.</p>';
  } else {
    $rightCont .= genFeedItem($result, array("type" => 2, "shareID" => (int) $sectionID));
  }

  $rightCont .= '</div>
  <script>
  $(document).ready(function() {
     initAnnouncements();
  });
  </script>';

  // show main annoucements
  genCoursePage($secData, $courseData, $rightCont, $cappID);

}

?>