// pjax link/box handler
linkClick = false;
// async var
asyncOvr = false;

// on ready stuff (will probably change)
$(document).ready(function() {
  // pjax stuff
  $('.js-pjax').pjax('#jaxecute', {
  timeout: null, error: function(xhr, err){
    $('.error').text('Something went wrong: ' + err)
  }});
  $('#jaxecute')
    .bind('pjax:start', function() {
      //$("#mainBlocker").css({cursor:"progress"});
      $('html, body').animate({ scrollTop: 0 }, 'fast');
      // remove web views if we have any open
      if ("#webControls".length > 0) {
        destroyWebContent();
      }

      if (!asyncActive) {
        asyncOvr = true;
        initAsyncBar('<img src="/assets/app/img/box/miniload.gif" style="margin-right:10px;margin-bottom:-1px" /> <span style="font-weight:bolder">Loading...</span>', 'yellowBox', 95, 622); 
      }
    })
    .bind('pjax:end',   function() {
      //$("#mainBlocker").css({cursor:"auto"});
      if (asyncOvr) {
        destroyAsyncBar();
        $('.fboxActBox').hide();
        asyncOvr = false;
      }
    });

  // twipsy for folders
  $("a[rel=sharedWith], .deleter").twipsy({
    live: true,
    placement: 'right',
    html: true
  });
  $(".descTip").twipsy({
    live: true,
    placement: 'below',
    html: true
  });
  $(".topDesc").twipsy({
    live: true,
    placement: 'above',
    html: true,
    delayIn: 200
  });

});



function initScrollSpy() {
  $(window).unbind('scroll');
    var thisPage = $(this);
  var panel = $(".fboxFloater");
  var panelTop = panel.offset().top;
  var thisPageTop = 0;
  var digit = 0;
  $(window).bind('scroll', function(){  
    thisPageTop = thisPage.scrollTop();
    if(thisPageTop > 14 && !panel.hasClass('fboxFloat')){
        panel.addClass('fboxFloat');
        panel.addClass('fboxPad');
        $("#crumbNav").addClass('fboxCrumbFloat');
        $("#padset").show();
      } else if(thisPageTop <= 14 && panel.hasClass('fboxFloat')){ 
        panel.removeClass('fboxFloat');
        panel.removeClass('fboxPad');
        $("#crumbNav").removeClass('fboxCrumbFloat');
        $("#padset").hide();
      }
  });
}


function killScrollSpy() {
  $(window).unbind('scroll');
  $('.fboxFloat').removeClass('fboxFloat');
  $('.fboxCrumbFloat').removeClass('fboxCrumbFloat');
  $("#padset").hide();
  
}


// display contents of a folder
function dispFolview(showCrumbs) {
  var side = $("#tempSidebar").html();
  var main = $("#tempMain").html();
  var crumbs = $("#tempCrumbs").html();

  // if we're coming from a file or need to refresh sidebar
  if (currentType != 1) {
    // hide folder view stuff; reset sidebar
    $("#leftSwap").html(side);
    currentType = 1;
  } else {
    $("#leftSwap").html(side);
  }
  if (main != '0') {
    $("#mainSwap").html(main);
  }
  if (showCrumbs != '0') {
    $("#crumbNav").html(crumbs);
  } else {
    if ($('#fol' + currentCon).length) {
      if ($('#fol' + currentCon).hasClass('activer')) {
        // do nothing
      } else {
        chooseCrumb($('#fol' + currentCon));
      }
      
    } else {
      // reset the crumb nav
      $("#crumbNav").html(crumbs);
      chooseCrumb($('#fol' + currentCon));
    }
  }

  // initialize folder stuff
  initFolUI();

  // remove temp
  $("#tempSidebar").remove();
  $("#tempMain").remove();
  $("#tempCrumbs").remove();
}


// display a file's contents
function dispFilview(sideOverride) {
  var side = $("#tempSidebar").html();
  var main = $("#tempMain").html();
  var crumbs = $("#tempCrumbs").html();

  // if we're coming from a folder or need to refresh sidebar
  if (currentType != 2 || sideOverride == 1) {
    // hide folder view stuff; reset sidebar
    $("#leftSwap").html(side);
    currentType = 2;
  }
  if (main != '0') {
    $("#mainSwap").html(main);
  }
    $("#crumbNav").html(crumbs);


    initFilUI();

    // remove temp
    $("#tempSidebar").remove();
    $("#tempMain").remove();
    $("#tempCrumbs").remove();
}



