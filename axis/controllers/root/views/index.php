<div class="splashHead">
  <div class="container">


    <div class="splashHeadRight">
      <div class="loginbox">
      <form method="POST" action="/app/">
        <input type="text" class="uninput" name="identity" placeholder="Email / Username" />
        <input type="password" class="passinput" name="pass" placeholder="Password" />
        <button class="btn logBtner">Login</button><br />
        <input type="hidden" name="logsubmit" value="submitted" />
        <div style="margin-top:5px;margin-left:175px; font-size:10px">
          <a href="/app/resetpassword">Forgot password?</a>
        </div>
        </form>
      </div>
    </div>


    <div class="splashHeadLeft">

      <img src="/assets/public/logo_front.png" class="logo" />

    </div>


    <div class="splashMain">

      <div class="slogan" style="margin-bottom:50px">
        Create standards aligned curriculum, <strong>collaboratively.</strong>
        <form action="#" id="add-teacher" class="form-stacked">
          <input type="text" name="email" style="font-size:30px;height:50px;width:500px; margin-top:40px" placeholder="enter your email for beta access" />
          <button class="btn success large" style="font-weight:bolder; font-size:28px; position:relative; padding-top:12px; padding-bottom: 12px; top:6px">Request Invite</button>
          <input type="hidden" name="submitted" value="true" />
        </form>
      </div>

<!--<img src="/assets/app/iste.png" style="height:100px;margin-top:30px" /> Attended ISTE? Sign up for our beta and you'll be entered to win a free iPad!-->

      <script>
      $('#add-teacher').submit(function() {
        var serData = $("#add-teacher").serialize();
        $.ajax({  
          type: "POST",  
          url: "/app/signup/teacher",  
          data: serData,  
          success: function(retData) {
              $("#add-teacher").html('<br /><br /><center>Sweet! We\'ll let you know once your beta invite is ready :)</center>');

          }
          
        });  
        return false;
      });

      </script>



    </div>



  </div>
</div>

<div class="splashDesc">
  <div class="container">

    <div class="descBox">
      <div class="boxtitle">Build Your Lessons</div>
      <div class="boxdesc">Find lessons aligned with the Common Core and add your own websites, online videos, Google Docs, files & more!</div>
      <img src="/assets/public/files.png" style="margin-top:20px;margin-left:40px" />

      <img src="/assets/public/arrow.png" style="position:absolute;margin-left:70px;margin-top:50px" />
    </div>

    <div class="descBox">
      <div class="boxtitle">Organize & Store</div>
      <div class="boxdesc">All of your lessons stay here until you delete them, so say goodbye to re- uploading your lessons every semester.</div>
      <img src="/assets/public/crate.png" style="height:110px; margin-top:15px; margin-left:70px;margin-bottom:-10px" />

      <img src="/assets/public/arrow.png" style="position:absolute;margin-left:80px;margin-top:50px" />
    </div>

    <div class="descBox" style="margin-right:0px">
    <div class="boxtitle">Share & Collaborate</div>
      <div class="boxdesc">It takes just a click to share with students, parents & colleagues. They automatically get notified when changes are made.</div>
      <img src="/assets/public/people.png" style="margin-top:20px;margin-left:70px" />
    </div>
    
    
  </div>
</div>