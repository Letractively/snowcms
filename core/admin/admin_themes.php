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

# Title: Control Panel - Themes

if(!function_exists('admin_themes'))
{
  /*
    Function: admin_themes

    Provides an interface for the selecting and uploading/downloading of themes.

    Parameters:
      none

    Returns:
      void - Nothing is returned by this function.

    Note:
      This function is overloadable.
  */
  function admin_themes()
  {
    global $api, $base_url, $member, $settings, $theme, $theme_dir, $theme_url;

    $api->run_hooks('admin_themes');

    # Can you view the error log? Don't try and be sneaky now!
    if(!$member->can('manage_themes'))
    {
      # Get out of here!!!
      admin_access_denied();
    }

    # Time for a Form, awesomeness!!!
    admin_themes_generate_form();
    $form = $api->load_class('Form');

    if(isset($_POST['install_theme_form']))
    {
      $form->process('install_theme_form');
    }

    # A couple things could happen :P
    # So let's just group them.
    if((!empty($_GET['set']) || !empty($_GET['delete'])) && verify_request('get'))
    {
      if(!empty($_GET['set']))
      {
        # Pretty simple to change the current theme ;-)
        $new_theme = basename($_GET['set']);

        # Check to see if the theme exists.
        if(file_exists($theme_dir. '/'. $new_theme) && theme_load($theme_dir. '/'. $new_theme) !== false)
        {
          # Simple enough, set the theme.
          $settings->set('theme', $new_theme, 'string');
        }
      }
      elseif(!empty($_GET['delete']))
      {
        # Deleting, are we?
        $delete_theme = basename($_GET['delete']);

        # Make sure it isn't the current theme.
        if($settings->get('theme', 'string', 'default') != $delete_theme && theme_load($theme_dir. '/'. $delete_theme) !== false)
        {
          # It's not, so we can delete it.
          # Which is simply a recursive delete.
          recursive_unlink($theme_dir. '/'. $delete_theme);
        }
      }

      # Let's get you out of here now :-)
      redirect($base_url. '/index.php?action=admin&sa=themes');
    }

    $theme->set_current_area('manage_themes');

    $theme->set_title(l('Manage themes'));

    $theme->header();

    echo '
  <h1><img src="', $theme->url(), '/manage_themes-small.png" alt="" /> ', l('Manage themes'), '</h1>
  <p style="margin-bottom: 20px;">', l('Here you can set the sites theme and also install themes as well.'), '</p>';

    # Get a listing of all the themes :-).
    $themes = theme_list();

    # Now load the information of the current theme.
    $current_theme = theme_load($theme_dir. '/'. $settings->get('theme', 'string', 'default'));

    echo '
  <div style="float: left; width: 200px;">
    <img src="', $theme_url, '/', $settings->get('theme', 'string', 'default'), '/image.png" alt="" title="', $current_theme['name'], '" />
  </div>
  <div style="float: right; width: 590px;">
    <h1 style="margin-top: 0px;">', l('Current theme: %s', $current_theme['name']), '</h1>
    <h3 style="margin-top: 0px;">', l('By %s', (!empty($current_theme['website']) ? '<a href="'. $current_theme['website']. '">' : ''). $current_theme['author']. (!empty($current_theme['website']) ? '</a>' : '')), '</h3>
    <p>', $current_theme['description'], '</p>
  </div>
  <div class="break">
  </div>
  <h1 style="margin-top: 20px;">', l('Available themes'), '</h1>
  <table class="theme_list">
    <tr>';

    # List all the themes ;-)
    $length = count($themes);
    for($i = 0; $i < $length; $i++)
    {
      $theme_info = theme_load($themes[$i]);

      if(($i + 1) % 3 == 1)
      {
        echo '
    </tr>
  </table>
  <table class="theme_list">
    <tr>';
      }

      echo '
      <td', (basename($theme_info['path']) == $settings->get('theme', 'string', 'default') ? ' class="selected"' : ''), '><a href="', $base_url, '/index.php?action=admin&amp;sa=themes&amp;set=', urlencode(basename($theme_info['path'])), '&amp;sid=', $member->session_id(), '" title="', l('Set as site theme'), '"><img src="', $theme_url, '/', basename($theme_info['path']), '/image.png" alt="" title="', $theme_info['description'], '" /><br />', $theme_info['name'], ' </a><br /><a href="', $base_url, '/index.php?action=admin&amp;sa=themes&amp;delete=', urlencode(basename($theme_info['path'])), '&amp;sid=', $member->session_id(), '" title="', l('Delete %s', $theme_info['name']), '" onclick="', ($settings->get('theme', 'string', 'default') == basename($theme_info['path']) ? 'alert(\''. l('You cannot delete the current theme.'). '\'); return false;' : 'return confirm(\''. l('Are you sure you want to delete this theme?\r\nThis cannot be undone!'). '\');"'), '" class="delete">[', l('Delete'), ']</a></td>';
    }

    echo '
    </tr>
  </table>

  <h1>', l('Install a theme'), '</h1>
  <p>', l('Below you can specify a file to upload or a URL at which to download a theme (zip, tar and tar.gz only).'), '</p>';

    $form->show('install_theme_form');

    $theme->footer();
  }
}

