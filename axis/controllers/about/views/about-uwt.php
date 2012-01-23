<?php
appHeader('United We Teach');
?>
<div class="content"> 
	<div class="row" style="padding:20px;padding-top:0px"> 

<div style="font-family:Varela Round;font-size:18px;text-align:center;color:#666;font-weight:bolder;margin-top:15px">
	Every once in a while, a group of pioneers stray from the well-worn path and create the future.
</div>
<div style="font-family:Varela Round;font-size:18px;text-align:center;margin-top:7px;font-weight:bolder">
	This is happening in education <span style="text-decoration:underline">right now</span>.
</div>

<div style="font-size:14px;margin-top:30px">
If your main job was to manage students they would call you managers. But you're a teacher, and your goal is to share knowledge and make content come to life. Great content. Captivating content. The problem is that ever-changing government requirements and the individual needs of dozens of students ensures that there aren't enough hours in the day to do everything you want to do effectively.
<br /><br />
<strong>This is about to change.</strong>
<br /><br />
You've seen it in your own classrooms - your skill plus great content equals real learning! Kids love to learn when you use awesome content and it's easier for you to teach from.

<br /><br />
The challenge is finding the time to create all this incredible content. The solution? We work together! We're asking teachers from around the world to work together to create, share, and enhance the best educational content to ensure high quality learning for our students.

<br /><br />

<div style="float:right;margin-left:10px;margin-top:-5px">
	<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.classconnect.com/about/unitedweteach&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=90&amp;appId=213954741999891" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:50px; height:70px; margin-right:3px" allowTransparency="true"></iframe>

    <iframe allowtransparency="true" frameborder="0" marginwidth="0" scrolling="no" src="https://plusone.google.com/_/+1/fastbutton?url=http%3A%2F%2Fwww.classconnect.com/about/unitedweteach%2F&amp;size=tall&amp;count=true&amp;annotation=&amp;width=120&amp;hl=en-US&amp;jsh=m%3B%2F_%2Fapps-static%2F_%2Fjs%2Fwidget%2F__features__%2Frt%3Dj%2Fver%3DSXEYxs5FO0c.en_US.%2Fsv%3D1%2Fam%3D!KW4lzGmbF_KIhSW8Og%2Fd%3D1%2F#id=I1_1327178530968&amp;parent=http%3A%2F%2Fwww.classconnect.com/about/unitedweteach&amp;rpctoken=350075819&amp;_methods=onPlusOne%2C_ready%2C_close%2C_open%2C_resizeMe" style="border:none; overflow:hidden; width:50px; height:70px;margin-bottom:-1px;margin-right:3px" title="+1"></iframe>

    <iframe allowtransparency="true" frameborder="0" scrolling="no"
    src="//platform.twitter.com/widgets/tweet_button.html?count=vertical&text=I'm a pioneer! %23UnitedWeTeach&via=ClassConnectInc&url=http://www.classconnect.com/about/unitedweteach"
    style="width:55px; height:70px;"></iframe>
</div>
We all win when we work together. You have your own unique ideas to contribute so come and be heard! Join our community and let's take the road less traveled and pioneer the future. <strong>United We Teach.</strong>


<div style="clear:both"></div>
<div style="width:900px;border-bottom:1px solid #ddd;margin-top:25px"></div>

<div style="font-family:Varela Round;font-size:18px;text-align:center;margin-top:25px;margin-bottom:10px;font-weight:bolder">
This is the turning point for education. Get involved!
</div>

<a href="#" id="teachBut" onclick="return false" class="btn large danger" type="submit" style="font-weight:bolder;margin-top:10px;margin-left:55px"> 
Be a pioneer - join your colleagues and let's change education
</a>

<a href="/spotlight" class="btn large" type="submit" style="font-weight:bolder;margin-top:10px;margin-left:15px"> 
Meet some pioneers in our community!
</a>

</div> 


	</div>
</div>


<script>
$('#teachBut').click(function() {
    jQuery.facebox({ 
    	ajax: '/app/signup/teacher'
  	});
});
</script>
<?php
appFooter();
?>