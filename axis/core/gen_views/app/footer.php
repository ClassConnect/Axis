      <footer> 
        <p>&copy; 2012 ClassConnect</p> 
      </footer> 


<?php
// wizard
require_once('axis/controllers/app/common/wizard/core/main.php');
require_once('axis/controllers/app/common/wizard/views/ftr.php');
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
}
?>
<style>
.habla_conversation_text_span {
	float:none;
	margin-left:0px;
}
</style>
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