<?php
appHeader('Settings', '');
$udata = getUser(user('id'));
?>
<div class="content"> 
    <div class="row" style="clear:both">
    <div style="margin-left:20px;margin-right:20px">


<ul class="tabs">
  <li class="active"><a href="#personalup">Personal Info & Password</a></li>
  <!-- <li><a href="#notiup">Notifications</a></li> -->
  <li><a href="#loclang">Location & Language</a></li>
</ul>
 
<div class="pill-content">
  <div class="active" id="personalup">

<div style="clear:both">

<div style="width:610px;float:right">
<form action="#" id="update-personal">

<div id="persError"></div>

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




  <div id="notiup">
  Noti here

  </div>


  <div id="loclang">

  <div style="font-size:20px;color:#555;margin-bottom:8px">Location/Timezone</div>
  <?php
// create an array listing the time zones
$zonelist = array('Kwajalein' => '(GMT-12:00) International Date Line West',
    'Pacific/Midway' => '(GMT-11:00) Midway Island',
    'Pacific/Samoa' => '(GMT-11:00) Samoa',
    'Pacific/Honolulu' => '(GMT-10:00) Hawaii',
    'America/Anchorage' => '(GMT-09:00) Alaska',
    'America/Los_Angeles' => '(GMT-08:00) Pacific Time (US &amp; Canada)',
    'America/Tijuana' => '(GMT-08:00) Tijuana, Baja California',
    'America/Denver' => '(GMT-07:00) Mountain Time (US &amp; Canada)',
    'America/Chihuahua' => '(GMT-07:00) Chihuahua',
    'America/Mazatlan' => '(GMT-07:00) Mazatlan',
    'America/Phoenix' => '(GMT-07:00) Arizona',
    'America/Regina' => '(GMT-06:00) Saskatchewan',
    'America/Tegucigalpa' => '(GMT-06:00) Central America',
    'America/Chicago' => '(GMT-06:00) Central Time (US &amp; Canada)',
    'America/Mexico_City' => '(GMT-06:00) Mexico City',
    'America/Monterrey' => '(GMT-06:00) Monterrey',
    'America/New_York' => '(GMT-05:00) Eastern Time (US &amp; Canada)',
    'America/Bogota' => '(GMT-05:00) Bogota',
    'America/Lima' => '(GMT-05:00) Lima',
    'America/Rio_Branco' => '(GMT-05:00) Rio Branco',
    'America/Indiana/Indianapolis' => '(GMT-05:00) Indiana (East)',
    'America/Caracas' => '(GMT-04:30) Caracas',
    'America/Halifax' => '(GMT-04:00) Atlantic Time (Canada)',
    'America/Manaus' => '(GMT-04:00) Manaus',
    'America/Santiago' => '(GMT-04:00) Santiago',
    'America/La_Paz' => '(GMT-04:00) La Paz',
    'America/St_Johns' => '(GMT-03:30) Newfoundland',
    'America/Argentina/Buenos_Aires' => '(GMT-03:00) Georgetown',
    'America/Sao_Paulo' => '(GMT-03:00) Brasilia',
    'America/Godthab' => '(GMT-03:00) Greenland',
    'America/Montevideo' => '(GMT-03:00) Montevideo',
    'Atlantic/South_Georgia' => '(GMT-02:00) Mid-Atlantic',
    'Atlantic/Azores' => '(GMT-01:00) Azores',
    'Atlantic/Cape_Verde' => '(GMT-01:00) Cape Verde Is.',
    'Europe/Dublin' => '(GMT) Dublin',
    'Europe/Lisbon' => '(GMT) Lisbon',
    'Europe/London' => '(GMT) London',
    'Africa/Monrovia' => '(GMT) Monrovia',
    'Atlantic/Reykjavik' => '(GMT) Reykjavik',
    'Africa/Casablanca' => '(GMT) Casablanca',
    'Europe/Belgrade' => '(GMT+01:00) Belgrade',
    'Europe/Bratislava' => '(GMT+01:00) Bratislava',
    'Europe/Budapest' => '(GMT+01:00) Budapest',
    'Europe/Ljubljana' => '(GMT+01:00) Ljubljana',
    'Europe/Prague' => '(GMT+01:00) Prague',
    'Europe/Sarajevo' => '(GMT+01:00) Sarajevo',
    'Europe/Skopje' => '(GMT+01:00) Skopje',
    'Europe/Warsaw' => '(GMT+01:00) Warsaw',
    'Europe/Zagreb' => '(GMT+01:00) Zagreb',
    'Europe/Brussels' => '(GMT+01:00) Brussels',
    'Europe/Copenhagen' => '(GMT+01:00) Copenhagen',
    'Europe/Madrid' => '(GMT+01:00) Madrid',
    'Europe/Paris' => '(GMT+01:00) Paris',
    'Africa/Algiers' => '(GMT+01:00) West Central Africa',
    'Europe/Amsterdam' => '(GMT+01:00) Amsterdam',
    'Europe/Berlin' => '(GMT+01:00) Berlin',
    'Europe/Rome' => '(GMT+01:00) Rome',
    'Europe/Stockholm' => '(GMT+01:00) Stockholm',
    'Europe/Vienna' => '(GMT+01:00) Vienna',
    'Europe/Minsk' => '(GMT+02:00) Minsk',
    'Africa/Cairo' => '(GMT+02:00) Cairo',
    'Europe/Helsinki' => '(GMT+02:00) Helsinki',
    'Europe/Riga' => '(GMT+02:00) Riga',
    'Europe/Sofia' => '(GMT+02:00) Sofia',
    'Europe/Tallinn' => '(GMT+02:00) Tallinn',
    'Europe/Vilnius' => '(GMT+02:00) Vilnius',
    'Europe/Athens' => '(GMT+02:00) Athens',
    'Europe/Bucharest' => '(GMT+02:00) Bucharest',
    'Europe/Istanbul' => '(GMT+02:00) Istanbul',
    'Asia/Jerusalem' => '(GMT+02:00) Jerusalem',
    'Asia/Amman' => '(GMT+02:00) Amman',
    'Asia/Beirut' => '(GMT+02:00) Beirut',
    'Africa/Windhoek' => '(GMT+02:00) Windhoek',
    'Africa/Harare' => '(GMT+02:00) Harare',
    'Asia/Kuwait' => '(GMT+03:00) Kuwait',
    'Asia/Riyadh' => '(GMT+03:00) Riyadh',
    'Asia/Baghdad' => '(GMT+03:00) Baghdad',
    'Africa/Nairobi' => '(GMT+03:00) Nairobi',
    'Asia/Tbilisi' => '(GMT+03:00) Tbilisi',
    'Europe/Moscow' => '(GMT+03:00) Moscow',
    'Europe/Volgograd' => '(GMT+03:00) Volgograd',
    'Asia/Tehran' => '(GMT+03:30) Tehran',
    'Asia/Muscat' => '(GMT+04:00) Muscat',
    'Asia/Baku' => '(GMT+04:00) Baku',
    'Asia/Yerevan' => '(GMT+04:00) Yerevan',
    'Asia/Yekaterinburg' => '(GMT+05:00) Ekaterinburg',
    'Asia/Karachi' => '(GMT+05:00) Karachi',
    'Asia/Tashkent' => '(GMT+05:00) Tashkent',
    'Asia/Kolkata' => '(GMT+05:30) Calcutta',
    'Asia/Colombo' => '(GMT+05:30) Sri Jayawardenepura',
    'Asia/Katmandu' => '(GMT+05:45) Kathmandu',
    'Asia/Dhaka' => '(GMT+06:00) Dhaka',
    'Asia/Almaty' => '(GMT+06:00) Almaty',
    'Asia/Novosibirsk' => '(GMT+06:00) Novosibirsk',
    'Asia/Rangoon' => '(GMT+06:30) Yangon (Rangoon)',
    'Asia/Krasnoyarsk' => '(GMT+07:00) Krasnoyarsk',
    'Asia/Bangkok' => '(GMT+07:00) Bangkok',
    'Asia/Jakarta' => '(GMT+07:00) Jakarta',
    'Asia/Brunei' => '(GMT+08:00) Beijing',
    'Asia/Chongqing' => '(GMT+08:00) Chongqing',
    'Asia/Hong_Kong' => '(GMT+08:00) Hong Kong',
    'Asia/Urumqi' => '(GMT+08:00) Urumqi',
    'Asia/Irkutsk' => '(GMT+08:00) Irkutsk',
    'Asia/Ulaanbaatar' => '(GMT+08:00) Ulaan Bataar',
    'Asia/Kuala_Lumpur' => '(GMT+08:00) Kuala Lumpur',
    'Asia/Singapore' => '(GMT+08:00) Singapore',
    'Asia/Taipei' => '(GMT+08:00) Taipei',
    'Australia/Perth' => '(GMT+08:00) Perth',
    'Asia/Seoul' => '(GMT+09:00) Seoul',
    'Asia/Tokyo' => '(GMT+09:00) Tokyo',
    'Asia/Yakutsk' => '(GMT+09:00) Yakutsk',
    'Australia/Darwin' => '(GMT+09:30) Darwin',
    'Australia/Adelaide' => '(GMT+09:30) Adelaide',
    'Australia/Canberra' => '(GMT+10:00) Canberra',
    'Australia/Melbourne' => '(GMT+10:00) Melbourne',
    'Australia/Sydney' => '(GMT+10:00) Sydney',
    'Australia/Brisbane' => '(GMT+10:00) Brisbane',
    'Australia/Hobart' => '(GMT+10:00) Hobart',
    'Asia/Vladivostok' => '(GMT+10:00) Vladivostok',
    'Pacific/Guam' => '(GMT+10:00) Guam',
    'Pacific/Port_Moresby' => '(GMT+10:00) Port Moresby',
    'Asia/Magadan' => '(GMT+11:00) Magadan',
    'Pacific/Fiji' => '(GMT+12:00) Fiji',
    'Asia/Kamchatka' => '(GMT+12:00) Kamchatka',
    'Pacific/Auckland' => '(GMT+12:00) Auckland',
    'Pacific/Tongatapu' => '(GMT+13:00) Nukualofa');
