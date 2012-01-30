<?php
////////////////////////////////////////////////////////////////////////////
//                              SnowCMS v2.0                              //
//                           By the SnowCMS Team                          //
//                             www.snowcms.com                            //
//            Released under the Microsoft Reciprocal License             //
//                 www.opensource.org/licenses/ms-rl.html                 //
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

/*
	Title: SnowCMS System Updater

	This file handles everything related to updating the system. This file is
	not accessed directly by a user, but AJAX requests are made to complete
	certain steps of the update process. In order for the step to be completed
	the user must supply a valid update key, which is contained within the
	update-key.php file in the base directory of the system.
*/

// Magic quotes, what a joke!!!
if(function_exists('set_magic_quotes_runtime'))
{
	@set_magic_quotes_runtime(0);
}

// All time/date stuff should be considered UTC, makes life easier!
if(function_exists('date_default_timezone_set'))
{
	date_default_timezone_set('UTC');
}
else
{
	@ini_set('date.timezone', 'UTC');
}

// We are currently in SnowCMS :)
define('INSNOW', true, true);

// We want to see those errors...
error_reporting(E_STRICT | E_ALL);

// Remove magic quotes, if it is on...
if((function_exists('get_magic_quotes_gpc') && @get_magic_quotes_gpc() == 1) || @ini_get('magic_quotes_sybase'))
{
	$_COOKIE = remove_magic($_COOKIE);
	$_GET = remove_magic($_GET);
	$_POST = remove_magic($_POST);
	$_REQUEST = remove_magic($_REQUEST);
}

// We will need to load a few things, but not everything.
if(file_exists('config.php'))
{
	require(dirname(__FILE__). '/config.php');
}
else
{
	die('No config.php file found.');
}

// We need the database, at least some point in time. We won't connect until
// we need to (which will be done whenever db() is called for the first
// time).
require(coredir. '/database.php');

require(coredir. '/compat.php');

// We definitely need the API.
require(coredir. '/api.class.php');

api();

// Along with a few other things.
require(coredir. '/typecast.class.php');
require(coredir. '/settings.class.php');

// Load up the settings with the Settings class.
settings();

require(coredir. '/func.php');

// Initialize the $func array.
init_func();

require(coredir. '/clean_request.php');

// Everything we need is up and running. Let's get started!
call_user_func(update_func());

/*
	Function: update_func

	Returns a string containing the function which should be called according
	to the current request being made.

	Parameters:
		none

	Returns:
		string - The name of the function to execute.
*/
function update_func()
{
	// We should see about loading the update key.
	if(is_file(basedir. '/update-key.php'))
	{
		$fp = fopen(basedir. '/update-key.php', 'r');

		// Seek passed some stuff.
		fseek($fp, 13);

		// Now we can get the key.
		$update_key = fread($fp, filesize(basedir. '/update-key.php') - 13);

		fclose($fp);
	}

	// Make sure the key they are specifying matches the real one.
	if(empty($update_key) || empty($_REQUEST['update_key']) || strlen($update_key) < 10 || $update_key != $_REQUEST['update_key'])
	{
		// That's no good!
		echo json_encode(array(
											 'error_message' => 'Invalid update key supplied.',
										 ));

		exit;
	}

	$actions = array(
							 'checkcompat' => 'update_check_compat',
							 'download' => 'update_download',
							 'cancel' => 'update_cancel',
						 );

	if(empty($_GET['action']) || !isset($actions[$_GET['action']]))
	{
		echo json_encode(array(
											 'error_message' => 'No action to execute specified.',
										 ));

		exit;
	}

	// Return the right function so we can move along!
	return $actions[$_GET['action']];
}

