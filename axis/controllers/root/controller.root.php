<?php
class rootController extends Axis_Controller
{
        function _default()
        {
        	if (checkSession()) {
        		header('location:/app/');
        	} else {
        		pubHeader('ClassConnect | Find, build and share lessons');
	        	require_once('views/index.php');
	        	pubFooter();
        	}
        }

}
?>