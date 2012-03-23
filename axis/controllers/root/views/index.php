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

      <div class="slogan">
        “ClassConnect <strong>saves me time</strong> building lessons<br />
        <span style="color:#555;">so I can focus on engaging & inspiring my students.”</span>
      </div>

      <div class="actionbtns">
        <button class="btn success" onclick="jQuery.facebox({ ajax: '/app/signup/teacher' });">
        Sign up now - it's Free!
        </button>
        
        <button class="btn" onclick="jQuery.facebox({ div: '#whatisVideo' });" style="color:#444;width:195px;text-align:right">
        <img src="/assets/public/play.png" style="float:left;height:30px;margin-bottom:-30px;margin-top:-3px;margin-left:-5px" />
        Watch a video
        </button>
      </div>


      <div id="whatisVideo" style="display:none">
      <iframe width="800" height="480" src="http://www.youtube.com/embed/BZ9o0dAfXGI?hd=1&modestbranding=1&rel=0" frameborder="0" style="margin-top:-10px" allowfullscreen></iframe>
       <button class="btn large" style="float:right;margin-right:5px;margin-top:5px;margin-bottom:5px;font-weight:bolder" onclick="jQuery.facebox({ div: '#resetter' });closeBox();">Close</button>
      <button class="btn large success" style="float:right;margin-right:10px;margin-top:5px;margin-bottom:5px;font-weight:bolder" onclick="jQuery.facebox({ ajax: '/app/signup/teacher' });">Sign up now - it's Free!</button>
      </div>

      <div id="resetter" style="display:none">
      &nbsp;
      </div>

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