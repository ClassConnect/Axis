  <!-- extra per page css -->
    <style>
		body{ background:#f7f7f7 url(/assets/public/images/home/gradient.png) repeat-x; }
    div#header-top{ background:none; }
    .copyTxt li {
        margin-top:8px;
    }
	</style>

<!--[if IE 7]>
<style type="text/css">
#homeSplash{
 margin-top:-10px;
}
</style>
<![endif]-->
<div id="homeSplash">


<div class="leftbox">
    <div class="slogan">Stop managing.<br />
    Start engaging.</div>
    <div class="copyTxt">
        <li>Engage students with interactive lectures</li>
        <li>Organize your digital content in one place</li>
        <li>Share content with colleagues & students</li>
        <li>And so much more.</li>
    </div>
    <div style="margin-left:65px;margin-top:20px">
        <div class="grayButtonset" style="width:140px;float:left;margin-right:10px" onclick="openBox($('#hideme').html(), 720, 1);">Watch a video</div>
        <div class="redButtonset" style="width:180px;float:left" onClick="openBox($('#signupPop').html(), 402, 1);">Sign up - it's free!</div>
    </div>
</div>

<div class="rightbox">
    <div class="splashLogin" style="margin-top:78px">
    <form action="/app/login.cc" method="post">
        Username / Email<br />
        <input class="splashInput" type="text" name="identity" /><br /><br />
        Password<br />
        <input class="splashInput" type="password" name="pass" /><br />
        <button class="silverButtonset" type="submit" style="margin-top:20px;margin-right:55px;width:80px;height:40px;float:right;border:none">Login</button>
        <input type="hidden" name="submitted" value="submitted" />
    </form>
    </div>
</div>


    

</div>

      


		</header><!-- END header -->

        <!--| content starts here |-->

        <div id="content">
        	<div id="content-inner" style="margin-top:-40px;height:150px">
<div class="iamannounce">How can ClassConnect help me?</div>
                    <div class="iamabox" style="width:220px;margin-left:60px" onClick="window.location = 'iama/teacher/';">
                        <img src="/assets/public/images/ima/folder.png" style="float:left" />
                        <div class="gen">I am a</div><br />
                       <div class="titler">Teacher</div>
                    </div>
                    <div class="iamabox" style="width:220px" onClick="window.location = 'iama/student/';">
                        <img src="/assets/public/images/ima/books.png" style="float:left" />
                        <div class="gen">I am a</div><br />
                       <div class="titler">Student</div>
                    </div>
                    <div class="iamabox" style="width:240px; margin-right:1px" onClick="window.location = 'iama/administrator/';">
                        <img src="/assets/public/images/ima/pen.png" style="float:left" />
                       <div class="gen">I am an</div><br />
                       <div class="titler">Administrator</div>
                    </div>

            </div><!-- END "#content-inner" -->
        </div><!-- END "#content" -->

<div id="hideme" style="display:none">
<img src="/app/core/site_img/gen/cross.png" style="position:absolute;margin-top:-30px; margin-left:720px; border:3px solid #999; background:#eee; padding:5px; cursor:pointer" onClick="closeBox();" />
<iframe width="720" height="480" src="http://www.youtube.com/embed/SuXi5qBtSco?HD=1;rel=0;showinfo=0" frameborder="0" allowfullscreen></iframe>
</div>