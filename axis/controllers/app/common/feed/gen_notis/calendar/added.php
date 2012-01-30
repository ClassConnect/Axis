<?php
// if there is more than 1 entry
                if (count($item['data']) > 1) {
                    $swapText = count($item['data']) . ' new entries';
                } else {
                    $swapText = '"' . $item['data'][count($item['data']) - 1]['title'] . '"';
                }

                $miniResult .= '<div class="feedItemStory" id="item-' . $item['_id'] . '">
' . $delML . '
  <img src="/assets/app/img/feed/cal.png" class="miniImg" /> ' . $idTitle . ' added ' . $swapText . ' to the <a href="/app/course/' . $unid['shareID'] . '/calendar" class="js-pjax">calendar</a>.

  <div style="clear:both"></div>
  </div>';

?>