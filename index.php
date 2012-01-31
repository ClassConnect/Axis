<?php
ob_start();
require_once('axis/lib/axis.command.php');
require_once('axis/lib/axis.urlinterpreter.php');
require_once('axis/lib/axis.commanddispatcher.php');
require_once('axis/lib/axis.controller.php');
require_once('axis/core/coreInc.php');
// set date/time & (soon) default language
setLocales();

$urlInterpreter = new Axis_URLInterpreter();
$command = $urlInterpreter->getCommand();
$commandDispatcher = new Axis_CommandDispatcher($command);
$commandDispatcher->Dispatch();


$buffer = ob_get_clean();
// set a custom header (just for kicks)
header('X-Powered-By: ClassConnect Axis');
// clean & send the final buffer
echo cleanBuffer($buffer);
/*
$domainarray = explode('.', $_SERVER['HTTP_HOST']);
$index=count($domainarray)-1;
$domainname= $domainarray[$index-1].".".$domainarray[$index];
$subdomainname="";
for($i=0;$i<$index-1;$i++)
{
if($subdomainname=="")
{
$subdomainname=$domainarray[$i];
}
else
{
$subdomainname=$subdomainname.".".$domainarray[$i];
}

}

if ($subdomainname != '' && $subdomainname != 'www'){
    header('location: /app/login.cc');
}
*/
?>