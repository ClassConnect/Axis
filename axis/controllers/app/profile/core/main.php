<?php

// generate page
function genProfPage($userData, $un, $rightCont, $appid, $crumb, $pageTitle) {

	if ($_GET['_pjax'] != true) {
		appHeader($userData['first_name'] . ' ' . $userData['last_name'] . ' ' . $pageTitle, '<link rel="stylesheet" type="text/css" href="/assets/app/filebox.css" /><script type="text/javascript" src="/assets/app/js/course/main.js"></script>');

		// <div class="container">
	echo '<div class="content"> 
	        <div class="row" style="clear:both"> 
	          <div class="sectionLeft">
	          ' . dispOnly('<div class="logoChange" onClick="jQuery.facebox({ ajax: \'/app/course/' . $secData['section_id'] . '/manage/icon\' }); return false;">Change Icon</div>', 3) . '
	            <img src="' . iconServer() . '210_' . $userData['prof_icon'] . '" class="courseLogo" />

	            <div class="appMenu">
	              <a href="latest" class="js-pjax" onClick="swapActive($(this).find(\'.appItem\'))"><div id="app-1" class="appItem">Latest</div></a>
	              <a href="shared" class="js-pjax" onClick="swapActive($(this).find(\'.appItem\'))"><div id="app-2" class="appItem">Shared <span class="label" style="position:relative;bottom:1px;left:4px">300</span></div></a>
	            </div>

	          </div> 
	          <div class="sectionRight">';

	          }

	          echo '<div class="courseCrumbs">
	            ' . $userData['first_name'] . ' ' . $userData['last_name'] . '
	              <span class="label" style="position:relative;bottom:5px;">300</span>';

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
UID = ' . $$userData['id'] . ';
preURL = "/app/course/" + secID + "/";
</script>';
		appFooter();
	}

}

?>