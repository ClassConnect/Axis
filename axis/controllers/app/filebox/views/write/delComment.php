<?php
$conID = $this->Command->Parameters[3];
$dataID = $this->Command->Parameters[4];
$comID = $this->Command->Parameters[5];


if (isset($_POST['submitted'])) {
  delConComment($conID, $dataID, $comID);

    echo '<script>
closeBox();
$("#com-' . $comID . '").css(\'opacity\', 1).slideUp(\'fast\').animate({ opacity: 0 },{ queue: false, duration: \'fast\'});
$(\'.selecterd\').find(\'.commentcount\').html(parseInt($(\'.selecterd\').find(\'.commentcount\').html()) - 1);
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
  var serData = $('#rm-comment').serialize();
  fbFormSubmitted('#rm-comment');
    $.ajax({  
      type: "POST",  
      url: "/app/filebox/write/rm/comment/<?= $conID . '/' . $dataID . '/' . $comID; ?>",  
      data: serData,
      success: function(retData) {
        $("#errorBox").html(retData);
      }  
      
  });  
    return false;
}

</script>
<form action="#" id="rm-comment" class="form-stacked">
  <fieldset>
    <div class="clearfix">
    <div id="errorBox">
    </div>
<span style="font-size:14px;line-height:1.3">Are you sure you want to remove this comment?</span>

<input type="hidden" name="submitted" value="true" />
    </div><!-- /clearfix -->
  </fieldset>
  <div id="fbActions" class="actions" style="margin-bottom:-17px">
    <div style="float:right">
      <button class="btn danger" onClick="deleteItem(); return false">Yes, remove this comment</button>
      <button class="btn" onClick="closeBox(); return false">Close</button>
    </div>
    <div style="clear:both"></div>
  </div>
</form>