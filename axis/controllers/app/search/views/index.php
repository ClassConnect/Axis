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
		<div class="btn searchTabber" onclick="$(this).animate({ height: 200 }, 300, function() {});">
			<img src="/assets/app/img/temp/course.png" class="icimg" /> Grade Level & Subject

			<div style="width:188px;padding: 3px 3px 3px 5px; margin-top:3px" class="label">High School</div>
			<div style="width:188px;padding: 3px 3px 3px 5px; margin-top:3px" class="label">Math</div>
		</div>

		<div class="btn searchTabber">
			<img src="/assets/app/img/temp/standard.png" class="icimg" /> Common Core
		</div>

		<div class="btn searchTabber">
			<img src="/assets/app/img/box/copy.png" class="icimg" /> File Type
		</div>

		<div class="btn searchTabber">
			<img src="/assets/app/img/temp/curric.png" class="icimg" /> Instructional Type
		</div>

	</div>

	<div id="mainBox" style="min-height:500px;height:auto !important">
	<?php
	$keyQuery = $_GET['query'];
$resultSet = performSearch($keyQuery);
echo genResults($resultSet);
?>
	</div>

	<div style="clear:both"></div>
</div>

<?php
appFooter();
?>