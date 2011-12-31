<?php
if (isset($_POST['submitted'])) {
  $start = 1; $tags = array();
  while ($start <= 4) {
    $localTags = explode("[]", $_POST['type' . $start]);
    foreach ($localTags as $local) {
      if ($local != '') {
        $tags[] = array("title"=>$local, "type"=>$start);
      }
    }
    $start++;
  }

updateTags($_POST['conIDs'], $tags);
echo 1;

  /*
  $attempt = 1;
  if ($attempt == 1) {
    echo 1;
  } else {
    echo '<div class="alert-message warning" style="width:310px">';
    foreach($attempt as $error) {
      echo '<li>' . say($error) . '</li>';
    }
    echo '</div>';

  }*/


  exit();
}
// SHOW REGULAR PAGE
// YAY IM DRUNK
$batchCon = getBatchContent($_GET['conIDs']);
$tags = getSharedTags($batchCon);
$numTags = count(explode(',', $_GET['conIDs'])) - 1;
$marker = 0;
$typeName = array();
$typeName[1] = "Grade Levels";
$typeName[2] = "Subjects";
$typeName[3] = "Standards";
$typeName[4] = "Keywords";
$finalArr = array();
$finalArr[1] = array();
$finalArr[2] = array();
$finalArr[3] = array();
$finalArr[4] = array();

// cycle through and sort tags
foreach ($tags as $tag) {
   if ($tag['type'] == 1) {
     $finalArr[1][] = array("title"=>$tag['title'], "loc"=>$tag['loc']);
   } elseif ($tag['type'] == 2) {
    $finalArr[2][] = array("title"=>$tag['title'], "loc"=>$tag['loc']);
  } elseif ($tag['type'] == 3) {
    $finalArr[3][] = array("title"=>$tag['title'], "loc"=>$tag['loc']);
  } elseif ($tag['type'] == 4) {
    $finalArr[4][] = array("title"=>$tag['title'], "loc"=>$tag['loc']);
  }
}
?>
<script type="text/javascript">
$(document).ready(function(){
  fbFormControl();

  $("img[rel=locked]").twipsy({
    live: true,
    placement: 'right',
    html: true
  });

  $("#keywordPut").keypress(function(event) {
    if (event.which == 13) {
       event.preventDefault();
       addTag(4, $("#keywordPut").val());
       $("#keywordPut").val('')
     }
  });
});
$('#update-tags').submit(function() {
    fbFormSubmitted('#update-tags');
    var type1 = '';
    var type2 = '';
    var type3 = '';
    var type4 = '';

    $('.type1text').each(function(index) {
      type1 += $(this).html() + "[]";
    });
    $('.type2text').each(function(index) {
      type2 += $(this).html() + "[]";
    });
    $('.type3text').each(function(index) {
      type3 += $(this).html() + "[]";
    });
    $('.type4text').each(function(index) {
      type4 += $(this).html() + "[]";
    });

    $.ajax({  
      type: "POST",  
      url: "/app/filebox/write/tags/", 
      data: 'submitted=true&conIDs=<?= $_GET['conIDs']; ?>&type1=' + escape(type1) + '&type2=' + escape(type2) + '&type3=' + escape(type3) + '&type4=' + escape(type4),
      success: function(retData) {
        
        if (retData == '1') {
          initAsyncBar('<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Tags updated successfully</span>', 'yellowBox', 190, 547, 1500);
          softRefresh();
          closeBox();
        } else {
          fbFormRevert('#update-tags');
          showFormError(retData);
        }
        
      }  
      
  });  
    return false;
});


// adding/removing tags functions
function addToggle() {
  //alert($(".addTags").height());
  if ($(".addTags").hasClass('active')) {
    if ($("#addTagMenu").is(":hidden")) {
      $("#swapText").html('- Add Tags');
      inCombo("#addTagMenu");
      $(".tagMainItemOpt").slideUp(200);
    } else {
    outCombo("#addTagMenu");
    $(".tagMainItemOpt").hide();
    $("#swapText").html('+ Add Tags');
    $(".addTags").removeClass('active');
  }
  } else {
    inCombo("#addTagMenu");
    $("#swapText").html('- Add Tags');
    $(".addTags").addClass('active');
  }
}



function swapTag(type, tag) {
  var pass = true;
  $('.type' + type + 'text').each(function(index) {
    if ($(this).html() == tag) {
      pass = false;
    }
  });

  // if we didn't fail anywhere, add it
  if (pass == true) {
    addTag(type, tag, 1);
  } else {
    rmTag(type, tag);
  }
  
}


