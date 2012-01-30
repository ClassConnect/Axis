<?php
$miniResult .= '<div class="feedItemStory" id="item-' . $item['_id'] . '">
' . $delML . '
  <img src="/assets/app/img/colleagues/minicard.png" class="miniImg" /> You successfully referred <strong>' . dispUser($item['data'][0]['friend_id'], 'first_name') . ' ' . dispUser($item['data'][0]['friend_id'], 'last_name') . '</strong>! To say thanks, we added storage space to your FileBox!

  <div style="clear:both"></div>
  </div>';

 ?>