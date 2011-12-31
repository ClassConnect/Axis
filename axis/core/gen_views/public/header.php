<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>ClassConnect | <?php echo $page_title; ?></title>

    <!-- // Meta //  -->
    <meta charset="utf-8">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
    <![endif]-->

    <!-- // Stylesheets // -->
    <!-- Core -->
    <link rel="stylesheet" href="/assets/public/css/style1.css" />
    <!-- internet explore -->
    <!--[if IE]>
    <link rel="stylesheet" href="css/style_ie.css" />
    <![endif]-->

    <link rel="shortcut icon" href="/favicon.ico">

    <!-- Modernizr which enables HTML5 elements & feature detects -->
    <script src="/assets/public/js/modernizr-1.6.min.js"></script>

    <!-- // Javascript/jQuery // -->
    <!-- jQuery core -->
<script type="text/javascript" src="/assets/ajax/jquery.js"></script>
    <!-- jQuery UI -->
    <script src="/assets/public/js/jquery-ui-1.8.6.min.js"></script>
    <!-- jQuery easing plugin -->
    <script src="/assets/public/js/jquery.easing.min.js"></script>
    <!-- jQuery lightbox -->
    <script src="/assets/public/js/pirobox-min.js"></script>
    <!-- jQuery twitter -->
    <script src="/assets/public/js/twitter.min.js"></script>
    <!-- jQuery cycle -->
    <script src="/assets/public/js/jquery.cycle.all.min.js"></script>
    <!-- jQuery preloader  -->
    <script src="/assets/public/js/jquery.preloader.js"></script>
    <!-- jQuery googlemaps  -->
    <script src="/assets/public/js/googlemaps.js"></script>
    <!-- jQuery custom -->
    <script src="/assets/public/js/main.js"></script>
    <!-- javascript form checker -->
    <script src="/assets/public/js/livevalidation.js"></script>
<!--[if IE 7]>
<style type="text/css">
.logBox{
 margin-left:-252px;
}
</style>
<![endif]-->
</head>
<body>
	<div id="container">

		<header id="index">
        	<div id="header-top">
            	<div id="header-top-inner">
                    <a href="/" id="logo">
                        <img src="/assets/public/images/logo.png" alt="" style="margin-left:20px" />
                        <h1>ClassConnect</h1>
                    </a>

                    <div id="wrap-menu">
                        <!-- dropdown menu -->
                        <ul id="header-menu">

                            <li><a href="/features/">Features</a></li>
                            <li><a href="/about/">About</a>

                                    <!-- second lvl -->
                                    <ul class="second-lvl">
                                        <li><a href="/about/company/">Our Company</a></li>
                                        <li><a href="/about/team/">Our Team</a></li>
                                        <li><a href="/about/advisors/">Advisors</a></li>
                                        <li><a href="/contact/">Contact Us</a></li>
                                    </ul>
                                    <!--/ END second lvl -->
                            </li>
                            <li><a href="/blog/">Blog</a></li>
                            <li><a href="#" onClick="openBox($('#signupPop').html(), 402, 1); return false;">Signup</a></li>
                            <div id="signupPop" style="display:none">
                                <div class="headTitle"><div style="position:absolute; margin-left:365px;margin-top:-35px"><a href="#" onClick="closeBox(); return false"><img src="/images/close.png" /></a></div><div>Signup as a...</div></div>
                                <div class="hoverSwapper" onClick="window.location = '/app/signup.cc';">
                                    <div class="inSwap"><img src="/app/core/site_img/gen/teacher.png" /> Teacher / Admin<br />
                                    <div class="swapSmall">You need an email address to signup.</div>
                                    </div>
                                </div>
                                <div class="hoverSwapper" onClick="window.location = '/app/enroll.cc';">

                                    <div class="inSwap"><img src="/app/core/site_img/gen/student.png" /> Student<br />
                                    <div class="swapSmall">You will need a class code from your teacher to signup.</div>
                                    </div>
                                    
                                </div>
                                
                            </div>
                            
                            <li><a href="#" id="login-catcher" onClick="return false;">Login</a>
                                <div class="logBox">
                                    <div style="float:right; margin-top:-4px; border-top:4px solid #fff; width:55px;"></div>
    <!-- hidden login box -->
    <div id="loginer-box">
        <form action="/app/login.cc" method="post">
        <input type="text" onfocus="if(this.value=='Username / Email')this.value='';" onblur="if(this.value=='')this.value='Username / Email';" value="Username / Email" name="identity" class="input-1" />
        <input type="password" onfocus="if(this.value=='Password')this.value='';" onblur="if(this.value=='')this.value='Password';" value="Password" name="pass" class="input-1" />
        <div class="loginer-submit">
            <a href="/app/reset_password.cc">Forgot your password?</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" id="login-btn" value="Login" />
        </div>
        <input type="hidden" name="submitted" value="submitted" />
       </form>
    </div>

                                </div>
                            </li>
                        </ul>

                    </div><!-- END "#wrap-menu" -->
                </div><!-- END "#header-top-inner" -->
            </div><!-- END "#header-top" -->   