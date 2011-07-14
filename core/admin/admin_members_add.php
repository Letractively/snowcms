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

if(!defined('INSNOW'))
{
	die('Nice try...');
}

// Title: Control Panel - Members - Add

if(!function_exists('admin_members_add'))
{
	/*
		Function: admin_members_add

		An interface for the manual addition of members.

		Parameters:
			none

		Returns:
			void - Nothing is returned by this function.

		Note:
			This function is overloadable.
	*/
	function admin_members_add()
	{
		api()->run_hooks('admin_members_add');

		// Trying to access something you can't? Not if I can help it!
		if(!member()->can('add_new_member'))
		{
			admin_access_denied();
		}

		admin_members_add_generate_form();
		$form = api()->load_class('Form');

		// Adding the member?
		if(!empty($_POST['admin_members_add_form']))
		{
			if(isset($_GET['ajax']))
			{
				// Through AJAX? How fancy!
				echo $form->json_process('admin_members_add_form');
				exit;
			}
			else
			{
				// How boring :P
				$form->process('admin_members_add_form');
			}
		}

		admin_current_area('members_add');

		theme()->set_title(l('Add a new member'));

		api()->context['form'] = $form;

		theme()->render('admin_members_add');
	}
}

if(!function_exists('admin_members_add_generate_form'))
{
	/*
		Function: admin_members_add_generate_form

		Generates the form for adding a new member.

		Parameters:
			none

		Returns:
			void - Nothing is returned by this function.

		Note:
			This function is overloadable.
	*/
	function admin_members_add_generate_form()
	{
		$form = api()->load_class('Form');

		$form->add('admin_members_add_form', array(
																					'action' => baseurl. '/index.php?action=admin&sa=members_add',
																					'ajax_submit' => true,
																					'callback' => 'admin_members_add_handle',
																					'submit' => 'Add member',
																				 ));

		// Their username.
		$form->add_field('admin_members_add_form', 'member_name', array(
																																'type' => 'string',
																																'label' => l('New username:'),
																																'subtext' => l('The username of the new member.'),
																																'length' => array(
																																							'min' => 1,
																																							'max' => 80,
																																						),
																																'function' => create_function('$value, $form_name, &$error', '
																																	$members = api()->load_class(\'Members\');

																																	if($members->name_allowed($value))
																																		return true;
																																	else
																																	{
																																		$error = l(\'The requested username is already in use or not allowed.\');
																																		return false;
																																	}'),
																																'value' => !empty($_REQUEST['member_name']) ? $_REQUEST['member_name'] : '',
																															));

		// Password, twice though!
		$form->add_field('admin_members_add_form', 'member_pass', array(
																																'type' => 'password',
																																'label' => l('Password:'),
																																'function' => create_function('$value, $form_name, &$error', '
																																	 // Passwords don\'t match? That isn\'t right.
																																	 if(empty($_POST[\'pass_verification\']) || $_POST[\'pass_verification\'] != $value)
																																	 {
																																		 $error = l(\'Your passwords do not match.\');
																																		 return false;
																																	 }

																																	 $members = api()->load_class(\'Members\');

																																	 if($members->password_allowed($_POST[\'member_name\'], $value))
																																	 {
																																		 return true;
																																	 }
																																	 else
																																	 {
																																		 $security = settings()->get(\'password_security\', \'int\');

																																		 if($security == 1)
																																		 {
																																			 $error = l(\'Your password must be at least 3 characters long.\');
																																		 }
																																		 elseif($security == 2)
																																		 {
																																			 $error = l(\'Your password must be at least 4 characters long and cannot contain your username.\');
																																		 }
																																		 else
																																		 {
																																			 $error = l(\'Your password must be at least 5 characters long, cannot contain your username and contain at least 1 number.\');
																																		 }

																																		 return false;
																																	 }')
																															));

		// As said, twice ;)
		$form->add_field('admin_members_add_form', 'pass_verification', array(
																																			'type' => 'password',
																																			'label' => l('Verify password:'),
																																			'subtext' => l('Just to be sure!'),
																																		));

		// Now for an email, please!
		$form->add_field('admin_members_add_form', 'member_email', array(
																														'type' => 'string',
																														'label' => l('Email:'),
																														'subtext' => l('Please enter a valid email address.'),
																														'length' => array(
																																					'max' => 255,
																																				),
																														'function' => create_function('$value, $form_name, &$error', '
																															$members = api()->load_class(\'Members\');

																															if($members->email_allowed($value))
																															{
																																return true;
																															}
																															else
																															{
																																$error = l(\'The supplied email address is already in use or not allowed.\');
																																return false;
																															}'),
																														'value' => !empty($_REQUEST['member_email']) ? $_REQUEST['member_email'] : '',
																													));

		// Should they be an administrator, or just a regular member?
		$form->add_field('admin_members_add_form', 'is_administrator', array(
																																		'type' => 'checkbox',
																																		'label' => l('Administrator?'),
																																		'subtext' => l('If checked, the new member will be an administrator, otherwise they will be just a member.'),
																																		'value' => !empty($_POST['is_administrator']),
																																	 ));

		// What other member group(s) should they be in..?
		$groups = api()->return_group();

		// We want to remove the administrator and member group ;)
		unset($groups['administrator'], $groups['member']);

		$form->add_field('admin_members_add_form', 'member_groups', array(
																																 'type' => 'select-multi',
																																 'label' => l('Member groups:'),
																																 'subtext' => l('Select any other member groups you want to be applied to the user. If you checked the administrator box, this will be ignored.'),
																																 'options' => $groups,
																																 'rows' => 4,
																																 'value' => !empty($_POST['member_groups']) ? $_POST['member_groups'] : '',
																															 ));
	}
}

if(!function_exists('admin_members_add_handle'))
{
	/*
		Function: admin_members_add_handle

		Handles the adding of the member called by the Form class.

		Parameters:
			array $data
			array &$errors

		Returns:
			bool - Returns true if the member was successfully created,
						 false if not.

		Note:
			This function is overloadable.
	*/
	function admin_members_add_handle($data, &$errors = array())
	{
		// Alright, all that's left to do is create the member!
		$members = api()->load_class('Members');

		// Did you want them to be an administrator?
		if(!empty($data['is_administrator']))
		{
			$groups = array('administrator');
		}
		else
		{
			$groups = array('member');

			if(!empty($data['member_groups']))
			{
				foreach(explode(',', $data['member_groups']) as $group_id)
				{
					$groups[] = $group_id;
				}
			}
		}

		// So create it! ;)
		$member_id = $members->add($data['member_name'], $data['member_pass'], $data['member_email'], array(
																																																		'member_activated' => 1,
																																																		'member_groups' => $groups,
																																																	));

		api()->add_filter('admin_members_add_form_message', create_function('$value', '
																												 return l(\'The member was successfully added!\');'));

		return true;
	}
}
?>