if(!function_exists('admin_themes_generate_form'))
{
  /*
    Function: admin_themes_generate_form

    Generates the form which allows themes to be installed.

    Parameters:
      none

    Returns:
      void - Nothing is returned by this function.

    Note:
      This function is overloadable.
  */
  function admin_themes_generate_form()
  {
    global $api, $base_url;

    $form = $api->load_class('Form');

    $form->add('install_theme_form', array(
                                       'action' => $base_url. '/index.php?action=admin&amp;sa=themes',
                                       'method' => 'post',
                                       'callback' => 'admin_themes_handle',
                                       'submit' => l('Install theme'),
                                     ));

    $form->add_field('install_theme_form', 'theme_file', array(
                                                           'type' => 'file',
                                                           'label' => l('From a file:'),
                                                           'subtext' => l('Select the theme file you want to install as a theme.'),
                                                         ));

    $form->add_field('install_theme_form', 'theme_url', array(
                                                          'type' => 'string',
                                                          'label' => l('From a URL:'),
                                                          'subtext' => l('Enter the URL of the theme you want to download and install.'),
                                                          'value' => 'http://',
                                                        ));
  }
}

if(!function_exists('admin_themes_handle'))
{
  /*
    Function: admin_themes_handle

    Handles the installation of the theme.

    Parameters:
      array $data
      array &$errors

    Returns:
      bool - Returns true on success, false on failure.

    Note:
      This function is overloadable.
  */
  function admin_themes_handle($data, &$errors = array())
  {
    global $api, $base_url, $member, $theme_dir;

    # Make a temporary file name which will be used for either downloading or uploading.
    $filename = $theme_dir. '/'. uniqid('theme_'). '.tmp';
    while(file_exists($filename))
    {
      $filename = $theme_dir. '/'. uniqid('theme_'). '.tmp';
    }

    # Downloading a theme, are we?
    if(!empty($data['theme_url']) && strtolower($data['theme_url']) != 'http://')
    {
      # We will need the HTTP class.
      $http = $api->load_class('HTTP');

      # Now attempt to download it.
      if(!$http->request($data['theme_url'], array(), 0, $filename))
      {
        # Sorry, but it appears that didn't work!
        $errors[] = l('Failed to download the theme from "%s"', htmlchars($data['theme_url']));
        return false;
      }

      # We want the name of the file...
      $name = basename($data['theme_url']);
    }
    # Did you want to upload a theme?
    elseif(!empty($data['theme_file']['tmp_name']))
    {
      # Now attempt to move the file.
      if(move_uploaded_file($data['theme_file']['tmp_name'], $filename))
      {
        # Keep the original file name...
        $name = $data['theme_file']['name'];
      }
      else
      {
        $errors[] = l('Failed to move the uploaded file.');
        return false;
      }
    }
    else
    {
      $errors[] = l('No file or URL specified.');
      return false;
    }

    // We will need to test the theme to make sure it is okay, not
    // deprecated and so on and so forth.
    redirect($base_url. '/index.php?action=admin&sa=themes&install='. urlencode(basename($filename)). '&sid='. $member->session_id());
  }
}