/*
	Function: update_check_compat

	Checks the compatibility of all currently enabled plugins and themes to
	see if they will be compatible with the update version that is going to be
	installed.

	Parameters:
		none

	Returns:
		void - Nothing is returned by this function.
*/
function update_check_compat()
{
	// We will keep track of warnings here.
	$warnings = array(
								'plugins' => array(),
								'theme' => false,
							);

	// Fetch the version we're going to be updating too!
	$version = update_version_to(true);

	// Let's go ahead and get the list of plugins that are currently enabled.
	// The API class will have that information on hand.
	$check_updates = array();
	foreach(api()->return_plugins() as $plugin_guid)
	{
		// We will need to load up the plugins information.
		$plugin_info = plugin_load($plugin_guid, false);

		// Thankfully there isn't much checking we have to do ourselves!
		if(!empty($plugin_info['compatible_with']) && !is_compatible($plugin_info['compatible_with'], $version))
		{
			// Well, that's no good!
			$warnings['plugins'][basename($plugin_info['directory'])] = array(
																																		'name' => $plugin_info['name'],
																																		'current_version' => $plugin_info['version'],
																																		'update_version' => false,
																																	);
			$check_updates[] = $plugin_info['directory'];
		}
	}

	// Did we get any hits?
	if(count($check_updates) > 0)
	{
		// This file has the function we need to use.
		require_once(coredir. '/admin/admin_plugins_manage.php');

		$updates = admin_plugins_check_updates($check_updates);;

		// Did we find any updates?
		if($updates !== false && count($updates) > 0)
		{
			// We may need to turn this into an array if it isn't already.
			if(!is_array($updates))
			{
				$dirname = basename(array_pop($check_updates));
				$tmp = $updates;
				$updates = array(
										 $dirname => $tmp,
									 );
			}

			// We will want to mark that these plugins have an update available
			// within the control panel.
			$plugin_updates = settings()->get('plugin_updates', 'array', array());

			// Now we can add to the list of warnings.
			foreach($updates as $dirname => $update_version)
			{
				$warnings['plugins'][$dirname]['update_version'] = $update_version;
				$plugin_updates[$dirname] = $update_version;
			}

			settings()->set('plugin_updates', $plugin_updates);
		}
	}

	require_once(coredir. '/theme.php');

	// Now it is time to check the current theme...
	$theme_info = theme_load(themedir. '/'. settings()->get('theme', 'string'));

	// Make sure we got something, and that the current theme is not default,
	// as even if it isn't compatible with the update version, it will be
	// updated to be supported if needed.
	if($theme_info !== null && settings()->get('theme', 'string') !== 'default')
	{
		// Let's see if it is going to cause any issues.
		if(!empty($theme_info['compatible_with']) && !is_compatible($theme_info['compatible_with'], $version))
		{
			// We will definitely need to warn about this theme...
			$warnings['theme'] = array(
														 'name' => $theme_info['name'],
														 'current_version' => !empty($theme_info['version']) ? $theme_info['version'] : false,
														 'update_version' => false,
													 );

			// But we will only check for updates if this theme has specified a
			// current version, otherwise there is no way for us to know if there
			// is an update available (themes aren't required to specify versions
			// like plugins are).
			if(!empty($warnings['theme']['current_version']))
			{
				require_once(coredir. '/admin/admin_themes.php');

				// This function will do all the work for us!
				$update_version = admin_themes_check_updates($theme_info['directory']);

				// Let's see, did we get a new version?
				if($update_version !== false)
				{
					$warnings['theme']['update_version'] = $update_version;

					// There is an update available, so mark it as such.
					$theme_updates = settings()->get('theme_updates', 'array', array());
					$theme_updates[dirname($theme_info['directory'])] = $update_version;
					settings()->set('theme_updates', $theme_updates);
				}
			}
		}
	}

	// Alrighty then... everything has been checked, and now we can output the
	// results. Well, almost. Let's remove the index names from the plugins
	// array first.
	$tmp = $warnings['plugins'];
	$warnings['plugins'] = array();
	foreach($tmp as $plugin)
	{
		$warnings['plugins'][] = $plugin;
	}

	echo json_encode($warnings);
	exit;
}

/*
	Function: update_version_to

	Returns the version that the system is in the process of updating to.

	Parameters:
		$bypass_file - Whether to save the result to a file.

	Returns:
		string - Returns a string containing the version the system is going to
						 update to.
*/
function update_version_to($bypass_file = false)
{
	static $update_version = null;

	// Do we need to fetch which version we're updating to?
	if(!is_file(basedir. '/update-to.php') || filemtime(basedir. '/update-to.php') + 900 < (time() - date('Z')))
	{
		require_once(coredir. '/admin/admin_update.php');

		$update_version = admin_latest_version();

		// We may not want to save it to a file (because creating the
		// update-to.php file will throw the site into maintenance mode).
		if($bypass_file === false)
		{
			// We will want to store it within a file.
			$fp = fopen(basedir. '/update-to.php', 'w');

			fwrite($fp, '<?php die; ?>'. $update_version);

			fclose($fp);
		}

		// There ya go!
		return $update_version;
	}
	elseif($update_version === null)
	{
		// No need to check again, we can get it from the file.
		$fp = fopen(basedir. '/update-to.php', 'r');

		// We will just move passed the die statement.
		fseek($fp, 13);

		$update_version = fread($fp, filesize(basedir. '/update-to.php') - 13);

		fclose($fp);

		// Now we're good to go!
		return $update_version;
	}
	else
	{
		// We don't need to fetch it or load it -- we already did.
		return $update_version;
	}
}

/*
	Function: update_download

	Downloads the update package to install.

	Parameters:
		none

	Returns:
		void - Nothing is returned by this function.
*/
function update_download()
{
	// Which version are we downloading?
	$version = update_version_to();

	// Let's construct the name of the file that we will download from the
	// SnowCMS update service. We would like to download a gzipped tarball,
	// but if the server doesn't support gzip, then we will have to do
	// without.
	$filename = $version. '.tar'. (function_exists('gzdeflate') ? '.gz' : '');

	// Not much more to do than to download it.
	$http = api()->load_class('HTTP');

	$response = $http->request(api()->apply_filters('admin_update_url', 'http://download.snowcms.com/updates/'. $filename), array(), 0, basedir. '/'. $filename);

	// Not much to say, either.
	echo json_encode($response);
	exit;
}

/*
	Function: update_cancel

	Cancels the current update process by removing any files created during
	the current process.

	Parameters:
		none

	Returns:
		void - Nothing is returned by this function.
*/
function update_cancel()
{
	// We definitely need to delete the update-key.php and update-to.php
	// files. But first we should see if there is any downloaded file that
	// needs to be removed.
	$version = update_version_to();

	if(file_exists(basedir. '/'. $version. '.tar'. (function_exists('gzdeflate') ? '.gz' : '')))
	{
		// We'll go ahead and remove that.
		@unlink(basedir. '/'. $version. '.tar'. (function_exists('gzdeflate') ? '.gz' : ''));
	}

	// Now for those other two.
	@unlink(basedir. '/update-key.php');
	@unlink(basedir. '/update-to.php');

	echo json_encode(true);
	exit;
}
?>