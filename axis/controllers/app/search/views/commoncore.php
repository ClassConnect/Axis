<?php
$string = file_get_contents("axis/controllers/app/filebox/views/write/standards.json");
$json_a=json_decode($string,true);

if (isset($_GET['curr'])) {
	$curr = $_GET['curr'];
} else {
	$curr = '';
}
$gradeLink = reverse_htmlentities($_GET['grade']);
$grade = str_replace('--amp--', '&', $gradeLink);
$topicLink = reverse_htmlentities($_GET['topic']);
$topic = str_replace('--amp--', '&', $topicLink);

if ($curr != '') {
	if ($grade != '') {
		if ($topic != '') {
			// display crumbs
			echo '<div style="color:#333;font-size:10px;margin-top:4px;margin-bottom:4px"><a href="#" onClick="swapCore1(); return false">Common Core</a> > <a href="#" onClick="swapCore1(\'' . $curr . '\'); return false">' . $curr . '</a> > <a href="#" onClick="swapCore1(\'' . $curr . '\', \'' . $gradeLink . '\'); return false">' . $grade . '</a></div>';
			// display main panel
			foreach ($json_a[$curr][$grade][$topic] as $topic) {
			echo '<div style="border-top:1px solid #ddd;color:#666;font-size:10px;padding:10px;line-height:1.2em">
			<div style="float:left;margin-right:10px">
			<input class="standardCheck" type="checkbox" onClick="swapCommonTag(\'' . $topic['title'] . '\');" value="' . $topic['title'] . '" />
			</div>
			' . $topic['title'] . '<br />
			<span style="color:#999;font-size:10px">' . strip_tags(substr($topic['body'], 0, 52)) . '...</span></div>';
			}
		echo '<script type="text/javascript">
$(document).ready(function() {
	$(\'.filterItem\').each(function(index) {
		var sTitle = $(this).text();

      $(\'.standardCheck\').each(function(index2) {
      	if ($(this).val() == sTitle) {
      		$(this).attr(\'checked\',\'checked\');
      	}

	   });
      

    });
});
</script>';
			
		} else {
			// display crumbs
			echo '<div style="color:#333;font-size:10px;margin-top:4px;margin-bottom:4px"><a href="#" onClick="swapCore1(); return false">Common Core</a> > <a href="#" onClick="swapCore1(\'' . $curr . '\'); return false">' . $curr . '</a> > ' . $grade . '</div>';
			// display main panel
			foreach ($json_a[$curr][$grade] as $ckey=>$curric) {
			echo '<div class="tagMenuItem" style="font-size:10px;line-height:1.2em" onClick="swapCore1(\'' . $curr . '\', \'' . $gradeLink . '\', \'' . str_replace('&', '--amp--', $ckey) . '\'); return false">&raquo; ' . $ckey . '</div>';
		}
		}
		
	} else {
		// display crumbs
		echo '<div style="color:#333;font-size:10px;margin-top:4px;margin-bottom:4px"><a href="#" onClick="swapCore1(); return false">Common Core</a> > ' . $curr . '</div>';
		// display main panel
		foreach ($json_a[$curr] as $ckey=>$curric) {
			echo '<div class="tagMenuItem" style="font-size:10px;line-height:1.2em" onClick="swapCore1(\'' . $curr . '\', \'' . str_replace('&', '--amp--', $ckey) . '\'); return false">&raquo; ' . $ckey . '</div>';
		}

	}
	
} else {
	// display crumbs
	echo '<div style="color:#333;font-size:10px;margin-top:4px;margin-bottom:4px">Common Core</div>';
	// display main panel
	foreach ($json_a as $ckey=>$curric) {
		echo '<div class="tagMenuItem" style="font-size:10px;line-height:1.2em" onClick="swapCore1(\'' . $ckey . '\'); return false">&raquo; ' . $ckey . '</div>';
		//echo '<a href="#" onClick="swapCore1(\'' . $ckey . '\'); return false">' . $ckey . '</a><br />';
	}
}

// curriculum -> grade -> topic -> standards
/*
	echo '<h1>' . $cur . '</h1>';
		foreach ($grades as $cur=>$cur_data) {
			echo '<h2>' . $grade . '</h2>';
			foreach ($cur_data as $topic=>$stands) {
				echo '<h3 style="color:#666">' . $topic . '</h3>';
				foreach ($stands as $stand) {
					//echo '<strong>' . $stand['title'] . '</strong><br />' . $stand['body'] . '<br /><br />';
				}
			}
		}
}
*/
?>