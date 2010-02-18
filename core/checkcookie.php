<?php
#########################################################################
#                             SnowCMS v2.0                              #
#                          By the SnowCMS Team                          #
#                            www.snowcms.com                            #
#                  Released under the GNU GPL v3 License                #
#                     www.gnu.org/licenses/gpl-3.0.txt                  #
#########################################################################
#                                                                       #
# SnowCMS originally pawned by soren121 started some time in early 2008 #
#                                                                       #
#########################################################################
#                                                                       #
#                SnowCMS v2.0 began in November 2009                    #
#                                                                       #
#########################################################################
#                     File version: SnowCMS 2.0                         #
#########################################################################

if(!defined('IN_SNOW'))
  die;

# Title: Cookie verification

if(!function_exists('checkcookie_verify'))
{
  /*
    Function: checkcookie_verify

    Verifies that your login cookie was actually saved by the browser.

    Parameters:
      none

    Returns:
      void - Nothing is returned by this function.

    Note:
      This function is overloadable.
  */
  function checkcookie_verify()
  {
    global $api, $base_url, $cookie_name, $core_dir, $theme;

    $api->run_hook('checkcookie_verify');

    # This is a pretty simple check...
    $cookie = isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : '';
    list($member_id) = explode('|', $cookie);

    if(empty($cookie) || empty($_GET['id']) || $_GET['id'] != $member_id)
    {
      # The cookie didn't save :(
      $api->add_filter('login_message', create_function('$value', '
        return l(\'It appears your login cookie couldn\\\'t be saved. Please be sure you have cookies enabled in your browser settings and try again.\');'));

      $api->run_hook('checkcookie_failed');

      # Login view function exist?
      $login_view_func = $api->apply_filter('login_view_function', 'login_view');
      if(!function_exists($login_view_func))
        require_once($api->apply_filter('login_view_path', $core_dir. '/login.php'));

      $theme->add_meta(array('name' => 'robots', 'content' => 'noindex'));

      $login_view_func();
      exit;
    }

    $api->run_hook('checkcookie_success');

    # Seemed to have worked, so let's go home!
    header('Location: '. $base_url);
    exit;
  }
}
?>