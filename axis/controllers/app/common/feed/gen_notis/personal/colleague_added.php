<?php
$miniResult .= '<div class="feedItemStory" id="item-' . $item['_id'] . '">
' . $delML . '
  <img src="/assets/app/img/colleagues/minicard.png" class="miniImg" /> You are now colleagues with <strong>' . dispUser($item['data'][0]['friend_id'], 'first_name') . ' ' . dispUser($item['data'][0]['friend_id'], 'last_name') . '</strong>.

  <div style="clear:both"></div>
  </div>';
?>