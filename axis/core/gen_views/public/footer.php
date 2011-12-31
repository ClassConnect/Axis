</div><!-- END "#container" -->

<!--| footer starts here |-->

    <footer>
<?php if(isset($foot_msg)) { ?>
        <div id="footer-top">
        	<div id="footer-bar-shadow-top"></div>

            <div id="footer-top-inner">
<?php echo $foot_msg; ?>
            </div><!-- END "#footer-top-inner" -->

            <div id="footer-bar-shadow-bottom"></div>
        </div><!-- END "#footer-top" -->
<?php } ?>

        <div id="footer-mid">
        	<div id="footer-mid-inner">


                <div class="footerbox-mid">
                <div>
                    <span class="footerHeader" style="color:#fff">About Us</span><br /><br />
                    <span class="footerHeader"><a href="/about/company/">Our Company</a></span><br />
                    <span class="footerHeader"><a href="/about/team/">Our Team</a></span><br />
                    <span class="footerHeader"><a href="/about/advisors/">Our Advisors</span><br />
              </div>
              <div style="margin-top:50px">
                <span class="footerHeader" style="color:#fff">Follow Us</span><div style="clear:both;margin-top:15px"></div>
                <a href="http://www.facebook.com/pages/ClassConnect/178960745494321" target="_blank"><img src="/assets/public/images/fb.png" style="float:left; margin-left:13px" /></a>
                <a href="http://www.twitter.com/ClassConnectInc" target="_blank"><img src="/assets/public/images/twitter.png" style="float:left; margin-left:13px" /></a>
                <a href="http://www.youtube.com/user/ClassConnectInc" target="_blank"><img src="/assets/public/images/yt.png" style="float:left; margin-left:13px" /></a>
              </div>
                </div><!-- END ".footerbox-mid" -->

                <div class="footerbox-connect">
                    <span class="footerHeader" style="color:#fff">Latest Blog Posts</span>

<?php
// Include WordPress

define('WP_USE_THEMES', false);
if(file_exists('wp-load.php')){
  require('wp-load.php');
} else {
  require('blog/wp-load.php');
}
query_posts('showposts=6');
?>

<?php while (have_posts()): the_post(); ?>
<li style="border-bottom:1px solid #ccc;list-style:none"><a href="<?php the_permalink(); ?>" style="color:#fff"><?php the_title(); ?></a></li>
<?php endwhile; 
?>

                </div><!-- END ".footerbox-mid" -->

                <div class="footerbox-right">
                <span class="footerHeader" style="color:#fff">Contact Us</span>
                    <div class="numbero" style="text-align:center;margin-top:15px">(866) 844-5250</div>
                    <div class="numbero" style="text-align:center;margin-top:10px;margin-bottom:20px">or we'll call you.</div>
                     <form id="footer-form" method="post" style="margin-top:-10px;clear:both">
                        <input type="text" onfocus="if(this.value=='Name')this.value='';" onblur="if(this.value=='')this.value='Name';" value="Name" name="field1footer" class="footer-input" id="field1footer" />
                        <input type="text" onfocus="if(this.value=='Phone Number')this.value='';" onblur="if(this.value=='')this.value='Phone Number';" value="Phone Number" name="field2footer" class="footer-input" id="field2footer" />
                        <textarea onfocus="if(this.value=='Any questions or comments?')this.value='';" onblur="if(this.value=='')this.value='Any questions or comments?';" class="footer-textarea" name="field3footer" id="field3footer" >Any questions or comments?</textarea>
                        <button class="silverButtonset" type="submit" style="width:80px;height:35px;float:right;font-size:14px;border:none">Call me!</button>
                    </form>
                </div><!-- END ".footerbox-right"" -->

                        <!-- form validation -->

                            <script type="text/javascript">
                            //<![CDATA[
                              var field1footer    = new LiveValidation('field1footer', {onlyOnSubmit: false, validMessage: " "});
                              var field2footer    = new LiveValidation('field2footer', {onlyOnSubmit: false, validMessage: " "});
                              var field3footer    = new LiveValidation('field3footer', {onlyOnSubmit: false, validMessage: " "});

                              field1footer.add( Validate.Presence,{failureMessage: " "});
							  field1footer.add( Validate.Exclusion, { within: [ 'Name' ] } );
                              field2footer.add( Validate.Email,{failureMessage: " "});
                              field2footer.add( Validate.Presence,{failureMessage: " "});
                              field3footer.add( Validate.Presence,{failureMessage: " "});
                           //]]>
                          </script>

            </div><!-- END "#footer-main-inner" -->
        </div><!-- END "#footer-main" -->

        <div id="footer-bottom">
        	<div id="footer-bottom-inner">
                <div id="copyright">
                	<p>ClassConnect Inc &copy; 2011</p>
                </div>
                <ul id="footer-menu">
                    <li><a href="/">Home</a></li>
                    <li><a href="/about-company.cc">About</a></li>
                    <li><a href="/blog/">Blog</a></li>
                    <li><a href="/contact.cc">Contact</a></li>
                    <li><a href="/app/login.cc">Login</a></li>
                    <li><a href="javascript:void(0);" id="top">(top)</a></li>
                </ul>
            </div><!-- END "#footer-bottom-inner" -->
        </div><!-- END "#ffooter-bottom" -->
    </footer><!-- END footer -->
<div id="dialogBox"></div><div id="blackbox"></div><div id="clearbox"></div> 

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-25057889-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>