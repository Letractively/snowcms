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

# Title: Control Panel - Error Log

if(!function_exists('admin_error_log'))
{
  /*
    Function: admin_error_log

    Displays the list of errors from the database error log, if enabled.

    Parameters:
      none

    Returns:
      void - Nothing is returned by this function.

    Note:
      This function is overloadable.
  */
  function admin_error_log()
  {
    global $api, $base_url, $member, $theme;

    $api->run_hooks('admin_error_log');

    # Can you view the error log? Don't try and be sneaky now!
    if(!$member->can('view_error_log'))
    {
      # Get out of here!!!
      admin_access_denied();
    }

    # Generate the table which we will use to display the errors.
    admin_error_log_generate_table();
    $table = $api->load_class('Table');

    $theme->set_current_area('system_error_log');

    $theme->set_title(l('Error log'));

    $theme->header();

    echo '
  <h1><img src="', $theme->url(), '/error_log-small.png" alt="" /> ', l('Error log'), '</h1>
  <p>', l('View a list of errors generated by PHP.'), '</p>';

    $table->show('error_log');

    $theme->footer();
  }
}

if(!function_exists('admin_error_log_generate_table'))
{
  /*
    Function: admin_error_log_generate_table

    Generates the table which displays the errors in the error log.

    Parameters:
      none

    Returns:
      void - Nothing is returned by this function.

    Note:
      This function is overloadable.
  */
  function admin_error_log_generate_table()
  {
    global $api, $base_url;

    $table = $api->load_class('Table');

    # Add our error log table.
    $table->add('error_log', array(
                               'base_url' => $base_url. '/index.php?action=admin&amp;sa=error_log',
                               'db_query' => '
                                              SELECT
                                                error_id, error_time, member_id, member_name, member_ip, error_type, error_message, error_file, error_line, error_url
                                              FROM {db->prefix}error_log',
                               'primary' => 'error_id',
                               'options' => array(
                                              'delete' => l('Delete'),
                                              'truncate' => l('Delete all'),
                                              'export' => l('Export selected'),
                                            ),
                               'callback' => 'admin_error_log_table_handle',
                               'sort' => array('error_id', 'DESC'),
                               'cellpadding' => '4px',
                             ));

    # The id of the error.
    $table->add_column('error_log', 'error_id', array(
                                                  'column' => 'error_id',
                                                  'label' => l('ID'),
                                                  'function' => create_function('$row', '
                                                                  global $base_url;

                                                                  return \'<a href="\'. $base_url. \'/index.php?action=admin&amp;sa=error_log&amp;id=\'. $row[\'error_id\']. \'" title="\'. l(\'View full error\'). \'">\'. $row[\'error_id\']. \'</a>\';'),
                                                  'width' => '6%',
                                                ));

    # When did it occur?
    $table->add_column('error_log', 'error_time', array(
                                                    'column' => 'error_time',
                                                    'label' => l('Time'),
                                                    'subtext' => l('The time at which the error occurred.'),
                                                    'function' => create_function('$row', '
                                                                    return timeformat($row[\'error_time\']);'),
                                                    'width' => '22%',
                                                  ));

    $table->add_column('error_log', 'error_message', array(
                                                       'column' => 'error_message',
                                                       'label' => l('Error message'),
                                                     ));

    $table->add_column('error_log', 'error_type', array(
                                                    'column' => 'error_type',
                                                    'label' => l('Type'),
                                                    'subtext' => l('The type of error which occurred.'),
                                                    'function' => create_function('$row', '
                                                                    $error_type = $row[\'error_type\'];

                                                                    if($error_type == 8)
                                                                      return l(\'<abbr title="Undefined variable">Undefined</abbr>\');
                                                                    elseif($error_type == 2)
                                                                      return l(\'General\');
                                                                    elseif($error_type == \'database\')
                                                                      return l(\'Database\');
                                                                    else
                                                                      return l(\'Other\');'),
                                                  ));
  }
}

if(!function_exists('admin_error_log_table_handle'))
{
  /*
    Function: admin_error_log_table_handle

    Performs the specified action on the selected errors, or not!

    Parameters:
      string $action - The action to perform.
      array $selected - The errors selected.

    Returns:
      void - Nothing is returned by this function.

    Note:
      This function is overloadable.
  */
  function admin_error_log_table_handle($action, $selected)
  {
    global $api, $db;

    # Deleting all? Cool.
    if($action == 'truncate')
    {
      # A simple truncate will do the job!
      $db->query('
        TRUNCATE {db->prefix}error_log',
        array(), 'admin_error_log_truncate_query');
    }
    elseif($action == 'delete' && is_array($selected) && count($selected) > 0)
    {
      # Deleting the selected errors? Alrighty then!
      $db->query('
        DELETE FROM {db->prefix}error_log
        WHERE error_id IN({int_array:selected})',
        array(
          'selected' => $selected,
        ), 'admin_error_log_delete_query');
    }
    elseif($action == 'export')
    {
      # We will need these :-)
      $error_const = array(
                       E_ERROR => 'E_ERROR',
                       E_WARNING => 'E_WARNING',
                       E_PARSE => 'E_PARSE',
                       E_NOTICE => 'E_NOTICE',
                       E_USER_ERROR => 'E_USER_ERROR',
                       E_USER_WARNING => 'E_USER_WARNING',
                       E_USER_NOTICE => 'E_USER_NOTICE',
                       E_STRICT => 'E_STRICT',
                       E_DEPRECATED => 'E_DEPRECATED',
                       E_USER_DEPRECATED => 'E_USER_DEPRECATED',
                       'database' => 'database',
                     );
      ob_clean();
      header('Content-Type: text/plain; charset=utf-8');
      header('Content-Disposition: attachment; filename="error log.txt"');

      # Load the selected errors to download.
      $result = $db->query('
        SELECT
          *
        FROM {db->prefix}error_log
        WHERE error_id IN({array_int:selected})',
        array(
          'selected' => $selected,
        ), 'admin_error_log_export_query');

      $num_errors = $result->num_rows();
      $current = 0;
      while($row = $result->fetch_assoc())
      {
        echo (isset($error_const[$row['error_type']]) ? '['. $error_const[$row['error_type']]. '] ' : ''), $row['error_message'], l(' in %s on line %s', $row['error_file'], $row['error_line']), ($current + 1 < $num_errors ? "\r\n" : '');
        $current++;
      }

      # Stop executing, otherwise this won't work ;-).
      exit;
    }
  }
}

if(!function_exists('admin_error_log_view'))
{
  /*
    Function: admin_error_log_view

    Displays the specific error, showing more details information.

    Parameters:
      none

    Returns:
      void - Nothing is returned by this function.

    Note:
      This function is overloadable.
  */
  function admin_error_log_view()
  {
    global $api, $base_url, $db, $member, $theme;

    $api->run_hooks('admin_error_log_view');

    # Can you view the error log? Don't try and be sneaky now!
    if(!$member->can('view_error_log'))
    {
      # Get out of here!!!
      admin_access_denied();
    }

    # Get the error id.
    $error_id = $_GET['id'];

    # Does the error exist?
    $result = $db->query('
      SELECT
        *
      FROM {db->prefix}error_log
      WHERE error_id = {int:error_id}
      LIMIT 1',
      array(
        'error_id' => $error_id,
      ), 'admin_error_log_view_query');

    if($result->num_rows() == 0)
    {
      # Nope, it does not exist.
      $theme->set_current_area('system_error_log');

      $theme->set_title(l('An error has occurred - Error log'));

      $theme->header();

      echo '
  <h1><img src="', $theme->url(), '/error_log-small.png" alt="" /> ', l('An error has occurred'), '</h1>
  <p>', l('The error you are trying to view does not exist. <a href="%s/index.php?action=admin&amp;sa=error_log" title="Back to error log">Back to error log</a>.', $base_url), '</p>';

      $theme->footer();
    }
    else
    {
      # Fetch the error information.
      $error = $result->fetch_assoc();

      # A list of error identifiers.
      $error_const = array(
                       E_ERROR => array('E_ERROR', l('Fatal run-time error')),
                       E_WARNING => array('E_WARNING', l('General')),
                       E_PARSE => array('E_PARSE', l('Compile-time parse error')),
                       E_NOTICE => array('E_NOTICE', l('Undefined variable')),
                       E_USER_ERROR => array('E_USER_ERROR', l('Fatal run-time error')),
                       E_USER_WARNING => array('E_USER_WARNING', l('General')),
                       E_USER_NOTICE => array('E_USER_NOTICE', l('Undefined variable')),
                       E_STRICT => array('E_STRICT', l('Interopability issue')),
                       E_DEPRECATED => array('E_DEPRECATED', l('Deprecated')),
                       E_USER_DEPRECATED => array('E_USER_DEPRECATED', l('Deprecated')),
                       'database' => array('database', l('Database')),
                     );

      $theme->set_current_area('system_error_log');

      $api->run_hooks('admin_error_log_view_id', array($error_id, &$error, &$error_const));

      $theme->set_title(l('Viewing error #%s - Error log', $error_id));

      $theme->header();

      echo '
  <h1><img src="', $theme->url(), '/error_log-small.png" alt="" /> ', l('Viewing error #%s', $error_id), '</h1>
  <p>', l('You are currently viewing error #%s. <a href="%s/index.php?action=admin&amp;sa=error_log" title="Back to error log">Back to error log</a>.', $error_id, $base_url), '</p>';

      # Output all the information.
      echo '
  <p style="margin-top: 10px;"><span class="bold">', l('Time:'), '</span> ', timeformat($error['error_time']), '</p>
  <p><span class="bold">', l('Type:'), '</span> ', isset($error_const[$error['error_type']]) ? $error_const[$error['error_type']][1] : l('Unknown'), ' ', ($error['error_type'] != $error_const[$error['error_type']][0] ? '('. $error_const[$error['error_type']][0]. ')' : ''), '</p>';

      # Was this a guest..?
      if($error['member_id'] == 0)
      {
        echo '
  <p><span class="bold">', l('Member:'), '</span> ', l('Guest (IP: %s)', $error['member_ip']), '</p>';
      }
      else
      {
        # Load up their information, if they exist.
        $members = $api->load_class('Members');
        $members->load($error['member_id']);
        $member = $members->get($error['member_id']);

        # Do they?
        if($member === false)
        {
          echo '
    <p><span class="bold">', l('Member:'), '</span> ', l('%s (No longer exists, IP: %s)', $error['member_name'], $error['member_ip']), '</p>';
        }
        else
        {
          echo '
    <p><span class="bold">', l('Member:'), '</span> ', l('<a href="%s/index.php?action=profile&amp;id=%s" target="_blank">%s</a> (IP: %s)', $base_url, $member['id'], $member['name'], $error['member_ip']), '</p>';
        }
      }

      # Now for the actual message, file and line.
      echo '
    <p><span class="bold">', l('Message:'), '</span></p>
    <p style="margin-left: 10px;">', $error['error_message'], '</p>
    <p><span class="bold">', l('File:'), '</span> ', $error['error_file'], ' (Line: ', $error['error_line'], ')</p>
    <p><span class="bold">', l('URL:'), '</span> <a href="', htmlchars($error['error_url']), '" target="_blank">', htmlchars($error['error_url']), '</a></p>';

      $theme->footer();
    }
  }
}
?>