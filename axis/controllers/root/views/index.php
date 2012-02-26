<div class="splashHead">
  <div class="container">


    <div class="splashHeadRight">
      <div class="loginbox">
      <form method="POST" action="/app/">
        <input type="text" class="uninput" name="identity" placeholder="Email / Username" /><br />
        <input type="password" class="passinput" name="pass" placeholder="Password" /><br />
        <input type="hidden" name="logsubmit" value="submitted" />
        <button class="btn" style="float:right;margin-top:5px">Login</button>
        <div style="margin-top:10px;margin-left:5px">
          <a href="/app/resetpassword">Forgot password?</a>
        </div>
        </form>
      </div>
    </div>


    <div class="splashHeadLeft">

      <img src="/assets/public/mainlogo.png" class="logo" />

      <div class="slogan">
      The easiest way to build and share your lessons.
      </div>

      <div class="actionbtns">
        <button class="btn success" onclick="jQuery.facebox({ ajax: '/app/signup/teacher' });">
        Sign up now - it's Free!
        </button>
      </div>


    </div>

  </div>
</div>

<div class="splashDesc">
  <div class="container">

    <div class="descBox">
      <div class="boxtitle">Build Your Lessons</div>
      <div class="boxdesc">Add websites, online videos, Google Docs, files & more. You can even find lessons that other teachers have built.</div>
      <img src="/assets/public/files.png" style="margin-top:20px;margin-left:40px" />

      <img src="/assets/public/arrow.png" style="position:absolute;margin-left:70px;margin-top:50px" />
    </div>

    <div class="descBox">
      <div class="boxtitle">Organize & Store</div>
      <div class="boxdesc">All of your lessons stay here until you delete them, so say goodbye to re- uploading your lessons every semester.</div>
      <img src="/assets/public/crate.png" style="height:100px; margin-top:15px; margin-left:70px" />

      <img src="/assets/public/arrow.png" style="position:absolute;margin-left:90px;margin-top:50px" />
    </div>

    <div class="descBox" style="margin-right:0px">
    <div class="boxtitle">Share & Collaborate</div>
      <div class="boxdesc">It takes just a click to share with students, parents & colleagues. They automatically get notified when changes are made.</div>
      <img src="/assets/public/people.png" style="margin-top:20px;margin-left:70px" />
    </div>
    
    
  </div>
</div>