<?php
if (isset($_FILES['files'])) {
    if ($_FILES['files']['error'] > 1) {
        echo 'error'; // error
      exit();
    }

    $content = array();
    $content['size'] = $_FILES["files"]["size"];
    $content['type'] = $_FILES["files"]["type"];

    $attempt = addFile($_GET['parent'], $_FILES["files"]["tmp_name"], $_FILES["files"]["name"], '', $content);
    
    echo 1;

    exit();
}
?>
<style>
#fileupload {
    position:absolute;
    top:15px;
    left:25px;

    opacity:0;
    -moz-opacity:0;
    filter:alpha(opacity:0);

    z-index:2; /* bring the real upload interactivity up front */
    width:325px;

    cursor:pointer;
}
</style>

<script src="/assets/app/js/up/jquery.iframe-transport.js"></script>
<script src="/assets/app/js/up/jquery.fileupload.js"></script>

<script>
$(function () {
    $('#fileupload').fileupload({
        dataType: 'json',
        type: 'POST',
        url: '/app/filebox/write/add/file/?parent=' + currentCon,
        add: function (e, data) {
            fbFormActLoader('Uploading files...');
            $("#hidester").hide();
            $.each(data.files, function (index, file) {
                var thisID = Math.floor(Math.random()*999999);
                file.unid = thisID;
                $("#queList").append('<div id="findex' + file.unid + '"><img src="/assets/app/img/box/miniload.gif" style="margin-right:10px;float:left;margin-top:4px" /><span style="font-weight:bolder">' + file.name + '</span> <span style="font-size:10px;color:#999;font-style:italic">(uploading...)</span></div>');
            });
            data.submit();
        },
        drop: function (e, data) {
        },
        always: function (e, data) {
            // data.errorThrown
            // data.textStatus;
            // data.jqXHR;
            //alert(data.textStatus);
            $.each(data.files, function (index, file) {
                $("#findex" + file.unid).remove();
                $("#successList").append('<div id="success' + file.unid + '"><img src="/assets/app/img/box/complete.gif" style="margin-right:10px;float:left;margin-top:4px;height:12px;width:12px" /><span style="font-weight:bolder">' + file.name + '</span></div>');

                if (!$('#queList').html()) {
                    $("#fbActions").html('<div style="float:right"><button type="reset" class="btn danger" onClick="softRefresh();closeBox();">Finish & add to My Files</button></div><div style="clear:both"></div>');
                }
            });
        }
    });
});
</script>

<input id="fileupload" type="file" name="files" multiple>
<div style="margin-left:20px">

    <div class="addTags">
          <div class="toggleTags" style="padding-left:120px;cursor:pointer;padding-bottom:10px;margin-bottom:-10px">
          <img src="/assets/app/img/box/addfile.png" style="float:left;width:16px;margin-right:5px" /> <span id="swapText">Add Files</span>
          </div>
    </div>

    <div id="hidester" style="margin-top:15px;text-align:center;color:#666;margin-left:-20px">Click the "Add Files" button to start uploading!</div>

    <div id="queList" style="margin-top:20px;margin-left:5px"></div>

    <div id="successList" style="margin-left:5px">
    </div>

</div>




<div id="fbActions" class="actions" style="margin-bottom:-1px">
    <div style="float:right">
      <button type="reset" class="btn" onClick="closeBox();">Cancel</button>
    </div>
    <div style="clear:both"></div>
  </div>