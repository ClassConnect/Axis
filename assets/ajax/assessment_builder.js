// initialize sortable properties
$(function() {
    $("#mainviewer").sortable({
        distance: 15,
        stop: function() {
            sortIt();
        }
    });
});




jQuery.fn.ForceNumericOnly =
function()
{
    return this.each(function()
    {
        $(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
            return (
                key == 8 || 
                key == 9 ||
                key == 46 ||
                (key >= 37 && key <= 40) ||
                (key >= 48 && key <= 57) ||
                (key >= 96 && key <= 105));
        })
    })
};



// floating right panel
$(document).ready(function (){           
  var thisPage = $(this);
  var panel = $("#sidepanel");
  var panelTop = panel.offset().top;
  var thisPageTop = 0;
  $(window).bind('scroll', function(){  
    thisPageTop = thisPage.scrollTop();
    if(thisPageTop > (panelTop - 10) && !panel.hasClass('floatingPanel')){
      panel.addClass('floatingPanel'); 
    }
    else if(thisPageTop <= (panelTop - 10) && panel.hasClass('floatingPanel')){ 
      panel.removeClass('floatingPanel');
    }
  });




}); 


// sort all questions numerically
function sortIt() {
    jQuery.each($('.question'), function(i, val) {
  $(this).find('.ordnum').html(i + 1);
});
}


// calculate score
function calcIt() {
    var total = 0;
    jQuery.each($('.question'), function(i, val) {
        total = total + parseInt($(this).find('.pointers').html());
    });
    $("#totalPoints").html(total);
}




// create an element
function create_ele(typer) {
    closeAnswers();
    // if this is a multiple choice question
    if (typer == 1) {
      var q = $("#mainviewer").append('<div class="selement question mcq"><div class="handle"><img src="/app/core/site_img/gen/drag.png" /></div><div class="points"><span class="pointers">1</span> points <img src="/app/core/site_img/gen/change.png" style="height:13px; margin-left:1px; margin-bottom:-2px;cursor:pointer" onClick="swapPoints(this);" /></div><div class="deler" onClick="del_ele(this);">x</div><span class="ordnum"></span><div style="font-size:16px"><span class="qText" style="font-style:italic">This is the question.</span><img src="/app/core/site_img/gen/change.png" style="margin-left:7px; margin-bottom:-2px;cursor:pointer" onClick="swapQ(this);" /></div><div class="answers"></div><div class="answers_open" style="font-size:14px;margin:10px"><a href="#" onClick="addMcqAnswer($(this).parent().parent()); return false">Add answer</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="saveAnswers($(this).parent().parent()); return false">Save answers</a></div><div class="answers_closed" style="font-size:14px;margin:10px;display:none"><a href="#" onClick="showAnswers($(this).parent().parent()); return false">Edit answers</a></div></div>');

      // add default of four answers
      addMcqAnswer($("#mainviewer div").parent().last(), 4);
        
    // if this is a fill in the blank question
    } else if (typer == 2) {
      // do nothing for now.
        
    // if this is a text block
    } else if (typer == 3) {
    $("#mainviewer").append('<div class="selement textblock"><div class="handle"><img src="/app/core/site_img/gen/drag.png" /></div><div class="deler" onClick="del_ele(this);">x</div><div style="font-size:16px"><span class="qText" style="font-style:italic">This is a text block.</span><img src="/app/core/site_img/gen/change.png" style="margin-left:7px; margin-bottom:-2px;cursor:pointer" onClick="swapQ(this);" /></div><div style="clear:both"></div></div>');

    }

    $.scrollTo('.addMore');
    $.scrollTo('-=180px');
    sortIt();
    calcIt();
}


function del_ele(obje) {
    $(obje).parent().remove();
    sortIt();
    calcIt();
}


