<?php
////////////////////////////////////////////////////////////////////////////
//                              SnowCMS v2.0                              //
//                           By the SnowCMS Team                          //
//                             www.snowcms.com                            //
//                  Released under the GNU GPL v3 License                 //
//                    www.gnu.org/licenses/gpl-3.0.txt                    //
////////////////////////////////////////////////////////////////////////////
//                                                                        //
//       SnowCMS originally pawned by soren121 started in early 2008      //
//                                                                        //
////////////////////////////////////////////////////////////////////////////
//                                                                        //
//                  SnowCMS v2.0 began in November 2009                   //
//                                                                        //
////////////////////////////////////////////////////////////////////////////
//                       File version: SnowCMS 2.0                        //
////////////////////////////////////////////////////////////////////////////

if(!defined('IN_SNOW'))
{
  die('Nice try...');
}

// Title: Control Panel - Members - Manage

if(!function_exists('admin_members_manage'))
{
  /*
    Function: admin_members_manage

    Provides the interface for the management of members.

    Parameters:
      none

    Returns:
      void - Nothing is returned by this function.

    Note:
      This function is overloadable.
  */
  function admin_members_manage()
  {
    api()->run_hooks('admin_members_manage');

    // How about managing members? Can you do that?
    if(!member()->can('manage_members'))
    {
      // You can't handle managing members! Or so someone thinks ;)
      admin_access_denied();
    }

    // Generate our table ;)
    admin_members_manage_generate_table();
    $table = api()->load_class('Table');

    theme()->set_current_area('members_manage');

    theme()->set_title(l('Manage Members'));

    theme()->add_js_var('delete_confirm', l('Are you sure you want to delete the selected members?\\r\\nThis cannot be undone!'));
    theme()->add_js_file(array('src' => themeurl. '/default/js/members_manage.js'));

    theme()->header();

    echo '
  <h1><img src="', theme()->url(), '/members_manage-small.png" alt="" /> ', l('Manage Members'), '</h1>
  <p>', l('All existing members can be managed here, such as editing, deleting, approving, etc.'), '</p>';

    $table->show('admin_members_manage_table');

    theme()->footer();
  }
}

if(!function_exists('admin_members_manage_generate_table'))
{
  /*
    Function: admin_members_manage_generate_table

    Generates the table which displays currently existing members.

    Parameters:
      none

    Returns:
      void - Nothing is returned by this function.

    Note:
      This function is overloadable.
  */
  function admin_members_manage_generate_table()
  {
    $table = api()->load_class('Table');

    $table->add('admin_members_manage_table', array(
                                                'db_query' => '
                                                                SELECT
                                                                  member_id, member_name, display_name, member_email, member_groups, member_registered, member_activated
                                                                FROM {db->prefix}members',
                                                'db_vars' => array(),
                                                'callback' => 'admin_members_manage_table_handle',
                                                'primary' => 'member_id',
                                                'sort' => array('member_id', 'asc'),
                                                'base_url' => baseurl. '/index.php?action=admin&sa=members_manage',
                                                'cellpadding' => '4px',
                                                'options' => array(
                                                               'activate' => 'Activate',
                                                               'deactivate' => 'Deactivate',
                                                               'delete' => 'Delete',
                                                             ),
                                              ));

    // Their member id!
    $table->add_column('admin_members_manage_table', 'member_id', array(
                                                                    'column' => 'member_id',
                                                                    'label' => l('ID'),
                                                                    'title' => l('Member ID'),
                                                                  ));

    // Name too!
    $table->add_column('admin_members_manage_table', 'member_name', array(
                                                                      'column' => 'display_name',
                                                                      'label' => l('Member name'),
                                                                      'title' => l('Member name'),
                                                                      'function' => create_function('$row', '
                                                                                      return l(\'<a href="%s/index.php?action=profile&amp;id=%s" title="Edit %s\\\'s account">%s</a>\', baseurl, $row[\'member_id\'], $row[\'display_name\'], $row[\'display_name\']);'),
                                                                    ));

    // How about that email? :P
    $table->add_column('admin_members_manage_table', 'member_email', array(
                                                                       'column' => 'member_email',
                                                                       'label' => l('Email address'),
                                                                     ));

    // Is their account activated..?
    $table->add_column('admin_members_manage_table', 'member_activated', array(
                                                                           'column' => 'member_activated',
                                                                           'label' => l('Activated'),
                                                                           'function' => create_function('$row', '
                                                                                           return $row[\'member_activated\'] == 0 ? l(\'No\') : l(\'Yes\');'),
                                                                         ));

    // Registered date?
    $table->add_column('admin_members_manage_table', 'member_registered', array(
                                                                            'column' => 'member_registered',
                                                                            'label' => l('Registered'),
                                                                            'function' => create_function('$row', '
                                                                                            return timeformat($row[\'member_registered\']);'),
                                                                          ));
  }
}

if(!function_exists('admin_members_manage_table_handle'))
{
  /*
    Function: admin_members_manage_table_handle

    Handles the option selected in the options list of the generated table.

    Parameters:
      string $action - The action wanting to be executed.
      array $selected - The selected members to perform the action on.

    Returns:
      void - Nothing is returned by this function.

    Note:
      This function is overloadable.
  */
  function admin_members_manage_table_handle($action, $selected)
  {
    // No point on executing anything if nothing was selected.
    if(!is_array($selected) || count($selected) == 0)
      return;

    // Activating accounts?
    if($action == 'activate')
    {
      // A different member system?
      $handled = false;
      api()->run_hooks('admin_members_manage_handle_activate', array(&$handled, 'activate', $selected));

      // So do we need to do it ourselves?
      if(empty($handled))
      {
        // Maybe we need to send them welcome emails (if administrative approval
        // was on at the time of their registration).
        $members = api()->load_class('Members');
        $members->load($selected);
        $members_info = $members->get($selected);

        if(count($members_info) > 0)
        {
          // Their activation code is admin_approval if they need an email.
          $send = array();
          foreach($members_info as $member_info)
          {
            if($member_info['acode'] == 'admin_approval')
            {
              // So they will need one!
              $send[] = $member_info['id'];
            }
          }

          // Did any need it..?
          if(count($send) > 0)
          {
            // Yup... The function to send them is in the register.php file.
            if(!function_exists('register_send_welcome_email'))
            {
              require_once(coredir. '/register.php');
            }

            // Simple :-), I like it!
            register_send_welcome_email($send);
          }
        }

        // Make them activated (delete their activation code, too)!
        db()->query('
          UPDATE {db->prefix}members
          SET member_activated = 1, member_acode = \'\'
          WHERE member_id IN({int_array:selected}) AND member_activated != 1',
          array(
            'selected' => $selected,
          ), 'admin_members_activate_query');
      }
    }
    // Deactivating? Alright.
    elseif($action == 'deactivate')
    {
      $handled = false;
      api()->run_hooks('admin_members_manage_handle_deactivate', array(&$handled, 'deactivate', $selected));

      if(empty($handled))
      {
        // Turn 'em off!
        db()->query('
          UPDATE {db->prefix}members
          SET member_activated = 0
          WHERE member_id IN({int_array:selected}) AND member_activated != 0',
          array(
            'selected' => $selected,
          ), 'admin_members_deactivate_query');
      }
    }
    elseif($action == 'delete')
    {
      // No need for a hook here for other member systems, that's in <Members::delete>!

      // I guess you want to delete them. That's your problem ;)
      // Luckily, the Members class can handle all this!
      $members = api()->load_class('Members');
      $members->delete($selected);
    }
  }
}
?>