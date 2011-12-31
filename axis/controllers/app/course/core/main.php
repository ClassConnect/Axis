<?php

// generate page
function genCoursePage($secData, $courseData, $rightCont, $appid, $crumb, $pageTitle) {

	if ($_GET['_pjax'] != true) {
		appHeader($courseData['title'] . ' (' . $secData['title'] . ') ' . $pageTitle, '<link rel="stylesheet" type="text/css" href="/assets/app/filebox.css" /><link rel="stylesheet" type="text/css" href="/assets/app/js/calendar/calendar.css" /><link rel="stylesheet" type="text/css" href="/assets/app/js/calendar/calendar.print.css" media="print" /><script type="text/javascript" src="/assets/app/js/calendar/fullcalendar.js"></script><script type="text/javascript" src="/assets/app/js/course/main.js"></script>', 4);

		// <div class="container">
	echo '<div class="content"> 
	        <div class="row" style="clear:both"> 
	          <div class="sectionLeft"> 
	            <img src="/assets/app/img/course/atom.png" class="courseLogo" />

	            <div class="appMenu">
	              <a href="/app/course/' . $secData['section_id'] . '/latest" class="js-pjax" onClick="swapActive($(this).find(\'.appItem\'))"><div id="app-1" class="appItem">Latest</div></a>
	              <a href="/app/course/' . $secData['section_id'] . '/calendar" class="js-pjax" onClick="swapActive($(this).find(\'.appItem\'))"><div id="app-2" class="appItem" >Calendar</div></a>
	              <a href="/app/course/' . $secData['section_id'] . '/handout" class="js-pjax" onClick="swapActive($(this).find(\'.appItem\'))"><div id="app-3" class="appItem">Hand-out</div></a>
	            </div>

	          </div> 
	          <div class="sectionRight">';

	          }

	          echo '<div class="courseCrumbs">
	            ' . $courseData['title'] . '
	              <span class="label" style="position:relative;bottom:5px;">' . $secData['title'] . '</span>';

	              if (isset($crumb)) {
	              	echo '<img src="/assets/app/img/course/arr.png" style="height:16px;margin-left:5px;margin-right:5px" />' . $crumb;
	              }
	              

	            echo '</div>';
	

	            
	            echo $rightCont . '<script>curApp = ' . $appid . ';</script>';
	            
	          


	if (!isset($_GET['_pjax'])) {
		echo '</div> 
	        </div> 
	      </div>
	      <script>
secID = ' . $secData['section_id'] . ';
preURL = "/app/course/" + secID + "/";
</script>';
		appFooter();
	}

}

?>