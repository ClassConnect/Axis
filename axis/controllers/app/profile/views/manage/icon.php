<form action="/app/manage/settings/icon/?redir=<?= urlencode($rootURL); ?>" method="post" enctype="multipart/form-data" id="change-icon" class="form-stacked">
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