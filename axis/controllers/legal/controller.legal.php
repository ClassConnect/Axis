<?php
class legalController extends Axis_Controller
{
        function _default()
        {
        	showError();
        }


        function _tos()
        {
                pubHeader('Terms of Service', true);
                require_once('views/tos.php');
                pubFooter();
        }

        function _privacy()
        {
                pubHeader('Privacy', true);
                require_once('views/privacy.php');
                pubFooter();
        }

}
?>