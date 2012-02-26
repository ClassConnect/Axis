<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?= $page_title; ?></title>
    <meta name="description" content="Free lesson plans & collaboration tools.">
    <meta name="author" content="ClassConnect Inc.">
    <!--
    The secret is not the source code.
    --> 
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<?php
global $developerMode;
if ($developerMode == true) {
?>
    <script src="/assets/app/js/jquery.js"></script>
    <script src="/assets/app/ui/ui.js"></script>
    <script src="/assets/app/js/dropdown.js"></script> 
    <script src="/assets/app/js/twipsy.js"></script> 
    <script src="/assets/app/js/tabs.js"></script>
    <script src="/assets/app/js/facebox.js"></script> 
    <script src="/assets/app/js/formControl.js"></script>
    <script src="/assets/app/js/pjax.js"></script>
    <script src="/assets/app/js/placer.js"></script>
    <script src="/assets/public/js/gen.js"></script>
    <link href="/assets/app/main.css" rel="stylesheet">
    <link href="/assets/app/public.css" rel="stylesheet">
    <link href="/assets/app/ui/ui.css" rel="stylesheet">


<?php
} else {
?>
    <script src="/assets/app/production/production.js"></script>
    <script src="/assets/public/js/gen.js"></script>
    <link href="/assets/app/production/main.css" rel="stylesheet">
    <link href="/assets/app/production/public.css" rel="stylesheet">
    <link href="/assets/app/ui/ui-production.css" rel="stylesheet">
<?php
}
?>

    <script type="text/javascript">
    $(document).ready(function() { 
          $('input, textarea').placeholder();
    });
    </script>

    <!-- Le fav and touch icons
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">-->
  </head>

  <body>

<?php
if ($showHead) {
?>

<div class="splashHead">
  <div class="container">


    <div class="splashHeadRight">
      <div class="loginbox">
      <form method="POST" action="/app/">
        <input type="text" class="uninput" name="identity" placeholder="Email / Username" /><br />
        <input type="password" class="passinput" name="pass" placeholder="Password" /><br />
        <input type="hidden" name="logsubmit" value="submitted" />
        <button class="btn" style="float:right;margin-top:5px">Login</button>
        <div style="margin-top:10px;margin-left:5px">
          <a href="/app/resetpassword">Forgot password?</a>
        </div>
        </form>
      </div>
    </div>


    <div class="splashHeadLeft">

      <a href="/"><img src="/assets/public/mainlogo.png" class="logo" /></a>

      <div class="actionbtns" style="margin-top:20px">
        <button class="btn success" onclick="jQuery.facebox({ ajax: '/app/signup/teacher' });">
        Sign up now - it's Free!
        </button>
      </div>


    </div>

<?php
}
?>