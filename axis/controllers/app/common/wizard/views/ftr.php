<div id="wizthing" onClick="wizSwapper();" class="schColor"><img src="/assets/app/img/wiz/gs.png" style="margin-left:8px;padding-top:10px" /></div>
<div id="wizpnel">
<div class="wizShade"><div class="wizLdr"><img src="/assets/app/img/box/loading.gif" /></div></div>


<div class="wizcol wizTop">Here are the basics for getting started with ClassConnect.</div>

<div class="wizDiv"<?= dispWizComplete(1); ?>>
    <span class="wizBld">1.&nbsp;&nbsp;<a href="#" onClick="initWiz(1);">Create your classes</a></span>
    <div class="sgtx">It takes just a few clicks to create your classes. Your students can join a class by using its access code.</div>
</div>

<div class="wizDiv"' . $fboxSty . '>
    <span class="wizBld">2.&nbsp;&nbsp;<a href="#" onClick="initWiz(2);">Add & organize class content</a></span>
    <div class="sgtx">Upload files, bookmark websites, organize content into folders, and then share class content with all your classes with just a click.</div>
</div>
<div class="wizDiv"' . $classSty . '>
    <span class="wizBld">3.&nbsp;&nbsp;<a href="#" onClick="initWiz(3);">Manage a class page</a></span>
    <div class="sgtx">Your classes have their own individual "pages" where you can post updates, manage the class calendar, open forums and start lectures.</div>
</div>
<div style="margin-top:60px;font-size:12px">
    <div class="wizcol wizEx"><a href="#" style="color:#fff;font-weight:bolder" onClick="endWiz();">End the 'Getting Started' wizard</a></div>
    <div style="padding-top:5px;padding-left:17px;color:#666">Done with these steps?</div>
</div>
</div>



<div id="wizExec" style="display:none"></div>




<script>
function wizSwapper() {
    if (!$("#wizpnel").is(":visible")) {
        $("#wizpnel").show().animate({right:"+=360px"},500);
        $("#wizthing").animate({right:"+=360px"},500);

    } else {
        $("#wizpnel").animate({right:"-=360px"},500);
        $("#wizthing").show().animate({right:"-=360px"},500);
        setTimeout("$('#wizpnel').hide();",500);
    }
}


function initWiz(num) {
    $(".wizShade").show();
    $.ajax({  
      type: "GET",  
      url: "/app/common/wizard/ajax/",  
      data: "step=" + num + "&loc=" + escape(document.URL),
      success: function(retData) {
          $(".wizShade").hide();
          $("#wizExec").html(retData);
          wizSwapper();

      }  
      
  }); 
}
</script>