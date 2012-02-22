<?php
class spotlightController extends Axis_Controller
{
        function _default()
        {
        	require_once('views/index.php');
        }

        function _error() 
        {
        	showError();
        }

}
?>