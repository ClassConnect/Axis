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
<div class="content">
	<div class="row"> 
<?php 
// if we have a login error
if ($loginError) {
	echo '<div class="alert-message error" style="margin-left:20px;text-align:center;font-family:\'Varela Round\', sans-serif;">
        <p><strong>' . say('Login attempt failed.').  '</strong> ' . say('Please try again.') . '</p>
      </div>';

// if this is a logout
} elseif (isset($_GET['lo'])){
	echo '<div class="alert-message success" style="margin-left:20px;text-align:center;font-family:\'Varela Round\', sans-serif;">
        <p><strong>' . say('You have been logged out successfully!').  '</strong> ' . say('See you soon!') . '</p>
      </div>';
} else {
	echo '<div class="alert-message warning" style="margin-left:20px;text-align:center;font-family:\'Varela Round\', sans-serif;">
        <p><strong>' . say('In order to see this page, you need to be logged in.').  '</strong></p>
      </div>';
}
?>
          <div class="span10" style="margin-left:200px;">
            <form method="POST" action="" style="padding-left:20px; padding-top:15px">
				<div style="width:250px;float:left">
					<?= say('Email / Username'); ?><br />
					<input id="idFirst" type="text" name="identity" style="width:215px;margin-top:4px" />
				</div>
				<div style="width:290px;float:left">
					<?= say('Password'); ?><br />
					<input type="password" name="pass" style="width:215px;margin-top:4px" />
					<input type="hidden" name="logsubmit" value="submitted" />
					<button class="btn pull-right" type="submit" style="font-size:11px;margin-top:4px"> 
                	<?= say('Login'); ?>
                	</button><br />
                	<div style="padding-top:3px;font-size:11px">
                	<a href="#"><?= say('Forgot your password?'); ?></a>
                	</div>
				</div>
			</form>
          </div>
          
          <div style="clear:both;margin-top:120px; -moz-box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1);
    -webkit-box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1);
    box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1); width:937px;height:10px"></div>

    <div class="span9" style="margin-left:200px;margin-top:20px;text-align:center">
    <div style="font-family:'Varela Round', sans-serif;font-weight:bolder;font-size:18px;">Need an account? Sign up now - it's Free!</div><br />
    	<button id="teachBut" class="btn large" type="submit" style="margin-top:-8px;margin-right:10px"> 
            <?= say('I\'m a Teacher'); ?>
        </button>
        <button id="studBut" class="btn large" type="submit" style="margin-top:-8px"> 
            <?= say('I\'m a Student'); ?>
        </button>

    </div>

	</div> 
</div> 