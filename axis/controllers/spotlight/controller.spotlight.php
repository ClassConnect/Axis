<?php
class spotlightController extends Axis_Controller
{
        function _default()
        {
                require_once('data/index.php');
                require_once('core/main.php');
        		require_once('views/index.php');
        }

        function _error() 
        {
        		require_once('data/index.php');
                require_once('core/main.php');
        		require_once('views/index.php');
        }

}
?>