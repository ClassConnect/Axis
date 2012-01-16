<?php
// create a course
function createCourse($title, $grade, $subject, $uid) {

	if (!isset($uid)) {
		$uid = user('id');
	}

	global $dbc;

	// check for a title
	if (isFilled($title) && strlen($title) > 1) {
		$title = escape($title);
	} else {
		$errors[] = say('You forgot to enter a course title.');
	}

	// check for a grade level
	if (isFilled($grade) && strlen($grade) >= 1 && $grade != 'default') {
		$grade = escape($grade);
	} else {
		$errors[] = say('You forgot to enter a grade level.');
	}

	// check for a subject
	if (isFilled($subject) && strlen($subject) > 1 && $subject != 'default') {
		$subject = escape($subject);
	} else {
		$errors[] = say('You forgot to enter a subject.');
	}

	$now = date("U");

	if(empty($errors)) {

		$insertCourse = @mysqli_query($dbc, "INSERT INTO courses (teach_id, title, grade_level, subject, start_date, status) VALUES ('$uid', '$title', '$grade', '$subject', '$now', '1')");

		$course_id = $dbc->insert_id;
		getCourses($uid, 1);
		return $course_id;

	} else {
		return $errors;
	}
}




// add a section
function addSection($courseID, $title, $uid) {
	global $dbc;

	if (!isset($uid)) {
		$uid = user('id');
	}

	// check for a title
	if (isFilled($title) && strlen($title) > 1) {
		$title = escape($title);
	} else {
		$errors[] = say('You forgot to enter a course title.');
	}

	if (checkCourseOwner($courseID, $uid)) {
		// do nothing
	} else {
		$errors[] = say('You don\'t have permission to add a section.');
	}

	$chash = genChash($title);

	$now = date("U");


	if (empty($errors)) {
		$insertSec = @mysqli_query($dbc, "INSERT INTO course_sections (course_link, title, code, sec_start) VALUES ('$courseID', '$title', '$chash', '$now')");

		$secID = $dbc->insert_id;

		addTeachToSection($secID, $uid, $uid);

		return $secID;
	} else {
		return $errors;
	}
}



// add teacher to section
function addTeachToSection($secID, $teachID, $uid) {
	if (!isset($uid)) {
		$uid = user('id');
	}

	$secData = getSection($secID);
	$courseID = $secData['course_link'];
	
	if (checkCourseOwner($courseID, $uid)) {
		$now = date("U");
		good_query("INSERT INTO course_teachers (teach_id, link_sec_id, add_date) VALUES ('$teachID', '$secID', '$now')");
	}

	getSections($teachID, 1);

	return 1;
}




// add student to section
function addStudToSection($secID, $uid) {
	if (!isset($uid)) {
		$uid = user('id');
	}

	if (authSection($secID, $uid) == false) {

		$secData = getSection($secID);
		$courseID = $secData['course_link'];
		
		$now = date("U");
		good_query("INSERT INTO course_students (student_id, link_sec_id, added_on) VALUES ('$uid', '$secID', '$now')");

		getSections($studID, 1);
		getSectionStudents($secID, 1);
		
	}

	return 1;
}


// function to add normally
function studAddCourse($code, $uid) {
	if (!isset($uid)) {
		$uid = user('id');
	}

	$codeTest = authCourseCode($code);
	if ($codeTest != false) {
		addStudToSection($codeTest, $uid);
		return 1;
	} else {
		return array(say("The code you entered is invalid."));
	}
	
}



// verify a course owner
function checkCourseOwner($courseID, $uid) {

	if (!isset($uid)) {
		$uid = user('id');
	}

	$courseData = getCourse($courseID);

	if ($courseData['teach_id'] == $uid) {
		return true;
	} else {
		return false;
	}
	
}



function authCourseCode($code) {
	$test = good_query_assoc("SELECT * FROM course_sections WHERE code = '$code' LIMIT 1");
	if ($test != false && isFilled($code)) {
		return $test['section_id'];
	} else {
		return false;
	}
}





