?>
<form action="#" id="update-locales">
  <select name="tz">
  <?php
  $settings = cleanSettings($udata['settings']);
foreach($zonelist as $key => $value) {
  if ($key == $settings['timezone']) {
    echo '    <option value="' . $key . '">' . $value . '</option>';
  }
}
?>
  <option value="America/Chicago">(GMT-06:00) Central Time (US &amp; Canada)</option>
  <option value="Pacific/Honolulu">(GMT-10:00) Hawaii</option>
  <option value="America/Anchorage">(GMT-09:00) Alaska</option>
  <option value="America/Los_Angeles">(GMT-08:00) Pacific Time (US &amp; Canada)</option>
  <option value="America/Phoenix">(GMT-07:00) Arizona</option>
  <option value="America/Denver">(GMT-07:00) Mountain Time (US &amp; Canada)</option>
  <option value="America/New_York">(GMT-05:00) Eastern Time (US &amp; Canada)</option>
  <option value="America/Indiana/Indianapolis">(GMT-05:00) Indiana (East)</option>
  <option disabled="disabled">&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8211;</option>
<?php
foreach($zonelist as $key => $value) {
  echo '    <option value="' . $key . '">' . $value . '</option>';
}
?>
  </select>

<div style="font-size:20px;color:#555;margin-bottom:8px;margin-top:30px">Language</div>
<p style="color:#777">We need your help translating ClassConnect - <a href="#" onclick="olark('api.box.expand'); return false">contact us!</a></p>


<div style="margin-top:30px">
  <input type="hidden" name="submitted" value="true" />
  <button id="subbtn3" type="submit" class="btn primary large">Update location & language settings</button>
</div>

</form>

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
        if (retData == 1) {
          $("#persError").html('');
          initAsyncBar('<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Settings updated successfully</span>', 'yellowBox', 220, 485, 2000);

        } else {
          // show error
          $("#persError").html(retData);
        }

        fbFormEnable('#update-personal');
        $("#rmSoon").remove();

      }  
      
  });  
    return false;
});



$('#update-locales').submit(function() {
  $('#subbtn3').append('<img id="rmSoon" src="/assets/app/img/box/miniload.gif" style="float:right;margin-top:4px;margin-left:10px" />');
   var serData = $("#update-locales").serialize();
    fbFormDisable('#update-locales');
    $.ajax({  
      type: "POST",
      url: "/app/manage/settings/locales",
      data: serData,
      success: function(retData) {
        initAsyncBar('<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Settings updated successfully</span>', 'yellowBox', 220, 485, 2000);


        fbFormEnable('#update-locales');
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