function chooseCrumb(obj) {
  $(".selectedr").removeClass('selectedr');
  obj.addClass('selectedr');
}


function checkBox(obj) {
  // if we're unselecting this item
  if (obj.hasClass('checkBoxed')) {
    obj.removeClass('checkBoxed');
    obj.removeClass('cboxSel');
    obj.parent().parent().removeClass('fbprogSel');

  // if we're selecting this item
  } else {
    obj.addClass('checkBoxed');
    obj.addClass('cboxSel');
    obj.parent().parent().addClass('fbprogSel');
  }
  // update our left bar UI
  updateLeftSwap();
}


function updateLeftSwap() {
  // if we have both read & write
  if (accessLevel == 2) {
    // if there is nothing selected...
    if ($('.fbprogSel').length === 0) {
      $("#rwActions").hide();
    } else {
      $("#rwActions").show();
    }

  // if we only have read
  } else if (accessLevel == 1) {
    if ($('.fbprogSel').length === 0) {
      $("#roActions").hide();
      } else {
        $("#roActions").show();
      }
  }
}




function initFolUI() {
  // get scrollig up in hurrr
  initScrollSpy();

  $("#storagebar").progressbar({
    value: parseInt($("#storageval").text())
  });

  // sometimes twipsies stay open, lets kill 'em
  $('.twipsy').remove();


  $('.fboxElement, .sharedEl').disableSelection();
  // set fbox element hover events
  $(".fboxElement, .sharedEl").hover(
   function() {
      $(this).addClass('fbprogHov');
      $(this).find('.checkBoxy').removeClass('cboxNorm').addClass('cboxHov');
   },
   function() {
      $(this).removeClass('fbprogHov');
      $(this).find('.checkBoxy').addClass('cboxNorm').removeClass('cboxHov');
      $(this).find('.optListers').hide();
   }
  );

  // let our option area be unclickable
  $('.optarea, .optBox, .rollFalse').click(function() {
    return false;
  });

  // if we click on a link
  $('.fboxElement a, .sharedEl a').click(function() {
    linkClick = true;
  });

  // click on the box, go to its page
  $('.fboxElement, .sharedEl').click(function() {
    // if a link wasn't clicked
    if (linkClick == false) {
      $.pjax({
        url: '/app/filebox/' + $(this).attr('id'),
        container: '#jaxecute'
      });
    }
    // reset the link click
    linkClick = false;
  });


  // we always have copy functionality..
  
  // if we have read/write, add draggable functionality
  if (accessLevel == 2) {
    // set click handler for buttons
    $('#addBtn').click(function() {
      addButtonToggle(this);
    });


    // set draggable property
    $('.fboxElement').each(function(index) {
      $(this).draggable({
        zIndex: 2700,
        distance: 20,
        opacity: 0.70,
        start:function(evt,ui){
          // axis controller 1 to stack UI, do you copy?
          // ten-four. drag sequence initiated; opening drop zones
          // is this a single drag/drop?
          if ($(this).hasClass('fbprogSel')) {
            // no? then lets set up drop zones accordingly
            $('.fboxElement').each(function(index) {
              if ($(this).hasClass('fboxFolder') && ($(this).hasClass('fbprogSel') == false)) {
                $(this).addClass('dropIdentify');
                $(this).droppable({
                  hoverClass: "fbprogHov",
                  tolerance: 'pointer',
                      drop: function( event, ui ) {
                        var conStr = '';
                        $('.fbprogSel').each(function(index) {
                          conStr += $(this).attr('id') + ',';
                        });
                        // remove clones
                        conStr = conStr.substring(0, conStr.length/2 - 1);
                        
                        moveContent($(this).attr('id'), conStr);
                       
                      } // end drop func
                    });
              }
            });
          // otherwise, this is a single drag
          } else {
            var curDrag = $(this).attr('id');
            $('.fboxElement').each(function(index) {
              if ($(this).attr('id') != curDrag && $(this).hasClass('fboxFolder')) {
                $(this).addClass('dropIdentify');
                $(this).droppable({
                  hoverClass: "fbprogHov",
                  tolerance: 'pointer',
                      drop: function( event, ui ) {
                        moveContent($(this).attr('id'), curDrag);
                      }
                    });
              }
            });
          }
        },
        stop:function(evt,ui){
          // drag ended, close drop zones
          $('.dropIdentify').each(function(index) {
            $(this).removeClass('dropIdentify');
            $(this).droppable("destroy");

          });
        },
        revert: function(socketObj) {
              if (socketObj === false) {
                  // Drop was rejected, revert the helper.
                  var $helper = $("#dragContainer");
                  $helper.fadeOut("slow").animate($helper.originalPosition);
                  return true;
              } else {
                  // Drop was accepted, don't revert.
                  return false;
              }
          },
        helper: function(){
          var selected = $('.fbprogSel');
          if (selected.length === 0) {
            selected = $(this);
          }
          if ($(this).hasClass('fbprogSel') == false) {
            selected = $(this);
          }
          var container = $('<div/>').attr('id', 'dragContainer');
          container.append(selected.clone());
          return container; 
        }
        
      });
    });
  }
  
}


