<?php
class aboutController extends Axis_Controller
{

        function _default()
        {
                pubHeader('Our Company');
                require_once('views/company.php');
                pubFooter();
        }
        function _company()
        {
                pubHeader('Our Company');
                require_once('views/company.php');
                pubFooter();
        }
        function _team()
        {
                pubHeader('Our Team');
                require_once('views/team.php');
                pubFooter();
        }
        function _advisors()
        {
                pubHeader('Our Advisors');
                require_once('views/advisors.php');
                pubFooter();
        }
}
?>