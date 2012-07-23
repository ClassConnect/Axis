
  /*
  $( ".content-item" ).draggable({ 
    revert: "invalid",
    distance: 20,
    zIndex: 99999
  });

  $( ".content-list" ).sortable();*/
hovBool = false;
noteClick = false;
noteInit = false;

$(document).ready(function() {

  // init our folder autocomplete
  initAutoTagger('#folder-tags');


  // JS for tags related stuff
  $('#addtags-btn').click(function() {
    container = $(this).parent().parent().parent();

    if (container.hasClass('act-live')) {
      container.removeClass('act-live');
      $(this).removeClass('btn-primary savebtn').html('Add New');


      // how should we close this?
      if ($('.tags li').length > 0) {
        // we're doing a custom close up
        $('.tag-group').each(function(index) {
            if ($(this).find('.tags li').length == 0) {
              $(this).css('opacity', 1).slideUp(150).animate({ opacity: 0 },{ queue: false, duration: 150});
            }
        });


        $('.tagenter').css('opacity', 1).slideUp(150).animate({ opacity: 0 },{ queue: false, duration: 150});


      } else {
        container.find('.content-fill').css('opacity', 1).slideUp(150).animate({ opacity: 0 },{ queue: false, duration: 150});
      container.find('.fortags').css('opacity', 0).slideDown(150).animate({ opacity: 1 },{ queue: false, duration: 150});
      }
      



    } else {
      container.addClass('act-live');
      $(this).addClass('btn-primary savebtn').html('&nbsp;&nbsp;Save&nbsp;&nbsp;');


      // how should we open this?
      if ($('.tags li').length > 0) {
        // we're doing a custom open up
        $('.tag-group').each(function(index) {
            if ($(this).find('.tags li').length == 0) {
              $(this).css('opacity', 0).slideDown(150).animate({ opacity: 1 },{ queue: false, duration: 150});
            }
        });

        $('.tagenter').css('opacity', 0).slideDown(150).animate({ opacity: 1 },{ queue: false, duration: 150});


      } else {
        container.find('.fortags').css('opacity', 1).slideUp(150).animate({ opacity: 0 },{ queue: false, duration: 150});
      container.find('.content-fill').css('opacity', 0).slideDown(150).animate({ opacity: 1 },{ queue: false, duration: 150});
      }

      //setTimeout(function() {$("#tag-adder").focus();},150);

      //$('#tag-adder').focus();
    }


  });

  // if we click the notepad, open the editor
  $(".descbox").click(function() {
    if ($('.real-text').is(':visible') && noteClick == false) {
      if (noteInit == false) {
        // init notepad stuff
        editor = new wysihtml5.Editor("notearea", {
          toolbar:      "wysitoolbar",
          stylesheets:  "css/stylesheet.css",
          parserRules:  wysihtml5ParserRules
        });

        noteInit = true;
      }
      $(editor.composer.element).html($('.real-text').html());
      $('.real-text').hide();
      $('.wysi-edit').show();
      editor.composer.element.focus();

    } else if (noteClick == true) {
      noteClick = false;

    }
  });

  $(".save-wysi").click(function() {
      editor.composer.element.blur();
      $('.real-text').html($(editor.composer.element).html());
      $('.wysi-edit').hide();
      $('.real-text').show();
      noteClick = true;
  });





    $( ".content-list" ).sortable({
      refreshPositions: true,
      opacity: 0.90,
      distance: 15,
      start: function(event, ui) {
        $('.ui-sortable-placeholder').after('<div class="placeBorder">&nbsp;</div>');
      },
      change: function(event, ui) {
        $('.placeBorder').remove();
        $('.ui-sortable-placeholder').after('<div class="placeBorder">&nbsp;</div>');
      },
      stop: function(event, ui) {
        $('.placeBorder').remove();
      }
    });


    $( ".droppable" ).droppable({
      tolerance: "intersect",
      hoverClass: "dropperblue",
      over: function(event, ui) {
        hovBool = true;

         // insert new div element
        $('.ui-sortable-helper').after('<div class="dropper-tog"><div class="folder-preview">' + $('.ui-sortable-helper').find('.folder-preview').html() + '</div><div class="big-text">move to folder</div><div class="tricontain"><div class="fattie"></div><div class="pointy"></div></div></div>');

        $('body').bind('mousemove', function(e){
            $('.dropper-tog').css({
               left:  e.pageX,
               top:   e.pageY
            });
        });

        setTimeout('dropHover()', 200);

      },
      out: function(event, ui) {
        hovBool = false;

        $('.placeBorder').fadeIn(100);

        killHover();

        $('.ui-sortable-helper').animate({
          opacity: 1
        }, 200, function() {
          // Animation complete.
        });
        
      },
      drop: function( event, ui ) {
        $('body').unbind('mousemove');
        // find position and move the element
        pos1 = $(this).find('.folder-preview').offset();
        pos2 = $('.dropper-tog').find('.folder-preview').offset();
   
        topfin = pos1.top - pos2.top + 15;
        leftfin = pos1.left - pos2.left + 15;

        $('.dropper-tog').find('.folder-preview').css({position: 'absolute'}).animate({ top: topfin, left: leftfin }, 100);

        $('.dropper-tog').css('opacity', 1).animate({ opacity: 0.01 },{ queue: false, duration: 300});


        // okay, now lets remove the dropped folder from the DOM
        rmBx = $('.ui-sortable-helper');
        $('.ui-sortable-helper').animate({ opacity: 0.01 },{ queue: false, duration: 1}).slideUp(500);
        setTimeout('$(\'.dropper-tog\').remove(); rmBx.remove()', 400);
        //$('.ui-sortable-helper').remove();

      }
    });




});




// helper methods for drag & dropping files/folders
function dropHover() {

  if (hovBool == true) {

    $('.placeBorder').fadeOut(200);

    $('.dropper-tog').css('opacity', 0.01).animate({ opacity: 1 },{ queue: false, duration: 'fast'});

    // animate the fadeout of the actual div
    $('.ui-sortable-helper').css('opacity', 1).slideDown('fast').animate({ opacity: 0.01 },{ queue: false, duration: 'fast'});


  }

}


function killHover() {

  $('body').unbind('mousemove');
  $('.dropper-tog').remove();

}