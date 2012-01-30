<?php

// close out the mini feed if there is one
                if ($miniResult != '') {
                    // do work
                    $finalResult .= '<div class="feedItem">' . $miniResult . '</div>';
                    $miniResult = '';
                }

                $finalResult .= '<div class="feedItem" id="item-' . $item['_id'] . '">
' . $delML . '
    <div class="feedLeft">
      <img src="' . iconServer() . '50_' . $secData['icon'] . '" class="profImg" /> 
    </div>

    <div class="feedRight">
      <a href="/app/course/' . $unid['shareID'] . '" style="font-weight:bolder">' . $idTitle . '</a><br />
      ' . spit($item['data'][count($item['data']) - 1]['status']) . '
    </div>

    <div style="clear:both"></div>

  </div>';

?>