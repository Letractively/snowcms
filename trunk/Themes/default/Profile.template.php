<?php
// default/Profile.template.php by SnowCMS Dev's

if(!defined('Snow'))
  die("Hacking Attempt...");
  
function Main() {
global $l, $settings, $cmsurl;
  
  $profile = $settings['profile'];
  
  echo '<h1>'.str_replace('%user%',$profile['display_name'],$l['profile_own_header']).'</h1>
        <p style="margin-top: 0"><a href="'.$cmsurl.'index.php?action=profile;sa=edit">'.$l['profile_edit_link'].'</a></p>';
  
  Info();
}

function View() {
global $l, $settings, $theme_url;
  
  $profile = $settings['profile'];
  
  echo '<h1>'.str_replace('%user%',$profile['display_name'],$l['profile_header']).'</h1>
        <table>
         '.($profile['online']
          ? '<tr><td><img src="'.$theme_url.'/'.$settings['theme'].'/images/status_online.png"
              alt="'.$l['profile_online'].'" width="16" height="16" /></td><td>
            '.$l['profile_online']
          : '<tr><td><img src="'.$theme_url.'/'.$settings['theme'].'/images/status_offline.png"
              alt="'.$l['profile_offline'].'" width="16" height="16" /></td><td>
            '.$l['profile_offline'])
          .'</td></tr>
        </table>';
  
  Info();
}

function AdminView() {
global $l, $settings, $theme_url, $cmsurl;
  
  $profile = $settings['profile'];
  
  echo '<h1>'.str_replace('%user%',$profile['display_name'],$l['profile_header']).'</h1>
        <table>
         '.($profile['online']
          ? '<tr><td><img src="'.$theme_url.'/'.$settings['theme'].'/images/status_online.png"
              alt="'.$l['profile_online'].'" width="16" height="16" /></td><td>
            '.$l['profile_online']
          : '<tr><td><img src="'.$theme_url.'/'.$settings['theme'].'/images/status_offline.png"
              alt="'.$l['profile_offline'].'" width="16" height="16" /></td><td>
            '.$l['profile_offline'])
          .' - <a href="'.$cmsurl.'index.php?action=admin;sa=members;u='.$profile['id'].'">'.$l['profile_moderate'].'</a></td></tr>
        </table>';
  
  Info();
}

function Info() {
global $l, $settings, $user, $cmsurl;
  
  $profile = $settings['profile'];
        
  echo '<br />
        
        <table width="100%">
        <tr><th style="text-align: left">Member Group:</th><td>'.$profile['group_name'].'</td></tr>
        <tr><th style="text-align: left; width: 30%">Member Since:</th><td>'.$profile['reg_date'].'</td></tr>
        <tr><th style="text-align: left">Total Posts:</th><td>'.$profile['posts'].'</td></tr>
        ';
  if ($user['group'] != -1 || $settings['captcha'])
    echo '<tr><th style="text-align: left">Email:</th><td><a href="mailto:'.$profile['email'].'">'.$profile['email'].'</a></td></tr>';
  else
    echo '<tr><th style="text-align: left">Email:</th><td><a href="'.$cmsurl.'index.php?action=profile;sa=show-email;u='.$profile['id'].'">'.$profile['email_guest'].'</a></td></tr>';
  echo '
        </table>
        
        <p>
        '.bbc($profile['text']).'
        </p>
        ';
}

function Settings() {
global $l, $settings, $cmsurl, $user;
  
  $profile = $settings['profile'];
  
  echo '<h1>'.$l['profile_edit_header'].'</h1>
        ';
  
  if (@$_SESSION['error'])
	 echo '<p>'.$_SESSION['error'].'</p>';
        
  echo '<form action="'.$cmsurl.'index.php?action=profile;sa=edit" method="post" style="display: inline">
        
        <p><input type="hidden" name="ssa" value="process-edit" /></p>
        
        <table style="width: 100%" class="padding">
        <tr><th style="text-align: left">'.$l['profile_edit_display_name'].':</th><td><input name="display_name" value="'.$profile['display_name'].'" /></td></tr>
        <tr><th style="text-align: left">'.$l['profile_edit_email'].':</th><td><input name="email" value="'.$profile['email'].'" /></td></tr>
        <tr><th style="text-align: left">'.$l['profile_edit_signature'].':</th><td><textarea name="signature" cols="45" rows="4">'.$profile['signature'].'</textarea></td></tr>
        <tr><th style="text-align: left">'.$l['profile_edit_profile_text'].':</th><td><textarea name="profile" cols="45" rows="4">'.$profile['text'].'</textarea></td></tr>
        <tr><td colspan="2"><br /></td></tr>
        <tr><th style="text-align: left">'.$l['profile_edit_password_old'].':</th><td><input type="password" name="password-old" /></td></tr>
        <tr><th style="text-align: left">'.$l['profile_edit_password_new'].':</th><td><input type="password" name="password-new" /></td></tr>
        <tr><th style="text-align: left">'.$l['profile_edit_password_verify'].':</th><td><input type="password" name="password-verify" /></td></tr>
        </table>
        
        <br />
        
        <p style="display: inline"><input type="submit" value="'.$l['profile_edit_change'].'" /></p>
        </form>
        
        <form action="'.$cmsurl.'index.php?action=profile;u='.$profile['id'].'" method="post" style="display: inline">
        <p style="display: inline">
        <input type="hidden" name="action" value="profile" />
        <input type="submit" value="'.$l['profile_edit_cancel'].'" />
        </p>
        </form>
       <br />
       <br />
       ';
}

function NoProfile() {
global $cmsurl, $l, $settings;
  
  echo '
  <h1>'.$l['profile_noprofile_header'].'</h1>
  <p>'.$l['profile_noprofile_desc'].'</p>';
}

function NotAllowed() {
global $cmsurl, $l, $settings, $user;
  
  echo '
  <h1>'.$l['profile_notallowed_header'].'</h1>
  ';
  if ($user['is_logged'])
    echo '<p>'.$l['profile_notallowed_desc'].'</p>';
  else
    echo '<p>'.$l['profile_notallowed_desc_loggedout'].'</p>';
}

function ShowEmail() {
global $l, $settings, $cmsurl;
  
  echo '
  <h1>'.str_replace('%user%',$settings['page']['username'],$l['profile_showemail_header']).'</h1>
  ';
  
  if (@$_SESSION['error'])
	 echo '<p>'.$_SESSION['error'].'</p>';
  
  echo '
  <p>'.$l['profile_showemail_desc'].'</p>
  
  <form action="'.$cmsurl.'index.php?action=profile;sa=show-email;u='.$settings['page']['uid'].'" method="post">
    <p><img src="'.$cmsurl.'image.php" alt="CAPTCHA" /></p>
    <p>
      <input name="captcha" />
      <input type="submit" value="'.$l['profile_showemail_submit'].'" />
    </p>
  </form>
  ';
}
?>