if(!function_exists('admin_themes_install'))
{
  /*
    Function: admin_themes_install

    Handles the installation of new themes.

    Parameters:
      none

    Returns:
      void

    Notes:
      This function is overloadable.
  */
  function admin_themes_install()
  {
    global $api, $base_url, $core_dir, $member, $theme, $theme_dir;

    // Can you do this? If not, get out of here!
    if(!$member->can('manage_themes'))
    {
      admin_access_denied();
    }

    $theme->set_current_area('manage_themes');

    // Check their session id supplied in the URL.
    verify_request('get');

    // Get the filename of the theme we are installing.
    $filename = realpath($theme_dir. '/'. basename($_GET['install']));
    $extension = explode('.', $filename);

    // Do some file checks, making sure it is in the right place and what
    // not. Don't want to let anyone do anything tricky, that's for sure.
    if(empty($filename) || !is_file($filename) || substr($filename, 0, strlen(realpath($theme_dir))) != realpath($theme_dir) || count($extension) < 2 || $extension[count($extension) - 1] != 'tmp')
    {
      $theme->set_title(l('An error has occurred'));

      $theme->header();

      echo '
    <h1><img src="', $theme->url(), '/manage_themes-small.png" alt="" /> ', l('An error has occurred'), '</h1>
    <p>', l('Sorry, but the supplied theme file either does not exist or is not a valid file.'), '</p>';

      $theme->footer();
    }
    else
    {
      // Install that theme! Maybe.
      $theme->set_title(l('Installing theme'));

      $theme->header();

      echo '
    <h1><img src="', $theme->url(), '/manage_themes-small.png" alt="" /> ', l('Installing theme'), '</h1>
    <p>', l('Please wait while the theme is being installed.'), '</p>

    <h3>', l('Extracting theme'), '</h3>';

      // The Update class can do the work for us.
      $update = $api->load_class('Update');

      // Get the name of the theme.
      $name = explode('.', basename($filename), 2);

      // We did this to remove the extension.
      $name = $name[0];

      // Make the directory where the theme will be extracted to.
      if(!file_exists($theme_dir. '/'. $name) && !@mkdir($theme_dir. '/'. $name, 0755, true))
      {
        echo '
    <p>', l('Failed to create the temporary theme directory. Make sure the theme directory is writable.'), '</p>';
      }
      elseif($update->extract($filename, $theme_dir. '/'. $name))
      {
        // If we were able to extract the theme package, that doesn't mean
        // it is a valid theme. Time to do some checking with <theme_load>!
        if(theme_load($theme_dir. '/'. $name) === false)
        {
          echo '
    <p>', l('The uploaded package was not a valid theme.'), '</p>';

          // Delete the NOT theme (:P) and the package that was uploaded.
          recursive_unlink($theme_dir. '/'. $name);
          unlink($filename);
        }
        else
        {
          echo '
    <p>', l('The theme was successfully extracted. Proceeding...'), '</p>';

          // The theme was extracted, and it appears to be a real theme,
          // so we may continue!
          $package_extracted = true;
        }
      }
      else
      {
        // Well, the Update class couldn't extract the package, so it isn't
        // a supported format (ZIP, Tarball, or Gzip tarball). That sucks.
        echo '
    <p>', l('The uploaded package could not be extracted.'), '</p>';

        recursive_unlink($theme_dir. '/'. $name);
        unlink($filename);
      }

      // Was the package extracted? If so, we can go on!
      if(!empty($package_extracted))
      {
        // Yes, yes I know! This is for checking the status of a plugin, but
        // it can do themes too! (Not like it knows better)
        // Why are we checking, you ask? Well, think about it! A theme is
        // also PHP, and in reality, it can do just as much as any plugin
        // can, meaning it can be as dangerous as any plugin.
        $status = plugin_check_status($filename, $reason);
        $theme_info = theme_load($theme_dir. '/'. $name);

        // Get the status message, and the color that the message should be.
        // But first, include a file.
        require_once($core_dir. '/admin/admin_plugins_add.php');

        // Okay, now get the response!
        $response = admin_plugins_get_message($status, $theme_info['name'], $reason, true);

        // Is it okay? Can we continue without prompting?
        $install_proceed = isset($_GET['proceed']) || $status == 'approved';
        $api->run_hooks('plugin_install_proceed', array(&$install_proceed, $status, 'theme'));

        echo '
    <h3>', l('Verifying theme status'), '</h3>
    <p style="color: ', $response['color'], ';">', $response['message'], '</p>';

        // Was it deemed okay?
        if(!empty($install_proceed))
        {
          // Yup! Sure was!
          echo '
    <h3>', l('Finishing installation'), '</h3>
    <p>', l('The installation of the theme was completed successfully. <a href="%s">Back to theme management</a>.', $base_url. '/index.php?action=admin&sa=themes'), '</p>';

          // Delete the file, and we really are done!
          unlink($filename);
        }
        else
        {
          // Uh oh!
          // It was not safe, but if you still want to continue installing
          // it, be my guest! Just be sure you know what you're getting
          // yourself into, please!
          // We will delete the extracted theme, you know, just incase ;).
          recursive_unlink($theme_dir. '/'. $name);

          echo '
      <form action="', $base_url, '/index.php" method="get" onsubmit="return confirm(\'', l('Are you sure you want to proceed with the installation of this theme?\r\nBe sure you trust the source of this plugin.'), '\');">
        <input type="submit" value="', l('Proceed'), '" />
        <input type="hidden" name="action" value="admin" />
        <input type="hidden" name="sa" value="themes" />
        <input type="hidden" name="install" value="', urlencode($_GET['install']), '" />
        <input type="hidden" name="sid" value="', $member->session_id(), '" />
        <input type="hidden" name="proceed" value="true" />
      </form>';
        }
      }

      $theme->footer();
    }
  }
}
?>