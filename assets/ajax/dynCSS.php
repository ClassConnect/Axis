<?php
session_start();
require_once('../../axis/core/coreInc.php');
if (isset($_SESSION['user_id'])) {
	$themeData =  getVanityScheme();
	$color = $theme['settingColor'];
} else {
    $themeData =  getVanityScheme();
    $color = $themeData['settingColor'];
}
header('Content-type: text/css');

$css = "
a:hover {
	color: " . $color . "; 
	background-color: inherit;
}

html ul.topnav li ul.subnav li a:hover { /*--Hover effect for subnav links--*/  
    background: " . $color . ";
	color: #FFF;
} 


#main h1 {
	margin: 0 0;
	
	padding: 0 0 0 5px;
	font-size: 14px;
	border-bottom: 1px solid #770000;
	color: #fff;
	background: " . $color . ";
	letter-spacing: 0px;
}

#class_main h1 {
	margin: 0 0;
	
	padding: 0 0 0 5px;
	font-size: 14px;
	border: 2px solid #770000;
	color: #fff;
	background: " . $color . ";
	letter-spacing: 0px;
		/*--Top left rounded corner--*/
	-moz-border-radius-topleft: 5px;
	-khtml-border-radius-topleft: 5px;
	-webkit-border-top-left-radius: 5px;
	/*--Top right rounded corner--*/
	-moz-border-radius-topright: 5px;
	-khtml-border-radius-topright: 5px;
	-webkit-border-top-right-radius: 5px;
}

#class_sidebar li.active_item {
	color: " . $color . ";
	font-weight: bolder;
                   border-left: 4px solid " . $color . ";
}
#class_sidebar li.active_item a{
	color: " . $color . ";

}

#teach_menu .item:hover {
background: #f5f5f2;
border-bottom: 3px solid " . $color . ";
}

#barline {
border-bottom: 1px solid #CCCCCC;
}

#chatpanel .subpanel li a:hover {
	background: " . $color . ";
	color: #fff;
	text-decoration: none;
}


#alertpanel .subpanel li a.delete:hover { background-color: " . $color . "; }
#footpanel #alertpanel li.view {
	text-align: right;
	padding: 5px 10px 5px 0;
}

#updateBox .postButton {
	float:left;
	background: " . $color . " url(" . imgServer() . "main/sub_rep.png) repeat-x;
                  color: #fff;
	font-size:16px;
	width:100px;
	height: 31px;
	border: 1px solid #999;
	outline: none;
        padding-top:5px;
        font-weight:bolder;
        margin-left:5px;
        -moz-border-radius: 0px;
-khtml-border-radius: 0px;
-webkit-border-radius: 0px;
}

#lightbox {  
 display:none;  
 background:" . $color . ";
 position:fixed;
 top:0px;  
 left:0px;  
 min-width:100%;  
 min-height:100%;  
 z-index:1000;
}  

div.sdmenu div a:hover {
	background : " . $color . ";
	color: #fff;
	text-decoration: none;
}


div.jGrowl div.jGrowl-notification a{
	color: " . $color . ";
}

.fc-event,
.fc-agenda .fc-event-time,
.fc-event a {
	border-style: solid; 
	border-color: #666;     /* default BORDER color (probably the same as background-color) */
	color: #fff;            /* default TEXT color */
	}

.facebook-auto ul li.auto-focus { background: " . $color . "; color: #fff; }

.bevColor {
background: " . $color . " url(" . imgServer() . "main/sub_rep.png) repeat-x;
}

.bevColor a{
    color: #fff;
}

.borderColor {
border-bottom: 4px solid " . $color . ";
}


.eventsList  a{
    color: " . $color . ";
    font-weight:bolder;
}

.eventsList  a:hover{
    color: " . $color . ";
    font-weight:bolder;
}

#sbox_menu .active {
background: #f1f1ec;
border-top: 3px solid " . $color . ";
border-left:1px solid #BABABA;
border-right:1px solid #BABABA;
padding-left:7px;
padding-right:7px;
}


.folderActive {
background-color: " . $color . ";
color: #fff;
}

.folderActive:hover {
background: " . $color . ";
color: #fff;
}


#managebar .active {
background: #f1f1ec;
border-top: 3px solid " . $color . ";
border-left:1px solid #BABABA;
border-right:1px solid #BABABA;
padding-left:7px;
padding-right:7px;
}



.guider_content h1 {
  color: " . $color . ";
  font-size: 21px;
  line-height:100%;
}

.guider_content p {
  color: #333;
  font-size: 13px;
}

.guider_button {
background: " . $color . " url(" . imgServer() . "main/sub_rep.png) repeat-x;
  border: solid 1px #4B5D7E;
  color: #FFF;
  cursor: pointer;
  display: inline-block;
  float: right;
  font-size: 12px;
  font-weight: bold;
  margin-left: 6px;
  min-width: 40px;
  padding: 3px 5px;
  text-align: center;
  text-decoration: none;
  /* Rounded corners */
  -moz-border-radius: 2px;
  -webkit-border-radius: 2px;
  border-radius: 2px;
  /* End rounded corners */
}

.schColor {
	background-color: " . $color . ";
}

";

$css = str_replace("\n", '', $css);
$css = str_replace("\t", '', $css);
echo $css;
?>