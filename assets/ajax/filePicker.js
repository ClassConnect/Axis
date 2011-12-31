function selectMe(obj) {
  $(".folderPicker").removeClass("folderActive");
  obj.addClass("folderActive");
  obj.find(".folderRad").attr('checked', true);
}


function swapDir(dirID) {
  $("#selectBox").html('<br /><center><img src="/app/core/site_img/loading.gif" /></center><br />');
  $.ajax({
    type: "GET",
    url: "/app/core/ajax/picker/file.cc?id=" + dirID + "&type=" + $("#contentAllow").html() + "&addType=" + $("#addAllow").html(),
    success: function(msg){
      $("#selectBox").html(msg);
    }
  });
}



$(document).ready(function() {
  swapDir(0);
});

//  List of all the divs that 
var filePickers = [];
//  Contains a map of all the settings objects, with the id of the containing
//  div being the key.
var settings = {};
//  Dictionary of Arrays. Key to the dictionaries are the ids of the filePicker
//  Arrays are filled with objects, holding data about the selected files
var _selected_files = {};

(function($){
  $.fn.filePicker = function(options){
    var $this = $(this);
    var id = $this.attr('id');
    //  If there is no id for the element, we can't do anything with it
    if(id == undefined)
    {
      console.log("Cannot call filePicker on an object without an id");
      return this;
    }
    //  If they have already done a filePicker for this id, we just return
    if($.inArray(id,filePickers) != -1)
      return $(this);
    var defaults = {
      allowMultiple:false,
      fileDblClick:function(){},
      allowedContentTypes:[],
      allowedFileExtensions:[],
      directoryChangeCallback:function(directory_id){},
      filesChangedCallback:function(files){}
    }
    $this.addClass('filePicker');
    reverse_merge(options,defaults);
    filePickers.push(id);
    settings[id] = options;
    view_directory(id,0);
    options.filesChangedCallback([]);
    return $this;
  };
  $.fn.selectedFiles = function(){
    return selected_files($(this).attr('id'));
  };
  $.fn.setSelectedFiles = function(files){
    set_selected_files($(this).attr('id'),files);
  }
})(jQuery);

function picker_options(picker_id)
{
  if(!$.inArray(picker_id,filePickers) == -1)
  {
    console.log(picker_id+" is not the id of a valid file picker.");
    return null;
  }
  else
  {
    return settings[picker_id];
  }
}

function view_directory(picker_id,directory_id)
{
  var options = picker_options(picker_id);
  if(!options)
    return;
  var $this = $("#"+picker_id);
  $this.html('<br /><center><img src="/app/core/site_img/loading.gif" /></center><br />');
  $.ajax({
    type:"GET",
    url: "/app/core/ajax/picker/filepicker.cc?element="+picker_id+"&id="+directory_id+"&type="+options.allowedContentTypes.join(',')+"&addType="+options.allowedFileExtensions.join(','),
    success:function(msg){
      $this.html(msg);
      //  Go through the selected files, make sure that the
      var files = selected_files(picker_id);
      for(var i = 0 ; i < files.length ; i++)
      {
        var file = files[i];
        $this.find('input').each(function(i,element){
          var $element = $(element);
          if($element.attr('value') == file.id)
          {
            $element.attr('checked',true);
            $element.parent().addClass('folderActive');
          }
        });
      }
      options.directoryChangeCallback(directory_id);
    }
  });
}

//  Assumes that object is a child of the div represented by picker_id.
//  Bad assumption?
function select_file(picker_id,object)
{
  var options = picker_options(picker_id);
  if(!options)
    return;
  var $this = $("#"+picker_id);
  if(!options.allowMultiple)
  {
    //  If we don't allow multiple selection, get rid of all the previously
    //  'selected' files
    $this.children('div').removeClass("folderActive");
    $this.find('input').removeAttr('checked');
  }
  var $input = object.find('input');
  $input.attr('checked',!$input.attr('checked'));
  if($input.attr('checked'))
    object.addClass('folderActive');
  else
    object.removeClass('folderActive');
  _refresh_selected_files(picker_id);
  options.filesChangedCallback(selected_files(picker_id));
}

function selected_files(picker_id)
{
  if($.inArray(picker_id,filePickers) == -1)
  {
    console.log(picker_id+" is not the id of a valid file picker.");
    return [];
  }
  if(_selected_files[picker_id] == undefined)
    return [];
  return _selected_files[picker_id];
}

function set_selected_files(picker_id,files)
{
  $this = $("#"+picker_id);
  //  Go through the selected files, and if any exist in the dom, check them
  var length = files.length;
  while(length--)
  {
    $input = $this.find("input[value="+files.id+"]");
    $input.attr('checked',true);
    $input.parent().addClass('folderActive');
  }
  _selected_files[picker_id] = files;
  picker_options(picker_id).filesChangedCallback(files);
}

function _refresh_selected_files(picker_id)
{
  var $this = $('#'+picker_id),
      ret = selected_files(picker_id);
  $this.find('input').each(function(i,element){
    //  Check the ret array to see if this element exists.
    //  It it does, take it out.
    var element_id = $(element).attr('value'),
        length = ret.length;
    while(length--)
      if(ret[length].id == element_id)
        ret.splice(length,1);
  });
  $this.find('input[checked]').each(function(i,element){
    var obj = {},
        $element = $(element),
        $parent = $element.parent();
    obj.id = $element.attr('value');
    obj.icon = $parent.find('img').attr('src');
    obj.filename = $parent.text();
    ret.push(obj);
  });
  _selected_files[picker_id] = ret;
}