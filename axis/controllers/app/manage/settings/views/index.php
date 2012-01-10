<?php
appHeader('Settings', '');
$udata = getUser(user('id'));
?>
<div class="content"> 
    <div class="row" style="clear:both">
    <div style="margin-left:20px;margin-right:20px">


<ul class="tabs">
  <li class="active"><a href="#info">Personal Info & Password</a></li>
  <li><a href="#icon">Notifications</a></li>
  <li><a href="#icon">Location & Language</a></li>
</ul>
 
<div class="pill-content">
  <div class="active" id="info">

<div style="clear:both">

<div style="width:610px;float:right">
<form action="#" id="update-personal">

<div style="font-size:20px;color:#555;margin-bottom:8px">Name</div>

<div><?= dispOnly('<select id="title" name="title" class="small">
        <option>' . $udata['pre_name'] . '</option>
        <option>' . say('Mr.') . '</option>
        <option>' . say('Mrs.') . '</option>
        <option>' . say('Ms.') . '</option>
        <option>' . say('Dr.') . '</option>
      </select>', 3); ?> <input name="first_name" size="30" type="text" placeholder="First name" value="<?= $udata['first_name']; ?>"> <input name="last_name" size="30" type="text" placeholder="Last name" value="<?= $udata['last_name']; ?>"></div>
<div style="color:#999;margin-top:5px;font-size:11px">
<?= dispOnly('Students will see your title instead of your first name (ie "Mr. Saget")', 3); ?>
<?= dispOnly('Your teachers will be notified if you change your name.', 1); ?>
</div>


<div style="font-size:20px;color:#555;margin-bottom:8px;margin-top:30px">Email Address</div>
<input name="e_mail" size="30" type="text" placeholder="Email address" style="width:300px" value="<?= $udata['e_mail']; ?>">
<div style="color:#999;margin-top:5px;font-size:11px">Having an email address allows you to reset your password if you ever forget it</div>


<div style="font-size:20px;color:#555;margin-bottom:8px;margin-top:30px">Change Password</div>
<input name="pass1" size="30" type="text" placeholder="New password"> <input name="pass2" size="30" type="text" placeholder="Confirm password">

<div style="margin-top:30px">
  <input type="hidden" name="submitted" value="true" />
  <button id="subbtn1" type="submit" class="btn primary large">Update personal settings</button>
</div>


</form>
</div>

<div style="width:225px;margin-left:15px">
  <center>
    <img src="<?= iconServer(); ?>210_<?= dispUser(user('id'), 'prof_icon'); ?>" class="vidView" style="background-image:none;margin-bottom:10px;" /><br />
    <a href="#" onclick="jQuery.facebox({ div:'#iconOpen' }); return false" class="btn"><img src="/assets/app/img/temp/change.png" style="float:left;margin-right:8px" />Change Picture</a>
  </center>
</div>

</div>




  </div>
</div>
 
<script>
  $(function () {
    $('.tabs').tabs();
  });

$('#update-personal').submit(function() {
  $('#subbtn1').append('<img id="rmSoon" src="/assets/app/img/box/miniload.gif" style="float:right;margin-top:4px;margin-left:10px" />');
   var serData = $("#update-personal").serialize();
    fbFormDisable('#update-personal');
    $.ajax({  
      type: "POST",  
      url: "/app/manage/settings/personal",  
      data: serData,
      success: function(retData) {
        alert(retData);
        if (retData == 1) {
          initAsyncBar('<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Settings updated successfully</span>', 'yellowBox', 220, 485, 2000);

        } else {
          // show error
        }

        fbFormEnable('#update-personal');
        $("#rmSoon").remove();

      }  
      
  });  
    return false;
});
</script>












<div id="iconOpen" style="display:none">
    <form action="/app/manage/settings/icon" method="post" enctype="multipart/form-data" id="change-icon" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <legend>Change Picture</legend>
    <div class="clearfix">
    <div id="errorBox" style="display:none"></div>

<div style="font-size:12px; font-weight:bolder; color:#666; margin-bottom:3px">Choose an image</div>
    <div class="input">
      <input type="file" name="file" id="file" /> 
    </div>

    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn primary">Upload new picture</button>&nbsp;<button class="btn" onClick="closeBox();return false">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>
  </div>


</div></div></div>

<?php appFooter(); ?>