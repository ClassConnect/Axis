<?php
appHeader('LiveLecture', $scripts, 3);
?>

<div class="content"> 
	<div class="row" style="clear:both"> 
	  <div class="sectionLeft" style="height:600px"> 
            <button class="btn success" style="margin-left:6px;font-weight:bolder" onClick="jQuery.facebox({ 
    ajax: '/app/livelecture/create'
  });
  return false;"><img src="/assets/app/img/box/lecture.png" style="height:14px;margin-right:7px;margin-bottom:-2px" />Create new LiveLecture</button>

  <div style="font-size:11px;color:#666;padding:15px;line-height:1.2em">
    Create interactive lectures that your students can explore & discover!
  </div>

       </div>


          <div class="sectionRight"> 
				<div class="alert-message block-message" style="margin:20px;margin-top:0">
			        <p><strong>LiveLecture is currently in beta.</strong> If you run across any bugs please let us know!</p>
			      </div>

<?php
$latest = getLatestFiles(7);
foreach ($latest as $cObj) {
  $lastMod = date('F jS, Y', $cObj['last_update']);
  $lastModder = $cObj['last_update_by'];
  echo '<div style="margin-left:20px; margin-right:20px; padding-top:10px; padding-bottom:10px; padding-left:5px; padding-right:5px; border-bottom:1px solid #e1e1e1;font-size:16px">
  <div style="float:right;color:#888;font-size:14px;margin-top:1px">Last updated ' . $lastMod . '</div>
  <a href="/app/livelecture/edit?fid=' . $cObj['_id'] . '-' . $cObj['versions'][count($cObj['versions']) - 1]['id'] . '">'. $cObj['title'] . '</a>
  </div>';
}
?>

  		  </div> 



	</div> 
</div>

<?php
appFooter();
?>