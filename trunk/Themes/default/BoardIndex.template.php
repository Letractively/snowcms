<?php
// BoardIndex.template.php by SnowCMS Dev's

if(!defined('Snow'))
  die('Hacking Attempt...');
  
function Main() {
global $cmsurl, $theme_url, $l, $settings, $user;
  foreach($settings['forum']['cats'] as $cat) {
    echo '
    <div id="category">
      <p class="title">'.$cat['name'].'</p>';
      foreach($cat['boards'] as $board) {
        echo '
        <div id="board">
          <p class="title">&nbsp;</p>
          <table>
            <tr>  
              <td width="10%"><img src="'.$theme_url.'/'.$settings['theme'].'/on.gif" alt=""/></td>
              <td width="54%"><p><a href="'.$cmsurl.'forum.php?board='.$board['id'].'">'.$board['name'].'</a><br />
                  '.$board['desc'].'</p>
              </td>
              <td width="16%">'.$board['posts'].' '.$l['forum_posts_in'].' '.$board['topics']. ' '.$l['forum_topics'].'</td>
              <td width="20%">Last Posts</td>
            </tr>
          </table>
        </div>';
      }
    echo '
    <div class="break">
    </div>
    </div>';
  }
}