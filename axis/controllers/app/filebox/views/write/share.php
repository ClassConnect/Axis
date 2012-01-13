<?php
if (isset($_POST['submitted'])) {
  $pers = array();

    // get read only
    $localPers = explode(",", $_POST['userRead']);
    foreach ($localPers as $local) {
      if ($local != '') {

        // are we friends?
        if (authFriend($local)) {
          $pers[] = array("shared_id"=>(int) $local, "type"=>1, "auth_level"=>1);
        // no? create them an account then...
        } else {
          // CREATE ACCOUNT
          $check = getUserByEmail($local);
          // if this user exists
          if ($check != false) {
            // if this is a "fake" account
            if ($check['pass'] == 'temp-user') {
              // add as a contact
              addFriend($check['id'], null, true);
            } else {
              // request as a friend
              addFriend($check['id']);
            }

            $pers[] = array("shared_id"=>(int) $check['id'], "type"=>1, "auth_level"=>1);

          // this email address isn't in our DB. lets create a fake account for it.
          } else {
            $newID = createTempUser($local);
            addFriend($newID, null, true);
            $pers[] = array("shared_id"=>(int) $newID, "type"=>1, "auth_level"=>1);
          }

        }

      }
    }

    // get read/write
    $localPers = explode(",", $_POST['userWrite']);
    foreach ($localPers as $local) {
      if ($local != '') {

        // are we friends?
        if (authFriend($local)) {
          $pers[] = array("shared_id"=>(int) $local, "type"=>1, "auth_level"=>2);
        // no? create them an account then...
        } else {
          // CREATE ACCOUNT
          $check = getUserByEmail($local);
          // if this user exists
          if ($check != false) {
            // if this is a "fake" account
            if ($check['pass'] == 'temp-user') {
              // add as a contact
              addFriend($check['id'], null, true);
            } else {
              // request as a friend
              addFriend($check['id']);
            }

            $pers[] = array("shared_id"=>(int) $check['id'], "type"=>1, "auth_level"=>2);

          // this email address isn't in our DB. lets create a fake account for it.
          } else {
            if (user('level') == 3) {
              $newID = createTempUser($local);
              addFriend($newID, null, true);
              $pers[] = array("shared_id"=>(int) $newID, "type"=>1, "auth_level"=>2);
            }
          }

        }

      }
    }



    // get courses
    $localCourses = explode(",", $_POST['courses']);
    foreach ($localCourses as $local) {
      if ($local != '' && authSection($local) && user('level') == 3) {
        $pers[] = array("shared_id"=>(int) $local, "type"=>2, "auth_level"=>1);
      }
    }



updatePermissions($_POST['conIDs'], $pers);
echo 1;


  exit();
}


