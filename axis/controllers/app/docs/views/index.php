<?php
appHeader('Docs', $scripts, 3);
?>

<div class="content"> 
	<div class="row" style="clear:both"> 
	  <div class="sectionLeft" style="height:600px"> 
            <button class="btn success" style="margin-left:12px;font-weight:bolder" onClick="jQuery.facebox({ 
    ajax: '/app/docs/create'
  });
  return false;"><img src="/assets/app/img/box/addfile.png" style="height:14px;margin-right:7px;margin-bottom:-2px" />Create new document</button>

  <div style="font-size:11px;color:#666;padding:15px;line-height:1.2em">
    View & edit documents without ever leaving ClassConnect!
  </div>

       </div>


          <div class="sectionRight"> 
				<div class="alert-message block-message" style="margin:20px;margin-top:0">
			        <p><strong>Docs is currently in beta.</strong> If you run across any bugs please let us know!</p>
			      </div>


  		  </div> 



	</div> 
</div>

<?php
appFooter();
?>