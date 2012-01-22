<?php
$bdata = genMainView($blogs, $this->Command->Parameters[0]);
appHeader($bdata['title']);
?>
<style type="text/css">
#pickBox {
	width:207px;
	height:500px;
	padding-top:15px;
	float:right;
}
#listBox {
	width:207px;
	height:410px;
	padding-top:5px;
	margin-top:5px;
	overflow: auto;
	overflow-x: hidden;
	border-top:2px solid #eee;
}
#listBox .newLnk{
	font-size:11px;
	margin-right:3px;
	margin-bottom:1px;
	-webkit-border-radius: 0 6px 6px 0;
     -moz-border-radius: 0 6px 6px 0;
          border-radius: 0 6px 6px 0;
    margin-left:-1px;
    width:170px;
}
#blogBox {
	width:690px;
	background:#fff;
	-webkit-border-radius: 0 0 6px 6px;
     -moz-border-radius: 0 0 6px 6px;
          border-radius: 0 0 6px 6px;
    -webkit-box-shadow: 0 1px 5px rgba(0,0,0,.35);
     -moz-box-shadow: 0 1px 5px rgba(0,0,0,.35);
          box-shadow: 0 1px 5px rgba(0,0,0,.35);
}
#blogBox .main-Content{
	padding:15px;
}

#blogBox .descriptor{
	background:#eee;
	width:641px;
	padding:10px;
	-webkit-border-radius: 4px 4px 4px 4px;
     -moz-border-radius: 4px 4px 4px 4px;
          border-radius: 4px 4px 4px 4px;
}
</style>

<?php
echo $bdata['data'];
appFooter();
?>