function restartFolUI(sidebar) {
  if ($(".fboxFloater").hasClass("fboxFloat")) {
    var shouldFloat = true;
  } else {
    var shouldFloat = false;
  }

  $("#mainSwap").html($("#mainSwap").html());
  if (!sidebar) {
    $("#leftSwap").html($("#leftSwap").html()); 
  } else {
    $("#leftSwap").html(sidebar);
  }
  $("#crumbNav").html($("#crumbNav").html());
  $('.fboxActBox').hide();

  if (shouldFloat) {
    var panel = $(".fboxFloater");
    panel.addClass('fboxFloat');
    panel.addClass('fboxPad');
    $("#crumbNav").addClass('fboxCrumbFloat'); 
  }

  initFolUI();
}



function initFilUI() {
  $("#storagebar").progressbar({
    value: parseInt($("#storageval").text())
  });
  
  // dude, floating panels aren't cool anymore...
  killScrollSpy();

  initCommentBars();
  
}



function restartFilUI(sidebar) {
  //$("#mainSwap").html($("#mainSwap").html());
  if (!sidebar) {
    //$("#leftSwap").html($("#leftSwap").html()); 
  } else {
    $("#leftSwap").html(sidebar);
  }
  //$("#crumbNav").html($("#crumbNav").html());


  $("#storagebar").progressbar({
    value: parseInt($("#storageval").text())
  });
  
  // dude, floating panels aren't cool anymore...
  killScrollSpy();
}




function swapDesc() {
  var obStr = '';
  // if this is a normal swap
  if ($('.descText').is(':empty')) {
    obStr = '.descPlacer';
  } else {
    obStr = '.descText';
  }
  if ($(obStr).is(':visible')) {
    $('.twipsy').remove();
    var textID = $('.descBox').attr('id');
    $(obStr).hide();

    if ($('.descBox').hasClass('mcActive')) {
      // do nothing
    } else {
      $('.descBox').addClass('mcActive');
      $('#' + textID).tinymce({
        // Location of TinyMCE script
        script_url : '/assets/app/js/edit/tiny_mce.js',

        // General options
        theme : "advanced",
        skin : "cirkuit",
        plugins : "spellchecker,safari,pagebreak,style,layer,save,advlink,advlist,iespell,inlinepopups,insertdatetime,contextmenu,directionality,noneditable,nonbreaking,xhtmlxtras,template",

        theme_advanced_buttons1 : "formatselect,fontsizeselect,forecolor,|,bold,italic,strikethrough,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,link,unlink", 
        theme_advanced_buttons2 : "", 
        theme_advanced_buttons3 : "", 
        theme_advanced_toolbar_location : "top", 
        theme_advanced_toolbar_align : "left", 
        theme_advanced_resizing_min_height : 100,
        force_br_newlines : true,
        force_p_newlines : false,
        save_onsavecallback: saveDesc,
        setup : function(ed) { 
            ed.onInit.add(function() { 
                var mceTable = $("#"+ed.editorContainer+" table:first").css("height","30px"); 
                $(".mceIframeContainer", mceTable).css("height","100px"); 
                $(".mceIframeContainer iframe", mceTable).height("100%");
                $("#mce_loading_gfx").hide();
                $('#' + textID).tinymce().focus();
            }); 
        } 
      });
    }

    $('.descHold').show();
    $('#' + textID).tinymce().focus();
    

    } else { // if the placer is visible
      $('.descHold').hide();
      $(obStr).show();


      
    }
}


