<?php

  $rightCont = '';

  $rightCont .= '<div id="usr_feed">';


  $queryData = array("shared_with.type" => 1, "shared_with.shareID" => (int) $usr1['id']);

  $result = retrieveFeedItems($queryData);

  $rcount = 0;
  foreach ($result as $res) {
    $rcount++;
  }
  if ($rcount == 0) {
    $rightCont .= '<p id="noneRM" style="text-align:center;color:#666">No activity found for this user...yet.</p>';
  } else {
    $rightCont .= genFeedItem($result, array("type" => 1, "shareID" => (int) $usr1['id']));
  }

  $rightCont .= '</div>
  <script>
  $(document).ready(function() {
     initAnnouncements();
  });
  </script>';


  // show main annoucements
  genProfPage($usr1, $un, $rightCont, $cappID);


?>