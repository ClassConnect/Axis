<?php
// create error handler
function customError($errno, $errstr)
  {
  //echo "<b>Error:</b> [$errno] $errstr";
  }

//set error handler
set_error_handler("customError");

session_start();

// GLOBAL SITE VARIABLES //
require_once('site/serverConfig.php');
/// Set session variables for easy access <-- hehehehehehehehe
require_once('user/var_set.php');
// Include DB connection and query functions
require_once('data/connect.php');
// Include core site functions
require_once('func/core.php');
// get cloud files ext
require_once('site/cloudFiles/cloudfiles.php');
// get scribd ext
require_once('site/scribd/scribd.php');
// pull in the thumbnailer
require_once('site/thumbnail/ThumbLib.inc.php');
// get php mailer functions
require_once('site/mail/swift_required.php');


// elastica autoload stuff
function elastica_autoload($class) {
    if (substr($class, 0, 9) == 'Elastica_') {
        $file = str_replace('_', '/', $class) . '.php';
        require_once('site/elastic/lib/' . $file);
    }
}

spl_autoload_register('elastica_autoload');



// signup referencer. store this in the session for use when we signup
if (isset($_GET['uref'])) {
	$_SESSION['uref'] = $_GET['uref'];
}

// initialize the wizard
if (isset($_GET['iwiz']) && checkSession()) {
	initWizard();
}


// check if this person is trying to log in
$loginError = false;

// check if this user submitted the login form
if (isset($_POST['logsubmit']) && !checkSession()) {

    if (!isset($_POST['identity'])) {
    $_POST['identity'] == '';
    }
    if (!isset($_POST['pass'])) {
        $_POST['pass'] == '';
    }
    $attempt = initLogin($_POST['identity'], $_POST['pass']);

    if ($attempt != false) {
        // do nothing, we're in
    } else {
        $loginError = true;
    }

}
?>