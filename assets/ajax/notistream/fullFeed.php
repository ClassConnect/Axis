<?php
require_once('../../../core/inc/coreInc.php');
requireSession();

// grab latest 20 notifications
$notifications = getNotifications($user_id, 2000);

echo '<img src="' . $imgServer . 'gen/cross.png" style="position:absolute;margin-top:-13px; margin-left:380px; border:3px solid #999; background:#eee; padding:5px; cursor:pointer" onClick="closeBox();" />
<div class="headTitle"><div>Your Notifications</div></div>
<div style="overflow-y:scroll; width:400px; height:330px">';

foreach ($notifications as $notification) {
	// if it has not been viewed, changed bg color
	if ($notification['viewed'] == 1) {
		$style = ' style="background: #ededed; border-top:1px solid #ccc"';
	} else {
		$style = ' style="border-top:1px solid #ccc; margin-bottom:20px"';
	}

	$date_sent = date('l, F jS', strtotime($notification['sent_at']));
	$time_sent = date('g:i A', strtotime($notification['sent_at']));

	echo '<div' . $style . '><p>' . reverse_htmlentities($notification['content']) . '<br /><span style="float:right; font-size:9px; color:#999">' . $date_sent . '  at ' . $time_sent . '</span></p></div>';
	
}

echo '</div>';
// clear notifications
clearNotifications($user_id);
?>