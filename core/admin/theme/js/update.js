var flashIntervalId = null;
var flashCurrentValue = 0;
var flashCurrentMultiplier = 1;

function percentage(which, value)
{
	if(which == 'overall' || which == 'step')
	{
		if(value && value >= 0 && value <= 100)
		{
			s.id('update-' + which).style.width = value.toString() + '%';

			return true;
		}
		else
		{
			return parseInt(s.id('update-' + which).style.width);
		}
	}
	else
	{
		return false;
	}
}

function showStepProgress(enabled)
{
	s.id('update-step-container').style.visibility = enabled ? 'visible' : 'hidden';
}

function set_message(which, value)
{
	if(which == 'overall' || which == 'step')
	{
		s.id('update-' + which + '-message').innerHTML = value;

		return true;
	}
	else
	{
		return false;
	}
}

function empty_update_box()
{
	while(s.id('update-box').childNodes.length > 0)
	{
		s.id('update-box').removeChild(s.id('update-box').firstChild);
	}
}

function flash_problem(enable)
{
	if(!enable)
	{
		// Let's make sure it is enabled in the first place.
		if(flashIntervalId != null)
		{
			clearInterval(flashIntervalId);
			flashIntervalId = null;
			flashCurrentValue = 0;
			s.id('overall-underlay').style.visibility = 'hidden';
		}
	}
	else if(enable && flashIntervalId == null)
	{
		s.id('overall-underlay').style.visibility = 'visible';
		s.id('overall-underlay').style.opacity = flashCurrentValue;

		flashIntervalId = setInterval(function()
			{
				flashCurrentValue += 0.05 * flashCurrentMultiplier;

				if(flashCurrentValue >= 1)
				{
					flashCurrentMultiplier = -1;
				}
				else if(flashCurrentValue <= 0.5)
				{
					flashCurrentMultiplier = 1;
				}

				s.id('overall-underlay').style.opacity = flashCurrentValue;

			}, 50);
	}
}

/*
	Function: check_compat_start
*/
function check_compat_start()
{
	// We may get called upon again, so we will reset everything.
	set_message('overall', l['compatibility']);
	percentage('overall', 0);
	showStepProgress(false);
	empty_update_box();
	flash_problem(false);

	s.ajaxCallback(baseurl + '/update.php?action=checkcompat', compat_checked, 'update_key=' + s.encode(update_key));
}

/*
	Function: compat_checked
*/
function compat_checked(data)
{
	var warnings = s.json(data);

	// Do we need to notify the user of any possible incompatibilities?
	if(warnings['plugins'].length > 0 || warnings['theme'] != false)
	{
		// We have a problem!
		flash_problem(true);

		// We will go ahead and tell them what we found.
		var h3 = document.createElement('h3');
		h3.innerHTML = l['compat_error_header'];

		s.id('update-box').appendChild(h3);
		s.id('update-box').style.display = 'block';

		var p = document.createElement('p');
		p.innerHTML = l['compat_error_message'];

		s.id('update-box').appendChild(p);

		if(warnings['plugins'].length > 0)
		{
			var h4 = document.createElement('h4');
			h4.innerHTML = l['compat_plugin_header'];

			s.id('update-box').appendChild(h4);

			// Let's generate a list of the plugins.
			var pluginList = document.createElement('ul');

			for(var index = 0; index < warnings['plugins'].length; index++)
			{
				var item = document.createElement('li');
				item.innerHTML = warnings['plugins'][index]['name'] + ' v' + warnings['plugins'][index]['current_version'] + ' (' + (warnings['plugins'][index]['update_version'] != false ? 'v' + warnings['plugins'][index]['update_version'] + ' ' + l['available'] : l['no_update_available']) + ')';

				pluginList.appendChild(item);
			}

			s.id('update-box').appendChild(pluginList);
		}

		if(warnings['theme'] != false)
		{
			var h4 = document.createElement('h4');
			h4.innerHTML = l['compat_theme_header'];

			s.id('update-box').appendChild(h4);

			// Let's generate a list of the plugins.
			var themeList = document.createElement('ul');
			var item = document.createElement('li');
			item.innerHTML = warnings['theme']['name'] + ' v' + warnings['theme']['current_version'] + ' (' + (warnings['theme']['update_version'] != false ? 'v' + warnings['theme']['update_version'] + ' ' + l['available'] : l['no_update_available']) + ')';
			themeList.appendChild(item);

			s.id('update-box').appendChild(themeList);
		}

		// We will need two buttons, one will be to ignore these warnings and
		// continue on anyways, the other will be to check again.
		var checkAgain = document.createElement('button');
		checkAgain.innerHTML = l['check_compat_again'];
		checkAgain.onclick = function()
			{
				flash_problem(false);
				check_compat_start();
			};

		var ignoreWarnings = document.createElement('button');
		ignoreWarnings.innerHTML = l['compat_ignore_warnings'];
		ignoreWarnings.onclick = function()
			{
				if(confirm(l['compat_ignore_confirm']))
				{
					download_update_start();
				}
			};

		p = document.createElement('p');
		p.appendChild(checkAgain);
		p.appendChild(ignoreWarnings);
		p.style.textAlign = 'right';

		s.id('update-box').appendChild(p);
	}
	else
	{
		download_update_start();
	}
}

