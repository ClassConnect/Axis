<?php

// generate page
function genProfPage($userData, $rootURL, $rightCont, $appid, $crumb, $pageTitle) {

	if ($_GET['_pjax'] != true) {
		appHeader(dispUser($userData['id'], 'first_name') . ' ' . dispUser($userData['id'], 'last_name') . ' ' . $pageTitle, '<link rel="stylesheet" type="text/css" href="/assets/app/filebox.css" /><script type="text/javascript" src="/assets/app/js/profile/main.js"></script>');

		// <div class="container">
	echo '<div class="content">';

	if (user('id') == $userData['id'] && $userData['level'] == 3 && $userData['user_name'] == '') {
		echo '<div id="urlPop" class="alert-message warning" style="margin-left:20px;text-align:center">
		<span id="urlSwapper" style="font-weight:bolder">You haven\'t claimed your ClassConnect URL yet!</span>
		<form id="urlSubber" onsubmit="setURL();return false" style="margin-bottom:0px;margin-top:-1px;float:right;padding-right:10px">http://www.classconnect.com/<input type="text" id="urlSpot" maxlength="60" style="height:12px;font-size:12px;margin-bottom:-2px" /> <button class="btn primary" id="urlSubBtn" type="submit" style="font-size:12px;padding:2px 6px 3px 6px; font-weight:bolder;margin-bottom:-2px">Claim Your URL</button></form>
		</div>';
	}

	        echo '<div class="row" style="clear:both"> 
	          <div class="sectionLeft">';

	          if ($userData['id'] == user('id')) {
	          	echo '<div class="logoChange" onClick="jQuery.facebox({ ajax: \'' . $rootURL . 'manage/icon\' }); return false;">Change Icon</div>';
	          }

	           echo '<img src="' . iconServer() . '210_' . $userData['prof_icon'] . '" class="courseLogo" />

	            <div class="appMenu">
	              <a href="' . $rootURL . 'latest" class="js-pjaxer" onClick="swapActive($(this).find(\'.appItem\'))"><div id="app-1" class="appItem">Latest</div></a>
	              <a href="' . $rootURL . 'shared" class="js-pjaxer" onClick="swapActive($(this).find(\'.appItem\'))"><div id="app-2" class="appItem">Shared <span class="label" style="position:relative;bottom:1px;left:4px">' . getSharedNumber($userData['id']) . '</span></div></a>
	            </div>

	          </div> 
	          <div class="sectionRight">';

	          }

	          // only show the add colleague button if we're both teachers and not already friends
	          if (user('level') == 3 && $userData['level'] == 3 && !authFriend($userData['id']) && user('id') != $userData['id']) {

	          	// if we've already send a friend request
	          	if (isRequested($userData['id'])) {
	          		echo '<button class="btn" style="font-weight:bolder;float:right;margin-right:20px;margin-top:-5px" disabled><img src="/assets/app/img/gen/mail.png" style="float:left;height:16px;width:16px;margin-right:5px"> Colleague request sent</button>';
	          	// no friend request detected
	          	} else {
	          		echo '<button class="btn" style="font-weight:bolder;float:right;margin-right:20px;margin-top:-5px" onclick="pingColleague(' . $userData['id'] . ');$(this).attr(\'disabled\', true);$(this).html(\'<img src=\\\'/assets/app/img/gen/mail.png\\\'style=\\\'float:left;height:16px;width:16px;margin-right:5px\\\'> Colleague request sent\');"><img src="/assets/app/img/colleagues/minicard.png" style="float:left;height:16px;width:16px;margin-right:5px"> Add Colleague</button>';
	          	}
	          } elseif (!checkSession()) {
	          	echo '<button class="btn" style="font-weight:bolder;float:right;margin-right:20px;margin-top:-5px" onclick="logPopper();"><img src="/assets/app/img/colleagues/minicard.png" style="float:left;height:16px;width:16px;margin-right:5px"> Add Colleague</button>';
	          }

	          echo '<div class="courseCrumbs">
	            ' . dispUser($userData['id'], 'first_name') . ' ' . dispUser($userData['id'], 'last_name') . '
	              <span class="label important" style="position:relative;bottom:4px;font-size:12px;cursor:pointer">' . $userData['karma'] . '</span>';

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

function setURL() {
	curVal = $("#urlSpot").val();
	$("#urlSubBtn").attr("disabled", true);
	$("#urlSpot").attr("disabled", true);
	$.ajax({
      type: "GET",
      url: "/app/profile/' . $userData['id'] . '/manage/url/?url=" + escape(curVal),
      success: function(data) {
          if (data == "1") {
          	$("#urlPop").html("<a href=\'#\' onclick=\'$(this).parent().remove();return false\' style=\'float:right\'>close</a> <span style=\'font-weight:bolder\'>Awesome! Your profile can now be accessed at http://www.classconnect.com/" + curVal + " <a href=\'https://twitter.com/intent/tweet?text=" + escape("I just claimed my ClassConnect URL! http://www.classconnect.com/" + curVal + " #UnitedWeTeach") + "&via=ClassConnectInc\' target=\'_blank\'>Tweet this!</a></span>");
          } else if (data == "2") {
          	$("#urlSwapper").html("<span style=\'color:#C43C35\'>Oops! That username has been taken. Try another!</span>");
          	$("#urlSubBtn").attr("disabled", false);
			$("#urlSpot").attr("disabled", false);
		  } else if (data == "3") {
          	$("#urlSwapper").html("<span style=\'color:#C43C35\'>Oops! Your username can only have A-Z, 1-9, and _</span>");
          	$("#urlSubBtn").attr("disabled", false);
			$("#urlSpot").attr("disabled", false);
          }
      }

    });
    return false;
}
</script>';
		appFooter();
	}

}


