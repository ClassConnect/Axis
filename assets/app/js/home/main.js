loading = false;
totalPull = 0;
$(document).ready(function() {
  $(window).scroll(function(){
      if (loading) {
        return;
      }

      if(nearBottomOfPage()) {
        totalPull++;
        loading=true;
        // pull in feed data
        $.ajax({  
        type: "GET",  
        url: "/app/common/feed/retrieve/?limit=20&off=" + (totalPull * 40) + urlComp,  
        dataType: "json",
        success: function(retData) {
          if (retData['empty'] == false) {
            $("#my_feed").append(retData['result']);
            loading=false;
          } else {
            $("#noneRM").remove();
            $("#my_feed").append('<p style="text-align:center;color:#666; background:#efefef;padding:7px;margin:20px">We couldn\'t find any more items.</p>');
            // dont unset the loading variable!
          }

        }  
        
        }); 


      }
  });

});

// helper function
function nearBottomOfPage() {
  return $(window).scrollTop() > $(document).height() - $(window).height() - 200;
}