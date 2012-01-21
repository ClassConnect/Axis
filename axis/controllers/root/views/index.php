<div style="text-align:center">
      <img src="/assets/public/mainlogo.png" style="width:400px;height:49px;margin-top:-20px" />
</div>

  <div class="backRect">
  <div class="sloganDiv">
Content collaboration for students and teachers, built by students and teachers.
</div>
      <!--
      <div style="font-size:15px;color:#333;text-align:center;margin-top:10px;font-family:Varela Round">
        Content collaboration built <strong>for</strong> students and teachers <strong>by</strong> students and teachers.
      </div>-->

      <div style="width: 50%;margin: auto;">
        <div style="width:600px">
            <form method="POST" action="/app/" style="padding-left:30px; padding-top:15px">
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
                  <a href="/app/resetpassword"><?= say('Forgot your password?'); ?></a>
                  </div>
                    <div style="padding-top:3px;font-size:11px">
                    </div>
                </div>
            </form>
          </div>
        </div>

        <div style="clear:both"></div>

        <div style="margin-top:30px;text-align:center">
      <div style="font-family:'Varela Round', sans-serif;font-weight:bolder;font-size:18px;">Need an account? Sign up now - it's Free!</div><br />
        <button id="teachBut" class="btn large" type="submit" style="margin-top:-8px;margin-right:10px"> 
            <?= say('I\'m a Teacher'); ?>
        </button>
        <button id="studBut" class="btn large" type="submit" style="margin-top:-8px"> 
            <?= say('I\'m a Student'); ?>
        </button>
    </div>

    <div style="clear:both"></div>
  </div>

<div style="height:250px">&nbsp;</div>

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