function buildSharingQuery($uid) {
	$result = array("uid" => (int) $uid);
	// if we're logged in
	if (checkSession()) {
		// remove UID show all for now
		// if ($uid != user('id')) {
			if (user('level') != 1) {
				$result['$or'][] = array("shared_with.type" => 3, "shared_with.shareID" => (int) 1);
			}
			$result['$or'][] = array("shared_with.type" => 1, "shared_with.shareID" => (int) user('id'));
			$secs = getSections();
			foreach ($secs as $sec) {
			  $result['$or'][] = array("shared_with.type" => 2, "shared_with.shareID" => (int)$sec['section_id']);
			}

	// not logged in? we can only view things shared publicly
	} else {
		$result['$or'][] = array("shared_with.type" => 3, "shared_with.shareID" => (int) 1);
	}

	return $result;
}



function buildProfOneliner($userData) {
	$startDiv = '<div id="miniDescer" style="margin-left:20px;font-size:13px;color:#666;margin-top:-5px;margin-bottom:10px">';
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
			$midDiv .= '<button class="btn primary" style="margin-left:10px; padding:2px 6px 2px 6px;font-weight:bolder" onclick="jQuery.facebox({ ajax: \'' . userURL($userData) . 'manage/about\' }); return false;"><img src="/assets/app/img/box/editcon.png" style="height:12px;width:12px;margin-bottom:-2px;margin-right:1px" /> Edit your profile</button>';
		}


	return $startDiv . $midDiv . $endDiv;


  /*'Teaches <strong>Science, Math</strong> in grades <strong>9, 10, 11</strong> in Naperville, Illinois USA 
  <a href="http://www.esft.com" target="_blank" style="margin-left:10px"><img src="/assets/app/img/box/globe.png" style="height:12px;width:12px;margin-bottom:-1px;margin-right:3px" />Website</a>

  <button class="btn primary" style="margin-left:10px; padding:2px 6px 2px 6px" onclick="jQuery.facebox({ ajax: \'' . $rootURL . 'manage/about\' }); return false;"><img src="/assets/app/img/box/editcon.png" style="height:12px;width:12px;margin-bottom:-2px;margin-right:1px" /> Edit your profile</button>';

*/

}
?>