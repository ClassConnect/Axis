  /*$.widget( "custom.catcomplete", $.ui.autocomplete, {
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
  });*/


function initAutoTagger(identifier) {


  // hide freeform notice on blur
  $(identifier).find('.add-field').blur(function() {
    $(this).parent().find('.empty-auto-hold').hide();
    $('.tooltip').remove();
  });

  ////////////////////////////////////////////////////////////////////////////////////
  //    lets start with grades
  ////////////////////////////////////////////////////////////////////////////////////

  $(identifier).find('.grade-addfield').autocomplete({
      autoFocus: true,
      delay: 0,
      source: grade_data,
      select: function( event, ui ) {
        $('.tooltip').remove();
        $(this).val('');


        var maincontain = $(identifier).find('.tagged-grades');
        

        maincontain.find('.tags').append('<li><a href="#">' + ui.item.title + '</a> <div class="delcir" onclick="delTag(this)">x</div></li>');
        maincontain.find('.no-tags').hide();

        return false;
      }
    }).keypress(function(e) {

      

          if (e.keyCode === 13) 
          {

            // dont post if there is no content
            if ($(this).val() != '') {
              $(identifier).find('.tagged-grades').find('.tags').append('<li><a href="#">' + htmlEncode($(this).val()) + '</a> <div class="delcir" onclick="delTag(this)">x</div></li>');

              $(identifier).find('.no-tags').hide();

              $(this).parent().find('.empty-auto-hold').hide();

              $(this).val('');

            }

          } else {

            if ($(this).val() != '') {
              $(this).parent().find('.empty-auto-hold').show();
            } else {
              $(this).parent().find('.empty-auto-hold').hide();
            }

          }

      })

    .data("autocomplete")._renderItem = function(ul, item) {
      return $( "<li></li>" )
      .data( "item.autocomplete", item )
      .append( "<a style='padding-left:5px'>" + item.title + "</a>")
      .appendTo( ul );
    };






  ////////////////////////////////////////////////////////////////////////////////////
  //    now lets do subjects
  ////////////////////////////////////////////////////////////////////////////////////

  $(identifier).find('.subject-addfield').autocomplete({
      autoFocus: true,
      delay: 0,
      source: subject_data,
      select: function( event, ui ) {
        $('.tooltip').remove();
        $(this).val('');


        var maincontain = $(identifier).find('.tagged-subjects');
        

        maincontain.find('.tags').append('<li><a href="#">' + ui.item.title + '</a> <div class="delcir" onclick="delTag(this)">x</div></li>');
        maincontain.find('.no-tags').hide();

        return false;
      }
    }).keypress(function(e) {

      
        $('.tooltip').remove();
      

          if (e.keyCode === 13) 
          {

            // dont post if there is no content
            if ($(this).val() != '') {
              $(identifier).find('.tagged-subjects').find('.tags').append('<li><a href="#">' + htmlEncode($(this).val()) + '</a> <div class="delcir" onclick="delTag(this)">x</div></li>');

              $(identifier).find('.no-tags').hide();

              $(this).parent().find('.empty-auto-hold').hide();

              $(this).val('');

            }

          } else {

            if ($(this).val() != '') {
              $(this).parent().find('.empty-auto-hold').show();
            } else {
              $(this).parent().find('.empty-auto-hold').hide();
            }

          }

      })

    .data("autocomplete")._renderItem = function(ul, item) {
      return $( "<li></li>" )
      .data( "item.autocomplete", item )
      .append( "<a style='padding-left:5px'>" + item.title + "</a>")
      .appendTo( ul );
    };





  ////////////////////////////////////////////////////////////////////////////////////
  //    now lets do subjects
  ////////////////////////////////////////////////////////////////////////////////////

  $(identifier).find('.standards-addfield').autocomplete({
      autoFocus: true,
      delay: 0,
      source: standards_data,
      select: function( event, ui ) {
        $('.tooltip').remove();
        $(this).val('');


        var maincontain = $(identifier).find('.tagged-standards');
        

        maincontain.find('.tags').append('<li><a href="#">' + ui.item.title + '</a> <div class="delcir" onclick="delTag(this)">x</div></li>');
        maincontain.find('.no-tags').hide();

        return false;
      },
      focus: function(event, ui) {
        $('.tooltip').remove();


          $('.ui-state-hover').tooltip({
            placement: 'right',
            title: ui.item.label,
            trigger: 'manual'
          });
          $('.ui-state-hover').tooltip("show");
        
        return false;
      }
    }).keypress(function(e) {

      
        $('.tooltip').remove();
      

          if (e.keyCode === 13) 
          {

            // dont post if there is no content
            if ($(this).val() != '') {
              $(identifier).find('.tagged-standards').find('.tags').append('<li><a href="#">' + htmlEncode($(this).val()) + '</a> <div class="delcir" onclick="delTag(this)">x</div></li>');

              $(identifier).find('.no-tags').hide();

              $(this).parent().find('.empty-auto-hold').hide();

              $(this).val('');

            }

          } else {

            if ($(this).val() != '') {
              $(this).parent().find('.empty-auto-hold').show();
            } else {
              $(this).parent().find('.empty-auto-hold').hide();
            }

          }

      })

    .data("autocomplete")._renderItem = function(ul, item) {
      return $( "<li></li>" )
      .data( "item.autocomplete", item )
      .append( "<a style='padding-left:5px'>" + item.title + "</a>")
      .appendTo( ul );
    };




  ////////////////////////////////////////////////////////////////////////////////////
  //    finally, lets do 'other'
  ////////////////////////////////////////////////////////////////////////////////////

  $(identifier).find('.other-addfield').keypress(function(e) {
      

          if (e.keyCode === 13) 
          {

            // dont post if there is no content
            if ($(this).val() != '') {
              $(identifier).find('.tagged-other').find('.tags').append('<li><a href="#">' + htmlEncode($(this).val()) + '</a> <div class="delcir" onclick="delTag(this)">x</div></li>');

              $(identifier).find('.no-tags').hide();

              $(this).parent().find('.empty-auto-hold').hide();

              $(this).val('');

            }

          } else {

            if ($(this).val() != '') {
              $(this).parent().find('.empty-auto-hold').show();
            } else {
              $(this).parent().find('.empty-auto-hold').hide();
            }

          }

      });

}












function delTag(objc) {
  var mePar = $(objc).parent().parent();
  $(objc).parent().remove();

  if (mePar.find('.delcir').length == 0) {
    mePar.parent().parent().find('.no-tags').show();
  }
}