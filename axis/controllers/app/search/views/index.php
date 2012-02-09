<?php
/*
$keyQuery = strip_tags($_GET['query']);
$resultSet = performSearch($keyQuery);
echo genResults($resultSet);
*/

appHeader('Search', '');
?>
<div id="mainBlocker" class="content">

	<div id="leftBox">
	hello<br /><br /><br />what's up son
	</div>

	<div id="mainBox" style="min-height:500px;height:auto !important;">
	<?php
	$keyQuery = strip_tags($_GET['query']);
$resultSet = performSearch($keyQuery);
echo genResults($resultSet);
?>
	</div>

	<div style="clear:both"></div>
</div>

<?php
appFooter();
?>