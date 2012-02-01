<?php

  $rightCont = '<div id="miniDescer" style="margin-left:20px;font-size:13px;color:#999;margin-top:-5px;margin-bottom:10px">
  Teaches <strong>Science, Math</strong> in grades <strong>9, 10, 11</strong> in Naperville, Illinois USA 
  <a href="http://www.esft.com" target="_blank" style="margin-left:10px"><img src="/assets/app/img/box/globe.png" style="height:12px;width:12px;margin-bottom:-1px;margin-right:3px" />Website</a>

  <button class="btn primary" style="margin-left:10px; padding:2px 6px 2px 6px" onclick="jQuery.facebox({ ajax: \'' . $rootURL . 'manage/about\' }); return false;"><img src="/assets/app/img/box/editcon.png" style="height:12px;width:12px;margin-bottom:-2px;margin-right:1px" /> Edit your profile</button>

  </div>';

  $rightCont .= '<div id="usr_feed">';


  $queryData = buildSharingQuery($usr1['id']);

  $result = retrieveFeedItems($queryData);

  $rcount = 0;
  foreach ($result as $res) {
    $rcount++;
  }

  
  if ($rcount == 0) {
    $rightCont .= '<p id="noneRM" style="text-align:center;color:#666">No activity found for this user...yet.</p>';
  } else {
    $rightCont .= genFeedItem($result, array("type" => 10, "shareID" => (int) $usr1['id']));
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