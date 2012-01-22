<?php
class aboutController extends Axis_Controller
{
        function _default()
        {
                // include about CC file
                require_once('views/about-cc.php');
        }

        function _us() 
        {
        	// include about eric file
                require_once('views/about-us.php');
        }

        function _unitedweteach() 
        {
                // include about eric file
                require_once('views/about-uwt.php');
        }

        function _error()
        {
                showError();
        }

}
?>