<?php
// generate the home page's thing at bottom left
function genHomeSnip($blogs) {
	$blogs = sort2d($blogs, 'stamp', 'desc', true);
	$blog = $blogs[0];
	return '<div class="btn featureBtn" onClick="window.location = \'/spotlight/u/' . $blog['url'] .'\';">
          <div class="videoTitle">
            ' . $blog['title'] .'
          </div>

          <a href="/spotlight/u/' . $blog['url'] .'">
            <div class="videoPreview" style="background:url(http://img.youtube.com/vi/' . $blog['vidID'] .'/' . $blog['vidThumb'] .'.jpg) no-repeat">
            <img src="/assets/public/play.png" class="playBtn" />
            </div>
          </a>

          <div class="videoText">
          ' . $blog['desc'] .'
          </div>
        </div>';
}


function genMainView($blogs, $url) {
	$final = '';
	// generate the pick box
	$final .= '<div id="pickBox">
<a href="#" class="btn danger" style="width:175px" onclick="olark(\'api.box.expand\'); return false">
<center><span style="font-weight:bolder;font-size:18px">Are you a pioneer?</span></center>
We would love to hear about what you\'re up to - contact us!
</a>
	<div id="listBox">';

	$count = 1;
	$blogs = sort2d($blogs, 'stamp', 'desc', true);
	foreach ($blogs as $blog) {
		if ($count == 1) {
			$primary = $blog;
		}
		if ($blog['url'] == $url) {
			$primary = $blog;
		}
		$final .= '<a href="/spotlight/u/' . $blog['url'] . '" class="btn newLnk">
		<strong>' . $blog['title'] . '</strong>
		<span style="color:#666;font-size:9px"><br />' . date("m/d/Y", $blog['stamp']) . '</span>
		</a>';
		$count++;
	}

$final .= '</div>
</div>';


// now we format the blog
$final .= '<div id="blogBox"><div class="main-Content">


<div style="font-size:24px; font-weight:bolder;margin-bottom:15px">' . $primary['title'] . '</div>';

if (isset($primary['vidID'])) {
	$final .= '<iframe width="660" height="400" class="vidView" src="http://www.youtube.com/embed/' . $primary['vidID'] . '?showinfo=0&hd=1&autoplay=1" frameborder="0" allowfullscreen></iframe>';
}

$final .= '<div class="descriptor">
<div style="float:right;margin-bottom:-10px">
		<iframe src="//www.facebook.com/plugins/like.php?href=' . urlencode('http://www.classconnect.com/spotlight/u/' . $primary['url']) . '&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=90&amp;appId=213954741999891" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:50px; height:70px; margin-right:3px" allowTransparency="true"></iframe>

        <iframe allowtransparency="true" frameborder="0" marginwidth="0" scrolling="no" src="https://plusone.google.com/_/+1/fastbutton?url=' . urlencode('http://www.classconnect.com/spotlight/u/' . $primary['url']) . '&amp;size=tall&amp;count=true&amp;annotation=&amp;width=120&amp;hl=en-US&amp;jsh=m%3B%2F_%2Fapps-static%2F_%2Fjs%2Fwidget%2F__features__%2Frt%3Dj%2Fver%3DSXEYxs5FO0c.en_US.%2Fsv%3D1%2Fam%3D!KW4lzGmbF_KIhSW8Og%2Fd%3D1%2F#id=I1_1327178530968&amp;parent=' . urlencode('http://www.classconnect.com/spotlight/u/' . $primary['url']) . '&amp;rpctoken=350075819&amp;_methods=onPlusOne%2C_ready%2C_close%2C_open%2C_resizeMe" style="border:none; overflow:hidden; width:50px; height:70px;margin-bottom:-1px;margin-right:3px" title="+1"></iframe>

        <iframe allowtransparency="true" frameborder="0" scrolling="no"
        src="//platform.twitter.com/widgets/tweet_button.html?count=vertical&text=' . $primary['title'] . ' %23UnitedWeTeach&via=ClassConnectInc&url=' . urlencode('http://www.classconnect.com/spotlight/u/' . $primary['url']) . '"
        style="width:55px; height:70px;"></iframe>
</div>
' . $primary['body'] . '
<div style="clear:both"></div>
</div>';

$final .= '</div></div>';



return array("title" => $primary['title'], "data" => $final);
}
?>