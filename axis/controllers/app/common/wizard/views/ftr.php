

<script src="/assets/app/js/wiz/guide.js"></script>
<div id="wizExec" style="display:none"><?= fireWizard($_SERVER['REQUEST_URI']); ?></div>

<div id="wizthing" onClick="wizSwapper();" class="schColor"><img src="/assets/app/img/wiz/gs.png" style="margin-left:8px;padding-top:10px" /></div>
<div id="wizpnel">
<div class="wizShade"><div class="wizLdr"><img src="/assets/app/img/box/loading.gif" /></div></div>


<div class="wizcol wizTop">Here are the basics for getting started with ClassConnect.</div>

<div class="wizDiv wzdv1 <?= dispWizComplete(1); ?>">
    <span class="wizBld">1.&nbsp;&nbsp;<a href="#" onClick="initWiz(1); return false">Add folders & files</a></span>
    <div class="wizGtx">Create folders for your lessons - then add websites, online videos, embed codes and files into them!</div>
</div>

<div class="wzdv2 <?= dispWizComplete(2); ?> wizGtx" style="margin-left:10px">
  You can even <a href="#" onClick="initWiz(2); return false">add tags & align with the Common Core!</a>
</div>

<div class="wizDiv wzdv3 <?= dispWizComplete(3); ?>">
    <span class="wizBld">2.&nbsp;&nbsp;<a href="#" onClick="initWiz(3); return false">Find lessons / resources</a></span>
    <div class="wizGtx">Find amazing resources that other teachers on ClassConnect are sharing publicly and snap them into your lessons!</div>
</div>

<div class="wizDiv wzdv3 <?= dispWizComplete(4); ?>">
    <span class="wizBld">3.&nbsp;&nbsp;<a href="#" onClick="initWiz(4); return false">Share with students & colleagues</a></span>
    <div class="wizGtx">Allow your students & colleagues to view and/or edit the content you added - say goodbye to emailing files/links back and forth!</div>
</div>
<div class="wizDiv wzdv4 <?= dispWizComplete(5); ?>">
    <span class="wizBld">4.&nbsp;&nbsp;<a href="#" onClick="initWiz(5); return false">Edit your profile</a></span>
    <div class="wizGtx">Show the world who you are and what you're working on!</div>
</div>
<div style="margin-top:39px;font-size:12px">
    <div class="wizcol wizEx"><a href="#" style="color:#fff;font-weight:bolder" onClick="initWiz(-1); return false">End the 'Getting Started' wizard</a></div>
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
          $('.guider').remove();
          $("#wizExec").html(retData);
          wizSwapper();

      }  
      
  });
}
</script>