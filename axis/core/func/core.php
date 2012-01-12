<?php
//////////////////////////////////// - CLASSCONNECT 4.0 : ROADMAP ECHO - ////////////////////////////////////
// This file contains ClassConnect's core functionality. Enjoy!
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cleanBuffer($output) {
    $search = array(
    '/\>[^\S ]+/s', //strip whitespaces after tags, except space
    '/[^\S ]+\</s', //strip whitespaces before tags, except space
    '/(\s)+/s'  // shorten multiple whitespace sequences
    );
    $replace = array(
    '>',
    '<',
    '\\1'
    );
    // clean out JS comments
    $output = preg_replace('/(?<!\S)\/\/\s*[^\r\n]*/', '', $output);
    // clean out whitespace, etc. do it twice just for good measure
    $output = preg_replace($search, $replace, $output);
    $output = preg_replace($search, $replace, $output);
    // return our final output
    return $output;
}


// check if this is empty
function isFilled($str) {
    $str = str_replace(' ', '', $str);
    if ($str == '') {
        return false;
    } else {
        return true;
    }
}


// generate headers and footers
function pubHeader($page_title) {
    require_once('axis/core/gen_views/public/header.php');
}


function pubFooter() {
    require_once('axis/core/gen_views/public/footer.php');
}


function appHeader($pageTitle, $insertJS, $setTab) {
    require_once('axis/core/gen_views/app/header.php');
}


function appFooter() {
    require_once('axis/core/gen_views/app/footer.php');
}


function showError() {
    appHeader('Error!');
    require_once('axis/core/gen_views/app/error.php');
    appFooter();
}

function underUpdate() {
    appHeader('Error!');
    require_once('axis/core/gen_views/app/updating.php');
    appFooter();
}

