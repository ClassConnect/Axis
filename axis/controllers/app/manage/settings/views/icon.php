<?php
  
$ext = strtolower(substr($_FILES["file"]["name"], strrpos($_FILES["file"]["name"], '.') + 1));
$imgTypes = array('jpg', 'jpeg', 'png', 'gif', 'bmp');

$encname = sha1($_FILES["file"]["name"] . uniqid()) . '.' . $ext;

// if it's an image, do the upload
if (in_array($ext, $imgTypes)) {
	move_uploaded_file($_FILES["file"]["tmp_name"], 'swap/' . $encname);
	$thumb = PhpThumbFactory::create('swap/' . $encname);
	$thumb->resize(210, 700);
	$thumb->save('swap/210_' . $encname);
	$thumb->adaptiveResize(50, 50);
	$thumb->save('swap/50_' . $encname);

	// cloud info
	$cloudUser = "ericmsimons"; // username
	$cloudKey = "be8dfe902754b75852bfceb3b5c9e2bb"; // api key


		// Connect to Rackspace
	$auth = new CF_Authentication($cloudUser, $cloudKey);
	$auth->authenticate();
	$conn = new CF_Connection($auth);

	// Get the container we want to use
	$container = $conn->get_container('axis_icons');
	// upload the 210
	$object = $container->create_object('210_' . $encname);
	$object->load_from_filename('swap/210_' . $encname);
	// upload the 50
	$object = $container->create_object('50_' . $encname);
	$object->load_from_filename('swap/50_' . $encname);
	// upload the original
	$object = $container->create_object($encname);
	$object->load_from_filename('swap/' . $encname);

	unlink('swap/210_' . $encname);
	unlink('swap/50_' . $encname);
	unlink('swap/' . $encname);

	// if this has courses
	if (($_POST['courses'] || $_POST['refCour']) && user('level') == 3) {
		foreach ($_POST['courses'] as $courseID) {
			if (authSection($courseID)) {
				good_query("UPDATE course_sections SET icon = '$encname' WHERE section_id = $courseID");
				getSection($courseID, true);
			}
		}
		header('location:/app/course/' . $_POST['refCour']);

		
	} else {
		$uid = user('id');
		good_query("UPDATE users SET prof_icon = '$encname' WHERE id = $uid");
		getUser($uid, true);
		if ($_GET['redir']) {
			header('location:' . $_GET['redir']);
		} else {
			header('location:/app/manage/settings');
		}
	}
} else {
	header('location:/app/manage/settings');	
}

?>