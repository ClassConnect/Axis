<?php
$rightCont .= '<div style="margin-left:7px;margin-top:0px">' . createSharedDirView($usr1['id']) . '</div>
<script>
$(document).ready(function() {
initShared();
});
</script>';
//$rightCont = getSharedChildren(8);
// show main annoucements
genProfPage($usr1, $rootURL, $rightCont, $cappID, 'Shared');


?>