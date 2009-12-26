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
  Class: Tasks

  This is another tool which is available to developers. With this tool
  plugins can add/remove/edit tasks which can be set to be processed on
  a regular basis.

  Note:
    Please realize that the scheduled tasks may not always occur at the
    time you expect them too! These tasks are ran when a visitor comes to
    a page, and if their are any tasks which need to be done, then they
    are ran, but not always at the time you want! Also realize that admins
    can disable tasks altogether.
*/
class Tasks
{
  /*
    Constructor: __construct
  */
}

function init_tasks()
{
  global $api, $settings;

  if($settings->get('enable_tasks') == 1)
    $tasks = $api->load_class('Tasks');
}
?>