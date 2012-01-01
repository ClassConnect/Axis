<?php appHeader('Settings', ''); ?>
<div class="content"> 
    <div class="row" style="clear:both">
    <div style="margin-left:20px;margin-right:20px">


<ul class="tabs">
  <li class="active"><a href="#icon">User Icon</a></li>
</ul>
 
<div class="pill-content">
  <div class="active" id="icon">


<div style="margin-left:300px;clear:both">
<img src="<?= iconServer(); ?>210_<?= dispUser(user('id'), 'prof_icon'); ?>" class="vidView" style="background-image:none;margin-bottom:10px;margin-left:15px" />
  <form action="/app/manage/settings/icon" method="post" enctype="multipart/form-data" style="border:1px solid #ccc;width:260px;padding:10px">
  <div style="font-size:14px;margin-bottom:8px;font-weight:bolder">Change Icon</div>
  <input type="file" name="file" id="file" /> 
  <br />
  <button type="submit" class="btn primary" style="margin-left:40px;margin-top:7px">Upload Profile Icon</button>
  </form>

</div>




  </div>
</div>
 
<script>
  $(function () {
    $('.tabs').tabs()
  })
</script>




</div></div></div>

<?php appFooter(); ?>