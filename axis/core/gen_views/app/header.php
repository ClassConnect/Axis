<!DOCTYPE html> 
<html lang="en"> 
  <head> 
    <meta charset="utf-8"> 
    <title><?= $pageTitle; ?></title> 
    <meta name="description" content="ClassConnect Inc."> 
    <meta name="author" content="ClassConnect Inc."> 
    <!--
    The secret is not the source code.
    --> 
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
    <script src="/assets/app/js/elastic.js"></script>
    <link href="/assets/app/main.css" rel="stylesheet">
    <link href="/assets/app/app.css" rel="stylesheet">
    <link href="/assets/app/ui/ui.css" rel="stylesheet">

<?php
} else {
?>
    <script src="/assets/app/production/production.js"></script>
    <link href="/assets/app/production/main.css" rel="stylesheet">
    <link href="/assets/app/production/app.css" rel="stylesheet">
    <link href="/assets/app/ui/ui-production.css" rel="stylesheet">
<?php
}


if ((checkSession() && user('level') == 3) || !checkSession()) {
  if ($setTab == 5) {
    $searchVal = 'value="' .  htmlentities($_GET['query']) . '"';
  }
  $searchPanel = '<form class="pull-left" action="/app/search/" id="searchboxForm" method="GET" style="width:160px;margin-left:15px;">

            <span style="position:absolute;top:5px;left:6px;display:block;width:20px;height:20px">
              <img src="/assets/app/img/nav/search.png" style="width:20px;height:20px" />
            </span>

            <input type="text" name="query" ' . $searchVal . ' class="searchInput" style="width:70px" onFocus="$(this).width(90);$(this).animate({
    width: 200
  }, 300, function() {});" onblur="if ($(this).width() == 200) { $(this).width(180);$(this).animate({
    width: 70
  }, 300, function() {}); }" placeholder="Search">

          </form>';
}
?>

    <script type="text/javascript">
    $(document).ready(function() { 
          $('.topbar').dropdown();
          $('input, textarea').placeholder();
    });
    </script>
    <?= $insertJS; ?>
 
    <!-- Le fav and touch icons
    <link rel="shortcut icon" href="images/favicon.ico"> 
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png"> 
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png"> 
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png"> --> 
  </head> 
 
  <body>
  <?php
  // aight, this is where we determine what topbar to output

  // if there is no session, show blank w/ login
  if (!checkSession()) {
    ?>
<div id="mainNavBar" class="topbar">
      <div class="topbar-inner">
        <div class="container">
          <a class="brand" href="/"><img src="/assets/app/img/logo.png" style="float:left;height:16px;margin-top:3px" /></a> 

          <?= $searchPanel; ?>

          <ul class="nav secondary-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle"><span style="font-size:10px">Have an account?</span>&nbsp;&nbsp;<span style="font-weight:bolder;color:#fff">Login</span></a>
              <ul class="dropdown-menu ddPersist">
              <form method="POST" action="" style="padding-left:10px;padding-right:10px">
                <div style="color:#ddd;margin-bottom:4px;font-size:11px"><?= say('Email / Username'); ?></div>
                <input id="idSecond" type="text" name="identity" style="width:180px">
                <div style="color:#ddd;margin-bottom:4px;margin-top:8px;font-size:11px"><?= say('Password'); ?></div>
                <input type="password" name="pass" style="width:180px">
                <input type="hidden" name="logsubmit" value="submitted" />
                <button class="btn pull-right" type="submit" style="font-size:11px;margin-top:10px"> 
                <?= say('Login'); ?>
                </button>
              </form>
              </ul>
            </li>
          </ul>
        </div>
      </div><!-- /topbar-inner -->
    </div>

    <?php
  } else {
  ?>
 
    <div id="mainNavBar" class="topbar"> 
      <div class="fill"> 
        <div class="container"> 
          <a class="brand" href="/app/latest/"><img src="/assets/app/img/logo.png" style="float:left;height:16px;margin-top:3px" /></a> 
          <ul class="nav"> 
          <!-- <span style="padding-top:4px" class="label important">&nbsp;1&nbsp;</span>&nbsp;&nbsp; -->
            <li<?php if ($setTab == 1) { echo ' class="active"'; } ?>><a href="/app/latest/">
            Latest
            <?php
$notis = getNotis();
if ($notis['data'] != 0) {
  echo '&nbsp;<span style="padding-top:4px" class="label important">&nbsp;' . $notis['data'] . '&nbsp;</span>';
}
?>
            </a></li> 

            <li id="filebox-tab"<?php if ($setTab == 2) { echo ' class="active"'; } ?>><a href="/app/filebox/">My Files</a></li> 
            <?php if (getSections()) { ?>
            <li class="dropdown<?php if ($setTab == 3) { echo ' active'; } ?>">
              <a href="#" class="dropdown-toggle">Apps</a>
              <ul class="dropdown-menu">
                <li><a href="/app/calendar">Calendar</a></li>
                <?php if (user('id') <= 2700) { ?>
                <li><a href="/app/livelecture">LiveLecture</a></li>
                <li><a href="/app/docs">Docs</a></li>
                <?php } ?>
              </ul>
            </li>
            <li id="courses-tab" class="dropdown<?php if ($setTab == 4) { echo ' active'; } ?>">
              <a href="#" class="dropdown-toggle">Courses</a>
              <ul class="dropdown-menu">
                <?= buildCourseNav(); ?>
                <li class="divider"></li>
                <li id="manage-courses-tab"><a href="/app/manage/courses" style="font-size:10px">add / manage courses</a></li>
              </ul>
            </li>
            <?php } ?>
            
            <?= $searchPanel; ?>

          </ul> 
          <ul class="nav secondary-nav">
          <!--
          <li><a href="#"><img src="/assets/app/img/nav/mail.png" style="height:25px;float:left;margin-top:-4px" />&nbsp;<span style="padding-top:4px" class="label important">&nbsp;3&nbsp;</span></a></li>

          <li><a href="#">Name Here&nbsp;&nbsp;<span style="padding-top:4px" class="label karma">&nbsp;12&nbsp;</span></a></li>-->
          <li id="meProfTab" style="max-width:210px;height:35px;overflow:hidden"><a href="<?= userURL(user('id')); ?>"><img src="<?= iconServer(); ?>50_<?= dispUser(user('id'), 'prof_icon'); ?>" class="topbarMiniprof" /> <?= dispUser(user('id'), 'first_name'); ?> <?= dispUser(user('id'), 'last_name'); ?></a></li>

            <li class="dropdown imgoverride">
              <a href="#" class="dropdown-toggle"><img src="/assets/app/img/nav/settings.png" style="height:25px;float:left;margin-top:-3px" /></a>
              <ul class="dropdown-menu">
                <li><a href="/app/manage/settings">Settings</a></li>
                <li class="divider"></li>
                <li><a href="/app/logout">Logout</a></li>
              </ul>
            </li>
          </ul>
        </div> 
      </div> 
    </div>

    <?php
  }
  ?>
     <div id="mainContent" class="container"> 