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
				<div class="alert-message block-message error" style="margin:20px;margin-top:0">
			        <p><strong>LiveLecture is currently in beta.</strong> If you run across any bugs please let us know!</p>
			      </div>
<a href="/app/livelecture/edit?fid=1">editor</a>

  		  </div> 



	</div> 
</div>

<?php
appFooter();
?>