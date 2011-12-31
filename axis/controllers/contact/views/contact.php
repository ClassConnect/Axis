 <?php

if (isset($_POST['field1'])) {

$msg = "From: " . $_POST['field1'] . "\n
Website: " . $_POST['field3'] . "\n
Phone: " . $_POST['field4'] . "\n\n

Subject: " . $_POST['field5'] . "\n
Message: " . $_POST['field6'] . "\n


";
            mail('founders@classconnect.com', 'CC Inquiry Email', $msg, "From: " . $_POST['field2'] . "");
        
        $announce = '<h2>Thanks! We\'ll be in touch soon.</h2><p>For immediate assistance, feel free to give us a call at (866) 844-5250 between 8am - 7pm PST.</p>';


} // isset POST submitted

?>

 <div id="header-mid">
            	<div id="top-shadow"></div>

                <div id="header-mid-inner">
                	<h2>Contact</h2>
                    <h3>
                    Have a question, comment, or concern?<br />
                    Our support team is always here to help you out.
                    </h3>
                </div><!-- END "#header-min-inner" -->

                <div id="bottom-shadow"></div>
            </div><!-- END "#header-min" -->

		</header><!-- END header -->

        <!--| content starts here |-->

        <div id="content">
            <div id="content-inner">

                <div id="content-inner-page">
                    <div class="page-box">
                        <?php
                        if (isset($announce)) {
                            echo $announce;
                        } else {
                            ?>
                        <!-- contact form -->
                        <form id="contactform" method="post" action="/contact/" style="margin-top:-20px">

                            <label for="field1">Name: *</label>
                            <input type="text" id="field1" value="" name="field1" tabindex="1" class="contact-input" />

                            <label for="field2">Email: *</label>
                            <input type="text" id="field2" value="" name="field2" tabindex="2" class="contact-input" />

                            <label for="field3">Website:</label>
                            <input type="text" id="field3" value="http://" name="field3" tabindex="3" class="contact-input" />

                            <label for="field4">Phone:</label>
                            <input type="text" id="field4" value="" name="field4" tabindex="4" class="contact-input" />

                            <label for="field5">Subject: *</label>
                            <input type="text" id="field5" value="" name="field5" tabindex="5" class="contact-input" />

                            <label for="field6">Message: *</label>
                            <textarea id="field6" name="field6" tabindex="6" class="contact-textarea"></textarea>

                            <input type="submit" id="submit" value="Send" name="submit" tabindex="7" />

                        </form>

                        <!-- form validation -->

                            <script type="text/javascript">
                            //<![CDATA[
                              var field1    = new LiveValidation('field1', {onlyOnSubmit: false, validMessage: " "});
                              var field2    = new LiveValidation('field2', {onlyOnSubmit: false, validMessage: " "});
                              //var field3    = new LiveValidation('field3', {onlyOnSubmit: false, validMessage: " "});
                             //var field4    = new LiveValidation('field4', {onlyOnSubmit: false, validMessage: " "});
                              var field5    = new LiveValidation('field5', {onlyOnSubmit: false, validMessage: " "});
                              var field6    = new LiveValidation('field6', {onlyOnSubmit: false, validMessage: " "});

                              field1.add( Validate.Presence,{failureMessage: " "});
                              field2.add( Validate.Email,{failureMessage: " "});
                              field2.add( Validate.Presence,{failureMessage: " "});
                              //field3.add( Validate.Presence,{failureMessage: " "});
                              //field4.add( Validate.Presence,{failureMessage: " "});
                              field5.add( Validate.Presence,{failureMessage: " "});
                              field6.add( Validate.Presence,{failureMessage: " "});
                           //]]>
                          </script>
<?php } ?>
                    </div><!-- END ".page-box" -->

                </div><!-- END "#content-inner-page" -->

                <div id="sidebar">
                    <div class="sidebar-box">
                    	<h3>Additional Contact Information</h3>
                        <ul class="sidebar-contact">
                            <li>
                                <p>ClassConnect Inc.</p>
                                <p>San Francisco, CA 94131</p>
                                <p>(866) 844-5250</p>
                                <p>support@classconnect.com</p>
                            </li>
                        </ul>
                    </div><!-- END ".sidebar-box" -->

                </div><!-- END "#sidebar" -->
            </div><!-- END "#content-inner" -->
        </div><!-- END "#content" -->