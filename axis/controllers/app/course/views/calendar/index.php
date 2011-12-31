<?php
if (user('level') == 3) {
    $calData = '<div class="alert-message warning" style="margin-right:15px;margin-left:15px;margin-top:10px">
  <p>Below are the calendar entries you have shared with this course via the <a href="/app/calendar/">Calendar</a> app.</p>
</div>';
}
$calData .= '<div id="calInit" style="width:690px;margin-left:20px"></div><script>
  $(document).ready(function() {
     initCalendar();
  });
  </script>';


genCoursePage($secData, $courseData, $calData, $cappID, '<span id="calheadt">Calendar</span>');

  ?>