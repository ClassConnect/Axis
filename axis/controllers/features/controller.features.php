<?php
class featuresController extends Axis_Controller
{

        function _default()
        {
                pubHeader('Features');
                require_once('views/features.php');
                pubFooter();
        }

}
?>