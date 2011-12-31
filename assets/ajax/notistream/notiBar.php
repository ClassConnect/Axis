<?php
require_once('../../../core/inc/coreInc.php');
requireSession();

// grab latest 20 notifications
$notifications = getNotifications($user_id, 20);


if (empty($notifications)) {
	echo '<li><p>You have no notifications.</p></li>';
	
} else {
	echo '<li class="view"><a href="#" onClick="openBox(\'/app/core/ajax/notistream/fullFeed.cc\', 400); $(\'#alertpanel a:first\').click(); return false">View All</a></li> ';
}

            	
foreach ($notifications as $notification) {
	// if it has not been viewed, changed bg color
	if ($notification['viewed'] == 1) {
		$style = ' style="background: #ededed; border-top:1px solid #ccc"';
	} else {
		$style = '';
	}

	$date_sent = date('l, F jS', strtotime($notification['sent_at']));
	$time_sent = date('g:i A', strtotime($notification['sent_at']));

	echo '<li' . $style . '><p>' . reverse_htmlentities($notification['content']) . '<br /><span style="float:right; font-size:9px; color:#999">' . $date_sent . '  at ' . $time_sent . '</span></p></li>';
	
}


// clear notifications
clearNotifications($user_id);
?>