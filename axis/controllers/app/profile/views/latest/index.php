<?php
// mini desc goes here
  $rightCont = buildProfOneliner($usr1);

  $rightCont .= '<div id="usr_feed">';


  $queryData = buildSharingQuery($usr1['id']);

  $result = retrieveFeedItems($queryData);

  $rcount = 0;
  foreach ($result as $res) {
    $rcount++;
  }

  
  if ($rcount == 0) {
    // if this user is us and we're a teacher
    if ($usr1['id'] == user('id') && user('level') == 3) {
      $rightCont .= '<div id="noneRM" style="margin-top:40px;text-align:center;color:#666">
      <div style="font-size:16px;font-weight:bolder">You haven\'t shared any files publicly from <a href="/app/filebox/">"My Files"</a> yet!</div>
      </div>';

    } else {
      $rightCont .= '<p id="noneRM" style="margin-top:40px;font-weight:bolder;text-align:center;color:#666">No activity found for this user...yet.</p>';

    }

  } else {
    $temp .= genFeedItem($result, array("type" => 10, "shareID" => (int) $usr1['id']));
  }

  if (str_replace(' ', '', $temp) == '' && $rcount != 0) {
    $rightCont .= '<p id="noneRM" style="text-align:center;color:#666">No recent activity found for this user.</p>';
  } else {
    $rightCont .= $temp;
  }


  $rightCont .= '</div>
  <script>
  $(document).ready(function() {
     initAnnouncements();
  });
  </script>';


  // show main annoucements
  genProfPage($usr1, $rootURL, $rightCont, $cappID);


?>