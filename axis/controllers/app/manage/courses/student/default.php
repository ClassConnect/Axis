<?php
if (isset($_GET['sf'])) {
  echo dispStudCourseView();
  exit();
}

appHeader('Manage Courses');
?>  
<script type="text/javascript">
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
          echo dispStudCourseView();
          ?>



          </div> 
          <div class="span4 courseRight"> 
          <button class="btn success" onClick="jQuery.facebox({ 
    ajax: '/app/manage/courses/enroll'
  });
  return false;"><img src="/assets/app/img/temp/course_l.png" style="height:14px;margin-right:7px;margin-bottom:-3px" />Enroll in a new course</button>
            <div style="padding-top:15px">
              <strong>Need help?</strong><br /><a href="#">Watch a video</a> showing you how to set up your courses. 
            </div>
          </div> 
        </div> 
      </div> 
<?php
appFooter();
?>