// archive a section
function archiveSection($secID, $uid) {

	if (!isset($uid)) {
		$uid = user('id');
	}

	$now = date("U");

	$secData = getSection($secID);
	$courseID = $secData['course_link'];

	if (checkCourseOwner($courseID, $uid)) {

		$update = good_query("UPDATE course_sections SET status='2', sec_end='$now', code='' WHERE section_id = $secID LIMIT 1");
		// reset cache
		getSection($secID, 1);
		// reset student & teacher cache
		$students = getSectionStudents($secID);
		$teachers = getSectionTeachers($secID);
		//iterate
		foreach ($students as $student) {
			$key = md5('sections-' . $student['student_id']);
			delMemKey($key);
		}
		//iterate
		foreach ($teachers as $teach) {
			$key = md5('sections-' . $teach['teach_id']);
			delMemKey($key);
		}
		return 1;

	} else {
		return array(say('You don\'t have permission to archive this section.'));
	}
}



// archive an entire course
function archiveCourse($courseID, $uid) {
	if (!isset($uid)) {
		$uid = user('id');
	}

	$now = date("U");

	if (checkCourseOwner($courseID, $uid)) {
		// foreach section loop goes here
		$secs = good_query_table("SELECT * FROM course_sections WHERE course_link = $courseID AND status='1'");

		foreach ($secs as $sec) {
			archiveSection($sec['section_id']);
		}

		$update2 = good_query("UPDATE courses SET status='2', end_date='$now' WHERE course_id = $courseID LIMIT 1");
		// reset cache
		getCourse($courseID, 1);
		getCourses($uid, 1);
		getSections($uid, 1);


		return 1;

	} else {
		return array(say('You don\'t have permission to archive this section.'));
	}
}



// update section's hash code
function updateSecHash($secID, $uid) {
	if (!isset($uid)) {
		$uid = user('id');
	}

	$secData = getSection($secID);
	$courseID = $secData['course_link'];

	$newCode = genChash($secID);

	if (checkCourseOwner($courseID, $uid)) {

		$update = good_query("UPDATE course_sections SET code='$newCode' WHERE section_id = $secID LIMIT 1");
		// reset cache
		getSection($secID, 1);
		return $newCode;

	}
}

// update a given secitons
function updateSection($secID, $title, $uid) {
	if (!isset($uid)) {
		$uid = user('id');
	}

	$secData = getSection($secID);
	$courseID = $secData['course_link'];


	// check for a title
	if (isFilled($title) && strlen($title) > 1) {
		$title = escape($title);
	} else {
		$errors[] = say('You forgot to enter a course title.');
	}

	if (checkCourseOwner($courseID, $uid)) {
		// do nothing
	} else {
		$errors[] = say('You don\'t have permission to edit this section.');
	}


	if (empty($errors)) {
		$update = good_query("UPDATE course_sections SET title='$title' WHERE section_id = $secID LIMIT 1");
		// reset cache
		getSection($secID, 1);
		return 1;
	} else {
		return $errors;
	}

}



// update a course
function updateCourse($courseID, $title, $grade, $subject, $uid) {

	if (!isset($uid)) {
		$uid = user('id');
	}

	global $dbc;

	// check for a title
	if (isFilled($title) && strlen($title) > 1) {
		$title = escape($title);
	} else {
		$errors[] = say('You forgot to enter a course title.');
	}

	// check for a grade level
	if (isFilled($grade) && strlen($grade) >= 1 && $grade != 'default') {
		$grade = escape($grade);
	} else {
		$errors[] = say('You forgot to enter a grade level.');
	}

	// check for a subject
	if (isFilled($subject) && strlen($subject) > 1 && $subject != 'default') {
		$subject = escape($subject);
	} else {
		$errors[] = say('You forgot to enter a subject.');
	}

	if (checkCourseOwner($courseID, $uid)) {
		// do nothing
	} else {
		$errors[] = say('You don\'t have permission to edit this course.');
	}


	if(empty($errors)) {

		$update = good_query("UPDATE courses SET title='$title', grade_level='$grade', subject='$subject' WHERE course_id = $courseID LIMIT 1");
		// reset cache
		getCourse($courseID, 1);
		return 1;

	} else {
		return $errors;
	}
}


// generate class hash
function genChash($name) {
	$cleared = false;

	$chash = substr(SHA1($name . uniqid() . rand(1, 9999)),0,10);

	$test = authCourseCode($chash);
	if ($test != false) {
		while ($cleared == false) {
			// we had a hit
			$chash = substr(SHA1(rand(9999, 9999999) . $name . uniqid()),0,10);

			$temp = authCourseCode($chash);

			if ($temp == false) {
				$cleared = true;
			}


			
		}
		
	}
	return $chash;
}













