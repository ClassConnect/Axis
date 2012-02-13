<?php
/*
$keyQuery = strip_tags($_GET['query']);
$resultSet = performSearch($keyQuery);
echo genResults($resultSet);
*/

appHeader('Search', '<script type="text/javascript" src="/assets/app/js/search/main.js"></script>');
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

					<div class="optListItem">
						<input value="Pre-Kindergarten" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						Pre-Kindergarten
					</div>
					<div class="optListItem">
						<input value="Lower Elementary" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						Lower Elementary
					</div>
					<div class="optListItem">
						<input value="Upper Elementary" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						Upper Elementary
					</div>
					<div class="optListItem">
						<input value="Middle School" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						Middle School
					</div>
					<div class="optListItem">
						<input value="High School" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						High School
					</div>
					<div class="optListItem">
						<input value="College" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						College
					</div>
				</div>
			</div>

			<div id="grades"></div>

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
            if ($fake == true) {
              $attr = 'checked disabled';
            } else {
              $attr = '';
            }
            echo '<div class="optListItem">
            <input value="' . $subject . '" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" /> ' . $subject . '
            </div>';
          }


          ?>




				</div>
			</div>

			<div id="subjs"></div>

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

			<div id="commonstand"></div>

		</div>

		<div class="searchTabber">
			<img src="/assets/app/img/box/copy.png" class="icimg" /> File Type

			<div class="selbtndef preselSty">
				<div class="labelText">Choose file types</div>

			<div class="labelPanel">
					<div class="tokenManifest" style="display:none">
					filetypes
					</div>


					<div class="optListItem">
						<input value="Website" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						Website
					</div>

					<div class="optListItem">
						<input value="Embed Code" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						Embed Code
					</div>

					<div class="optListItem">
						<input value="Document" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						Document
					</div>
					<div class="optListItem">
						<input value="Presentation" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						Presentation
					</div>
					<div class="optListItem">
						<input value="Spreadsheet" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						Spreadsheet
					</div>
					<div class="optListItem">
						<input value="Video" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						Video
					</div>
					<div class="optListItem">
						<input value="Image" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						Image
					</div>
					<div class="optListItem">
						<input value="Audio" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						Audio
					</div>
				</div>

			</div>

			<div id="filetypes"></div>

		</div>

		<div class="searchTabber">
			<img src="/assets/app/img/temp/curric.png" class="icimg" /> Instructional Type

			<div class="selbtndef preselSty">
				<div class="labelText">Choose instructional types</div>

			<div class="labelPanel">
					<div class="tokenManifest" style="display:none">
					instypes
					</div>


					<div class="optListItem">
						<input value="Activity" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						Activity
					</div>
					<div class="optListItem">
						<input value="Assessment" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						Assessment
					</div>
					<div class="optListItem">
						<input value="Lab" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						Lab
					</div>
					<div class="optListItem">
						<input value="Lesson Plan" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						Lesson Plan
					</div>
					<div class="optListItem">
						<input value="Practice" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						Practice
					</div>
					<div class="optListItem">
						<input value="Project" type="checkbox" class="checkMePlease" style="margin-right:3px;float:left" />
						Project
					</div>


				</div>

			</div>

			<div id="instypes"></div>
		
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