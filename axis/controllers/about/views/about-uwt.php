<?php
pubHeader('United We Teach', true);
?>


    <div class="clear:both;margin-top:20px">

    <div style="font-size:24px;font-weight:bolder;margin-top:15px">
    If your job was to manage students they would call you managers.
</div>
<div style="font-size:20px;margin-top:20px;font-weight:bolder">
    But you're a <span style="text-shadow: 0px 1px 0px #ddd;color:#36A936">teacher</span>, and your goal is to share knowledge and make content come to life.
</div>




<div style="font-size:14px;margin-top:40px">

<div style="float:left;margin-right:40px;margin-bottom:40px">
  <script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'search',
  search: '#UnitedWeTeach',
  interval: 3000,
  title: 'ClassConnect',
  subject: '#UnitedWeTeach',
  width: 200,
  height: 180,
  theme: {
    shell: {
      background: '#424242',
      color: '#ffffff'
    },
    tweets: {
      background: '#ffffff',
      color: '#444444',
      links: '#1985b5'
    }
  },
  features: {
    scrollbar: false,
    loop: true,
    live: true,
    behavior: 'default'
  }
}).render().start();
</script>
</div>
<!--<img src="/assets/public/uwt.png" style="float:left;margin-right:40px;margin-bottom:40px;height:300px;" />-->

<div style="background:#E1E1E1;width:660px;height:1px;float:right;margin-bottom:20px">&nbsp;</div>


<div style="width:800px">
The problem is that finding the time to create all this incredible content and having the energy to teach it is impossible if we play by the old rules.

<br /><br />
<strong>Technology and the internet have changed the game.</strong> Now, for the first time ever, you can work collaboratively with teachers from around the world to build lessons. This gives you more time & energy to focus on what you do best — teaching and inspiring your students.
<br /><br />
 Every once in a while, a group of pioneers stray from the well-worn path and create the future. This is happening in education <span style="text-decoration:underline">right now</span>. Join the thousands of teachers from around the world that are celebrating each other's unique ideas and lets leave education with something really beautiful.<br />

<button class="btn success" onclick="jQuery.facebox({ ajax: '/app/signup/teacher' });" style="font-weight:bolder; padding:7px 15px 5px 15px;font-size:16px;margin-top:20px"> Join the movement!</button>

</div>

<div style="margin-left:20px;margin-top:-35px;margin-right:100px;float:right">
    <iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.classconnect.com/about/unitedweteach&amp;send=false&amp;layout=none&amp;width=450&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=90&amp;appId=213954741999891" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:52px; height:30px; margin-right:3px" allowTransparency="true"></iframe>

    <iframe allowtransparency="true" frameborder="0" marginwidth="0" scrolling="no" src="https://plusone.google.com/_/+1/fastbutton?url=http%3A%2F%2Fwww.classconnect.com/about/unitedweteach%2F&amp;size=tall&amp;count=false&amp;annotation=&amp;width=120&amp;hl=en-US&amp;jsh=m%3B%2F_%2Fapps-static%2F_%2Fjs%2Fwidget%2F__features__%2Frt%3Dj%2Fver%3DSXEYxs5FO0c.en_US.%2Fsv%3D1%2Fam%3D!KW4lzGmbF_KIhSW8Og%2Fd%3D1%2F#id=I1_1327178530968&amp;parent=http%3A%2F%2Fwww.classconnect.com/about/unitedweteach&amp;rpctoken=350075819&amp;_methods=onPlusOne%2C_ready%2C_close%2C_open%2C_resizeMe" style="border:none; overflow:hidden; width:50px; height:30px;margin-bottom:-1px;margin-right:3px" title="+1"></iframe>

    <iframe allowtransparency="true" frameborder="0" scrolling="no"
    src="//platform.twitter.com/widgets/tweet_button.html?count=none&text=I'm a pioneer! %23UnitedWeTeach&via=ClassConnectInc&url=http://www.classconnect.com/about/unitedweteach"
    style="width:55px; height:30px;"></iframe>
</div>




</div> 

    </div>

  </div>
</div>









<div class="splashDesc">
  <div class="container" style="padding-bottom:25px">

    <div style="font-size:18px;text-align:center;margin-top:25px;margin-bottom:10px;font-weight:bolder">
      Get involved with the United We Teach movement!
    </div>

    <div style="font-size:14px;color:#777;margin-left:170px;margin-bottom:-10px">
      <ul>
        <li><a href="#" onclick="jQuery.facebox({ ajax: '/app/signup/teacher' });return false">Create a free account</a> and share your lessons publicly for other teachers to see</li>
        <li style="margin-top:5px"><a href="/app/search/">Find</a>, use and review lessons that other teachers have built</li>
        <li style="margin-top:5px">Tell the world how you're being a pioneer in your classroom on <a href="/spotlight">Pioneer Chat!</a></li>
      </ul>
    </div>



  </div>
</div>


<script>
$('#teachBut').click(function() {
    jQuery.facebox({ 
    	ajax: '/app/signup/teacher?forceURL=/app/'
  	});
});
</script>
<?php
pubFooter();
?>