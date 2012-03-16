<?php
$totTeachers = count(good_query_table("SELECT * FROM users WHERE level = 3 OR level = 0"));
$today = strtotime(date("d-m-Y"));

$totToday = count(good_query_table("SELECT * FROM users WHERE reg_date >= $today AND (level = 3 OR level = 0)"));

echo $totToday . '<br /><br />' . ($totTeachers - 2000) . '<br /><br />' . $totTeachers;

?>