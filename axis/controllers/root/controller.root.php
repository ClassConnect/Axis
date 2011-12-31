<?php
class rootController extends Axis_Controller
{
        function _default()
        {
        	pubHeader('Home');
        	require_once('views/index.php');
        	pubFooter();
        }

}
?>