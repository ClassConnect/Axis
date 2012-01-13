

<script src="/assets/app/js/wiz/guide.js"></script>
<div id="wizExec" style="display:none"><?= fireWizard($_SERVER['REQUEST_URI']); ?></div>

<div id="wizthing" onClick="wizSwapper();" class="schColor"><img src="/assets/app/img/wiz/gs.png" style="margin-left:8px;padding-top:10px" /></div>
<div id="wizpnel">
<div class="wizShade"><div class="wizLdr"><img src="/assets/app/img/box/loading.gif" /></div></div>


<div class="wizcol wizTop">Here are the basics for getting started with ClassConnect.</div>

<div class="wizDiv"<?= dispWizComplete(1); ?>>
    <span class="wizBld">1.&nbsp;&nbsp;<a href="#" onClick="initWiz(1); return false">Set up your FileBox</a></span>
    <div class="wizGtx">It's a crazy simple way to organize files, bookmarks, embed codes, Google Docs and more!</div>
</div>

<div class="wizDiv"<?= dispWizComplete(2); ?>>
    <span class="wizBld">2.&nbsp;&nbsp;<a href="#" onClick="initWiz(2);">Collaborate with your colleagues</a></span>
    <div class="wizGtx">Allow your colleagues to view and/or edit the content you added to your FileBox - in just a few clicks.</div>
</div>
<div class="wizDiv"<?= dispWizComplete(3); ?>>
    <span class="wizBld">3.&nbsp;&nbsp;<a href="#" onClick="initWiz(3);">Share with your students</a></span>
    <div class="wizGtx">Your students can access content you share with them as well as their class calendar & announcements.</div>
</div>
<div style="margin-top:60px;font-size:12px">
    <div class="wizcol wizEx"><a href="#" style="color:#fff;font-weight:bolder" onClick="endWiz();">End the 'Getting Started' wizard</a></div>
    <div style="padding-top:5px;padding-left:17px;color:#666">Done with these steps?</div>
</div>
</div>


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