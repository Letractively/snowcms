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
  Title: Mitigate Globals

  Mitigates any possible effects of register_globals
  http://www.php.net/manual/en/ini.core.php#ini.register-globals>, because
  you should know, register_globals is HORRIBLE!!!
*/

/*
  Function: mitigate_globals

  register_globals is one of the worst ideas ever pawned, and if enabled, could
  lead to devastating incidents, and we don't want ;)

  Parameters:
    none

  Returns:
    void - Nothing is returned by this function.
*/
function mitigate_globals()
{
  # There are some things which are fine, all the rest are bad...
  $safe_variables = array('GLOBALS', '_POST', '_GET', '_COOKIE', '_FILES', 'start_time', 'db_type', 'db_host', 'db_user', 'db_pass', 'db_pass', 'db_name', 'db_persist', 'db_debug', 'tbl_prefix', 'base_dir', 'core_dir', 'theme_dir', 'plugin_dir', 'base_url', 'theme_url', 'cookie_name');

  # Loop through GLOBALS and remove anything that shouldn't be there...
  foreach($GLOBALS as $variable => $dummy)
    if(!in_array($variable, $safe_variables))
      unset($GLOBALS[$variable]);


  # Don't even try it, okay?
  if(isset($_REQUEST['GLOBALS']) || isset($_COOKIE['GLOBALS']) || isset($_FILES['GLOBALS']))
    die('Hacking attempt...');
}
?>