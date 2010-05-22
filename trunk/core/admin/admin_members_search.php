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

# Title: Control Panel - Member - Search

if(!function_exists('admin_members_search'))
{
  /*
    Function: admin_plugins_add

    Handles the downloading and extracting of plugins.

    Parameters:
      none

    Returns:
      void - Nothing is returned by this function.

    Note:
      This function is overloadable.
  */
  function admin_members_search()
  {
    global $api, $base_url, $member, $settings, $theme, $theme_url;

    $api->run_hooks('admin_members_search');

    # Can you search for members?
    if(!$member->can('search_members'))
    {
      # That's what I thought!
      admin_access_denied();
    }

    $theme->set_current_area('members_search');

    $theme->set_title(l('Search for members'));

    $theme->header();

    echo '
  <h1><img src="', $theme->url(), '/members_search-small.png" alt="" /> ', l('Search for members'), '</h1>
  <p>', l('You can search for members via the form below.'), '</p>';

    $theme->footer();
  }
}
?>