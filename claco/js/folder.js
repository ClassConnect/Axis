
  /*
  $( ".content-item" ).draggable({ 
    revert: "invalid",
    distance: 20,
    zIndex: 99999
  });

  $( ".content-list" ).sortable();*/
hovBool = false;

$(document).ready(function() {
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

        $('.dropper-tog').find('.folder-preview').css({position: 'absolute'}).animate({ top: topfin, left: leftfin }, 200);

        $('.dropper-tog').css('opacity', 1).animate({ opacity: 0.01 },{ queue: false, duration: 400});

      }
    });




});




// helper methods
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