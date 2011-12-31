<?php
class appController extends Axis_Controller
{

        function _default()
        {
        	if (!checkSession()) {
        		showLogin();
        	} else {
                        appHeader('Latest', '<script type="text/javascript" src="/assets/app/js/home/main.js"></script>', 1);
                        require_once('home/index.php');
                        appFooter();       
                }
        	
        }


        function _login()
        {
        	// check if this bro is already logged in
        	// if he isn't, show that login screen
        	if (!checkSession()) {
        		showLogin();
	        } else {
	        	// it they are already logged in, send them home
	        	header('location: /app/');
	        }
        }



        function _filebox()
        {
                require_once('filebox/core/main.php');
                // filebox routing
                require_once('filebox/index.php');
        }


        function _common()
        {
                require_once('common/index.php');
        }



        function _course()
        {
                if (!checkSession()) {
                        showLogin();
                } else {
                        require_once('course/core/main.php');
                        require_once('course/index.php');
                }
        }


        function _calendar()
        {
                require_once('calendar/core/main.php');
                require_once('calendar/index.php');
        }


        function _manage()
        {
                if (!checkSession()) {
                        showLogin();
                } else {
                        require_once('manage/index.php');
                }
        }



        function _signup()
        {
                if (!checkSession()) {
                        require_once('signup/core/main.php');
                        // signup routing
                        require_once('signup/index.php');
                } else {
                        header('location: /app/');
                }
        }


        function _livelecture()
        {
                if (!checkSession()) {
                        showLogin();
                } else {
                        underUpdate();
                }
        }


        function _docs()
        {
                if (!checkSession()) {
                        showLogin();
                } else {
                        underUpdate();
                }
        }


        function _logout()
        {
        	killSession();
        	header('location: /app/?lo=true');
        }

}
?>