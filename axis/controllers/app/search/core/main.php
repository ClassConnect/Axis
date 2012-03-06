<?php

function performSearch($keyQuery, $reqpars, $offset, $limit) {
	global $search_index;
	global $search_type;


	if (!isset($limit)) {
		$limit = 20;
	}

	if (!isset($offset)) {
		$offset = 0;
	}

	//clean our query
	$keyQuery = preg_replace("/[^a-zA-Z0-9\s]/", "", strip_tags($keyQuery));


	$client = initElastica();
	$index = $client->getIndex($search_index);
	//$index->clearCache();

	$type = $index->getType($search_type);



	if ($keyQuery == '') {
		return false;
	}


	$queryTerm = new Elastica_Query_QueryString($keyQuery);
	$queryTerm->setFuzzyMinSim(0.5);

	// filter terms for public permissions
	$filter1 = new Elastica_Filter_Term();
	$filter1->setTerm('permissions.type', '3');
	$filter2 = new Elastica_Filter_Term();
	$filter2->setTerm('parentPermissions.type', '3');
		
	// or filter
	$orFilt = new Elastica_Filter_Or();
	$orFilt->addFilter($filter1);
	$orFilt->addFilter($filter2);

	$finFilt = new Elastica_Filter_And();
	$finFilt->addFilter($orFilt);

	foreach ($reqpars as $fkey => $field) {
		if (!empty($field)) {
			if ($fkey == 'grades') {

				$torFilt = new Elastica_Filter_Or();

				foreach ($field as $filer) {

					// get the right grade levels
					if ($filer == 'Pre-Kindergarten') {
						$return = 'gradeprekindergarten';

					} elseif ($filer == 'Lower Elementary') {
						$return = 'gradelowerelementary';

					} elseif ($filer == 'Upper Elementary') {
						$return = 'gradeupperelementary';

					} elseif ($filer == 'Middle School') {
						$return = 'grademiddleschool';

					} elseif ($filer == 'High School') {
						$return = 'gradehighschool';

					} elseif ($filer == 'College') {
						$return = 'gradecollege';

					} else {
						$return = 'gradeother';
					}

					// set filter
					$tfilt1 = new Elastica_Filter_Term();
					$tfilt1->setTerm('tagstore', $return);
					$torFilt->addFilter($tfilt1);

				}

				$finFilt->addFilter($torFilt);


			} elseif ($fkey == 'subjects') {

				// initialize our "or"

				$torFilt = new Elastica_Filter_Or();

				foreach ($field as $filer) {
					$filer = strtolower($filer);
					$tfilt1 = new Elastica_Filter_Term();
					$tfilt1->setTerm('tagstore', $filer);
					$torFilt->addFilter($tfilt1);

				}

				$finFilt->addFilter($torFilt);



			} elseif ($fkey == 'commoncore') {

				// initialize our "or"

				$torFilt = new Elastica_Filter_Or();

				foreach ($field as $filer) {
					$filer = convStandardToString($filer);
					$tfilt1 = new Elastica_Filter_Term();
					$tfilt1->setTerm('tagstore', $filer);
					$torFilt->addFilter($tfilt1);

				}

				$finFilt->addFilter($torFilt);


			} elseif ($fkey == 'filetypes') {

				// initialize our "or"
				$torFilt = new Elastica_Filter_Or();

				foreach ($field as $filer) {

					if ($filer == 'Website') {
						$tfilt1 = new Elastica_Filter_Term();
						$tfilt1->setTerm('format', 2);
						$torFilt->addFilter($tfilt1);

					} elseif ($filer == 'Embed Code') {
						$tfilt1 = new Elastica_Filter_Term();
						$tfilt1->setTerm('format', 3);
						$torFilt->addFilter($tfilt1);

					} elseif ($filer == 'Document') {
						$docTypes = array('pdf', 'ps', 'doc', 'docx', 'odt', 'sxw', 'ods', 'txt', 'rtf');
						foreach ($docTypes as $dt) {
							$tfilt1 = new Elastica_Filter_Term();
							$tfilt1->setTerm('versions.ext', $dt);
							$torFilt->addFilter($tfilt1);
						}

						// google doc
						$tfilt1 = new Elastica_Filter_Term();
						$tfilt1->setTerm('format', 6);
						$torFilt->addFilter($tfilt1);

					} elseif ($filer == 'Presentation') {
						$presTypes = array('ppt', 'pps', 'pptx', 'odp', 'sxi');
						foreach ($presTypes as $dt) {
							$tfilt1 = new Elastica_Filter_Term();
							$tfilt1->setTerm('versions.ext', $dt);
							$torFilt->addFilter($tfilt1);
						}

					} elseif ($filer == 'Spreadsheet') {
						$spreadTypes = array('ods', 'sxc', 'xls', 'xlsx');
						foreach ($spreadTypes as $dt) {
							$tfilt1 = new Elastica_Filter_Term();
							$tfilt1->setTerm('versions.ext', $dt);
							$torFilt->addFilter($tfilt1);
						}


					
					} elseif ($filer == 'Video') {
						$videoTypes = array('3gp', 'avi', 'flv', 'mpeg', 'mpg', 'mpe', 'mp4', 'swf', 'wmv');
						foreach ($videoTypes as $dt) {
							$tfilt1 = new Elastica_Filter_Term();
							$tfilt1->setTerm('versions.ext', $dt);
							$torFilt->addFilter($tfilt1);
						}

						// web video
						$tfilt1 = new Elastica_Filter_Term();
						$tfilt1->setTerm('format', 4);
						$torFilt->addFilter($tfilt1);


					} elseif ($filer == 'Audio') {
						$audioTypes = array('wav', 'm4a', 'wma', 'mp2', 'mp3', 'aac');
						foreach ($audioTypes as $dt) {
							$tfilt1 = new Elastica_Filter_Term();
							$tfilt1->setTerm('versions.ext', $dt);
							$torFilt->addFilter($tfilt1);
						}


					} elseif ($filer == 'Image') {
						$imgTypes = array('gif', 'bmp', 'ico', 'jpg', 'jpeg', 'png');
						foreach ($imgTypes as $dt) {
							$tfilt1 = new Elastica_Filter_Term();
							$tfilt1->setTerm('versions.ext', $dt);
							$torFilt->addFilter($tfilt1);
						}


					}

				}

				$finFilt->addFilter($torFilt);


			} elseif ($fkey == 'instructionaltypes') {

				// initialize our "or"
				$torFilt = new Elastica_Filter_Or();

				foreach ($field as $filer) {
					$filer = strtolower(str_replace(" ", "", $filer));
					$tfilt1 = new Elastica_Filter_Term();
					$tfilt1->setTerm('tagstore', $filer);
					$torFilt->addFilter($tfilt1);

				}

				$finFilt->addFilter($torFilt);



			}
		}
	}


	$queryFinal = new Elastica_Query_Filtered($queryTerm, $finFilt);

	$query = Elastica_Query::create($queryFinal);
	$query->setSize($limit)->setFrom($offset);
	$query->setSort(array('versions.forkTotal' => array('order' => 'desc'), 'versions.recs' => array('order' => 'desc')));

	$resultSet = $type->search($query);

	return $resultSet;
	
}



