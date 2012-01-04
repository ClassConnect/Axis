<link rel='stylesheet' type='text/css' href='/assets/app/js/calendar/calendar.css' />
<div class="content">  
  <div class="row">

<?php
$queryData = array();
$secs = getSections();
$secStr = '';
foreach ($secs as $sec) {
  $secStr .= $sec['section_id'] . ',';
  $queryData[] = array("shared_with.type" => 2, "shared_with.shareID" => (int)$sec['section_id']);
}
?>
<script>
urlComp = '&t2=<?= $secStr; ?>';
</script>

     <div id="my_feed" class="homeLeft"> 
<?php
$queryData[] = array("shared_with.type" => 1, "shared_with.shareID" => (int) user('id'));
$params = array('$or' => $queryData);
$result = retrieveFeedItems($params);

$rcount = 0;
foreach ($result as $res) {
  $rcount++;
}
if ($rcount == 0) {
  $final .= '<p id="noneRM" style="text-align:center;color:#666">No activity found...yet.</p>';
} else {
  $final .= genFeedItem($result);
}


echo $final;

?>
      </div>

      <div class="homeRight">
      <div style="width:1px;height:500px;float:left"></div>

      <a class="btn success" href="/app/manage/courses" style="margin-left:7px;margin-bottom:10px">Add / manage your courses</a>

      <?php
      // generate calendar
      require_once('axis/controllers/app/calendar/core/main.php');
      $cstart = strtotime(date("Y-m-d")) - 3600;
      $cend = strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . " +6 day");
      $entries = getCalEntries($cstart, $cend, null, $secStr);
      $enOrg = array();
      foreach ($entries as $entry) {
        $d1 = date("Y-m-d", $entry['end']);
        $d2 = date("U", strtotime($d1));
        $enOrg[$d2][] = $entry;
      }

      if (!empty($enOrg)) {
        ksort($enOrg);

        foreach ($enOrg as $gkey=>$group) {
          echo '<div class="calhdr">' . date("l, F jS", $gkey) . '</div>';
          foreach ($group as $entry) {
            $edata = determineEvType($entry['type']);
            $color = $edata['color'];
            echo '<div class="calent" title="' . htmlentities(createBubble($entry)) . '" onClick="$(\'.twipsy\').remove();jQuery.facebox({ 
              ajax: \'/app/calendar/view/' . $entry['_id'] . '\'
            });"><div class="calBub" style="background:' . $color . '"></div>' . $entry['title'] . '</div>';

          }
        }

      }




      ?>


      </div> 



  </div>
</div>
<script>
$('.calent').twipsy({
    live: true,
    placement: 'left',
    html: true
  });
</script>
<style>
.calhdr {
  font-weight:bolder;
  display: inline-block;
  background-color: #F4F4F4;
  background-repeat: no-repeat;
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), color-stop(25%, #ffffff), to(#F4F4F4));
  background-image: -webkit-linear-gradient(#ffffff, #ffffff 25%, #F4F4F4);
  background-image: -moz-linear-gradient(top, #ffffff, #ffffff 25%, #F4F4F4);
  background-image: -ms-linear-gradient(#ffffff, #ffffff 25%, #F4F4F4);
  background-image: -o-linear-gradient(#ffffff, #ffffff 25%, #F4F4F4);
  background-image: linear-gradient(#ffffff, #ffffff 25%, #F4F4F4);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#F4F4F4', GradientType=0);
  padding: 5px 5px 6px;
  text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
  color: #555;
  font-size: 13px;
  line-height: normal;
  border: 1px solid #ccc;
  border-bottom-color: #bbb;
  border-left:none;
  border-right:none;
  width:198px;
}
.calent {
  border-bottom:1px solid #efefef;
  padding:4px;
  margin-left:3px;
  margin-right:3px;
  cursor:pointer;
  color:#555;
  font-size:12px;
}
.calBub {
  width:12px;
  height:12px;
  border:1px solid #ccc;
  float:left;
  margin-right:5px;
  margin-top:1px;
}
.calent:hover {
  background:#efefef;
}
</style>