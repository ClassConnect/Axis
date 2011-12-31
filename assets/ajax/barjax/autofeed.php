<?php
require_once('../../inc/coreInc.php');

$fillArray = array();
// start JSON string
echo '[';
// load differently for students & teachers
$fellows = getFellows();

$start = 1;
foreach ($fellows as $fellow) {
    echo '{"key": "' . $fellow['first_name'] . ' ' . $fellow['last_name'] . '", "value": "' . $fellow['id'] . '"}';
    
    if ($start != count($fellows)) {
        echo ', ';
    }
    $start++;
}

// end JSON string
echo ']';
?>