function genResults($resultSet) {
	$finTxt = '';

	if ($resultSet == false || $resultSet->count() == 0) {
		$finTxt .= '<div style="margin-top:100px;text-align:center;font-size:18px;font-weight:bolder;color:#666">We couldn\'t find anything - try another search!</div>

		<div style="margin-top:10px;font-size:14px;color:#777;text-align:center;font-style:italic">Psst - search is still in beta!</div>';


	} else {
		if ($resultSet->getTotalHits() == 1) {
			$phrase = $resultSet->getTotalHits() . ' result found';
		} else {
			$phrase = $resultSet->getTotalHits() . ' results found';
		}

		$finTxt .= '<div style="padding:7px 0 7px 15px; font-size:13px;font-weight:bolder;color:#555;border-bottom:2px solid #ccc">
		' . $phrase . '</div>';


		foreach ($resultSet as $result) 
		{
		  $cobj = $result->getData();
		  //var_dump($cobj);
		  $cobj['_id'] = $result->getId();
		  $finTxt .= genResultStripe($cobj);
		} 
		// $resultSet->count();
	}

	return $finTxt;
}



function genResFeed($resultSet) {
	$finTxt = '';

	if ($resultSet == false || $resultSet->count() == 0) {
		$finTxt .= '<p style="text-align:center;color:#666; background:#efefef;padding:7px;margin:20px">No more results found!</p><script>killLoad=true;</script>';


	} else {

		foreach ($resultSet as $result) 
		{
		  $cobj = $result->getData();
		  //var_dump($cobj);
		  $cobj['_id'] = $result->getId();
		  $finTxt .= genResultStripe($cobj);
		} 
		// $resultSet->count();
	}

	return $finTxt;
	
}


