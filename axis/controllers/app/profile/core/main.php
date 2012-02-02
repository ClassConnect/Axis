<?php

// generate page
function genProfPage($userData, $rootURL, $rightCont, $appid, $crumb, $pageTitle) {

	if ($_GET['_pjax'] != true) {
		appHeader(dispUser($userData['id'], 'first_name') . ' ' . dispUser($userData['id'], 'last_name') . ' ' . $pageTitle, '<link rel="stylesheet" type="text/css" href="/assets/app/filebox.css" /><script type="text/javascript" src="/assets/app/js/profile/main.js"></script>');

		// <div class="container">
	echo '<div class="content"> 
	        <div class="row" style="clear:both"> 
	          <div class="sectionLeft">';

	          if ($userData['id'] == user('id')) {
	          	echo '<div class="logoChange" onClick="jQuery.facebox({ ajax: \'' . $rootURL . 'manage/icon\' }); return false;">Change Icon</div>';
	          }

	           echo '<img src="' . iconServer() . '210_' . $userData['prof_icon'] . '" class="courseLogo" />

	            <div class="appMenu">
	              <a href="' . $rootURL . 'latest" class="js-pjaxer" onClick="swapActive($(this).find(\'.appItem\'))"><div id="app-1" class="appItem">Latest</div></a>
	              <a href="' . $rootURL . 'shared" class="js-pjaxer" onClick="swapActive($(this).find(\'.appItem\'))"><div id="app-2" class="appItem">Shared <span class="label" style="position:relative;bottom:1px;left:4px">300</span></div></a>
	            </div>

	          </div> 
	          <div class="sectionRight">';

	          }

	          // only show the add colleague button if we're both teachers and not already friends
	          if (user('level') == 3 && $userData['level'] == 3 && !authFriend($userData['id']) && user('id') != $userData['id']) {

	          	// if we've already send a friend request
	          	if (isRequested($userData['id'])) {
	          		echo '<button class="btn" style="font-weight:bolder;float:right;margin-right:10px;margin-top:-5px" disabled><img src="/assets/app/img/gen/mail.png" style="float:left;height:16px;width:16px;margin-right:5px"> Colleague request sent</button>';
	          	// no friend request detected
	          	} else {
	          		echo '<button class="btn" style="font-weight:bolder;float:right;margin-right:10px;margin-top:-5px" onclick="pingColleague(' . $userData['id'] . ');$(this).attr(\'disabled\', true);$(this).html(\'<img src=\\\'/assets/app/img/gen/mail.png\\\'style=\\\'float:left;height:16px;width:16px;margin-right:5px\\\'> Colleague request sent\');"><img src="/assets/app/img/colleagues/minicard.png" style="float:left;height:16px;width:16px;margin-right:5px"> Add Colleague</button>';
	          	}
	          } elseif (!checkSession()) {
	          	echo '<button class="btn" style="font-weight:bolder;float:right;margin-right:10px;margin-top:-5px" onclick="logPopper();"><img src="/assets/app/img/colleagues/minicard.png" style="float:left;height:16px;width:16px;margin-right:5px"> Add Colleague</button>';
	          }

	          echo '<div class="courseCrumbs">
	            ' . dispUser($userData['id'], 'first_name') . ' ' . dispUser($userData['id'], 'last_name') . '
	              <span class="label important" style="position:relative;bottom:4px;font-size:12px;cursor:pointer">314</span>';

	              if (isset($crumb)) {
	              	echo '<img src="/assets/app/img/course/arr.png" style="height:16px;margin-left:5px;margin-right:5px" />' . $crumb;
	              }
	              

	            echo '</div>';
	

	            
	            echo $rightCont . '<script>curApp = ' . $appid . ';</script>';
	            
	          


	if (!isset($_GET['_pjax'])) {
$secs = getSections();
$secStr = '';
foreach ($secs as $sec) {
  $secStr .= $sec['section_id'] . ',';
}

		echo '</div> 
	        </div> 
	      </div>
	      <script>
myUID = ' . user('id') . ';
UID = ' . $userData['id'] . ';
mySecs = "' . $secStr . '";
preURL = "' . $rootURL . '";
function pingColleague(id) {
	$.ajax({
      type: "GET",
      url: "/app/common/colleagues/ping?id=" + id,
      success: function(data) {
          // do nothing
      }

    });
}
</script>';
		appFooter();
	}

}


