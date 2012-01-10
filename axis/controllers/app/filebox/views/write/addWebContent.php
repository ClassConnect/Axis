<?php
if ($this->Command->Parameters[3] == 'get_title') {
  $theURL = formatURL(reverse_htmlentities($_GET['url']));
  $doc = new DOMDocument();
  @$doc->loadHTMLFile($theURL);
  $xpath = new DOMXPath($doc);
  echo $xpath->query('//title')->item(0)->nodeValue;

  if (is_null($xpath->query('//title')->item(0)->nodeValue)) {
    echo ' ';
  }
  exit();
}


if (isset($_POST['submitted'])) {
  // we need to determine if this is a url or an embed
  $parent = $_POST['parent'];

  if ($_POST['embedCode'] == '' || $_POST['embedCode'] == '<embed code>') {
    // url handling
    $title = $_POST['urlTitle'];
    $data = $_POST['url'];
    $desc = $_POST['urlDesc'];
    $type = 1;

  } else {
    // embed handling
    $title = $_POST['embedTitle'];
    $data = $_POST['embedCode'];
    $desc = $_POST['embedDesc'];
    $type = 2;

  }


  // lets insert this
  $attempt = addWebContent($parent, $title, $desc, $data, $type);


  if ($attempt == 1) {
    echo 1;
  } else {
    echo '<div class="alert-message warning" style="width:310px">';
    foreach($attempt as $error) {
      echo '<li>' . say($error) . '</li>';
    }
    echo '</div>';

  }


  exit();
}

?>
<style type="text/css">
.pendDesc {
  width:40px;
  float:left;
  margin-top:7px;
  margin-right:8px;
  text-align:right;
  color:#666;
}
</style>
<script type="text/javascript">
titleCheck = false;

$('#urlTitle').focus(function() {
  if ($('#urlTitle').val() == '' && $('#urlLoc').val() != '' && titleCheck == false) {
    $('#urlTitle').val('Retrieving the title for you...');
    $('#urlTitle').attr('disabled', 'disabled');
    fbFormActLoader('Retrieving the page title...');

    // get the title
    $.ajax({  
      type: "GET",  
      url: "/app/filebox/write/add/web/get_title?url=" + escape($('#urlLoc').val()),
      success: function(titleData) {
        $('#urlTitle').val(titleData.substring(0, 60));
        $('#urlTitle').removeAttr('disabled');
        $('#urlTitle').focus();
        fbFormActRevert();

        titleCheck = true;
      }
    });





  }

});

$(document).ready(function(){
  fbFormControl();
  $('.tabs').tabs();
  $(".helperQ").twipsy({
    live: true,
    placement: 'above',
    html: true
  });
});
$('#add-web').submit(function() {
   var serData = $("#add-web").serialize() + '&parent=' + currentCon;
    fbFormSubmitted('#add-web');
    $.ajax({  
      type: "POST",  
      url: "/app/filebox/write/add/web/",  
      data: serData,
      success: function(retData) {
        if (retData == '1') {
          initAsyncBar('<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Web content added successfully</span>', 'yellowBox', 225, 532, 1500);
          softRefresh();
          closeBox();

        } else {
          fbFormRevert('#add-web');
          showFormError(retData);
        }
      }  
      
  });  
    return false;
});
</script>
<form action="#" id="add-web" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <div class="clearfix">
    <div id="errorBox" style="display:none"></div>

    <ul class="tabs">
      <li class="active"><a href="#addUrl">Add URL / Video <!--<span class="label warning helperQ" style="margin-top:-15px" data-original-title="Some title text">?</span>--></a></li>
      <li><a href="#addEmbed">Add Embed Code</a></li>
    </ul>

    <div class="tab-content">

      <div class="active" id="addUrl">
        <div class="input" style="margin-bottom:10px">
          <input class="xlarge span6" id="urlLoc" name="url" placeholder="http:// (website or video URL)" size="30" type="text" style="margin-right:20px">
        </div>

        <div class="input" style="margin-bottom:10px">
          <input class="xlarge span6" id="urlTitle" name="urlTitle" placeholder="Title" size="30" maxlength="60" type="text" style="margin-right:20px">
        </div>

        <div style="font-size:11px;margin-top:5px;margin-left:5px"><a href="#" onclick="$('#descHide').show(); $('#urlDesc').focus(); $(this).remove(); return false">Add a description...</a></div>

        <div id="descHide" style="display:none">
          <div class="input"><textarea class="xlarge span6" style="margin-right:20px" placeholder="Description" name="urlDesc" id="urlDesc" rows="3"></textarea></div>
        </div>

      </div>

      <div id="addEmbed">

      <div class="input" style="margin-bottom:10px">
          <input class="xlarge span6" name="embedTitle" placeholder="Title" size="30" type="text" style="margin-right:20px">
        </div>

        <div class="input" style="margin-bottom:10px">
          <textarea class="xlarge span6" style="margin-right:20px" name="embedCode" id="textarea" placeholder="<embed code>" rows="3"></textarea>
        </div>

        <div style="font-size:11px;margin-top:5px;margin-left:5px"><a href="#" onclick="$('#descEmbed').show(); $('#embedDesc').focus(); $(this).remove(); return false">Add a description...</a></div>

        <div id="descEmbed" style="display:none">
          <div class="input"><textarea class="xlarge span6" style="margin-right:20px" placeholder="Description" name="embedDesc" id="embedDesc" rows="3"></textarea></div>
        </div>

      </div>


    </div>


    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Add Web Content</button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>