function dispTeachCourseView() {
	$result = '';
	$courses = getMyCourses();
	foreach ($courses as $course) {
		$result .= '<div class="courseTitle"><strong>' . $course['title'] . '</strong> <img class="arcTog hovTip" data-original-title="Archive this course" src="/assets/app/img/manage/archive.png" onClick="jQuery.facebox({ 
    ajax: \'/app/manage/courses/archive?cid=' . $course['course_id'] . '\'
  });
  return false;" />
<img src="/assets/app/img/manage/edit.png" class="keyTog hovTip" data-original-title="Edit this course" onClick="jQuery.facebox({ ajax: \'/app/manage/courses/edit/course?cid=' . $course['course_id'] . '\' });" />
  </div>';
		$result .= '<div class="courseEl">';
		
		foreach ($course['sections'] as $section) {
			$totStuds = count(getSectionStudents($section['section_id']));
			$result .= '<div class="sectionWrapper">
                <div style="margin-left:10px;font-size:16px;padding:10px">
                  » <a href="/app/course/' . $section['section_id'] . '">' . $section['title'] . '</a>

                  <img class="arcTog hovTip" data-original-title="Archive this section" src="/assets/app/img/manage/archive.png" onClick="jQuery.facebox({ 
    ajax: \'/app/manage/courses/archive?sid=' . $section['section_id'] . '\'
  });
  return false;" />

<img src="/assets/app/img/manage/edit.png" class="keyTog hovTip" data-original-title="Edit this section" onClick="jQuery.facebox({ ajax: \'/app/manage/courses/edit/section?sid=' . $section['section_id'] . '\' });" />

  <img src="/assets/app/img/manage/key.png" class="keyTog hovTip" data-original-title="View this section\'s code" onClick="jQuery.facebox({ div: \'#' . $section['section_id'] . 'code\' });" />

  <div style="float:right;margin-right:10px"><a href="#" style="font-size:12px" onClick="jQuery.facebox({ 
    ajax: \'/app/manage/courses/edit/students/' . $section['section_id'] . '\'
  });
  return false;">' . $totStuds . ' students</a></div>


  <div style="display:none" id="' . $section['section_id'] . 'code">
  <div style="font-size:14px;margin-left:10px;text-align:center"><strong>"' . $section['title'] . '"</strong> access code:</div>
  	<div class="alert-message warning" style="margin-left:130px;width:100px;font-weight:bolder;text-align:center;margin-top:5px"><img src="/assets/app/img/manage/key.png" style="height:16px;float:left;margin-right:6px;margin-top:1px" /><span class="codeUpdate">' . $section['code'] . '</span></div>
  	<div style="margin-left:20px;margin-right:10px;margin-bottom:10px;color:#666;font-size:11px">Your students can enroll in your course using this secret code. You can reset the code to prevent users from enrolling.</div>
  	<div id="fbActions" class="actions" style="margin-bottom:0px">
    <div style="float:right">
    <div style="display:none" class="secID">' . $section['section_id'] . '</div>
      <button type="submit" class="btn primary" onClick="resetCode(this);"><img src="/assets/app/img/manage/reset.png" style="height:16px;float:left;margin-right:6px;margin-top:1px;margin-bottom:-1px" /> Reset Code</button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
  </div>
                </div>
              </div>';
		}



		$result .= '<div class="sectionAddBut" onClick="jQuery.facebox({ 
		ajax: \'/app/manage/courses/add/section?cid=' . $course['course_id'] . '\'
		});
		return false;">
		    Add a new section for this course
		</div>
		</div>';
	}

	if (empty($result)) {
		$result = '<div style="text-align:center;margin-top:25px;margin-bottom:120px;font-size:14px">You don\'t have any courses...yet.</div>';
	}

	return $result;
}





function dispStudCourseView() {
	$result = '';
	$courses = getMyCourses();
	foreach ($courses as $course) {
		$result .= '<div class="courseTitle"><strong>' . $course['title'] . '</strong></div>';
		$result .= '<div class="courseEl">';
		
		foreach ($course['sections'] as $section) {
			$result .= '<div class="sectionWrapper">
                <div style="margin-left:10px;font-size:16px;padding:10px">
                  » <a href="/app/course/' . $section['section_id'] . '">' . $section['title'] . '</a>
                </div>
              </div>';
		}

		$result .= '</div>';
	}

	if (empty($result)) {
		$result = '<div style="text-align:center;margin-top:25px;margin-bottom:120px;font-size:14px">You don\'t have any courses...yet.</div>';
	}

	return $result;
}





?>