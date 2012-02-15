<?php
if (user('level') == 1) {
	showError();
	exit();
}


$keyQuery = $_GET['query'];
$resultSet = performSearch($keyQuery);
$genQuery = genResults($resultSet);

if (isset($_GET['_pjax'])) {
	echo $genQuery;
	exit();
}


// prepare our preset filters (if any)
$gradeArray = explode(',', $_GET['grades']);
$subjArray = explode(',', $_GET['subjs']);
$commonArray = explode(',', $_GET['commonstand']);
$filesArray = explode(',', $_GET['filetypes']);
$instArray = explode(',', $_GET['instypes']);


appHeader('Search', '<script type="text/javascript" src="/assets/app/js/search/main.js"></script><link href="/assets/app/filebox.css" rel="stylesheet">', 5);
?>
<div id="mainBlocker" class="content">

	<div id="leftBox" class="searchLeftBkg">
		<div class="searchTabber">
			<img src="/assets/app/img/temp/course.png" class="icimg" /> Grade Level & Subject

			<div class="selbtndef preselSty">
				<div class="labelText">Choose grade levels</div>

				<div class="labelPanel">
					<div class="tokenManifest" style="display:none">
					grades
					</div>


					<?php
          $grades = 'Pre-Kindergarten,Lower Elementary,Upper Elementary,Middle School,High School,College';
          $inGrade = explode(",", $grades);
          foreach ($inGrade as $grade) {
            if (in_array($grade, $gradeArray)) {
              $attr = 'checked="checked"';
            } else {
              $attr = '';
            }
            echo '<div class="optListItem">
            <input value="' . $grade . '" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" ' . $attr . ' /> ' . $grade . '
            </div>';
          }


          ?>

				</div>
			</div>

			<div id="grades"><?= genFilterBtns($gradeArray); ?></div>

			<div class="selbtndef preselSty">
				<div class="labelText">Choose subjects</div>
				
				<div class="labelPanel">
					<div class="tokenManifest" style="display:none">
					subjs
					</div>


					<?php
          $subjects = 'Math,Science,Social Studies,English / Language Arts,Foreign Language,Music,Physical Education,Health,Dramatic Arts,Visual Arts,Special Education,Technology and Engineering';
          $inSub = explode(",", $subjects);
          foreach ($inSub as $subject) {
            if (in_array($subject, $subjArray)) {
              $attr = 'checked="checked"';
            } else {
              $attr = '';
            }
            echo '<div class="optListItem">
            <input value="' . $subject . '" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" ' . $attr . ' /> ' . $subject . '
            </div>';
          }


          ?>




				</div>
			</div>

			<div id="subjs"><?= genFilterBtns($subjArray); ?></div>

		</div>

		<div class="searchTabber">
			<img src="/assets/app/img/temp/standard.png" class="icimg" /> Common Core


			<div class="selbtndef preselSty">
				<div class="labelText">Choose standards</div>

			<div class="labelPanel" style="height:200px;overflow:auto">
					<div class="tokenManifest" style="display:none">
					commonstand
					</div>

					<div id="commonSwapper" style="font-size:11px">
					<?php require_once('commoncore.php'); ?>
					</div>
					
				</div>

			</div>

			<div id="commonstand"><?= genFilterBtns($commonArray); ?></div>

		</div>

		<div class="searchTabber">
			<img src="/assets/app/img/box/copy.png" class="icimg" /> File Type

			<div class="selbtndef preselSty">
				<div class="labelText">Choose file types</div>

			<div class="labelPanel">
					<div class="tokenManifest" style="display:none">
					filetypes
					</div>


					<?php
          $filetypes = 'Website,Embed Code,Document,Presentation,Spreadsheet,Video,Image,Audio';
          $inType = explode(",", $filetypes);
          foreach ($inType as $file) {
            if (in_array($file, $filesArray)) {
              $attr = 'checked="checked"';
            } else {
              $attr = '';
            }
            echo '<div class="optListItem">
            <input value="' . $file . '" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" ' . $attr . ' /> ' . $file . '
            </div>';
          }


          ?>

				</div>

			</div>

			<div id="filetypes"><?= genFilterBtns($filesArray); ?></div>

		</div>

		<div class="searchTabber">
			<img src="/assets/app/img/temp/curric.png" class="icimg" /> Instructional Type

			<div class="selbtndef preselSty">
				<div class="labelText">Choose instructional types</div>

			<div class="labelPanel">
					<div class="tokenManifest" style="display:none">
					instypes
					</div>


					<?php
          $instypes = 'Activity,Assessment,Lab,Lesson Plan,Practice,Project';
          $inType = explode(",", $instypes);
          foreach ($inType as $inst) {
            if (in_array($inst, $instArray)) {
              $attr = 'checked="checked"';
            } else {
              $attr = '';
            }
            echo '<div class="optListItem">
            <input value="' . $inst . '" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" ' . $attr . ' /> ' . $inst . '
            </div>';
          }


          ?>


				</div>

			</div>

			<div id="instypes"><?= genFilterBtns($instArray); ?></div>
		
		</div>

	</div>

	<div id="mainBox" style="min-height:500px;height:auto !important">
	<?php
	echo $genQuery;
	?>
	</div>

	<div style="clear:both"></div>
</div>

<?php
appFooter();
?>