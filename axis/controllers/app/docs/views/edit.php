<?php
$cdata = array();
$cdata['conID'] = $this->Command->Parameters[1];
$cdata['verID'] = $this->Command->Parameters[2];
//$cdata['conID'], $cdata['verID'];
// generate sections to pass along
$mySecs = array();
foreach (getSections() as $secd) {
    $mySecs[] = (int) $secd['section_id'];
}

$cObj = getContent($cdata['conID']);
// if we're good to go, lets get the permissions
$permissionObj = verifyPermissions($cObj, user('id'), $mySecs);
$perLevel = determinePerLevel($cObj['_id'], $permissionObj);

// verify that we have all needed permissions
if (verifyDataAuth($cdata['verID'], $cObj) && $perLevel >= 1) {
    // get the data
    $data = getContentData($cdata['verID']);
    if ($data['data'] != 'none') {
        $docData = file_get_contents(cloudServer() . $data['data']);
    }

} else {
    showError();
    exit();
}


appHeader('Edit', '<script type="text/javascript" src="/assets/app/js/edit/jquery.tinymce.js"></script>', 3);
?>

<div class="content"> 
	<div class="row" class="span15" style="margin-top:-20px;margin-bottom:-20px;"> 
<textarea id="editorpane" style="width:937px;height:500px"><?= $docData; ?></textarea>



<script>
$(document).ready(function() {
  $('#editorpane').tinymce({
    // Location of TinyMCE script
    script_url : '/assets/app/js/edit/tiny_mce.js',

     // General options
        mode : "exact",
        elements : "elm3",
        theme : "advanced",
        skin : "o2k7",
        skin_variant : "black",
        plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

        // Theme options
        theme_advanced_buttons1 : "save,print,|,undo,redo,|,cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,bold,italic,underline,strikethrough,|,forecolor,backcolor,|,outdent,indent,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect,|,fullscreen",
        theme_advanced_buttons2 : "link,unlink,anchor,image,media,|,sub,sup,charmap",
        theme_advanced_buttons3 : "",
        theme_advanced_buttons4 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,

    theme_advanced_resizing_min_height : 500,
    force_br_newlines : true,
    force_p_newlines : false,
    save_onsavecallback: saveDoc
  });
    });

function saveDoc() {
    initAsyncBar('<img src="/assets/app/img/box/miniload.gif" style="margin-right:10px;margin-bottom:-1px" /> <span style="font-weight:bolder">Saving document...</span>', 'yellowBox', 170, 527);
    $.ajax({  
      type: "POST",  
      url: "/app/docs/save/<?= $cdata['conID']; ?>/<?= $cdata['verID']; ?>",  
      data: 'data=' + escape($("#editorpane").val()),
      dataType: "json",
      success: function(retData) {
        if (retData['success'] == true) {
          initAsyncBar('<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Document saved successfully</span>', 'yellowBox', 210, 527, 1500);
          softRefresh();
          closeBox();

        } else {
          // show error
          initAsyncBar('<span style="font-weight:bolder">Cannot save document</span>', 'yellowBox', 210, 527, 50000);
        }
      }  
  });
}    
</script>

	</div> 
</div>

<?php
appFooter();
?>