function saveDesc() {
  var descData = $('.descBox').val();
  $('.descText').html(descData);
  swapDesc();
    $.ajax({
    type: "POST",
    data: "conID=" + currentCon + "&" + $('.descBox').serialize(),
    url: "/app/filebox/write/desc/",
    success: function(data) {
           // successful write
    }

  });
}


function recommendThis(obje, conID, dataID) {
  if ($(obje).hasClass('fboxFilUIbtnSel')) {
    $(obje).removeClass('fboxFilUIbtnSel');
    $(obje).attr('title', 'Recommend this');
    $('.twipsy').remove();
    $(obje).twipsy('show');
    var primObj = $(obje).find('.numbero');
    primObj.html(parseInt(primObj.html()) - 1);
    $.ajax({
      type: "GET",
      url: "/app/filebox/write/rm/rec/" + conID + "/" + dataID,
      success: function(data) {
          // do nothing
      }

    });
    
  } else {

    $(obje).addClass('fboxFilUIbtnSel');
    $(obje).attr('title', 'Un-recommend this');
    $('.twipsy').remove();
    $(obje).twipsy('show');
    var primObj = $(obje).find('.numbero');
    primObj.html(parseInt(primObj.html()) + 1);
    $.ajax({
      type: "GET",
      url: "/app/filebox/write/add/rec/" + conID + "/" + dataID,
      success: function(data) {
          // do nothing
             
      }

    });
    
  }
}


function moveContent(target, contIDs) {
  $.ajax({
    type: "GET",
    url: "/app/filebox/write/move/drag/?target=" + target + "&conIDs=" + contIDs,
    success: function(data) {
        if (data == 1) {
           softRefresh();
     } else {
         alert('An error occurred');

         
     }
           
    }

  }); // end ajax
}


// our non-drag move function
function moveDefault() {
  jQuery.facebox({ 
    ajax: '/app/filebox/write/move/?conIDs=' + getSelected()
  });
  return false;
}


// our non-drag move function
function copyDefault() {
  jQuery.facebox({ 
    ajax: '/app/filebox/write/copy/?conIDs=' + getSelected()
  });
  return false;
}



function softRefresh() {
  $.pjax({
    url: '/app/filebox/' + currentCon,
    container: '#jaxecute',
    push: false
  });
}



function getSelected() {
  var selData = '';
  $('.fbprogSel').each(function(index) {
    selData += $(this).attr('id') + ',';
  });
  return selData;
}




function addContent(route) {
  jQuery.facebox({ 
    ajax: '/app/filebox/write/add/' + route + '/'
  });
  return false;
}



function deleteContent() {
  jQuery.facebox({ 
    ajax: '/app/filebox/write/delete/?conIDs=' + getSelected()
  });
  return false;
}


function tagContent() {
  jQuery.facebox({ 
    ajax: '/app/filebox/write/tags/?conIDs=' + getSelected()
  });
  return false;
}

function tagCurrent() {
  jQuery.facebox({ 
    ajax: '/app/filebox/write/tags/?conIDs=' + currentCon
  });
}

function shareContent() {
  jQuery.facebox({ 
    ajax: '/app/filebox/write/share/?conIDs=' + getSelected()
  });
  return false;
}

function shareCurrent() {
  jQuery.facebox({ 
    ajax: '/app/filebox/write/share/?conIDs=' + currentCon
  });
  return false;
}

