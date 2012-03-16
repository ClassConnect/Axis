<?php
pubHeader('Pioneer Chat', true);
?>
<div id="videoSplash" style="width:720px;height:500px;margin-left:80px;margin-top:20px"><br /><br /><br /><br /><br /><br /><br /><br /><br /><center><img src="/assets/app/img/box/loading.gif" /></center></div>
<div id="videoManifest" style="display:none"><?php
if ($this->Command->Parameters[0] == '') {
	echo 0;
} else {
	echo $this->Command->Parameters[0];
}
?></div>



  </div>
</div>

<div class="splashDesc">
  <div class="container">

  	<div id="fillMeUp" class="pioneerGallery">

  	</div>
    
    
  </div>
</div>


<script type="text/javascript">
function initPioneer(videoID, title) {
	$("#videoSplash").html(formatData(videoID, title));
}
</script>

<?php
pubFooter();
?>