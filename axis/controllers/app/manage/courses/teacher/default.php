<?php
if (isset($_GET['sf'])) {
  echo dispTeachCourseView();
  exit();
}

appHeader('Manage Courses');
?>  
<script type="text/javascript">
function resetCode(cObj) {
  var parent = $(cObj).parent();
  var grand = $(cObj).parent().parent().parent();
  var defActions = parent.html();
  var secID = parent.find('.secID').html();

  //codeUpdate
  parent.html('<div style="float:right"><div style="float:right; color:#999; margin-left:5px">submitting...</div><img src="/assets/app/img/box/sub.gif" /></div><div style="clear:both"></div>');
  grand.find(".codeUpdate").html('<img src="/assets/app/img/box/sub.gif" />');
  $.ajax({
   type: "GET",
   url: "/app/manage/courses/reset_code?sid=" + secID,
   success: function(msg){
      parent.html(defActions);
      grand.find(".codeUpdate").html(msg);
   }
 });
}
$(".hovTip").twipsy({
    live: true,
    placement: 'above',
    html: true
});
function softFresh() {
  $("#courseDisp").html('<center><br /><br /><img src="/assets/app/img/box/loading.gif" /><br /><br /></center>');
  $.ajax({
   type: "GET",
   url: "/app/manage/courses?sf=1",
   success: function(msg){
     $("#courseDisp").html(msg);
   }
 });
}
</script>
<div class="container"> 
 
      <div class="content"> 
        <div class="row"> 
          <div id="courseDisp" class="span11" style="border-right:1px solid #ccc;">

          <?php
          echo dispTeachCourseView();
          ?>



          </div> 
          <div class="span4 courseRight"> 
          <button class="btn success" style="font-weight:bolder" onClick="jQuery.facebox({ 
    ajax: '/app/manage/courses/add/course'
  });
  return false;"><img src="/assets/app/img/temp/course_l.png" style="height:14px;margin-right:7px;margin-bottom:-3px" />Create New Course</button>
            <div style="padding-top:15px">
              <strong>REMINDER!</strong><br />You need to give your students the course section code in order for them to enroll. <a href="#" onclick="jQuery.facebox({ div: '#popVid' });">Watch a video!</a>
            </div>
          </div> 
        </div> 
      </div> 

<div id="popVid" style="width:840px;display:none">
<iframe width="820" height="480" style="margin-top:-10px;margin-bottom:-5px" src="http://www.youtube.com/embed/rVXM5ISnRek" frameborder="0" allowfullscreen></iframe><br />
<button style="position:absolute;margin-left:710px;font-weight:bolder" class="btn danger" onclick="closeBox()">Close Video</button>
</div>
<?php
appFooter();
?>