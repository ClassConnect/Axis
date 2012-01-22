<?php
appHeader('About ClassConnect');
?>
<div class="content"> 
	<div class="row" style="padding:20px;padding-top:0px"> 
<div style="font-family:Varela Round;font-size:18px;text-align:center;margin-top:7px;font-weight:bolder">
	ClassConnect is a crazy simple way to organize, collaborate and share content.
</div>

<div class="vidView" style="margin-left:65px; width:750px; height:450px;margin-top:15px">
<object width="750" height="450">
  <param name="movie" value="http://www.youtube.com/v/zYhnutkP_eo?hd=1" />
  <param name="wmode" value="transparent" />
  <embed src="http://www.youtube.com/v/zYhnutkP_eo?hd=1"
         type="application/x-shockwave-flash"
         wmode="transparent" width="750" height="450" />
</object>
</div>

<center>
<div style="font-family:'Varela Round', sans-serif;font-weight:bolder;font-size:18px;margin-top:25px">Need an account? Sign up now - it's Free!</div><br />
    	<button id="teachBut" class="btn large" type="submit" style="margin-top:-8px;margin-right:10px"> 
            <?= say('I\'m a Teacher'); ?>
        </button>
        <button id="studBut" class="btn large" type="submit" style="margin-top:-8px"> 
            <?= say('I\'m a Student'); ?>
        </button>
</center>

	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
  // focus on first input
  $("#idFirst").focus();
  $('#studBut').click(function() {
    jQuery.facebox({ 
    	ajax: '/app/signup/student'
  	});
  });
  $('#teachBut').click(function() {
    jQuery.facebox({ 
    	ajax: '/app/signup/teacher'
  	});
  });
});
</script>
<?php
appFooter();
?>