function showLogin() {
    $loginError = false;

    // check if this user submitted the login form
    if (isset($_POST['logsubmit'])) {

        if (!isset($_POST['identity'])) {
        $_POST['identity'] == '';
        }
        if (!isset($_POST['pass'])) {
            $_POST['pass'] == '';
        }
        $attempt = initLogin($_POST['identity'], $_POST['pass']);

        if ($attempt != false) {
            header('location: ' . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            $loginError = true;
        }

    }

    appHeader('Login');
    require_once('axis/controllers/app/login/index.php');
    appFooter();
}


// determine & set time settings
function setLocales() {
    // if we're logged in
    if (checkSession()) {
        $data = getUser(user('id'));
        $settings = cleanSettings($data['settings']);
        if (isset($settings['timezone'])) {
            date_default_timezone_set($settings['timezone']);
        }
    }
}


// clean our settings thing
function cleanSettings($setObj) {
    $newObj = html_entity_decode($setObj);
    $settings = json_decode($newObj, true);
    return $settings;
}


// initialize the wizard
function initWizard() {
    $_SESSION['wiz'] = true;
    $_SESSION['wizData'] = array();
}


// set wiz to false, destroy current wiz data
function destroyWizard() {
    $_SESSION['wiz'] = false;
    $_SESSION['wizData'] = array();
}


// initialize connection to SMTP server & send an email
function sendEmail($subject, $toArr, $fromArr, $body, $bodyType) {
    /*
    Example use case
    
    $subj = 'Func send';
    $sendTo = array('eric@classconnect.com' => 'Eric Simons');
    $sendFrom = array('support@classconnect.com' => 'CC Eric');
    $body = "Hi there!\n\n\nSUP";

    sendEmail($subj, $sendTo, $sendFrom, $body);
    */
    $totalTo = 0;
    foreach ($toArr as $tkey=>$to) {
        if (is_numeric($tkey)) {
            if (filter_var($to, FILTER_VALIDATE_EMAIL) != true) {
                unset($toArr[$tkey]);
            } else {
                $totalTo++;
            }
        } else {
            if (filter_var($tkey, FILTER_VALIDATE_EMAIL) != true) {
                unset($toArr[$tkey]);
            } else {
                $totalTo++;
            }
        }
    }

    if ($totalTo > 0) {

        if (!isset($bodyType)) {
            $bodyType = 'text/plain';
        }

        if (!isset($fromArr)) {
            $fromArr = array('support@classconnect.com' => 'ClassConnect');
        }


        $smtp = Swift_SmtpTransport::newInstance('smtp.sendgrid.net', 587)->setUsername('classconnectinc')->setPassword('cc221g7tx');
        $mailer = Swift_Mailer::newInstance($smtp);
                    $message = Swift_Message::newInstance($subject);
    $message
      ->setTo($toArr)
      ->setFrom($fromArr)
      ->setBody(
        $body,
        $bodyType
      );


    try {

        if ($mailer->send($message)) {
            return true;
        } else {
            return false;
        }

    } catch (Exception $e) {
        return false;
    }
        
    } else {
        return false;
    }
}


// internationalization stuff (language & timezones)
function say($text, $lang) {
    // only put to strs file if its local
    global $developerMode;
    if ($developerMode == true) {
    $strings = file_get_contents('axis/lang/strs.json');
        $strAr = json_decode($strings);
        if (array_key_exists($text, $strAr)) {
            if (array_key_exists($lang, $strAr->$text)) {
                return $strAr->$text->$lang;
            } else {
                return $text;
            }
        } else {
            $strAr->$text = array();
            $myFile = "axis/lang/strs.json";
            $fh = fopen($myFile, 'w') or die("can't open file");
            $stringData = json_encode($strAr);
            fwrite($fh, $stringData);
            fclose($fh);
            return $text;
        }
    // production? just return the string
    } else {
        return $text;
    }
}

//only returns the string if they're allowed to see it
function dispOnly($str, $level) {
    if (user('level') == $level) {
        return $str;
    }
}




// sort multidimensional arrays
function sort2d ($array, $index, $order='asc', $natsort=FALSE, $case_sensitive=FALSE)  
    { 
        if(is_array($array) && count($array)>0)  
        { 
           foreach(array_keys($array) as $key)  
               $temp[$key]=$array[$key][$index]; 
               if(!$natsort)  
                   ($order=='asc')? asort($temp) : arsort($temp); 
              else  
              { 
                 ($case_sensitive)? natsort($temp) : natcasesort($temp); 
                 if($order!='asc')  
                     $temp=array_reverse($temp,TRUE); 
           } 
           foreach(array_keys($temp) as $key)  
               (is_numeric($key))? $sorted[]=$array[$key] : $sorted[$key]=$array[$key]; 
           return $sorted; 
      } 
      return $array; 
    }  


// format URL
function formatURL($url) {
    if ((strpos($url,'http://') === false) && strpos($url,'https://') === false) {
        $url = 'http://' . $url;
    }

    return $url;
}







/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Non-user specific functions (login, sessions, etc)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

// login user via email/username and password
function authLogin($identity, $password) {
	$errors = array();	
	
	if ($identity != '') {
		$identity = escape($identity);
	} else {
		$errors[] = 'No username was entered.';
	}
	
	if ($password != '') {
		$password = escape($password);
	} else {
		$errors[] = 'No password was entered.';
	}
	if (empty($errors)) {
		$user = good_query_assoc("SELECT * FROM users WHERE pass = SHA1('$password') AND (user_name = '$identity' OR e_mail = '$identity') LIMIT 1");
		return $user;
	} else {
		return false;
	}
} // end userLogin



function initLogin($identity, $password) {
    $attempt = authLogin($identity, $password);
    if ($attempt != false) {
        // login
        killSession();
        setSession($attempt['id']);
        return true;
    } else {
        return false;
    }
}



// get the local user's data from the session
function user($str) {
    $currentID = $_SESSION['user_id'];
    // if it's their ID, pull from session
    if ($str == 'id') {
        return $currentID;

    }

    // otherwise, pull from database
    $userData = getUser($currentID);
    if ($str == 'first_name') {
        return $userData['first_name'];

    } elseif ($str == 'last_name') {
        return $userData['last_name'];

    } elseif ($str == 'level') {
        return $userData['level'];

    } else {
        return false;
    }
}

// get a user's information
function getUser($userid, $reset) {
    $userid = escape($userid);
    // query memcached
    $key = md5('uid-' . $userid);
    $get_result = getMemKey($key);
    if ($get_result && $reset != true) {
        return $get_result;
    } else {
        // Run the query and transform the result data into your final dataset form
        $row = good_query_assoc("SELECT * FROM users WHERE id = '$userid' LIMIT 1");
        setMemKey($key, $row, TRUE, 86400); // Store the result of the query for a day
        return $row;
    }

} // end getUser


// return a user's data
function dispUser($userid, $field){
    $userData = getUser($userid);
    // if this is a student, show mr/mrs title
    if (user('level') == 1 && $userData['level'] == 3 && $userData['pre_name'] != '') {
        $userData['first_name'] = $userData['pre_name'];
    }
    return $userData[$field];
}

// get a list of users
function getUsers($userIDs) {
    $uidArray = explode(',', $userIDs);
    foreach($uidArray as $uid) {
        if (is_numeric($uid)) {
            $tot .= $uid . ', ';
        }
    }
    $rows = good_query_table("SELECT * FROM users WHERE id IN ($tot 0)");
    return $rows;
}
// end getUsers


// If login success, set session variables
function setSession($userid) {
	// retrieve user information
	$row = getUser($userid);
                   
	$_SESSION['session_key'] = session_id();
	
	// set session variables
	$_SESSION['user_id'] = $row['id'];
	
} // end setSession




// This function will detect is the user is logged in
function checkSession() {
	// detect if user_id session is set
	if (!isset($_SESSION['user_id'])) {
		// return null
		return false;
	} else {
		//return true
		return true;
	}
} // end checkSession


// This function will kill a user's session
function killSession() {
	// detect if user_id session is set
	if (isset($_SESSION['user_id'])) {
		$sessionKey = session_id();
		
		$_SESSION = array();
		session_destroy();
		setcookie ('PHPSESSID', '', time()-3600, '/', '', 0, 0);
		}
} // end killSession


// reverse html stripping (use for embed codes, etc
function reverse_htmlentities($mixed)
{
    $htmltable = get_html_translation_table(HTML_ENTITIES);
    foreach($htmltable as $key => $value)
    {
        $mixed = ereg_replace(addslashes($value),$key,$mixed);
    }
    return $mixed;
}
// end reverse html entities



/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// END non-user specific functions (login, etc)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
















/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// User generic functions
/////////////////////////////////////////////////////////////////////////////////////////////////////////////


// get a user's notifications
function getNotis($uid) {
    if (!isset($uid)) {
        $uid = user('id');
    }

    $key = md5('noti-' . $uid);
    $get_result = getMemKey($key);

}

// add/remove notis
function updateNotis($value, $uid) {
    if (!isset($uid)) {
        $uid = user('id');
    }

    $key = md5('noti-' . $uid);
    $get_result = getMemKey($key);
    if ($get_result) {
        $get_result['data'] = $get_result['data'] + 1;

    } else {
        setMemKey($key, array("data" => 1), TRUE, 604800); // Store the result for 7 days
        return $secs;

    }
    
}



// get # of friend requests
function getReqs($uid) {
    if (!isset($uid)) {
        $uid = user('id');
    }

    $key = md5('reqs-' . $uid);
    $get_result = getMemKey($key);

    if ($get_result) {
        return $get_result['data'];
        
    } else {
        $total = count(getFriendReqs($uid));
        setMemKey($key, array("data" => $total), TRUE, 604800);
        return $total;
    }

}

// add a notification
function updateReqs($value, $uid) {
    if (!isset($uid)) {
        $uid = user('id');
    }

    $key = md5('reqs-' . $uid);
    $get_result = getMemKey($key);
    if ($get_result) {
        $get_result['data'] = $get_result['data'] + $value;

        setMemKey($key, $get_result, TRUE, 604800); // set for 7 days

    }
    
}



// get user & reset password
function resetPassStream($email) {
    $email = escape($email);
    $user = good_query_assoc("SELECT * FROM users LEFT JOIN school_users ON school_users.uid = users.id WHERE (users.e_mail = '$email' OR school_users.email='$email') LIMIT 1");
    if ($user != false) {
        $chash = substr(SHA1($email . date('m/d/Y/i/s') . 'cc4'),0,7);
        setPassword($user['id'], $chash);
        sendPasswordEmail($user['id'], $chash);
        return true;
    } else {
        return false;
    }

}


// Reset password function
function setPassword($userID, $password) {
	$enc_pass  = SHA1($password);
	good_query("UPDATE users SET pass = '$enc_pass' WHERE id = $userID");
}
// end resetPassword function


// Reset password function
function sendPasswordEmail($userID, $password) {
	$user = getUser($userID);
	$body = "Hello " . $user['first_name'] . ",\n
Your ClassConnect password has been reset. You can now login using your email/username and new password listed below.\n\n
	
Your new password: $password \n
Your username: " . $user['user_name'] . "\n\n

Login using your email/username and password at http://www.classconnect.com. If you encounter any problems, feel free to reply to this email and we'll assist you in any way possible!\n\n
	
Sincerely,\n
The ClassConnect Support Team";
	$emails = getUserEmails($userID);
	
	foreach ($emails as $address) {
		mail($address, 'ClassConnect Account Password Reset', $body, "From: support@classconnect.com");
	}

}
// end resetPassword function


/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// END user generic functions
/////////////////////////////////////////////////////////////////////////////////////////////////////////////













/// Course specific functions

// function get a teacher's courses
function getMyCourses($uid) {
    $final = array();

    if (!isset($uid)) {
        $uid = user('id');
    }

    $userData = getUser($uid);

    // if this is a student
    if ($userData['level'] == 1) {
        $secs = getSections($uid);

        foreach ($secs as $sec) {
            $secData = getSection($sec['section_id']);
            if (array_key_exists($sec['course_link'], $final)) {
                $final[$sec['course_link']]['sections'][$sec['section_id']] = $secData;
            } else {
                $courseData = getCourse($sec['course_link']);
                $final[$sec['course_link']] = $courseData;
                $final[$sec['course_link']]['sections'][$sec['section_id']] = $secData;
            }
        }

        // sort sections
        foreach ($final as $ckey=>$course) {
            $final[$ckey]['sections'] = sort2d($course['sections'], 'title', 'asc', true);
        }

        $final = sort2d($final, 'title', 'asc', true);

        return $final;
        
    // if this is a teacher
    } elseif ($userData['level'] == 3) {

        $secs = getSections($uid);
        // Run the query and transform the result data into your final dataset form

        $courses = getCourses($uid);

        foreach ($courses as $course) {
            $final[$course['course_id']] = getCourse($course['course_id']);
        }

        foreach ($secs as $sec) {
            $secData = getSection($sec['section_id']);
            if (array_key_exists($sec['course_link'], $final)) {
                $final[$sec['course_link']]['sections'][$sec['section_id']] = $secData;
            } else {
                $courseData = getCourse($sec['course_link']);
                $final[$courseData['course_id']] = $courseData;
                $final[$sec['course_link']]['sections'][$sec['section_id']] = $secData;
            }
        }

        // sort sections
        foreach ($final as $ckey=>$course) {
            $final[$ckey]['sections'] = sort2d($course['sections'], 'title', 'asc', true);
        }

        $final = sort2d($final, 'title', 'asc', true);

        return $final;
            
    }
    
}


function getSections($uid, $reset) {
    if (!isset($uid)) {
        $uid = user('id');
    }

    $userData = getUser($uid);

    $key = md5('sections-' . $uid);
    $get_result = getMemKey($key);
    if ($get_result && !isset($reset)) {
        return $get_result;
    } else {

        // if this is a student
        if ($userData['level'] == 1) {
            $secs = good_query_table("SELECT course_sections.section_id, course_sections.course_link FROM course_sections LEFT JOIN course_students ON course_students.link_sec_id = course_sections.section_id WHERE course_students.student_id = '$uid' AND course_sections.status = 1");
            setMemKey($key, $secs, TRUE, 86400); // Store the result of the query for a day
            return $secs;
            
        // if this is a teacher
        } elseif ($userData['level'] == 3) {
            $secs = good_query_table("SELECT course_sections.section_id, course_sections.course_link FROM course_sections LEFT JOIN course_teachers ON course_teachers.link_sec_id = course_sections.section_id WHERE course_teachers.teach_id = '$uid' AND course_sections.status = 1");
            setMemKey($key, $secs, TRUE, 86400); // Store the result of the query for a day
            return $secs;

        }

    }
}



function getCourses($uid, $reset) {
    if (!isset($uid)) {
        $uid = user('id');
    }

    $key = md5('courses-' . $uid);
    $get_result = getMemKey($key);
    if ($get_result && !isset($reset)) {
        return $get_result;
    } else {

        $courses = good_query_table("SELECT course_id FROM courses WHERE teach_id = '$uid' AND status = 1");
        setMemKey($key, $courses, TRUE, 86400); // Store the result of the query for a day
        return $courses;

    }
}




// get a course
function getCourse($courseID, $reset) {
    // query memcached
    $key = md5('course-' . $courseID);
    $get_result = getMemKey($key);
    if ($get_result && !isset($reset)) {
        return $get_result;
    } else {
        // Run the query and transform the result data into your final dataset form
        $row = good_query_assoc("SELECT * FROM courses WHERE course_id = '$courseID' LIMIT 1");
        setMemKey($key, $row, TRUE, 86400); // Store the result of the query for a day
        return $row;
    }
}


// get a section
function getSection($secID, $reset) {
    // query memcached
    $key = md5('section-' . $secID);
    $get_result = getMemKey($key);
    if ($get_result && !isset($reset)) {
        return $get_result;
    } else {
        // Run the query and transform the result data into your final dataset form
        $row = good_query_assoc("SELECT * FROM course_sections WHERE section_id = '$secID' LIMIT 1");
        setMemKey($key, $row, TRUE, 86400); // Store the result of the query for a day
        return $row;
    }
}



function authSection($secID, $uid) {
    if (!isset($uid)) {
        $uid = user('id');
    }


    $sections = getSections($uid);
    foreach ($sections as $section) {
        if ($section['section_id'] == $secID) {
            return true;
        }
    }
    

    return false;
}



// for the top navigation
function buildCourseNav() {
    $courses = getMyCourses();
    $result = '';

    foreach ($courses as $course) {
        $result .= '<div class="courseID">' . $course['title'] . '</div>';
        foreach ($course['sections'] as $section) {
            $result .= '<li><a href="/app/course/' . $section['section_id'] . '">- ' . $section['title'] . '</a></li>';
        }
    }

    if ($result == '') {
        $result = '<li><a>No courses found.</a></li>';
    }

    return $result;
}




// set a smart course picker
function setTempSwap($presel, $uni, $time, $uid) {
    if (!isset($uid)) {
        $uid = user('id');
    }
    if (!isset($time)) {
        // default one day
        $time = 86400;
    }
    // set memcached
    $key = md5('uid-' . $uid . '-' . $uni);
    setMemKey($key, $presel, TRUE, $time);

} // end setSmartCourse



// get a smart course picker
function getTempSwap($uni, $uid) {
    if (!isset($uid)) {
        $uid = user('id');
    }
    // query memcached
    $key = md5('uid-' . $uid . '-' . $uni);
    $get_result = getMemKey($key);
    if ($get_result) {
        return $get_result;
    } else {
        return false;
    }

} // end getSmartCourse


// build the course picker
function buildCoursePicker($presel, $permsel, $inputName, $exCSS) {
    $courses = getMyCourses();
    $result = '<div class="coursePickerMain" style="' . $exCSS . '">';

    foreach ($courses as $course) {
        // by default, set selall to true
        $selAll = true;
        $sectionML = '';
        $keepOpen = false;
        foreach ($course['sections'] as $section) {
            if (in_array($section['section_id'], $presel)) {
                $checked = ' checked';
                $keepOpen = true;
            } else {
                $checked = '';
                $selAll = false;
            }
            $sectionML .= '<div class="coursePickerSection"><input name="courses[]" value="' . $section['section_id'] . '" type="checkbox" style="float:left"' . $checked . '>' . $section['title'] . '</div>';
        }

        if (empty($course['sections'])) {
            $selAll = false;
        }

        if ($keepOpen == true) {
            $showSecs = ' style="display:block"';
            if ($selAll == true) {
                $preCheck = ' checked';
            } else {
                $preCheck = '';
            }
            
        } else {
            $showSecs = '';
            $preCheck = '';
        }

        $result .= '<div class="coursePickerWrap">
<div class="coursePickerTitle" onClick="pickShowSections(this);"><input type="checkbox" style="float:left" onClick="pickCourse(this);"' . $preCheck . '><img class="arrSwap" src="/assets/app/img/gen/arrDown.png" />' . $course['title'] . '</div>

<div class="coursePickerSections"' . $showSecs . '>' . $sectionML . '</div></div>';


    }

    $result .= '</div>';

    if ($result == '') {
        $result = 'No courses found.';
    }

    return $result;
    
}












// this is for the feed API

// insert an item into the feed
function insertFeedItem($appType, $notiType, $shared_first, $data, $withinInc, $forceNew, $uid) {
    if (isset($uid)) {
        $uid = (int) $userid;
    } else {
        $uid = (int) user('id');
    }

    $shared_with = array();

    // make sure all shared withs are ints
    foreach ($shared_first as $share) {
        $temp = array();
        $temp['type'] = (int) $share['type'];
        $temp['shareID'] = (int) $share['shareID'];
        $shared_with[] = $temp;
    }

    $rightNow = (int) date("U");
    // set data timestamp
    $data['sent'] = $rightNow;

    global $mdb;
    // select a collection (analogous to a relational database's table)
    $collection = $mdb->feed;

    // default true
    $shouldInsert = true;

    // if we want to skip the check/update process
    if ($forceNew != true) {
        // set default for withinInc if its not set
        if ($withinInc == false) {
            // default is six hours (timestamp in seconds)
            $withinInc = 21600;
        }


        $minTime = $rightNow - $withinInc;


        $queryOpt = array("shared_with" => $shared_with, "appType" => $appType, "notiType" => $notiType, "sent_at" => array('$gte' => $minTime));
        $curData = $collection->findOne($queryOpt);

        // if we actually have a result
        if (isset($curData)) {
            // no need to insert after this
            $shouldInsert = false;
            // do the update bizznitch
            $upID = $curData['_id'];
            unset($curData['_id']);
            $curData['data'][] = $data;
            $curData['sent_at'] = $rightNow;
 
            $collection->update(array('_id' => new MongoId($upID)), array('$set' => $curData), array("upsert" => true));

            $returnID = $upID;

        }


    }


    if ($shouldInsert == true) {
        $obj = array(
            "appType" => $appType, // general app id
            "notiType" => $notiType, // ie: cal update, fbox update, etc
            "uid" => $uid, // user who created this
            "shared_with" => $shared_with, // array of key value (type (1-person, 2-course), shareID)
            "data" => array($data),
            "sent_at" => $rightNow // timestamp sent at
        );

        $collection->insert($obj);

        // send notis (if applicable)
        pushNotis($obj);

        $returnID = $obj['_id'];
    }


    return $returnID;

}



// remove feed item
function rmFeedItem($itemID, $rmAll, $rmSingle, $uid) {
    global $mdb;
    // select a collection (analogous to a relational database's table)
    $collection = $mdb->feed;

    if (!isset($uid)) {
        $uid = user('id');
    }


    $idata = getFeedItem($itemID);

    if ($idata['uid'] != $uid) {
      return array("You do not have permission to edit this.");
    }

    // this is an actual delete request
    if ($rmAll == true) {
        $collection->remove(array('_id' => new MongoId($itemID)), array('safe' => true));

    // this is an update (minus one)
    } else {
        foreach ($idata['shared_with'] as $skey=>$share) {
            if ($share == $rmSingle) {
                unset($idata['shared_with'][$skey]);
            }
        }

        $idata['shared_with'] = array_values($idata['shared_with']);

        unset($idata['_id']);

        $collection->update(array('_id' => new MongoId($itemID)), array('$set' => $idata));
        
    }
}



// get feed item
function getFeedItem($itemID) {
    global $mdb;
    // select a collection (analogous to a relational database's table)
    $collection = $mdb->feed;
    $data = $collection->findOne(array('_id' => new MongoId($itemID)));
    return $data;
}



// retrieve items from the feed
function retrieveFeedItems($queryData, $offset, $limit, $order) {
    global $mdb;
    // select a collection (analogous to a relational database's table)
    $collection = $mdb->feed;

    if ($limit == false) {
        $limit = 40;
    }

    if ($offset == false) {
        $offset = 0;
    }

    // if false, order by latest
    if ($order == false) {
        $order = array("sent_at" => -1);
    // otherwise, order by last sent (why, idk)
    } else {
        $order = array("sent_at" => 1);
    }

    $data = $collection->find($queryData)->limit($limit)->skip($offset)->sort($order);

    return $data;
    

}


// turn feed item into HTML. primary will force course/user as main identity
function genFeedItem($items, $primary, $uid) {
    if (!isset($uid)) {
        $uid = user('id');
    }
    // set the final result
    $finalResult = '';
    $miniResult = '';

    if (isset($primary)) {
        if ($primary['type'] == 1) {
            $dType = 't1=' . $primary['shareID'];
        } elseif ($primary['type'] == 2) {
            $dType = 't2=' . $primary['shareID'];
        }
    }

    // iterate through each of our feed items
    foreach ($items as $item) {

        if ($uid == $item['uid']) {
            $delML = '<img src="/assets/app/img/colleagues/del.png" class="deleter" data-original-title="Remove" onClick="jQuery.facebox({ ajax: \'/app/common/feed/remove/' . $item['_id'] . '?' . $dType . '\' }); return false;" />';
        } else {
            $delML = '';
        }

        // lets figure out the proper identity(s)
        foreach ($item['shared_with'] as $sharePer) {
            if ($sharePer == $primary) {
                $unid = $sharePer;
                $halt = true;
            }

            if ($halt != true) {
                // see if we have auth for this section
                if ($sharePer['type'] == 2 && authSection($sharePer['shareID'])) {
                    $unid = $sharePer;
                // if this us?
                } elseif ($sharePer['type'] == 1 && $sharePer['shareID'] == $uid) {
                    $unid = $sharePer;
                }
            }
        }

        // generate title
        if ($unid['type'] == 2) {
            $secData = getSection($unid['shareID']);
            $courseData = getCourse($secData['course_link']);
            $idTitle = $courseData['title'];
        } elseif ($unid['type'] == 1) {
            $idTitle = dispUser($item['uid'], 'first_name') . ' ' . dispUser($item['uid'], 'last_name');
        }

        // filebox handler
        if ($item['appType'] == 1) {
            require_once('axis/controllers/app/filebox/core/main.php');

            // determine URLs & show info
            if ($unid['type'] == 1) {
                $fbURL = '/app/filebox/';

            } elseif ($unid['type'] == 2) {
                $fbURL = '/app/course/' . $unid['shareID'] . '/handout/';

            }

            // if content has been added
            if ($item['notiType'] == 1) {

                $obj = array();
                $obj['title'] = $item['data'][0]['title'];
                $obj['format'] = $item['data'][0]['format'];
                $obj['versions'][0]['ext'] = $item['data'][0]['ext'];
                if ($item['data'][0]['format'] == 0) {
                    $obj['format'] = 1;
                    $obj['versions'][0]['ext'] = 'folder';
                }

             $miniResult .= '<div class="feedItemStory" id="item-' . $item['_id'] . '">
' . $delML . '
  <img src="/assets/app/img/box/miniadd.png" class="miniImg" /> ' . $idTitle . ' created <a href="' . $fbURL . $item['data'][0]['id'] . '" class="js-pjax">' . createConTitle($obj) . '</a>

  <div style="clear:both"></div>
  </div>';
              // if content has been added
            } elseif ($item['notiType'] == 2) {

                $obj = array();
                $obj['title'] = $item['data'][0]['title'];
                $obj['format'] = $item['data'][0]['format'];
                $obj['versions'][0]['ext'] = $item['data'][0]['ext'];
                if ($item['data'][0]['format'] == 0) {
                    $obj['format'] = 1;
                    $obj['versions'][0]['ext'] = 'folder';
                }

             $miniResult .= '<div class="feedItemStory" id="item-' . $item['_id'] . '">
' . $delML . '
  <img src="/assets/app/img/box/minifoladd.png" class="miniImg" /> ' . $idTitle . ' added new content to <a href="' . $fbURL . $item['data'][0]['id'] . '" class="js-pjax">' . createConTitle($obj) . '</a>

  <div style="clear:both"></div>
  </div>';


    // if content has been shared
            } elseif ($item['notiType'] == 3) {

                $obj = array();
                $obj['title'] = $item['data'][0]['title'];
                $obj['format'] = $item['data'][0]['format'];
                $obj['versions'][0]['ext'] = $item['data'][0]['ext'];
                if ($item['data'][0]['format'] == 0) {
                    $obj['format'] = 1;
                    $obj['versions'][0]['ext'] = 'folder';
                }

             $miniResult .= '<div class="feedItemStory" id="item-' . $item['_id'] . '">
' . $delML . '
  <img src="/assets/app/img/box/minishared.png" class="miniImg" /> ' . $idTitle . ' shared <a href="' . $fbURL . $item['data'][0]['id'] . '" class="js-pjax">' . createConTitle($obj) . '</a>

  <div style="clear:both"></div>
  </div>';



            }


        // calendar handler
        } elseif ($item['appType'] == 2) {

            // added calendar entries
            if ($item['notiType'] == 1) {

                // if there is more than 1 entry
                if (count($item['data']) > 1) {
                    $swapText = count($item['data']) . ' new entries';
                } else {
                    $swapText = '"' . $item['data'][count($item['data']) - 1]['title'] . '"';
                }

                $miniResult .= '<div class="feedItemStory" id="item-' . $item['_id'] . '">
' . $delML . '
  <img src="/assets/app/img/feed/cal.png" class="miniImg" /> ' . $idTitle . ' added ' . $swapText . ' to the <a href="/app/course/' . $unid['shareID'] . '/calendar" class="js-pjax">calendar</a>.

  <div style="clear:both"></div>
  </div>';

            }



        // if this is a personal update
        } elseif ($item['appType'] == 3) {
            // status update
            if ($item['notiType'] == 1) {
                // close out the mini feed if there is one
                if ($miniResult != '') {
                    // do work
                    $finalResult .= '<div class="feedItem">' . $miniResult . '</div>';
                    $miniResult = '';
                }

                $finalResult .= '<div class="feedItem" id="item-' . $item['_id'] . '">
' . $delML . '
    <div class="feedLeft">
      <img src="' . iconServer() . '50_' . $secData['icon'] . '" class="profImg" /> 
    </div>

    <div class="feedRight">
      <a href="/app/course/' . $unid['shareID'] . '" style="font-weight:bolder">' . $idTitle . '</a><br />
      ' . nl2br($item['data'][count($item['data']) - 1]['status']) . '
    </div>

    <div style="clear:both"></div>

  </div>';


            // if this is an auto add noti (storage!)
            } elseif ($item['notiType'] == 2) {
                $miniResult .= '<div class="feedItemStory" id="item-' . $item['_id'] . '">
' . $delML . '
  <img src="/assets/app/img/colleagues/minicard.png" class="miniImg" /> You successfully referred <strong>' . dispUser($item['data'][0]['friend_id'], 'first_name') . ' ' . dispUser($item['data'][0]['friend_id'], 'last_name') . '</strong>! To say thanks, we added storage space to your FileBox!

  <div style="clear:both"></div>
  </div>';

            // if this is a colleague add success
            } elseif ($item['notiType'] == 3) {
               


               $miniResult .= '<div class="feedItemStory" id="item-' . $item['_id'] . '">
' . $delML . '
  <img src="/assets/app/img/colleagues/minicard.png" class="miniImg" /> You are now colleagues with <strong>' . dispUser($item['data'][0]['friend_id'], 'first_name') . ' ' . dispUser($item['data'][0]['friend_id'], 'last_name') . '</strong>.

  <div style="clear:both"></div>
  </div>';


            }
            
        }
        
    }


    // final dump of mini result
    if ($miniResult != '') {
        // do work
        $finalResult .= '<div class="feedItem">' . $miniResult . '</div>';
        $miniResult = '';
    }

    return $finalResult;
}


// send noti emails and (soon) text messages
function pushNotis($itemObj) {

    // filebox notis
    if ($itemObj['appType'] == 1) {
        // new share email
        if ($itemObj['notiType'] == 3) {
            foreach ($itemObj['shared_with'] as $share) {
                if ($share['type'] == 1) {
                    // send an email to our shared friend
                    $myName = dispUser($itemObj['uid'], 'first_name') . ' ' . dispUser($itemObj['uid'], 'last_name');
                    $subj = $myName . ' has shared "' . $itemObj['data'][0]['title'] . '" with you';
                    $sendTo = array(dispUser($share['shareID'], 'e_mail'));
                    $sendFrom = array('support@classconnect.com' => $myName);
                    $body = "Hi there,\n$myName just shared \"{$itemObj['data'][0]['title']}\" with you on ClassConnect. To access this content, visit http://www.classconnect.com/app/filebox/{$itemObj['data'][0]['id']} in your web browser.\n\n-The ClassConnect Team";

                    sendEmail($subj, $sendTo, $sendFrom, $body);
                    
                }
            }
        }
    }
}








// function for adding colleagues
function addFriend($friendID, $uid, $autoAdd) {
    if (isset($uid)) {
        $uid = $uid;
    } else {
        $uid = user('id');
    }

    $friendID = escape($friendID);


    // check if there is an existing request
    $creq = good_query_assoc("SELECT * FROM colleagues WHERE (user1 = '$uid' AND user2 = '$friendID') OR (user2 = '$uid' AND user1 = '$friendID') LIMIT 1");
    // if it exists but isn't confirmed
    if ($creq['status'] == 1) {
        // check and see if we initiated it or vice versa
        if ($creq['user2'] == $uid) {
            $linkID = $creq['link_col_id'];
            good_query("UPDATE colleagues SET status = 2 WHERE link_col_id = $linkID");

            // notify the other guy that we accepted their request
            insertFeedItem(3, 3, array(array("type"=>1, "shareID"=>$creq['user1'])), array("friend_id"=>(int)$creq['user2']));

            updateReqs(-1, $uid);

        }

    // if it wasn't found, insert it
    } elseif ($creq == false) {
        if (isset($autoAdd)) {
            $status = 2;
        } else {
            $status = 1;
        }
        $now = date("U");
        good_query("INSERT INTO colleagues (user1, user2, status, initiated) VALUES ($uid, $friendID, $status, $now)");
        updateReqs(1, $friendID);

        // send an email to our new friend
        $myName = dispUser($uid, 'first_name') . ' ' . dispUser($uid, 'last_name');
        $subj = $myName . ' requested you as a colleague on ClassConnect';
        $sendTo = array(dispUser($friendID, 'e_mail'));
        $sendFrom = array('support@classconnect.com' => $myName);
        $body = "Hi there,\n$myName just added you as a colleague on ClassConnect. To accept this colleague request, visit http://www.classconnect.com/app in your web browser.\n\n-The ClassConnect Team";

        sendEmail($subj, $sendTo, $sendFrom, $body);
        
    }


    // update friends cache for both IDs
    getFriends(true, $friendID);
    getFriends(true, $uid);

}


// function for removing colleagues
function rmFriend($friendID, $uid) {
    if (isset($uid)) {
        $uid = escape($uid);
    } else {
        $uid = user('id');
    }

    $friendID = escape($friendID);


    // lets just remove all instances that may/may not exist
    good_query("DELETE FROM colleagues WHERE (user1 = '$uid' AND user2 = '$friendID') OR (user2 = '$uid' AND user1 = '$friendID') LIMIT 1");

    // update friends cache for both IDs
    getFriends(true, $friendID);
    getFriends(true, $uid);

}



// get my friends
function getFriends($reset, $uid) {
    if (isset($uid)) {
        $usrData = getUser($uid);
        $level = $usrData['level'];
    } else {
        $uid = user('id');
        $level = user('level');
    }

    $uid = escape($uid);


    // should attempt to pull from cache
    $key = md5('friends-' . $uid);
    $get_result = getMemKey($key);
    if ($get_result && !isset($reset)) {
        return $get_result;
    } else {
        // Run the query and transform the result data into your final dataset form
        $row = good_query_table("SELECT * FROM colleagues WHERE (user1 = '$uid' OR user2 = '$uid') AND status = 2");
        $final = array();
        foreach ($row as $data) {

            if ($data['user1'] == $uid) {
                $userData = $data['user2'];
            } else {
                $userData = $data['user1'];
            }

            $final[] = $userData;
        }

        setMemKey($key, $final, TRUE, 86400); // Store the result of the query for a day
        return $final;
    }




}



// get requests
function getFriendReqs($uid) {
    if (!isset($uid)) {
        $uid = user('id');
    } else {
        $uid = escape($uid);
    }

    $row = good_query_table("SELECT * FROM colleagues WHERE user2 = '$uid' AND status = 1");
    $final = array();
    foreach ($row as $data) {

        if ($data['user1'] == $uid) {
            $userData = $data['user2'];
        } else {
            $userData = $data['user1'];
        }

        $final[] = $userData;
    }


    return $final;

}


// format friends in JSON
function genFriendsJSON() {
    $finalList = array();
    $amigos = getFriends();
    foreach ($amigos as $amigoID) {
      $temp = array("label" => dispUser($amigoID, 'first_name') . ' ' . dispUser($amigoID, 'last_name'),
      "val" => $amigoID, "icon" => dispUser($amigoID, 'prof_icon'));
        $finalList[] = $temp;
    }

    return json_encode($finalList);
}





// authenticate a friendship
function authFriend($friendID, $uid) {
    if (isset($uid)) {
        $uid = $uid;
    } else {
        $uid = user('id');
    }

    // default pass to false
    $pass = false;

    $amigos = getFriends(null, $uid);

    foreach ($amigos as $amigo) {
        if ($amigo == $friendID) {
            $pass = true;
        }
    }

    return $pass;


}



// create a temp user, just with an email address!
function createTempUser($email) {
    global $dbc;
    $now = date("U");
    // you can determine temp users by the password field (string 'temp-user')
    $insertUser = @mysqli_query($dbc, "INSERT INTO users (first_name, pass, e_mail, reg_date) VALUES ('$email', 'temp-user', '$email', $now)");

    $userID = $dbc->insert_id;

    return $userID;
}




function getUserByEmail($email) {
    $email = escape($email);
    $checkMail = good_query_assoc("SELECT * FROM users WHERE e_mail = '$email' LIMIT 1");
    return $checkMail;
}

function getUserByHash($hash) {
    $hash = escape($hash);
    $checkMail = good_query_assoc("SELECT * FROM users WHERE mehash = '$hash' LIMIT 1");
    return $checkMail;
}








// functions for storage & invites

// increment this user's invites
function rewardInvite($inc, $userID) {
    $userData = getUser($userID);
    $new = $userData['invites'] + $inc;
    good_query("UPDATE users SET invites = $new WHERE id = $userID");
    getUser($userID, true);
}

// calculate a user's storage capacity
function storageInfo($userID) {
    if (!isset($userID)) {
        $userID = user('id');
    }
    $udata = getUser($userID);
    // calculate total storage available
    // 524 288 = 500mb
    $total = ($udata['invites'] * 524288) + 524288;

    $total = $total * 1024; // get this to bytes

    return array("used" => $udata['storage_used'], "available" => $total);
}

// format storage info
function dispStorageInfo($userID) {
    $data = storageInfo($userID);

    // show in megabytes
    if ($data['used'] < 1073741824) {
        $used['data'] = ceil($data['used'] / 1048576);
        $used['fix'] = 'MB';

    // show in gigs
    } elseif ($data['used'] >= 1073741824) {
        $used['data'] = sprintf("%.1f", $data['used'] / 1073741824);
        $used['fix'] = 'GB';

    }


    // show in megabytes
    if ($data['available'] < 1073741824) {
        $avail['data'] = ceil($data['available'] / 1048576);
        $avail['fix'] = 'MB';

    // show in gigs
    } elseif ($data['available'] >= 1073741824) {
        $avail['data'] = sprintf("%.1f", $data['available'] / 1073741824);
        $avail['fix'] = 'GB';

    }

    $percentage = substr(sprintf("%01.2f", $data['used'] / $data['available']), 2, 2);

    // dont show "00%" or "05%", etc
    if (substr($percentage, 0, 1) == '0') {
        $percentage = substr($percentage, 1, 1);
    }


    $final['used'] = $used;
    $final['available'] = $avail;
    $final['percentage'] = $percentage;



    return $final;
}

// check if storage exceeds max
function checkStorage($add, $uid) {
    $data = storageInfo($uid);
    if (($data['storage_used'] + $add) > $data['available']) {
        return false;
    } else {
        return true;
    }
}

// add (or subtract) to a user's current storage
function incStorage($inc, $userid) {

    if (is_numeric($userid) && is_numeric($inc)) {

        $udata = getUser($userid);
        $curStorage = $udata['storage_used'];
        $newStorage = $curStorage + $inc;

        if ($newStorage < 0) {
            $newStorage = 0;
        }

        $udata['storage_used'] = $newStorage;

        good_query("UPDATE users SET storage_used=$newStorage WHERE id=$userid");

        // query memcached
        $key = md5('uid-' . $userid);
        setMemKey($key, $udata, TRUE, 86400); // Store the result of the query for a day

    // is_numeric check
    }
    
}
?>