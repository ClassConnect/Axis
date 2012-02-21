      <footer> 
        <p>&copy; 2012 ClassConnect</p> 
      </footer> 


<?php
if ($_SESSION['wiz'] == true) {
	// wizard
	require_once('axis/controllers/app/common/wizard/core/main.php');
	require_once('axis/controllers/app/common/wizard/views/ftr.php');	
}
?>



    </div> <!-- /main-container --> 
<?php 
if (checkSession()) {
?>
<script type="text/javascript">
    var amigos = 
    <?php
echo genFriendsJSON();
?>
</script>
<?php
// not logged in? preload the login popup
} else {
?>
<div id="logPopper" style="display:none">
  <form action="" method="POST" class="form-stacked">
  <input type="hidden" name="submitted" value="true" />
    <fieldset>
      <div class="clearfix">

        <div style="margin-left:35px">
          <div class="input">
            <label>Email / Username</label>
            <input class="idFirst" type="text" name="identity" style="width:250px;margin-top:4px" />
          </div>
          <div class="input">
            <label>Password</label>
            <input type="password" name="pass" style="width:250px;margin-top:4px" />
            <input type="hidden" name="logsubmit" value="submitted" />
          </div>

          <div style="float:left;margin-top:8px">
            <a href="/app/resetpassword">Forgot your password?</a>
          </div>
          <button type="submit" class="btn danger" style="font-weight:bolder;margin-top:8px;margin-left:60px">Login</button>
        </div>

      </div><!-- /clearfix -->
    </fieldset>
    </form>
    <div style="text-align:center;border-top:1px solid #eee;padding-top:10px;margin-top:20px">
          <div style="font-size:20px;margin-bottom:8px">
          Need an account?
          </div>
          <button class="btn large" onclick="jQuery.facebox({ ajax: '/app/signup/teacher' });">I'm a Teacher</button>
          <button class="btn large" onclick="jQuery.facebox({ ajax: '/app/signup/student' });">I'm a Student</button>
        </div>
    <div style="float:right;margin:8px">
    <a href="#" onclick="closeBox();return false">close</a>
    </div>

</div>

<script>
function logPopper() {
  jQuery.facebox({ div: '#logPopper' });
  $(".idFirst:visible").focus();
}
</script>
<?php
}
?>
<style>
.habla_conversation_text_span {
	float:none;
	margin-left:0px;
}
</style>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-25057889-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<script type='text/javascript'>/*{literal}<![CDATA[*/window.olark||(function(i){var e=window,h=document,a=e.location.protocol=="https:"?"https:":"http:",g=i.name,b="load";(function(){e[g]=function(){(c.s=c.s||[]).push(arguments)};var c=e[g]._={},f=i.methods.length; while(f--){(function(j){e[g][j]=function(){e[g]("call",j,arguments)}})(i.methods[f])} c.l=i.loader;c.i=arguments.callee;c.f=setTimeout(function(){if(c.f){(new Image).src=a+"//"+c.l.replace(".js",".png")+"&"+escape(e.location.href)}c.f=null},20000);c.p={0:+new Date};c.P=function(j){c.p[j]=new Date-c.p[0]};function d(){c.P(b);e[g](b)}e.addEventListener?e.addEventListener(b,d,false):e.attachEvent("on"+b,d); (function(){function l(j){j="head";return["<",j,"></",j,"><",z,' onl'+'oad="var d=',B,";d.getElementsByTagName('head')[0].",y,"(d.",A,"('script')).",u,"='",a,"//",c.l,"'",'"',"></",z,">"].join("")}var z="body",s=h[z];if(!s){return setTimeout(arguments.callee,100)}c.P(1);var y="appendChild",A="createElement",u="src",r=h[A]("div"),G=r[y](h[A](g)),D=h[A]("iframe"),B="document",C="domain",q;r.style.display="none";s.insertBefore(r,s.firstChild).id=g;D.frameBorder="0";D.id=g+"-loader";if(/MSIE[ ]+6/.test(navigator.userAgent)){D.src="javascript:false"} D.allowTransparency="true";G[y](D);try{D.contentWindow[B].open()}catch(F){i[C]=h[C];q="javascript:var d="+B+".open();d.domain='"+h.domain+"';";D[u]=q+"void(0);"}try{var H=D.contentWindow[B];H.write(l());H.close()}catch(E){D[u]=q+'d.write("'+l().replace(/"/g,String.fromCharCode(92)+'"')+'");d.close();'}c.P(2)})()})()})({loader:(function(a){return "static.olark.com/jsclient/loader0.js?ts="+(a?a[1]:(+new Date))})(document.cookie.match(/olarkld=([0-9]+)/)),name:"olark",methods:["configure","extend","declare","identify"]});
<?php
if (checkSession()) {
?>
olark('api.visitor.updateFullName', {fullName: '<?= dispUser(user('id'), 'first_name') . ' ' . dispUser(user('id'), 'last_name');?>'});
olark('api.visitor.updateEmailAddress', {emailAddress: '<?= dispUser(user('id'), 'e_mail');?>'});
olark('api.chat.updateVisitorNickname', {snippet: 'UID-<?= user('id'); ?>'}); 
<?
}
?>
olark.identify('8849-415-10-3302');/*]]>{/literal}*/</script>
  </body> 
</html> 