function addButtonToggle(butObj) {
  // if it's hidden, fade it in
  if ($(butObj).find('.contentPanel').is(":hidden")) {
    $(butObj).css('z-index', "1");
    $(butObj).find('.contentPanel').css('opacity', 0).slideDown('fast').animate({ opacity: 1 },{ queue: false, duration: 'fast'});

  // otherwise, fade it out
  } else {
    $(butObj).find('.contentPanel').css('opacity', 1).slideUp('fast').animate({ opacity: 0 },{ queue: false, duration: 'fast'});
    $(butObj).css('z-index', "0");
  }
}

function toggleOptPanel(butObj) {
  var lstObj = $(butObj).find('.optListers');
  if (lstObj.is(":hidden")) {
    lstObj.show();
  } else {
    lstObj.hide();
  }
}

// display a website, gdoc or embed
function displayWebContent(type, title, data) {
  var marTop = 70;
  var marHeight = 89;
  var res = 73;
  var topbar = '';
  var fsData = '';
  // if this is a URL
  if (type == 2) {
    topbar = '<div style="float:right;margin-top:2px"><button type="submit" class="btn danger" style="font-size:10px;font-weight:bolder" onclick="destroyWebContent()">Close</button></div><div class="alert-message elem" style="width:410px;font-size:12px;padding:1px;padding-left:5px;margin-top:4px"><div class="descTip" style="float:right;margin:0;padding:0;margin-top:-1px" data-original-title="Open in new window"><a href="' + data + '" target="_blank" onClick="destroyWebContent();$(\'.twipsy\').remove();"><img src="/assets/app/img/box/expand.png" /></a></div><div style="line-height:1;margin-top:3px;width:380px;overflow:hidden;height:12px;">' + title + '</div></div></div>';

    fsData = '<iframe allowtransparency="true" frameborder="0" id="webframe" class="webConLoader" scrolling="auto" src="' + data + '" style="width:100%;height:100%"></iframe>';

  // embed
  } else if (type == 3) {
    topbar = '<div style="float:right;margin-top:2px"><button type="submit" class="btn danger" style="font-size:10px;font-weight:bolder" onclick="destroyWebContent()">Close</button></div><div class="alert-message elem" style="width:410px;font-size:12px;padding:1px;padding-left:5px;margin-top:4px"><div style="line-height:1;margin-top:3px;width:380px;overflow:hidden;height:12px;">' + title + '</div></div></div>';

    fsData = '<center>' + data + '</center>';


    // google doc
  } else if (type == 5) {
    topbar = '<div style="float:right;margin-top:2px"><button type="submit" class="btn danger" style="font-size:10px;font-weight:bolder" onclick="destroyWebContent()">Close</button></div><div class="alert-message elem" style="width:410px;font-size:12px;padding:1px;padding-left:5px;margin-top:4px"><div class="descTip" style="float:right;margin:0;padding:0;margin-top:-1px" data-original-title="Open in new window"><a href="' + data + '" target="_blank" onClick="destroyWebContent();$(\'.twipsy\').remove();"><img src="/assets/app/img/box/expand.png" /></a></div><div style="line-height:1;margin-top:3px;width:380px;overflow:hidden;height:12px;">' + title + '</div></div></div>';

    fsData = '<iframe allowtransparency="true" frameborder="0" id="webframe" class="webConLoader" scrolling="auto" src="' + data + '" style="width:100%;height:100%"></iframe>';

    marTop = 30;
    marHeight = 93;
    res = 33;


  }
  $("#mainContent").hide();
  $("#mainNavBar").after('<div id="webControls" class="topbar" style="top:40px; z-index:1000"> <div class="fill"> <div class="container" style="height:30px">' + topbar + '</div> </div>');

  $('body').append('<div class="fullscreenContent" style="top:' + marTop + 'px;height:' + marHeight + '%">' + fsData + '</div>');
  resizeWebContent(res);
  $(window).resize(function(){
    resizeWebContent(res);
  });
}
// helper function
function resizeWebContent(num) {
  var h = $(window).height();
  $("#webframe").css('height', h - num);
}

// remove web content panel
function destroyWebContent() {
  $("#webControls").remove();
  $(".fullscreenContent").remove();
  $("#mainContent").show();
}