/*
	Function: download_update_start
*/
function download_update_start()
{
	percentage('overall', 17);
	showStepProgress(false);
	empty_update_box();
	flash_problem(false);
	set_message('overall', l['downloading']);

	// Start the update process by checking
	s.ajaxCallback(baseurl + '/update.php?action=download', update_downloaded, 'update_key=' + s.encode(update_key));
}

/*
	Function: update_downloaded
*/
function update_downloaded(data)
{
	var downloaded = s.json(data);

	// Did we get the update downloaded?
	if(downloaded)
	{
		// Yup, so we can move on to validating the update.
		verify_download_start();
	}
	else
	{
		// Uh oh! Something went wrong, which isn't good (of course!).
		empty_update_box();
		flash_problem(true);

		var h3 = document.createElement('h3');
		h3.innerHTML = l['download_error_header'];

		s.id('update-box').appendChild(h3);
		s.id('update-box').style.display = 'block';

		var p = document.createElement('p');
		p.innerHTML = l['download_error_message'];

		s.id('update-box').appendChild(p);

		var cancelUpdate = document.createElement('button');
		cancelUpdate.innerHTML = l['cancel_update'];
		cancelUpdate.onclick = function()
			{
				// We will need to send a message to the update system to cancel
				// everything.
				set_message(l['cancelling']);
				percentage('overall', 0);
				flash_problem(false);

				s.ajaxCallback(baseurl + '/update.php?action=cancel', update_cancel_finished, 'update_key=' + s.encode(update_key));
			};

		var downloadAgain = document.createElement('button');
		downloadAgain.innerHTML = l['download_again'];
		downloadAgain.onclick = function()
			{
				// Just restart the process.
				download_update_start();
			};

		p = document.createElement('p');
		p.appendChild(cancelUpdate);
		p.appendChild(downloadAgain);
		p.style.textAlign = 'right';

		s.id('update-box').appendChild(p);
	}
}

/*
	Function: update_cancel_finished
*/
function update_cancel_finished(data)
{
	// We're good to go... back to the update page.
	location.href = baseurl + '/index.php?action=admin&sa=update';
}

/*
	Function: verify_download_start
*/
function verify_download_start()
{
	percentage('overall', 33);
	showStepProgress(false);
	empty_update_box();
	flash_problem(false);
	set_message('overall', l['verifying']);

	s.ajaxCallback(baseurl + '/update.php?action=verify', download_verified, 'update_key=' + s.encode(update_key));
}

/*
	Function: download_verified
*/
function download_verified(data)
{
	var response = s.json(data);

	// If the update package was verified, then we can go ahead and move on to
	// the next step.
	if(response['verified'])
	{
		extract_update_start();
	}
	else
	{
		empty_update_box();
		flash_problem(true);

		var h3 = document.createElement('h3');
		h3.innerHTML = l['verify_error_header'];

		s.id('update-box').appendChild(h3);
		s.id('update-box').style.display = 'block';

		var p = document.createElement('p');
		p.innerHTML = l['verify_error_' + response['error_code']];

		s.id('update-box').appendChild(p);

		// Just give up, perhaps?
		var cancelUpdate = document.createElement('button');
		cancelUpdate.innerHTML = l['cancel_update'];
		cancelUpdate.onclick = function()
			{
				// We will need to send a message to the update system to cancel
				// everything.
				set_message(l['cancelling']);
				percentage('overall', 0);
				flash_problem(false);

				s.ajaxCallback(baseurl + '/update.php?action=cancel', update_cancel_finished, 'update_key=' + s.encode(update_key));
			};

		// If we got an error code of 1, that means the checksum couldn't be
		// downloaded, so you can try to do it over again.
		if(response['error_code'] == 1)
		{
			var downloadAgain = document.createElement('button');
			downloadAgain.innerHTML = l['verify_again'];
			downloadAgain.onclick = function()
				{
					// Just restart the process.
					verify_download_start();
				};
		}
		// Otherwise there is a problem with the file we downloaded earlier.
		else
		{
			var downloadAgain = document.createElement('button');
			downloadAgain.innerHTML = l['download_again'];
			downloadAgain.onclick = function()
				{
					// Just restart the process.
					download_update_start();
				};
		}

		p = document.createElement('p');
		p.appendChild(cancelUpdate);
		p.appendChild(downloadAgain);
		p.style.textAlign = 'right';

		s.id('update-box').appendChild(p);
	}
}

