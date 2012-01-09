<?php
appHeader('Edit', '<script type="text/javascript" src="/assets/app/js/edit/jquery.tinymce.js"></script>', 3);
?>

<div class="content"> 
	<div class="row" class="span15" style="margin-top:-20px;margin-bottom:-20px;"> 
<textarea id="editorpane" style="width:937px;height:500px"></textarea>



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
    force_p_newlines : false
    //save_onsavecallback: saveDoc
  });
    });
</script>

	</div> 
</div>

<?php
appFooter();
?>