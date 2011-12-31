<?php
class iamaController extends Axis_Controller
{

        function _default()
        {

        }


        function _teacher()
        {
        	pubHeader('Teachers');
            require_once('views/teacher.php');
            pubFooter();
        }


        function _student()
        {
        	pubHeader('Students');
            require_once('views/student.php');
            pubFooter();
        }


        function _administrator()
        {
        	pubHeader('Administrators');
            require_once('views/admin.php');
            pubFooter();
        }

}
?>