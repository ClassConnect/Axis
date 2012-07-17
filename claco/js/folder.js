
  /*
  $( ".content-item" ).draggable({ 
    revert: "invalid",
    distance: 20,
    zIndex: 99999
  });

  $( ".content-list" ).sortable();*/
hovBool = false;

$(document).ready(function() {

  // init notepad stuff
  var editor = new wysihtml5.Editor("textarea", {
    toolbar:      "wysitoolbar",
    stylesheets:  "css/stylesheet.css",
    parserRules:  wysihtml5ParserRules
  });

  // if we click the notepad, open the editor
  $(".real-text").click(function() {
    
  });

  var keyFrame = function() {
    editor.composer.iframe.style.height = (editor.composer.element.scrollHeight + 20) + "px";
  }

  var blurFrame = function() {
    editor.composer.iframe.style.height = (editor.composer.element.scrollHeight + 20) + "px";
  }

  var focusFrame = function() {
    editor.composer.iframe.style.height = (editor.composer.element.scrollHeight + 20) + "px";
  }

  editor.on("load", function() {
    editor.composer.iframe.style.height = (editor.composer.element.scrollHeight + 20) + "px";
    editor.composer.element.addEventListener("keyup", keyFrame, false)
    editor.composer.element.addEventListener("blur", blurFrame, false)
    editor.composer.element.addEventListener("focus", focusFrame, false)
  })




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



// this is for toggling the notepad functionality
function toggleNotepad() {
  if ($('.real-text').is(':visible')) {
    alert(1);
  }
}




































  $.widget( "custom.catcomplete", $.ui.autocomplete, {
    _renderMenu: function( ul, items ) {
      var self = this,
        currentCategory = "";
      $.each( items, function( index, item ) {
        if ( item.category != currentCategory ) {
          ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
          currentCategory = item.category;
        }
        self._renderItem( ul, item );
      });
    }
  });

  $(function() {

    $( "#tag-adder" ).catcomplete({
      autoFocus: true,
      delay: 0,
      source: tag_data,
      select: function( event, ui ) {
        $("#tag-adder").val(ui.item.title)
        return false;
      },
      focus: function(event, ui) {
        $('.tooltip').remove();

        if (ui.item.category == "Common Core") {
          $('.ui-state-hover').tooltip({
            placement: 'right',
            title: ui.item.label,
            trigger: 'manual'
          });
          $('.ui-state-hover').tooltip("show");
        }
        return false;
      }
    })
    .data("catcomplete")._renderItem = function(ul, item) {
      return $( "<li></li>" )
      .data( "item.autocomplete", item )
      .append( "<a style='padding-left:10px'>" + item.title + "</a>")
      .appendTo( ul );
    };
  });