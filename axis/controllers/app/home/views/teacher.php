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
  if ($_SESSION['wiz']) {
?>
<div class="alert-message block-message info" style="margin:20px">
<div style="font-size:20px;font-weight:bolder">Welcome! Get started using the "Getting Started" tab on the right :)</div>
<div style="clear:both;margin-top:5px;color:#666;font-size:11px;float:right">This message will go away when a colleague adds you.</div>
<div style="clear:both;margin-bottom:-15px"></div>
</div>
<?php
  } else {
?>
<div class="alert-message block-message info" style="margin:20px">
  <div style="font-size:20px;font-weight:bolder">Welcome! Lets get you started.</div>
  <div class="getStartedBox btn" onclick="window.location='/app/?iwiz=true';">
      <div class="startTitle">
      Take a guided tour
      </div>
      <div class="startBody">
        <img src="/assets/app/img/box/type/folder.png" class="startImg" style="margin-top:3px" />
        A quick walkthrough showing you how to use ClassConnect.
      </div>
  </div>
  <div class="getStartedBox btn" onClick="jQuery.facebox({ 
    ajax: '/app/common/colleagues/add'
  });
  return false;">
      <div class="startTitle">
      Invite your colleagues
      </div>
      <div class="startBody">
        <img src="/assets/app/img/colleagues/minicard.png" class="startImg" style="margin-top:3px" />
        Every user you invite earns you 500mb of free storage.
      </div>
  </div>
  <div class="getStartedBox btn" onclick="olark('api.box.expand');">
      <div class="startTitle">
      Get involved!
      </div>
      <div class="startBody">
        Be a pioneer and join the United We Teach movement. We'd love to hear from you!
      </div>
  </div>


  <div style="clear:both;margin-top:5px;color:#666;font-size:11px;float:right">This message will go away when a colleague adds you.</div>

  <div style="clear:both;margin-bottom:-15px"></div>
</div>



<?php
  }
} else {
  echo genFeedItem($result);
}

?>



      </div>

      <div class="homeRight">
      <div style="width:1px;height:500px;float:left"></div>

      <div style="margin:10px;margin-top:5px;font-size:11px;line-height:1.2;color:#666">For every teacher you invite to ClassConnect, you receive 500mb of free storage space in FileBox!</div>


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
    finalText += '<div class="colItem"><img src="/assets/app/img/colleagues/del.png" class="deleter" data-original-title="Remove" onClick="jQuery.facebox({ ajax: \'/app/common/colleagues/remove/' + myCols[per]['val'] + '\' }); return false;" /> <a style="color:#444" href="/app/profile/' + myCols[per]['val'] + '"><img src="<?= iconServer(); ?>50_' + myCols[per]['icon'] + '" class="smallProfImg" />' + myCols[per]['label'] + '</a></div>';
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



