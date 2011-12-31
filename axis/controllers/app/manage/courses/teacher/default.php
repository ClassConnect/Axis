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
          <button class="btn success" onClick="jQuery.facebox({ 
    ajax: '/app/manage/courses/add/course'
  });
  return false;"><img src="/assets/app/img/temp/course_l.png" style="height:14px;margin-right:7px;margin-bottom:-3px" />Create New Course</button>
            <div style="padding-top:15px">
              <strong>REMINDER!</strong><br />You need to give your students the course section code in order for them to enroll. <a href="#">Watch a video.</a>
            </div>
          </div> 
        </div> 
      </div> 
<?php
appFooter();
?>