$(document).ready(function() {

    $(".pioneerDiv").twipsy({
        live: true,
        placement: 'above',
        html: true
      });

    // init our pioneer chat bar
    var playListURL = 'http://gdata.youtube.com/feeds/api/playlists/09E947DF25B6C6C2?v=2&alt=json&callback=?';
    var videoURL= 'http://www.youtube.com/watch?v=';
    $.getJSON(playListURL, function(data) {
        var vid_data="";
        var item = data.feed.entry[0];

            var feedTitle = item.title.$t;
            var feedDesc = item.media$group.media$description.$t.substring(0, 130).replace( /\n/g, ' ') + '...';
            var feedURL = item.link[1].href;
            var fragments = feedURL.split("/");
            var videoID = fragments[fragments.length - 2];
            var url = videoURL + videoID;
            var thumb = "http://img.youtube.com/vi/"+ videoID +"/default.jpg";
            vid_data += '<div onclick="watchChat(\'' + videoID + '\', \'' + feedTitle + '\');"><img alt="'+ feedTitle+'" src="'+ thumb +'" class="videoPreview" /><div class="vidTitle">'+ feedTitle +'</div><div class="vidDesc">'+feedDesc+'</div></div>';
       
       $('.pioneerDiv').html(vid_data);
        //jQuery.facebox('<div>' + list_data + '</div>');

        if(typeof window.initPioneer == 'function') {
            var curTxt = $("#videoManifest").text();
            if (curTxt == '0') {
               initPioneer(videoID, feedTitle); 
            } else {
                // we need to iterate ourselves
                $.each(data.feed.entry, function(i, item) {
                    var feedTitle = item.title.$t;
                    var feedURL = item.link[1].href;
                    var fragments = feedURL.split("/");
                    var videoID = fragments[fragments.length - 2];
                    if (curTxt == videoID) {
                        initPioneer(videoID, feedTitle);
                    }
                });
            }
        }
    });

});



function formatData(vidID, title, isBox) {
    if (isBox) {
        var boxTog = '<button class="btn large" style="float:right;margin-left:20px;margin-top:15px;font-weight:bolder" onclick="closeBox();">Close</button>';
    } else {
        var boxTog = '';
    }

    var datas = '<div style="font-size:20px;font-weight:bolder;margin-bottom:5px;margin-left:10px">' + title + '</div><iframe width="720" height="400" src="http://www.youtube.com/embed/' + vidID + '" frameborder="0" allowfullscreen style="margin:5px 10px 10px 10px"></iframe><br /><div style="float:right;margin-right:10px"><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.classconnect.com%2Fspotlight%2Fvideo%2f' + vidID + '&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=90&amp;appId=213954741999891" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:50px; height:70px; margin-right:10px" allowTransparency="true"></iframe><iframe allowtransparency="true" frameborder="0" marginwidth="0" scrolling="no" src="https://plusone.google.com/_/+1/fastbutton?url=http%3A%2F%2Fwww.classconnect.com%2Fspotlight%2Fvideo%2f' + vidID + '&amp;size=tall&amp;count=true&amp;annotation=&amp;width=120&amp;hl=en-US&amp;jsh=m%3B%2F_%2Fapps-static%2F_%2Fjs%2Fwidget%2F__features__%2Frt%3Dj%2Fver%3DSXEYxs5FO0c.en_US.%2Fsv%3D1%2Fam%3D!KW4lzGmbF_KIhSW8Og%2Fd%3D1%2F#id=I1_1327178530968&amp;parent=http%3A%2F%2Fwww.classconnect.com%2Fspotlight%2Fvideo%2f' + title + '&amp;rpctoken=350075819&amp;_methods=onPlusOne%2C_ready%2C_close%2C_open%2C_resizeMe" style="border:none; overflow:hidden; width:50px; height:70px;margin-bottom:-1px;margin-right:10px" title="+1"></iframe><iframe allowtransparency="true" frameborder="0" scrolling="no" src="//platform.twitter.com/widgets/tweet_button.html?count=vertical&text=' + title + ' %23UnitedWeTeach&via=ClassConnectInc&url=http://www.classconnect.com/spotlight/video/' + vidID + '" style="width:55px; height:70px;"></iframe>' + boxTog + '</div>';

    return datas;
}


function watchChat(vidID, title) {
    var fbData = formatData(vidID, title, true);

    jQuery.facebox(fbData);
}