function swapQ(obje) {
    var qtext = $(obje).parent().find('.qText').html();
    qtext = qtext.replace(/<br>/g,'\n');
    $(obje).parent().find('.qText').after('<textarea id="textOpen" cols="107">' + qtext + '</textarea><button class="button" type="submit" style="float:right;-webkit-border-top-left-radius: 0px;-moz-border-radius-topleft: 0px;-khtml-border-radius-topleft: 0px;-webkit-border-top-right-radius: 0px;-moz-border-radius-topright: 0px;-khtml-border-radius-topright: 0px;" onClick="swapA(this);"><img src="/app/core/site_img/gen/save.png" /> Update</button>');
    $(obje).parent().find('#textOpen').autoGrow();
    $(obje).parent().find('.qText').remove();
    $(obje).remove();
}


function swapA(obje) {
    var qtext = $(obje).parent().find('#textOpen').val();
    qtext = qtext.replace(/\n/g,'<br>');
    $(obje).parent().find('#textOpen').after('<span class="qText">' + qtext + '</span><img src="/app/core/site_img/gen/change.png" style="margin-left:7px; margin-bottom:-2px;cursor:pointer" onClick="swapQ(this);" />');
    $(obje).parent().find('#textOpen').remove();
    $(obje).remove();
    
}


function swapPoints(obje) {
    var parent = $(obje).parent();
    var numero = $(obje).parent().find('.pointers').html();
    $(obje).parent().find('.pointers').remove();
    $(obje).before('<input type="text" maxlength="3" class="pointput" value="' + numero + '" size="3" />');
    $(obje).before('<img src="/app/core/site_img/gen/save.png" style="height:13px; margin-left:1px; margin-bottom:-2px;cursor:pointer" onClick="setPoints(this);" />');
    $(obje).remove();

    $(".pointput").ForceNumericOnly();
}


function setPoints(obje) {
    var parent = $(obje).parent();
    var numero = $(obje).parent().find('.pointput').val();
    $(obje).parent().find('.pointput').remove();
    parent.html('<span class="pointers">' + numero + '</span> points <img src="/app/core/site_img/gen/change.png" style="height:13px; margin-left:1px; margin-bottom:-2px;cursor:pointer" onClick="swapPoints(this);" />');
    calcIt();
}


function addMcqAnswer(obje, num) {
    start = 1;
    if (num == undefined) {
        num = 1;
    }

    while (start <= num) {
       $(obje).find('.answers').append('<div class="answer wrong"><img class="correctImg" src="/app/core/site_img/gen/red.png" height="16" style="float:left; margin-top:5px; margin-right:5px; cursor:pointer" onClick="swapCorrect(this)" /><input class="answered" type="text" />&nbsp;&nbsp;<a href="#" onClick="delete_answer(this); return false">X</a></div>'); 
       start++;
    }
}


function swapCorrect(obje) {
    var parent = $(obje).parent();
    if (parent.hasClass('wrong')){

        parent.find('.correctImg').attr('src', '/app/core/site_img/gen/green.png');
        parent.removeClass('wrong');
        parent.addClass('right');
    } else {

        parent.find('.correctImg').attr('src', '/app/core/site_img/gen/red.png');
        parent.addClass('wrong');
        parent.removeClass('right');
    }
}




function saveAnswers(obje) {
    $(obje).find('.answers').hide();
    $(obje).find('.answers_open').hide();
    $(obje).find('.answers_closed').show();
}

function showAnswers(obje) {
    $(obje).find('.answers').show();
    $(obje).find('.answers_open').show();
    $(obje).find('.answers_closed').hide();
}

function delete_answer(obje) {
    $(obje).parent().remove();
}


function closeAnswers() {
    jQuery.each($('.answers'), function(i, val) {
        if ($(this).is(':hidden')) {
            // do nothing
        } else {
            // close it
            saveAnswers($(this).parent());
        }
    });
    
}







function saveMent() {
jQuery.each($('.selement'), function(i, val) {
    if ($(this).hasClass('question')) {
        alert($(this).find('.qText').html());
    }
});
    
}