/*
	Function: extract_update_start
*/
function extract_update_start()
{
	percentage('overall', 50);
	showStepProgress(false);
	empty_update_box();
	flash_problem(false);
	set_message('overall', l['extracting']);

	s.ajaxCallback(baseurl + '/update.php?action=extract', update_extracted, 'update_key=' + s.encode(update_key));
}

/*
	Function: update_extracted
*/
function update_extracted(data)
{
	var response = s.json(data);

	if(response['extracted'])
	{
		copy_files_start();
	}
	else
	{
		empty_update_box();
		flash_problem(true);

		var h3 = document.createElement('h3');
		h3.innerHTML = l['extract_error_header'];

		s.id('update-box').appendChild(h3);
		s.id('update-box').style.display = 'block';

		var p = document.createElement('p');
		p.innerHTML = l['extract_error_' + response['error_code']];

		s.id('update-box').appendChild(p);

		// Just give up, perhaps?
		var cancelUpdate = document.createElement('button');
		cancelUpdate.innerHTML = l['cancel_update'];
		cancelUpdate.onclick = function()
			{
				// We will need to send a message to the update system to cancel
				// everything.
				set_message(l['cancelling']);
				percentage('overall', 0);
				flash_problem(false);

				s.ajaxCallback(baseurl + '/update.php?action=cancel', update_cancel_finished, 'update_key=' + s.encode(update_key));
			};

		var extractAgain = document.createElement('button');
		extractAgain.innerHTML = l['extract_again'];
		extractAgain.onclick = function()
			{
				// Just restart the process.
				extract_update_start();
			};

		p = document.createElement('p');
		p.appendChild(cancelUpdate);
		p.appendChild(extractAgain);
		p.style.textAlign = 'right';

		s.id('update-box').appendChild(p);
	}
}

/*
	Function: copy_files_start
*/
function copy_files_start()
{
	percentage('overall', 67);
	showStepProgress(true);
	empty_update_box();
	flash_problem(false);
	set_message('overall', l['copying']);

	s.ajaxCallback(baseurl + '/update.php?action=copy', copy_file_done, 'update_key=' + s.encode(update_key) + '&start=0');
}

/*
	Function: copy_file_done
*/
function copy_file_done(data)
{
	var response = s.json(data);

	if(response['finished'])
	{
		percentage('step', 100);
		set_message('step', '100%');

		apply_update_start();
	}
	else
	{
		percentage('overall', 67 + Math.ceil(16 * (response['percent_finished'] / 100)));
		percentage('step', response['percent_finished']);
		set_message('step', response['percent_finished'].toString() + '%');

		s.ajaxCallback(baseurl + '/update.php?action=copy&t=' + (new Date).getTime(), copy_file_done, 'update_key=' + s.encode(update_key) + '&start=' + response['offset']);
	}
}

/*
	Function: apply_update_start
*/
function apply_update_start()
{
	percentage('overall', 83);
	showStepProgress(false);
	empty_update_box();
	flash_problem(false);
	set_message('overall', l['applying']);

	s.ajaxCallback(baseurl + '/update.php?action=apply', applying_update, 'update_key=' + s.encode(update_key));
}

/*
	Function: applying_update
*/
function applying_update(data)
{
	var response = s.json(data);

	if(response['finished'])
	{
		update_completed();
	}
	else
	{
		if(response['percent_finished'] != false)
		{
			showStepProgress(true);
			percentage('overall', 83 + Math.ceil(17 * (response['percent_finished'] / 100)));
			percentage('step', response['percent_finished']);
			set_message('step', response['percent_finished'].toString() + '%');
		}

		s.ajaxCallback(baseurl + '/update.php?action=apply', applying_update, 'update_key=' + s.encode(update_key) + '&state=' + s.encode(response['state']));
	}
}

/*
	Function: update_completed
*/
function update_completed()
{
	percentage('overall', 100);
	showStepProgress(false);
	empty_update_box();
	flash_problem(false);
	set_message('overall', l['completed']);

	var h3 = document.createElement('h3');
	h3.innerHTML = l['completed_header'];

	s.id('update-box').appendChild(h3);
	s.id('update-box').style.display = 'block';

	var p = document.createElement('p');
	p.innerHTML = l['completed_message'];

	s.id('update-box').appendChild(p);

	// Time to go back!
	var back = document.createElement('button');
	back.innerHTML = l['completed_button'];
	back.onclick = function()
		{
			location.href = baseurl + '/index.php?action=admin&sa=update';
		};

	p = document.createElement('p');
	p.appendChild(back);
	p.style.textAlign = 'right';

	s.id('update-box').appendChild(p);
}

s.onload(function()
	{
		check_compat_start();
	});