function xTag(type, obj) {
  var tag = $(obj).parent().find('.type' + type + 'text').text();

  if(type == 1) {
    $("#grade-" + tag).removeAttr("checked");
  } else if (type == 2) {
    $("#sub-" + tag).removeAttr("checked");
  } else if (type == 3) {
    $('.standardCheck').each(function(index2) {
        if ($(this).val() == tag) {
          $(this).removeAttr("checked");
        }
    });
  }

  //remove the tag
  rmTag(type, tag);
  
}


function addTag(type, tag, bypass) {
  if (bypass != 1) {
      var pass = true;
      $('.type' + type + 'text').each(function(index) {
        if ($(this).html() == tag) {
          pass = false;
        }
      }); 
  } else {
    pass = true;
  }

  if (tag == '') {
    pass = false;
  }

  // if we didn't fail anywhere, add it
  if (pass == true) {

    tag = tag.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
      $("#type" + type).show();
      $('#lackTag').hide();
    

    $('.type' + type).append('<div class="alert-message elem"><span class="type' + type + 'text">' + tag + '</span><span style="font-weight:bolder;float:right;cursor:pointer" onClick="xTag(\'' + type + '\', this)">x</span></div>');
  }
  
}



function rmTag(type, tag) {
  tag = tag.replace(/'/i, "\'");
  tag = tag.replace(/"/i, '\"');
  tag = tag.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
  var pass = true;
  $('.type' + type + 'text').each(function(index) {
    if ($(this).html() == tag) {
      if ($(this).parent().find('.tagLock').length) {
        // do nothing
      } else {
        $(this).parent().remove();
      }
    }
  });

  if ($('.type' + type + 'text').length) {
    // do nothing
  } else {
    $("#type" + type).hide();
    var groupShow = false;
    $('.tagroup').each(function(index) {
      if ($(this).is(":hidden")) {
        // do nothing
      } else {
        groupShow = true;
      }
    });
    if (groupShow == false) {
      $('#lackTag').show();
    }
  }
}


function showMain(open, close) {
  outCombo(close);
  inCombo(open);
  $("#swapText").html('Back to menu');
}


function inCombo(obj) {
  $(obj).css('opacity', 0).slideDown('fast').animate({ opacity: 1 },{ queue: false, duration: 'fast'});
}
function outCombo(obj) {
  $(obj).css('opacity', 1).slideUp('fast').animate({ opacity: 0 },{ queue: false, duration: 'fast'});
}



function swapCore(curr, grade, topic) {
  if (curr == null) {
    curr = '';
  }
  if (grade == null) {
    grade = '';
  }
  if (topic == null) {
    topic = '';
  }
  $("#addStandardSub").html('<center><br /><br /><img src="/assets/app/img/box/loading.gif" /><br /><br /></center>');
  $.ajax({
   type: "GET",
   url: "/app/filebox/write/tags/commoncore/?curr=" + curr + "&grade=" + grade + "&topic=" + topic,
   success: function(msg){
     $("#addStandardSub").html(msg);
   }
 });

}

</script>
<form action="#" id="update-tags" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <!--<legend>Update Tags</legend>-->
    <div class="clearfix">
    <div id="errorBox" style="display:none"></div>

    <div class="addTags">
      <div class="toggleTags" onClick="addToggle();" style="padding-left:110px;cursor:pointer;padding-bottom:10px;margin-bottom:-10px">
      <img src="/assets/app/img/box/tag.png" style="float:left;width:16px;margin-right:5px" /> <span id="swapText">+ Add Tags</span>
      </div>

      <div id="addTagMenu" class="tagMainItem">
        <div class="tagMenuItem" style="border-top:none" onClick="showMain('#addGradeSub', '#addTagMenu')">
          <img src="/assets/app/img/temp/course.png" style="float:left;margin-right:5px;height:12px;margin-top:-1px" />Add grade levels & subjects
        </div>
        <div class="tagMenuItem" onClick="showMain('#addStandardSub', '#addTagMenu')">
          <img src="/assets/app/img/temp/standard.png" style="float:left;margin-right:5px;height:12px;margin-top:-1px" />Add state standards (common core)
        </div>
        <div class="tagMenuItem" onClick="showMain('#addKeywordSub', '#addTagMenu'); $('#keywordPut').focus();">
          <img src="/assets/app/img/temp/keywords.png" style="float:left;margin-right:5px;height:12px;margin-top:-1px" />Add keywords
        </div>
      </div>


       <div id="addKeywordSub" class="tagMainItem tagMainItemOpt">
        <div style="margin-top:10px">
        <div style="font-size:13px; color:#666; text-align:center;margin-bottom:10px;font-style:italic">
        Type in a keyword then hit enter...
        </div>
          <input class="xlarge" id="keywordPut" name="xlInput" size="30" type="text" style="margin-left:19px;">
        </div>
       </div>

       <div id="addStandardSub" class="tagMainItem tagMainItemOpt">
        <?php require_once('commoncore.php'); ?>
        </div>


      <div id="addGradeSub" class="tagMainItem tagMainItemOpt">
        <div style="width:150px;border-right:1px solid #ccc;float:left">
          <div class="tagTitleGradeSub">
          Grade Levels
          </div>
          <div>
          <?php
          $grades = 'PS,PK,K,1,2,3,4,5,6,7,8,9,10,11,12,Prep,BS/BA,Masters,PhD,Post-Doc';
          $inGrades = explode(",", $grades);
          foreach ($inGrades as $grade) {
            if (in_array(array("title"=>$grade, "loc"=>'1'), $finalArr[1])) {
              $attr = 'checked disabled';
            } elseif (in_array(array("title"=>$grade, "loc"=>'2'), $finalArr[1])) {
              $attr = 'checked';
            } else {
              $attr = '';
            }
            echo '<input id="grade-' . $grade . '" type="checkbox" onClick="swapTag(1, \'' . $grade . '\');" name="grades[]" value="' . $grade . '" ' . $attr . ' /> <span style="font-size:11px;color:#666">' . $grade . '</span><br />';
          }


          ?>
          </div>
        </div>
        <div style="width:168px;float:left">
          <div class="tagTitleGradeSub" style="border-right:1px solid #ccc;">
          Subjects
          </div>
          <div style="padding-left:3px">
          <?php
          $subjects = 'Math,Science,Social Studies,English / Language Arts,Foreign Language,Music,Physical Education,Health,Dramatic Arts,Visual Arts,Special Education,Technology and Engineering';
          $inSub = explode(",", $subjects);
          foreach ($inSub as $subject) {
            if (in_array(array("title"=>$subject, "loc"=>'1'), $finalArr[2])) {
              $attr = 'checked disabled';
            } elseif (in_array(array("title"=>$subject, "loc"=>'2'), $finalArr[2])) {
              $attr = 'checked';
            } else {
              $attr = '';
            }
            echo '<input id="sub-' . $subject . '" type="checkbox" onClick="swapTag(2, \'' . $subject . '\');" name="subjects[]" value="' . $subject . '" ' .$attr . ' /> <span style="font-size:11px;color:#666">' . $subject . '</span><br />';
          }


          ?>
          </div>
        </div>
        <div style="clear:both"></div>
      </div>

    </div>

     <?php 
    foreach ($finalArr as $tid=>$type) {
      if (empty($type)) {
        $disp = 'style="display:none"';
      } else {
        $disp = '';
        $marker++;
      }

      echo '<div id="type' . $tid . '" class="tagroup"' . $disp . '>
      <div class="tagroupTitle">
      ' . say($typeName[$tid]) . '
      </div>
      <div class="listItems type' . $tid . '">';

      foreach ($type as $item) {
        // if this is shared by parent
        if ($item['loc'] == 1) {
          $tagPend = '<img src="/assets/app/img/box/lock.png" rel="locked" data-original-title="This is a tag from a parent folder" class="tagLock" style="height:12px;float:right;cursor:pointer" />';

        // if this is local
        } elseif ($item['loc'] == 2) {
          $tagPend = '<span style="font-weight:bolder;float:right;cursor:pointer" onClick="xTag(\'' . $tid . '\', this)">x</span>';


        }
        echo '<div class="alert-message elem"><span class="type' . $tid . 'text">' . $item['title'] . '</span>' . $tagPend . '</div>';
      }

      echo '</div>
    </div>';
    }


    // if there were no tags found
    if ($marker != 0) {
      $markDisp = ';display:none';
    }

    if ($numTags > 1) {
      $text = '<div style="margin-left:20px;">There aren\'t any shared tags for these items...yet.</div>';
    } else {
      $text = '<div style="margin-left:55px;">There are no tags for this content...yet.</div>';
    }

    echo '<div id="lackTag" style="margin-top:13px; color:#666' . $markDisp . '">' . $text . '</div>';









     ?>
    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Update Tags</button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>