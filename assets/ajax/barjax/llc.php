<?php
require_once('../../inc/coreInc.php');
$lectures = getOpenLLs($user_id, $myClasses);

foreach ($lectures as $lec) {
    $time_start = date("g:ia", strtotime($lec['created']));
    foreach ($myClasses as $classer) {
        if ($classer['id'] == $lec['classID']) {
            $className = $classer['name'];
        }
    }
    echo '<div style="border-bottom:1px solid #ccc; padding-left:10px; padding-top:5px; padding-bottom:5px">
        <span style="font-size:16px; font-weight:bolder"><a href="/app/livelecture/Presenter/index.php?lid=' . $lec['lid'] . '">' . $lec['title'] . '</a></span><br />
            <span style="font-size:10px; color:#666">started at ' . $time_start . ' by ' . $className . '</span>
            </div>';
}

if (empty($lectures)) {
        echo '<div style="padding:10px">
        <span style="font-size:14px; font-weight:bolder; color:#666">Oops!</span><br />
        <span style="font-size:10px; color:#666">We can\'t find any open LiveLectures.</span>
            </div>';
}

?>