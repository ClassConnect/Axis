<?php
require_once('../../inc/coreInc.php');
killSession();
echo '<div class="headTitle"><img src="' . $imgServer . 'header/logout.png" style="margin-right:5px; margin-top:5px" /><div>You Have Been Logged Out</div></div>
<div id="content" style="margin:5px; font-size:14px">
' . urldecode(str_replace('_dq_', '"', $_GET['data'])) . '
</div>

<div style="margin-bottom:5px; margin-top:5px; float:right">
<a href="login.cc"class="button">Continue</a>
</div>';
?>