function genResultStripe($child) {
	$verID = verifyDataAuth('0', $child);
	$numLikes = genNumLikes($child, $verID);
	$numForks = genNumForks($child, $verID);

		$list = '';
	if ($child['type'] == 1) {
	    	$class = "fboxFolder";
	    	$icon = '<img src="/assets/app/img/box/type/folder.png" class="conicon" />';
	    } else {
	    	$class = "fboxContent";
	    	$icon = createConIcon($child);
	    }

	    $lastMod = date('F jS, Y', $child['last_update']);
	    $lastModder = $child['owner_id'];

	    $list .= '<div id="' . $child['_id'] . '" class="fboxElement ' . $class . '">
	    <div style="margin-left:10px">' . $icon  . '</div>
	    <div class="conmain">
	    	<div class="optarea">
	    		<div style="margin-top:17px;">
	    		<div style="float:right;margin-right:34px;height:10px"></div>
	    			
	    			<div class="descTip" title="This has been used ' . $numForks . ' times" style="float:left;margin-right:20px">
		    			<img src="/assets/app/img/box/fork.png" style="margin-top:5px;margin-right:3px;float:left" />
		    			<span class="label numbero" style="background:#666;text-shadow:none">' . $numForks . '</span>
	    			</div>

	    			<div class="descTip" title="This has been recommended ' . $numLikes . ' times">
		    			<img src="/assets/app/img/box/thumbup.png" style="margin-top:5px;margin-right:3px;float:left;height:12px" />
		    			<span class="label numbero" style="background:#666;text-shadow:none">' . $numLikes . '</span>
	    			</div>


	    			';

	    $list .= '</div>
	    	</div>
	    	<div class="mainarea" style="margin-left:-15px">
	    		<div class="contitle">
	    		<a href="/app/filebox/' . $child['_id'] . '">' . $child['title'] . '</a>
	    		</div>
	    		<div class="conlast">
	    		Updated ' . $lastMod . ' by <a href="' . userURL($lastModder) . '" class="textTogg">' . dispUser($lastModder, 'first_name') . ' ' . dispUser($lastModder, 'last_name') . '</a>';


	    		if ($child['files'] > 0 && $child['type'] == 1) {
	    			$sizeData = sizeToText($child['total_size']);
	    			$list .= '<span class="topDesc rollFalse" title="' . $child['files'] . ' files, ' . $sizeData['data'] . ' ' . $sizeData['fix'] .'"><img src="/assets/app/img/box/mini/file.png" style="margin-left:6px;margin-right:-1px;" /> ' . $child['files'] . '</span>';
	    		}


	    		//if this has tags, show the mini button thing
	    		$totTags = (count($child['tags']) + count($child['parentTags']));
	    		if ($totTags > 0) {
	    			$list .= '<span class="topDesc rollFalse" title="' . $totTags . ' tags" onClick="jQuery.facebox({ 
    ajax: \'/app/filebox/write/tags/?conIDs=' . $child['_id'] . '\'
  });$(\'.twipsy\').remove();"><img src="/assets/app/img/box/mini/tag.png" style="margin-left:6px;margin-right:-1px;" /> ' . $totTags . '</span>';
	    		}

	    $list .= '</div>
	    	</div>
	    </div>
	    </div>';
	    return $list;
}






function convGradeToString($title) {
	$title = strtolower($title);
	if ($title == 'ps' || $title == 'pk' || $title == 'k') {
		$return = 'gradeprekindergarten';

	} elseif ($title == '1' || $title == '2' || $title == '3') {
		$return = 'gradelowerelementary';

	} elseif ($title == '4' || $title == '5') {
		$return = 'gradeupperelementary';

	} elseif ($title == '6' || $title == '7' || $title == '8') {
		$return = 'grademiddleschool';

	} elseif ($title == '9' || $title == '10' || $title == '11' || $title == '12') {
		$return = 'gradehighschool';

	} elseif ($title == 'prep' || $title == 'bs/ba' || $title == 'masters' || $title == 'phd' || $title == 'post-doc') {
		$return = 'gradecollege';
	} else {
		$return = 'gradeother';
	}

	return $return;
}


function convStandardToString($title) {
	$title = str_replace('.', 'dot', $title);
	$title = str_replace('-', 'dash', $title);
	$title = str_replace('0', 'zero', $title);
	$title = str_replace('1', 'one', $title);
	$title = str_replace('2', 'two', $title);
	$title = str_replace('3', 'three', $title);
	$title = str_replace('4', 'four', $title);
	$title = str_replace('5', 'five', $title);
	$title = str_replace('6', 'six', $title);
	$title = str_replace('7', 'seven', $title);
	$title = str_replace('8', 'eight', $title);
	$title = str_replace('9', 'nine', $title);
	return strtolower($title);
}






function genFilterBtns($filtArr) {
	$fin = '';
	foreach ($filtArr as $res) {
		if ($res != '') {
			$fin .= '<div class="label filterItem">'  . $res . '<img src="/assets/app/img/box/rem.png" style="height:12px;float:right;cursor:pointer;margin-top:4px" onclick="removeFilter(\'' . $res . '\')" /></div>';
		}
	}

	return $fin;
}
?>