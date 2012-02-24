<div class="pubFooter">
  <div class="container">
    
    <div class="barBlock">
      <a href="/about/us" class="btn barBtn aboutbut" style="margin-left:0">
      About
      </a>
      <a href="/about/UnitedWeTeach" class="btn barBtn uwtbut" style="color:#555">
      United We Teach
      </a>
      <a href="http://classconnect.tumblr.com" target="_blank" class="btn barBtn blogbut">
      Blog
      </a>
      <a href="#" class="btn barBtn contactbut" onclick="olark('api.box.expand'); return false">
      Contact
      </a>
    </div>



<div class="shareBox"> 
  <iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.classconnect.com&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=90&amp;appId=213954741999891" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:50px; height:70px; margin-right:10px" allowTransparency="true"></iframe> 

  <iframe allowtransparency="true" frameborder="0" marginwidth="0" scrolling="no" src="https://plusone.google.com/_/+1/fastbutton?url=http%3A%2F%2Fwww.classconnect.com%2F&amp;size=tall&amp;count=true&amp;annotation=&amp;width=120&amp;hl=en-US&amp;jsh=m%3B%2F_%2Fapps-static%2F_%2Fjs%2Fwidget%2F__features__%2Frt%3Dj%2Fver%3DSXEYxs5FO0c.en_US.%2Fsv%3D1%2Fam%3D!KW4lzGmbF_KIhSW8Og%2Fd%3D1%2F#id=I1_1327178530968&amp;parent=http%3A%2F%2Fwww.classconnect.com&amp;rpctoken=350075819&amp;_methods=onPlusOne%2C_ready%2C_close%2C_open%2C_resizeMe" style="border:none; overflow:hidden; width:50px; height:70px;margin-bottom:-1px;margin-right:10px" title="+1"></iframe>

  <iframe allowtransparency="true" frameborder="0" scrolling="no" src="//platform.twitter.com/widgets/tweet_button.html?count=vertical&text=I'm a pioneer! %23UnitedWeTeach&via=ClassConnectInc&url=http://www.classconnect.com" style="width:55px; height:70px;"></iframe>

  <a href="http://www.rackspace.com" target="_blank"><img src="/assets/rack.png" style="height:30px;margin-bottom:22px;margin-left:15px" /></a>
</div>


<div class="pioneerDiv" title="<div style='font-size:14px;padding:10px'>Click to watch this video!</div>">
<br /><br />
<center>
  <img src="/assets/app/img/box/miniload.gif" style="margin-right:5px;margin-bottom:-1px" />
  <span style="color:#666;font-weight:bolder">Loading the latest pioneer chat...</span>
  </center>
</div>



<div class="copyFooter">
&copy 2012 ClassConnect Inc
</div>


  </div>
</div>



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