<?php
$rmID = $this->Command->Parameters[2];
$idata = getFeedItem($rmID);
if ($idata['uid'] != user('id')) {
  echo 'You do not have permission to edit this.';
  exit();
}

$type1 = (int)$_GET['t1'];
$type2 = (int)$_GET['t2'];

if ($type1 != '') {
  $default = array("type"=>1, "shareID"=> $type1);
}
if ($type2 != '') {
  $default = array("type"=>2, "shareID"=> $type2);
}



if (isset($_POST['submitted'])) {
  if (isset($_POST['removeAll'])) {
    $rall = true;
  } else {
    $rall = false;
  }
 rmFeedItem($rmID, $rall, $default);

    echo '<script>
closeBox();
$("#item-' . $rmID . '").css(\'opacity\', 1).slideUp(\'fast\').animate({ opacity: 0 },{ queue: false, duration: \'fast\'});
</script>';


  exit();
}
?>
<style type="text/css">
.rowPut {
    margin-top:5px;
}
.ui-datepicker {
    width:auto;
    font-size:1.0em;
}
</style>
<script type="text/javascript">
function deleteItem() {
  var serData = $('#rm-item').serialize();
  fbFormSubmitted('#rm-item');
    $.ajax({  
      type: "POST",  
      url: "/app/common/feed/remove/<?= $rmID; ?>?t1=<?= $type1; ?>&t2=<?= $type2; ?>",  
      data: serData,
      success: function(retData) {
        $("#errorBox").html(retData);
      }  
      
  });  
    return false;
}

</script>
<form action="#" id="rm-item" class="form-stacked">
  <fieldset>
    <div class="clearfix">
    <div id="errorBox">
    </div>
<span style="font-size:14px;line-height:1.3">Are you sure you want to remove this item?</span>

<?php
if (count($idata['shared_with']) > 1 && !isset($_GET['rmall'])) {
?>
<div style="margin-top:10px;margin-bottom:-15px">
  <label>
    <input type="checkbox" name="removeAll" value="true" style="float:left;margin-right:5px">
    <div style="font-weight:normal;color:#666;font-size:12px">Also remove from all other attached courses & colleagues</div>
  </label>
</div>
<?php
} else {
?>
<input type="hidden" name="removeAll" value="true" />
<?php
}
?>
<input type="hidden" name="submitted" value="true" />
    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button class="btn danger" onClick="deleteItem(); return false">Yes, remove this item</button>
      <button class="btn" onClick="closeBox(); return false">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>