<?php
$secID = $this->Command->Parameters[3];
if (authSection($secID)) {
	// get the students for this section
	$students = getSectionStudents($secID);

	if (empty($students)) {
		echo '<p style="color:#666;text-align:center;padding:10px">There are no students enrolled in this class...yet.</p>';
	} else {
		foreach ($students as $stud) {
			echo '<div style="padding:5px;padding-left:10px;border-bottom:1px solid #eee; color:#454545;font-size:11px;font-weight:bolder;">
<img src="' . iconServer() . '50_' . dispUser($stud['student_id'], 'prof_icon') . '" style="width:18px;float:left;margin-right:10px;" />
			' . dispUser($stud['student_id'], 'first_name') . ' ' . dispUser($stud['student_id'], 'last_name') . '</div>';
		}	
	}
?>
<div style="margin:10px;margin-bottom:45px">
<button style="float:right" type="reset" class="btn" onClick="closeBox();">Close</button>
</div>
<div style="clear:both"></div>
<?php
}
?>