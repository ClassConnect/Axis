<?php
class contactController extends Axis_Controller
{

        function _default()
        {
                pubHeader('Contact Us');
                require_once('views/contact.php');
                pubFooter();
        }
}
?>