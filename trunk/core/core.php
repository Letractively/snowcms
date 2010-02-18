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

/*
  Title: Core actions

  Function: init_core

  Registers actions which are default "features", such as logging in/out,
  registration, and other such operations. Plus a couple other things ;)

  Parameters:
    none

  Returns:
    void - Nothing is returned by this function.

  Note:
    All the actions registered in this function can be overloaded, simply
    by registering the actions before init_core is called, but also, all
    the functions which are used are overloadable as well.
*/
function init_core()
{
  global $api, $core_dir;

  # We have a couple default actions of our own :) (Remember, you can register
  # these actions before they are registered here! :) But also all these functions
  # are overloadable, so simply define them before this too!!!)
  $api->add_action('activate', 'activate_view', $core_dir. '/activate.php');
  $api->add_action('admin', 'admin_switch', $core_dir. '/admin.php');
  $api->add_action('checkcookie', 'checkcookie_verify', $core_dir. '/checkcookie.php');
  $api->add_action('login', 'login_view', $core_dir. '/login.php');
  $api->add_action('login2', 'login_view2', $core_dir. '/login.php');
  $api->add_action('logout', 'logout_process', $core_dir. '/logout.php');
  $api->add_action('profile', 'profile_switch', $core_dir. '/profile.php');
  $api->add_action('register', 'register_view', $core_dir. '/register.php');
  $api->add_action('register2', 'register_process', $core_dir. '/register.php');
  $api->add_action('resend', 'resend_view', $core_dir. '/resend.php');
  $api->add_action('reminder', 'reminder_view', $core_dir. '/reminder.php');
  $api->add_action('reminder2', 'reminder_view2', $core_dir. '/reminder.php');

  # Start output buffering.
  ob_start($api->apply_filter('output_callback', null));
}
?>