function buildSharingQuery($uid) {
	$result = array("uid" => (int) $uid);
	// if we're logged in
	if (checkSession()) {
		if ($uid != user('id')) {
			if (user('level') != 1) {
				$result['$or'][] = array("shared_with.type" => 3, "shared_with.shareID" => (int) 1);
			}
			$result['$or'][] = array("shared_with.type" => 1, "shared_with.shareID" => (int) user('id'));
			$secs = getSections();
			foreach ($secs as $sec) {
			  $result['$or'][] = array("shared_with.type" => 2, "shared_with.shareID" => (int)$sec['section_id']);
			}

		}

	// not logged in? we can only view things shared publicly
	} else {
		$result['$or'][] = array("shared_with.type" => 3, "shared_with.shareID" => (int) 1);
	}

	return $result;
}



function buildProfOneliner($userData) {
	$startDiv = '<div id="miniDescer" style="margin-left:20px;font-size:13px;color:#999;margin-top:-5px;margin-bottom:10px">';
	$endDiv = '</div>';
	$midDiv = '';


	if ($userData['level'] == 1) {
		return $startDiv . 'Student' . $endDiv;
	}

	$settings = cleanSettings($userData['settings']);

	if ($settings['profile_data']['title'] == 'Teacher') {
		$midDiv .= 'Teaches ';
	} elseif ($settings['profile_data']['title'] == 'Librarian') {
		$midDiv .= 'Librarian';
	} elseif ($settings['profile_data']['title'] == 'Administrator') {
		$midDiv .= 'Administrator';
	}

		// if we have subjects
		if (!empty($settings['profile_data']['subjects'])) {
			if ($settings['profile_data']['title'] != 'Teacher') {
				$midDiv .= ' specializing in ';
			}

			$midDiv .= '<strong>';
			foreach ($settings['profile_data']['subjects'] as $subj) {
				$midDiv .= $subj . ', ';
			}
			$midDiv = substr($midDiv, 0, strlen($midDiv) - 2);
			$midDiv .= '</strong>';
		}

		// if we have grade levels
		if (!empty($settings['profile_data']['grades'])) {

			if (count($settings['profile_data']['grades']) == 1) {
				$gradeStr = 'grade';
			} else {
				$gradeStr = 'grades';
			}

			if (!empty($settings['profile_data']['subjects'])) {
				$midDiv .= ' in ' . $gradeStr . ' ';
			} else {
				$midDiv .= $gradeStr . ' ';
			}
			$midDiv .= '<strong>';
			foreach ($settings['profile_data']['grades'] as $grade) {
				$midDiv .= $grade . ', ';
			}
			$midDiv = substr($midDiv, 0, strlen($midDiv) - 2);
			$midDiv .= '</strong>';
		}


		if ((isset($settings['profile_data']['city']) && $settings['profile_data']['city'] != '') || (isset($settings['profile_data']['state']) && $settings['profile_data']['state'] != '') || (isset($settings['profile_data']['country']) && $settings['profile_data']['country'] != '')) {
			$midDiv .= ' in ';
		}

		// if we have city
		if (isset($settings['profile_data']['city'])) {
			$midDiv .= ' ' . $settings['profile_data']['city'] . ' ';
		}

		// if we have state
		if (isset($settings['profile_data']['state'])) {
			$midDiv .= ' ' . $settings['profile_data']['state'] . ', ';
		}

		// if we have country
		if (isset($settings['profile_data']['country'])) {
			$midDiv .= ' ' . $settings['profile_data']['country'];
		}

		// if we have country
		if (isset($settings['profile_data']['website']) && $settings['profile_data']['website'] != '') {
			$midDiv .= ' <a href="' . $settings['profile_data']['website'] . '" target="_blank" style="margin-left:10px"><img src="/assets/app/img/box/globe.png" style="height:12px;width:12px;margin-bottom:-1px;margin-right:3px" />Website</a>';
		}

		if ($userData['id'] == user('id')) {
			$midDiv .= '<button class="btn primary" style="margin-left:10px; padding:2px 6px 2px 6px" onclick="jQuery.facebox({ ajax: \'' . userURL($userData) . 'manage/about\' }); return false;"><img src="/assets/app/img/box/editcon.png" style="height:12px;width:12px;margin-bottom:-2px;margin-right:1px" /> Edit your profile</button>';
		}


	return $startDiv . $midDiv . $endDiv;


  /*'Teaches <strong>Science, Math</strong> in grades <strong>9, 10, 11</strong> in Naperville, Illinois USA 
  <a href="http://www.esft.com" target="_blank" style="margin-left:10px"><img src="/assets/app/img/box/globe.png" style="height:12px;width:12px;margin-bottom:-1px;margin-right:3px" />Website</a>

  <button class="btn primary" style="margin-left:10px; padding:2px 6px 2px 6px" onclick="jQuery.facebox({ ajax: \'' . $rootURL . 'manage/about\' }); return false;"><img src="/assets/app/img/box/editcon.png" style="height:12px;width:12px;margin-bottom:-2px;margin-right:1px" /> Edit your profile</button>';

*/

}
?>