$batchCon = getBatchContent($_GET['conIDs']);
$permissions = getSharedPermissions($batchCon);
$amigos = array();
$coursesMal = array();
foreach ($permissions as $per) {
  if ($per['type'] == 1) {
    $amigos[] = $per;
  } elseif ($per['type'] == 2) {
    // catch here for the uncheckables
    $coursesMal[] = $per['shared_id'];

  }
}
?>
<style type="text/css">
.ui-autocomplete {
  font-size:12px;
  -moz-border-radius-topleft: 0; -webkit-border-top-left-radius: 0; -khtml-border-top-left-radius: 0; border-top-left-radius: 0;
  -moz-border-radius-topright: 0; -webkit-border-top-right-radius: 0; -khtml-border-top-right-radius: 0; border-top-right-radius: 0;
  border-color:#bbb;
  border-top:none;
  background-color: #eeeeee;
  background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee);
  background-repeat: repeat-x;
  background-image: -khtml-gradient(linear, left top, left bottom, from(#ffffff), to(#eeeeee));
  background-image: -moz-linear-gradient(top, #ffffff, #eeeeee);
  background-image: -ms-linear-gradient(top, #ffffff, #eeeeee);
  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ffffff), color-stop(100%, #eeeeee));
  background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee);
  background-image: -o-linear-gradient(top, #ffffff, #eeeeee);
  background-image: linear-gradient(top, #ffffff, #eeeeee);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#eeeeee', GradientType=0);
}
.ui-autocomplete a.ui-state-hover {
  color:#333;
  background:none;
}
#ui-active-menuitem {
  border-color:#ddd;
  border-left:1px solid #D9E8FF;
  border-right:1px solid #D9E8FF;
  -moz-border-radius: 0; -webkit-border-radius: 0; -khtml-border-radius: 0; border-radius: 0;
    background-color: #D9E8FF;
  background-image: -webkit-linear-gradient(top, #D9E8FF, #BDD6FC);
  background-repeat: repeat-x;
  background-image: -khtml-gradient(linear, left top, left bottom, from(#D9E8FF), to(#BDD6FC));
  background-image: -moz-linear-gradient(top, #D9E8FF, #BDD6FC);
  background-image: -ms-linear-gradient(top, #D9E8FF, #BDD6FC);
  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #D9E8FF), color-stop(100%, #BDD6FC));
  background-image: -webkit-linear-gradient(top, #D9E8FF, #BDD6FC);
  background-image: -o-linear-gradient(top, #D9E8FF, #BDD6FC);
  background-image: linear-gradient(top, #D9E8FF, #BDD6FC);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#D9E8FF', endColorstr='#BDD6FC', GradientType=0);
}
</style>

<script>
rwPer = 'View & edit';
rPer = 'View-only';
function determineAuth(str) {
  if(str == rPer) {
    return 1;
  } else {
    return 2;
  }
}


function checkBeforeAdd() {
  var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
   if ($("#adder").val().search(emailRegEx) == -1) {
    // do nothing
   } else {
    addPer(1, $("#adder").val(), determineAuth($("#perAuther").val()), $("#adder").val());
    $("#adder").val('');
   }
}


$(function() {
  fbFormControl();
    $("img[rel=locked]").twipsy({
    live: true,
    placement: 'right',
    html: true
  });
  $("#adder").keypress(function(event) {
    if (event.which == 13) {
       event.preventDefault();
       checkBeforeAdd();
     }
  });

  $( "#adder" ).autocomplete({
    autoFocus: true,
    source: amigos,
    select: function( event, ui ) {
      $("#adder").val('');

      addPer(1, ui.item.val, determineAuth($("#perAuther").val()), ui.item.label);
      //alert(ui.item.val);
      //$( "#project-id" ).val( ui.item.value );

      return false;
    }
  })
  .data("autocomplete")._renderItem = function(ul, item) {
    return $( "<li></li>" )
      .data( "item.autocomplete", item )
      .append( "<a>" + item.label + "</a>")
      .appendTo( ul );
  };
});

// adding a permission
function addPer(type, data, auth, placer) {
  // if this is a person being added
  if (type == 1) {
    $("#rmMe").remove();
    var pass = true;
    $('.uid').each(function(index) {
      if ($(this).val() == data) {
        pass = false;
      }
    });
    if (pass != false) {
      var rSel = '';
      var rwSel = '';
      var ptml = '<div class="alert-message elem">' + placer + '<span style="font-weight:bolder;float:right;cursor:pointer" onClick="rmPer(' + type + ', \'' + data + '\')">x</span><select class="auth" name="auth" style="height:23px;width:95px;font-size:11px;padding:3px;float:right;margin-top:-6px;margin-right:5px;background:none;border-radius:0">';
      if (auth == 1) {
        rSel = ' selected';
      } else {
        rwSel = ' selected';
      }

      ptml += '<option' + rwSel + '>View & edit</option><option' + rSel + '>View-only</option></select><input type="hidden" class="uid" name="uid" value="' + data + '" /></div>';
      $('.listItems').append(ptml);
  }
    
  } //type == 1
}

// removing a permission
function rmPer(type, data) {
  if (type == 1) {
    $('.uid').each(function(index) {
      if ($(this).val() == data) {
        if ($(this).parent().find('.tagLock').length) {
          // do nothing
        } else {
          $(this).parent().remove();
        }
      }
    });
  }
}


$('#update-pers').submit(function() {
  checkBeforeAdd();
  fbFormSubmitted('#update-pers');
  uidRead = '';
  uidWrite = '';
  courses = '';

  // get the users
  $('.uid').each(function(index) {
    if (determineAuth($(this).parent().find('.auth').val()) == 1) {
      uidRead += $(this).val() + ",";
    } else {
      uidWrite += $(this).val() + ",";
    }

  });

  // get the courses
  $("input:checkbox['courses']:checked").each(function() {
         courses += $(this).val() + ",";
    });


  if (uidRead.length) {
    uidRead = uidRead.substr(0,uidRead.length-1);
  }
  if (uidWrite.length) {
    uidWrite = uidWrite.substr(0,uidWrite.length-1);
  }
  if (courses.length) {
    courses = courses.substr(0,courses.length-1);
  }


  $.ajax({  
      type: "POST",  
      url: "/app/filebox/write/share/", 
      data: 'submitted=true&conIDs=<?= $_GET['conIDs']; ?>&userRead=' + uidRead + '&userWrite=' + uidWrite + '&courses=' + courses,
      success: function(retData) {
        if (retData == '1') {
          initAsyncBar('<img src="/assets/app/img/gen/success.png" style="height:14px;margin-bottom:-2px;margin-right:5px" /> <span style="font-weight:bolder">Sharing updated successfully</span>', 'yellowBox', 210, 527, 1500);
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
</script>

<form action="#" id="update-pers" class="form-stacked">
<input type="hidden" name="submitted" value="true" />
  <fieldset>
    <!--<legend>Sharing</legend>-->
    <div class="clearfix">
    <div id="errorBox" style="display:none"></div>
      <div class="input">


      <div class="tagroup" style="margin-top:-10px">
        <div class="tagroupTitle" style="margin-bottom:5px">
        Colleagues
        </div>
        <div class="listItems">
        <?php
        // no colleagues?
        if (empty($amigos)) {
          echo '<div id="rmMe" style="color:#666;margin-top:10px;text-align:center">
          We couldn\'t find any shared colleagues...yet.
          </div>';
        // otherwise, list em out
        } else {
          foreach ($amigos as $col) {
            if ($col['loc'] == 1) {
          $tagPend = '<img src="/assets/app/img/box/lock.png" rel="locked" data-original-title="This is shared from a parent folder" class="tagLock" style="height:12px;float:right;cursor:pointer" />';
          $isLock = ' disabled';
        // if this is local
        } elseif ($col['loc'] == 2) {
          $tagPend = '<span style="font-weight:bolder;float:right;cursor:pointer" onClick="rmPer(' . $col['type'] . ', \'' . $col['shared_id'] . '\')">x</span>';
          $isLock = '';


        }
            echo '<div class="alert-message elem">' . dispUser($col['shared_id'], 'first_name') . ' ' . dispUser($col['shared_id'], 'last_name') . $tagPend . '<select class="auth" name="auth" style="height:23px;width:95px;font-size:11px;padding:3px;float:right;margin-top:-6px;margin-right:5px;background:none;border-radius:0"' . $isLock . '>';
      if ($col['auth_level'] == 1) {
        $rSel = ' selected';
        $rwSel = '';
      } else {
        $rSel = '';
        $rwSel = ' selected';
      }

      echo '<option' . $rwSel . '>View & edit</option><option' . $rSel . '>View-only</option></select><input type="hidden" class="uid" name="uid" value="' . $col['shared_id'] . '" /></div>';
          }
          
        }

        $placerSwap = dispOnly('Enter colleague name or email...', 3) . dispOnly('Enter classmate or teacher name...', 1);
        ?>
        </div>

            <div class="addLeagues" style="margin-top:10px">
              <div class="input-append">
                <input size="30" type="text" id="adder" style="width:220px;height:15px;font-style:italic" onfocus="if(this.value=='<?= $placerSwap; ?>')this.value='';$(this).css('font-style','');" onblur="if(this.value=='')this.value='<?= $placerSwap; ?>';$(this).css('font-style','italic');" value="<?= $placerSwap; ?>">
                <label class="add-on" style="height:15px"><select id="perAuther" style="width:95px;font-size:11px;padding-top:3px;float:right;margin-top:-5px;background:none;border-radius:0;border:none">
                <option>View & edit</option>
                <option>View-only</option>
              </select></label>
              </div>
            </div>

      </div>



      </div>
      <?php
      if (getSections() && user('level') == 3) {
      ?>

      <div style="margin-top:70px">
      <div style="font-size:13px; font-weight:bolder; color:#666; margin-bottom:5px"> Courses </div>

      <?= buildCoursePicker($coursesMal,0,'','line-height:1.4'); ?>

      </div>
      <?php
      }
      ?>
    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button type="submit" class="btn danger">Update Sharing Permissions</button>&nbsp;<button type="reset" class="btn" onClick="closeBox();">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>