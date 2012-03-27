$(".wzdv1").addClass('wizard-crossed');
guiders.createGuider({
  xButton: true,
  buttons: [],
  attachTo: "#addBtn",
  position: 3,
  id: "first",
  next:"second",
  title: "Click the \"Add Files\" button!"
}).show();

guiders.createGuider({
  xButton: true,
  buttons: [],
  attachTo: "#addBtn",
  position: 3,
  id: "second",
  title: "Add a folder or file!",
  width:230
});


$(document).ready(function() {
  // reset all
  resetAddBtn();
  $('.contentItem').unbind('click');

  $('#addBtn').click(function() {
    setTimeout('guiders.next();',200);
    resetAddBtn();
  }); 
  $('.contentItem').click(function() {
    guiders.hideAll();
    determineEmpty();
    $('.contentItem').unbind('click');
  });

});

// temp reset
function resetAddBtn() {
  $('#addBtn').unbind('click');
  $('#addBtn').click(function() {
    addButtonToggle(this);
  });
}

function determineEmpty() {
  if ($("#mainSwap").html().length != 396) {
    if ($("#facebox").is(":hidden")) {
      guiders.hideAll();
      guiders.createGuider({
        buttons: [{name: "Close & keep building your lessons!", classString: "danger", onclick: guiders.hideAll }],
        id: "uniqer",
        title: "You've got the hang of it!",
        description: "<span style='font-size:14px'>Need some inspiration? Take a look at how <a href='/app/filebox/4f6b9fc2c58216510f000003' target='_blank'>this teacher organized their lessons!</a></span>"
      }).show();

    }
    
    return true;

  } else {
    setTimeout('determineEmpty();',1500);

  }
}