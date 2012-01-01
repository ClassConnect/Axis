<form action="/app/manage/settings/icon" method="post" enctype="multipart/form-data" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
  <legend>Change Icon</legend>
    <div class="clearfix">
    <div id="errorBox" style="display:none"></div>

    <input type="file" name="file" id="file" /> 


    <div style="font-weight:bolder;color:#666;margin-top:15px;margin-bottom:5px">Also set as the icon for...</div>
    <?= buildCoursePicker(array($sectionID),0,'','line-height:1.4em'); ?>
    <input type="hidden" name="refCour" value="<?= $sectionID; ?>" />
    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Upload Icon</button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>