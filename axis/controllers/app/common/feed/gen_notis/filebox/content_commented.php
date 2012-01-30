<?php
$obj = array();
                $obj['title'] = $item['data'][0]['title'];
                $obj['format'] = $item['data'][0]['format'];
                $obj['versions'][0]['ext'] = $item['data'][0]['ext'];
                if ($item['data'][0]['format'] == 0) {
                    $obj['format'] = 1;
                    $obj['versions'][0]['ext'] = 'folder';
                }

                if ($item['data'][0]['optID'] != '') {
                    $url = '/app/course/' . $item['data'][0]['optID'] . '/handout/' . $item['data'][0]['id'];
                } else {
                    $url = $fbURL . $item['data'][0]['id'];
                }


             $miniResult .= '<div class="feedItemStory" id="item-' . $item['_id'] . '">
' . $delML . '
  <img src="/assets/app/img/box/comment.png" class="miniImg" /> ' . $idTitle . ' commented on <a href="' . $url . '" class="js-pjax">' . createConTitle($obj) . '</a>

  <div style="clear:both"></div>
  </div>';

?>