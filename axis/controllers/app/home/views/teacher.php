<div class="content">  
  <div class="row">

<?php
// check for colleague requests
$totReqs = getReqs();
if ($totReqs > 0) {
?>
<div id="collPop" class="alert-message warning" style="margin-left:20px;font-weight:bolder;text-align:center"><img src="/assets/app/img/colleagues/minicard.png" style="height:16px;width:16px;margin-bottom:-3px;margin-right:5px"/> 
You have <a href="#" onClick="jQuery.facebox({ 
    ajax: '/app/common/colleagues/review'
  });
  return false;"><?= $totReqs; ?> new colleague requests <span style="font-weight:normal">(click to view)</span></a>
</div>
<?php
}
?>


<script>
urlComp = '&t1=1';
</script>

     <div id="my_feed" class="homeLeft"> 
<?php

$queryData = array("shared_with.type" => 1, "shared_with.shareID" => (int) user('id'));

$result = retrieveFeedItems($queryData);

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

      <div style="margin:10px;margin-top:5px;font-size:11px;line-height:1.2;color:#666">For every teacher you invite to ClassConnect, you receive 200mb of free storage space in FileBox!</div>


      <button class="btn success" style="font-weight:bolder;margin-left:7px" onClick="jQuery.facebox({ 
    ajax: '/app/common/colleagues/add'
  });
  return false;"><img src="/assets/app/img/colleagues/minicard.png" style="float:left;height:16px;width:16px;margin-right:5px"/> Add / Invite Colleagues</button>

      <div style="font-size:12px; font-weight:bolder; color:#666; margin-top:10px;margin-left:10px;margin-bottom:8px"> Your Colleagues </div>
      <div id="colleagueList">

      </div>


      </div> 



  </div>
</div>

<style>
.colItem {
  margin:5px;
  margin-top:-5px;
  padding:5px;
  padding-top:none;
  border-bottom:1px solid #ededed;
  font-size:11px;
  color:#454545;
  font-weight:bolder;
  cursor:default;
  overflow:hidden;
  word-wrap: break-word;
}
.colItem .smallProfImg {
  width:18px;
  float:left;
  margin-right:10px;
}
.colItem:hover{
  background:#efefef;
}
.colItem .deleter {
  display:none;
  height:14px;
  width:14px;
  margin-top:2px;
  position:absolute;
  margin-left:173px;
  cursor:pointer;
  padding:2px;
  margin-top:0px;
}
.colItem:hover .deleter {
    display: block;
    filter: alpha(opacity=65);
    -khtml-opacity: 0.65;
    -moz-opacity: 0.65;
    opacity: 0.65;
}
.colItem .deleter:hover {
  filter: alpha(opacity=95);
  -khtml-opacity: 0.95;
  -moz-opacity: 0.95;
  opacity: 0.95;
}
</style>

<script>
function updateSidebar() {
  var finalText = '';
  var myCols = sortNest('label', amigos);
  for (per in myCols) {
    finalText += '<div class="colItem"><img src="/assets/app/img/colleagues/del.png" class="deleter" data-original-title="Remove" onClick="jQuery.facebox({ ajax: \'/app/common/colleagues/remove/' + myCols[per]['val'] + '\' }); return false;" /> <img src="/assets/app/small.jpg" class="smallProfImg" />' + myCols[per]['label'] + '</div>';
  }

  if (finalText == '') {
    finalText = '<div style="text-align:center;font-size:11px;color:#999">You don\'t have any colleagues...yet.</div>';
  }

  $("#colleagueList").html(finalText);
}


$(document).ready(function() {
  updateSidebar();
  $(".deleter").twipsy({
    live: true,
    placement: 'right',
    html: true
  });
});
</script>



