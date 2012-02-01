<?php
class errorController extends Axis_Controller
        {
        function _default()
                {
                	require_once('axis/controllers/app/profile/index.php');
               		//header('location:/app/');
                }

        function _error()
        		{
                	require_once('axis/controllers/app/profile/index.php');
        		}

        }
?>