<?php session_start(); ?>
<script type="text/javascript" src="http://www.ccrte.com:8124/faye.js"></script>

<script type="text/javascript">
var client = new Faye.Client('http://www.ccrte.com:8124/faye');
var subscription = client.subscribe('/<?php echo session_id(); ?>', function(message) {
	window.parent.receiveCCMessage(message); 
});
</script>