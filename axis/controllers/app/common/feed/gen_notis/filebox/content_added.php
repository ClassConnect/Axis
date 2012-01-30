<?php
$obj = array();
                $obj['title'] = $item['data'][0]['title'];
                $obj['format'] = $item['data'][0]['format'];
                $obj['versions'][0]['ext'] = $item['data'][0]['ext'];
                if ($item['data'][0]['format'] == 0) {
                    $obj['format'] = 1;
                    $obj['versions'][0]['ext'] = 'folder';
                }

             $miniResult .= '<div class="feedItemStory" id="item-' . $item['_id'] . '">
' . $delML . '
  <img src="/assets/app/img/box/miniadd.png" class="miniImg" /> ' . $idTitle . ' created <a href="' . $fbURL . $item['data'][0]['id'] . '" class="js-pjax">' . createConTitle($obj) . '</a>

  <div style="clear:both"></div>
  </div>';

?>