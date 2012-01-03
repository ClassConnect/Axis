<div class="row">
    <div class="span15" style="text-align:center">
    <img src="/assets/public/mainlogo.png" style="width:400px;height:49px" />
    </div>
</div>

      <!-- Example row of columns -->
      <div class="row">
        <div class="span15">

            <form method="POST" action="/app/" style="padding-left:180px; padding-top:15px">
                <div style="width:250px;float:left">
                    <span style="color:#666"><?= say('Email / Username'); ?></span><br />
                    <input id="idFirst" type="text" name="identity" style="width:215px;margin-top:4px" />
                </div>
                <div style="width:290px;float:left">
                    <span style="color:#666"><?= say('Password'); ?></span><br />
                    <input type="password" name="pass" style="width:215px;margin-top:4px" />
                    <input type="hidden" name="logsubmit" value="submitted" />
                    <button class="btn pull-right" type="submit" style="font-size:11px;margin-top:4px"> 
                    <?= say('Login'); ?>
                    </button><br />
                    <div style="padding-top:3px;font-size:11px">
                    </div>
                </div>
            </form>
        </div>
      </div>
<div class="row" style="margin-top:40px">
    <div class="span15" style="text-align:center">
      <div style="font-family:'Varela Round', sans-serif;font-weight:bolder;font-size:18px;">Need an account? Sign up now - it's Free!</div><br />
        <button id="teachBut" class="btn large" type="submit" style="margin-top:-8px;margin-right:10px"> 
            <?= say('I\'m a Teacher'); ?>
        </button>
        <button id="studBut" class="btn large" type="submit" style="margin-top:-8px"> 
            <?= say('I\'m a Student'); ?>
        </button>

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