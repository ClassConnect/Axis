<?php
require_once('../../inc/coreInc.php');
echo '<div class="headTitle"><img src="' . $imgServer . 'gen/warning.png" style="margin-right:5px; margin-top:3px" /><div style="font-weight:bolder">Warning</div></div>
<div id="content" style="margin:5px; font-size:14px">
' . urldecode(str_replace('_dq_', '"', $_GET['data'])) . '
</div>

<div style="margin-bottom:5px; margin-top:5px; float:right">
<button class="button" type="submit" onClick="closeBox()"><img src="' . $imgServer . 'gen/cross.png" />Close</a>
</div>';
?>