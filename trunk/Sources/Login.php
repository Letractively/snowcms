<?php
//                 SnowCMS
//           By aldo and soren121
//  Founded by soren121 & co-founded by aldo
//    http://snowcms.northsalemcrew.net
//
// SnowCMS is released under the GPL v3 License
// Which means you are free to edit it and then
//       redistribute it as your wish!
// 
//              Login.php file 


if(!defined("Snow"))
  die("Hacking Attempt...");

// This function prepares to show the login page  
function Login() {
global $cmsurl, $db_prefix, $l, $settings, $user;
  // Set the Page title, load Login.template.php
  $settings['page']['title'] = $l['login_title'];  
  loadTheme('Login');
}

// This processes the login form
function Login2() {
global $cmsurl, $db_prefix, $l, $settings, $user, $cookie_prefix;
  // Get and sanitize the username and encrypt the password
  $username = @clean($_REQUEST['username']);
  $password = @md5($_REQUEST['password']);
  if((!empty($username)) && (!empty($password))) {
    $result = sql_query("SELECT * FROM {$db_prefix}members WHERE `username` = '{$username}' AND `password` = '{$password}'");
    if(mysql_num_rows($result)>0) {
      while($row = mysql_fetch_assoc($result)) {
        // We need their user ID
        $id = $row['id'];
        $is_activated = $row['activated'];
        $is_banned = $row['banned'];
        $is_suspended = $row['suspension'];
      }
      // Just cause their password and username is right, doesn't mean they can login :P
      if($is_activated && $is_banned==0 && $is_suspended<time()) {
        // Set cookies :) Mmmmm, the good kind too, like Chocolate Chip, but not Oatmeal! Ewww!
        $login_length = (int)$_REQUEST['login_length'];
        if($login_length==0)
          $login_length = $settings['remember_time']*60;
        setcookie($cookie_prefix."username", $_REQUEST['username'], time()+$login_length);
        setcookie($cookie_prefix."password", md5($_REQUEST['password']), time()+$login_length);
        setcookie($cookie_prefix."uid", $id, time()+$login_length);
        
        // Set the Session variables, like ID and Pass, enables them to be validated
        // Its more secure to authenticate them on each page load, or at least we think so :P
        $_SESSION['id'] = $id;
        $_SESSION['pass'] = $password;
        // Update a few things, lkike last login, last ip, their session ID
        sql_query("UPDATE {$db_prefix}members SET `last_login` = '".time()."', `last_ip` = '{$user['ip']}' WHERE `id` = '{$_SESSION['id']}'");
        // Redirect them to the CMSURL URL :P
        header("Location: {$cmsurl}");
      }
      elseif(!$is_activated) {
        // Not activated... Give them a message and a link to a resend form
        $settings['page']['title'] = $l['login_title'];
        loadTheme('Login','NotActivated');
      }
      elseif($is_banned) {
        $settings['page']['title'] = $l['login_title'];
        loadTheme('Login','Banned');
      }
      elseif($is_suspended>time()) {
        $settings['page']['title'] = $l['login_title'];
        $settings['time'] = formattime($is_suspended);
        loadTheme('Login','Suspended');
      }
    }
    else {
      // That username doesn't exist, or it is a wrong password! but we won't say which, hehe. Error!
      $settings['page']['error'] = $l['login_error'];
      $settings['page']['title'] = $l['login_title'];
      loadTheme('Login');
    }    
  }
  else {
    // No Username and password, Error!
    $settings['page']['error'] = $l['login_error'];
    $settings['page']['title'] = $l['login_title'];
    loadTheme('Login');
  }
}

// Logout, need I explain? :P
function Logout() {
global $cmsurl, $db_prefix, $l, $settings, $user, $cookie_prefix;
  if(ValidateSession(@$_REQUEST['sc'])) {
    // Are they even logged in? Lol.
    if($user['is_logged']) {
      // Destroy! Destroy! Their session :D
      session_destroy();
      // Delete them from the {db_prefix}online table
      sql_query("DELETE FROM {$db_prefix}online WHERE `user_id` = '{$user['id']}'");
      // Delete the Cookies... If they have any (the @ means to stfu, I don't want any errors)
        @setcookie($cookie_prefix."username", '', time()-($settings['remember_time']*60));
        @setcookie($cookie_prefix."password", '', time()-($settings['remember_time']*60));
        @setcookie($cookie_prefix."uid", '', time()-($settings['remember_time']*60));    
      // Redirect to the CMSURL URL
      header("Location: {$cmsurl}");
    }
    else {
      // Your not logged in -.-'
      header("Location: {$cmsurl}");
    }
  }
  else {
    $settings['page']['title'] = $l['logout_error_title'];
    loadTheme('Login','LogoutError');
  }
}
?>