<?php
// SnowCMS Main.language.php File for English
// Version 0.2

// Login.template.php stuff
$l['login_title'] = 'Login';
$l['login_header'] = 'Login';
$l['login_details'] = 'Here you can login to your '.$settings['site_name'].' account. If you do not have an account you can register one <a href="'.$cmsurl.'index.php?action=register">here</a>';
$l['login_user'] = 'Username:';
$l['login_pass'] = 'Password:';
$l['login_button'] = 'Login!';
$l['login_error'] = 'Wrong Username or Password';
$l['login_length'] = 'Session Length:';
$l['login_hour'] = 'An Hour';
$l['login_day'] = 'A Day';
$l['login_week'] = 'A Week';
$l['login_month'] = 'A Month';
$l['login_forever'] = 'Forever';
// Sub stuff for logging out in this template...
$l['logout_error_title'] = 'Logout Error';
$l['logout_error_header'] = 'Error';
$l['logout_error_desc'] = 'Session Verification Failed! Try Clicking on the logout link again.';

// Register.template.php stuff
$l['register_title'] = 'Register';
$l['register_header'] = 'Register';
$l['register_details'] = 'You can register for an account on '.$settings['site_name'].' here';
$l['register_username'] = 'Username:';
$l['register_password'] = 'Password:';
$l['register_verify_password'] = 'Verify Password:';
$l['register_email'] = 'Email:';
$l['register_captcha'] = 'Enter the Text you see in the image';
$l['register_success'] = 'Thank you '.@clean($_REQUEST['username']).'! Your account has been created successfully and you may now <a href="'.$cmsurl.'index.php?action=login">login</a>';
$l['register_successbut1'] = 'Thanks for registering! The administrators require you to activate your account via email, please check your email you used on your account, and click the link to activate your account';
$l['register_successbut2'] = 'Thanks for registering! The administrators require themselves to activate accounts; you will recieve an email once your account is activated.';
$l['register_failed'] = 'Registration failed!';
// For Account Activation...
$l['activate_title'] = 'Activate your Account';
$l['activate_acode_no_match'] = 'Wrong Activation Code';
$l['activate_account_already_activated'] = 'That account is already activated';
$l['activate_no_such_user'] = 'That username doesn\'t exist';
$l['activate_header'] = 'Account Activation';
$l['activate_desc'] = 'Here you can activate your account by entering your username and activation code';
$l['activate_button'] = 'Activate Account';
$l['activate_account_activated'] = 'Your account has been successfully activated! You may now <a href="'. $cmsurl. 'index.php?action=login">login</a>.';

// Error Stuff for Register.template.php
$l['register_error_user_taken'] = 'That username is already in use.';
$l['register_error_username_to_short'] = 'The username must be 3 characters or longer.';
$l['register_error_passwords'] = 'Those passwords don\'t match!';
$l['register_error_password_to_short'] = 'Your password is too short!';
$l['register_error_invalid_email'] = 'That email address is invalid!';
$l['register_error_captcha'] = 'You failed the CAPTCHA test! Please try again.';
$l['register_error_unknown'] = 'An unknown error has disabled us from registering your account. Info: '.mysql_error();
$l['register_button'] = 'Register';

// Page.template.php
$l['page_error_title'] = $settings['site_name'].' - Error';
$l['page_error_header'] = 'Error';
$l['page_error_details'] = 'An error has occurred! The page you have requested does not exist!';

// Online.template.php
$l['online_title'] = 'Who\'s Online';
$l['online_title_unknown'] = 'Unknown';
$l['online_user_guest'] = 'Guest';
$l['online_header'] = 'Who\'s Online';
$l['online_desc'] = 'Here you can view who is online, and what page they are viewing.';
$l['online_user'] = 'User';
$l['online_ip'] = 'IP';
$l['online_currently_viewing'] = 'Currently Viewing';
$l['online_time'] = 'Last Active';

// Admin.template.php
$l['admin_title'] = 'Admin CP';
$l['admin_error_title'] = 'Error';
$l['admin_cant_get_news_1'] = 'We were unable to get the latest SnowCMS News from the <a href="http://www.snowcms.com">SnowCMS</a> site because your server configuration does not have <a href="http://php.net/curl">cURL</a> setup. Please check out the SnowCMS site for the latest news &amp; updates.';
$l['admin_cant_get_news_2'] = 'Unable to get the latest news from the <a href="http://www.snowcms.com">SnowCMS</a> site due to a server timeout. Refresh the page, or check out our site.';
$l['admin_current_version'] = 'Your SnowCMS Version:';
$l['admin_snowcms_current_version'] = 'Latest SnowCMS Version:';
$l['admin_version_unavailable'] = 'N/A';
$l['admin_error_header'] = 'Error';
$l['admin_error_reason'] = 'You don\'t have sufficient permission to access the Admin CP!';
$l['admin_menu_managepages'] = 'Manage Pages';
$l['admin_menu_managepages_desc'] = 'You can create, edit and delete pages in your database';
$l['admin_menu_basic-settings'] = 'Basic Settings';
$l['admin_menu_basic-settings_desc'] = 'Edit simple settings such as site name, slogan, time format, etc.';
$l['admin_menu_members'] = 'Manage Members';
$l['admin_menu_members_desc'] = 'Manage your members, such as changing their member group, anything in their profile';
$l['admin_menu_permissions'] = 'Group Permissions';
$l['admin_menu_permissions_desc'] = 'This is where you can choose what a member group can and cannot do on your site';

// Settings.template.php
$l['basicsettings_title'] = 'Basic Settings';
$l['basicsettings_header'] = 'Basic Settings';
$l['basicsettings_desc'] = 'Here are basic settings for your site, such as site name, slogan, time format, etc.';
$l['basicsettings_site_name'] = 'Site Name:';
$l['basicsettings_slogan'] = 'Slogan:';
$l['basicsettings_account_activation'] = 'Account activation:';
$l['basicsettings_value_no_activation'] = 'No activation';
$l['basicsettings_value_email_activation'] = 'Email activation';
$l['basicsettings_value_admin_activation'] = 'Admin activation';
$l['basicsettings_login_threshold'] = 'Login Threshold:';
$l['basicsettings_remember_time'] = 'Default Cookie Time:';
$l['basicsettings_timeformat'] = 'Time Format:';
$l['basicsettings_update'] = 'Update Settings';
$l['basicsettings_num_news_items'] = 'News items per page:';
$l['basicsettings_manage_members_per_page'] = 'Members per page:';

// ManagePages.template.php
$l['adminpage_make_title'] = 'Manage Pages';
$l['managepages_makepage'] = 'Create Page';
$l['managepages_pagetitle'] = 'Page Title:';
$l['managepages_header'] = 'Manage Pages';
$l['managepages_desc'] = 'You can manage and create pages here.';
$l['adminpage_make_success'] = 'The page %title% was successfully created!';
$l['adminpage_make_fail'] = 'Failed to create the page %title%';
$l['adminpages_title_td'] = 'Page Title';
$l['adminpages_pageowner'] = 'Page Owner';
$l['adminpages_datemade'] = 'Created on';
$l['adminpages_no_pages'] = 'Their are currently no pages in your database';
$l['managepages_edit_title'] = 'Editing page %title%';
$l['managepages_no_page_title'] = 'That page doesn\'t exist!';
$l['managepages_no_page_header'] = 'That page doesn\'t exist!';
$l['managepages_no_page_desc'] = 'The page you have requested to edit does not exist.';
$l['managepages_edit_header'] = 'Edit Page';
$l['managepages_edit_desc'] = 'You are currently editing a page; you can edit the content and the title of the page, and you use can use HTML in the content of the page.';
$l['managepages_editpage_title'] = 'Page Title:';
$l['managepages_editpage_content'] = 'Page Content:';
$l['managepages_editpage_button'] = 'Update Page';
$l['managepages_update_failed'] = 'Page update failed!';
$l['managepages_update_success'] = 'Page updated successfully!';
$l['managepages_editpage_show_info'] = "Show extra page info";

// Some Email stuff, for Email Activation, etc.
$l['mail_smtp_fail'] = 'Sending email failed! Error: %error%';
$l['mail_smtp_success'] = 'Email sent successfully!';
$l['mail_mail_fail'] = 'Sending email failed!';
$l['mail_mail_success'] = 'Email sent successfully!';
$l['email_register_subject'] = 'Activate your account at '.$settings['site_name'].'.';
$l['email_register_tpl'] = "Hello %username%!\r\n Someone has requested an account at {$settings['site_name']} If you didn't request an account at this site, ignore this email.\r\nIf you did request this, click on the link below to activate your account\r\n%alink%\r\n \r\nRegards,\r\nThe {$settings['site_name']} Team";

// List pages
$l['listpage_header'] = 'Page List';
$l['listepage_desc'] = 'This page shows all the pages that was writen in the CMS.';

// Forum.template.php
$l['forum_title'] = $settings['site_name'].' - Index';
$l['forum_error_title'] = $settings['site_name'].' - Error';
$l['forum_error_header'] = 'Error';
$l['forum_error_message'] = 'Sorry, but you aren\'t allowed to view the '.$settings['site_name'].' board, if you have not yet tried, maybe <a href="'.$cmsurl.'index.php?action=register">registering</a> and <a href="'.$cmsurl.'index.php?action=login">logging</a> will allow you to view the forum';
$l['forum_posts_in'] = 'posts in';
$l['forum_topics'] = 'topics';
$l['forum_board_new'] = 'New posts';
$l['forum_board_old'] = 'No news posts';
$l['forum_error_cantviewb_message'] = 'Sorry, you are not allowed to view or access this board';
$l['forum_error_noboard_message'] = 'The Board ID you have requested does not exist!';

// Links for Forum.template.php
$l['forum_link_home'] = 'Home';
$l['forum_link_forumindex'] = 'Forum Index';
$l['forum_link_search'] = 'Search';
$l['forum_link_admin'] = 'Admin';
$l['forum_link_profile'] = 'Profile';
$l['forum_link_members'] = 'Members';
$l['forum_link_pm'] = 'Personal Messages';
$l['forum_link_register'] = 'Register';
$l['forum_link_login'] = 'Login';

// Topic.template.php
$l['topic_pages'] = 'Pages:';
$l['topic_on'] = 'on';
$l['topic_firstpage'] = 'First Page';
$l['topic_lastpage'] = 'Last Page';
$l['topic_newtopic'] = 'New Topic';
$l['topic_reply'] = 'Post Reply';

// Profile.template.php
$l['profile_title'] = 'Profile';
$l['profile_error_title'] = 'Error';
$l['profile_error_header'] = 'Error';
$l['profile_error_desc'] = 'Sorry, but you are not <a href="'.$cmsurl.'index.php?action=login">logged in</a>, if you have not yet done so, why not <a href="'.$cmsurl.'index.php?action=register">register</a> and or <a href="'.$cmsurl.'index.php?action=login">login</a>';
$l['profile_profile_of'] = 'Profile of %user%';

// Permissions.template.php
$l['permissions_title'] = 'Manage Permissions';
$l['permissions_header'] = 'Group Permissions';
$l['permissions_desc'] = 'Here is where you can manage member group specific permissions, in other words, you can choose what people in a member group can and cannot do.</p>';
$l['permissions_modify'] = 'Modify';
$l['permissions_membergroup'] = 'Member Group';
$l['permissions_numusers'] = 'Users';
$l['permissions_permissions'] = 'Permissions';
$l['permissions_editperms_title'] = 'Edit Permissions';
$l['permissions_nogroup_desc'] = 'The member group you have requested does not exist!';
$l['permissions_edit_header'] = 'Edit Permissions';
$l['permissions_edit_desc'] = 'Here you can edit the permissions for this specific group, which allows you to choose what they can and cannot do.';
$l['permissions_edit_save'] = 'Save';
$l['permissions_perm_admin'] = 'Allow to Administrate the site';
$l['permissions_perm_manage_basic-settings'] = 'Allow them to edit basic settings';
$l['permissions_perm_manage_members'] = 'Allow them to manage members';
$l['permissions_perm_manage_menus'] = 'Allow them to manage menus';
$l['permissions_perm_manage_news'] = 'Allow them to manage news';
$l['permissions_perm_manage_pages'] = 'Allow them to manage pages';
$l['permissions_perm_manage_permissions'] = 'Allow them to manage permissions';
$l['permissions_perm_manage_forum_perms'] = 'Allow them to manage forum permissions';
$l['permissions_perm_view_forum'] = 'Let them view the forum';
$l['permissions_perm_view_online'] = 'Let them view who is online';
$l['permissions_perm_view_profile'] = 'Let them view others profiles';
$l['permissions_perm_manage_mail_settings'] = 'Allow them to edit mail settings';
$l['permissions_perm_manage_groups'] = 'Allow them to manage member groups';
$l['permissions_perm_manage_forum'] = 'Allow them to Manage the Forum';

// ManageMembers.template.php
$l['managemembers_title'] = 'Manage Members';
$l['managemembers_showing'] = 'Showing members %from% to %to%.';
$l['managemembers_showing_one'] = 'Showing member %number%.';
$l['managemembers_showing_none'] = 'There are no members on this page.';
$l['managemembers_next_page'] = 'Next Page';
$l['managemembers_previous_page'] = 'Previous Page';
$l['managemembers_id'] = 'ID';
$l['managemembers_username'] = 'Username';
$l['managemembers_group'] = 'Group';
$l['managemembers_join_date'] = 'Join Date';
$l['managemembers_moderate_button'] = 'Moderate';
$l['managemembers_moderate_title'] = 'Moderate %name%';
$l['managemembers_moderate_id'] = 'ID';
$l['managemembers_moderate_username'] = 'Username';
$l['managemembers_moderate_display_name'] = 'Display Name';
$l['managemembers_moderate_email'] = 'Email Address';
$l['managemembers_moderate_group'] = 'Member Group';
$l['managemembers_moderate_posts'] = 'Total Posts';
$l['managemembers_moderate_suspended_until'] = 'Suspended Until';
$l['managemembers_moderate_registration_date'] = 'Registration Date';
$l['managemembers_moderate_last_login'] = 'Last Login Date';
$l['managemembers_moderate_registration_ip'] = 'Registration IP';
$l['managemembers_moderate_last_ip'] = 'Last Used IP';
$l['managemembers_moderate_signature'] = 'Signature';
$l['managemembers_moderate_change'] = 'Change Details';
$l['managemembers_moderate_profile'] = 'View Profile';
$l['managemembers_moderate_suspend'] = '%button% for %input% hour(s)';
$l['managemembers_moderate_suspend_button'] = 'Suspend';
$l['managemembers_moderate_unsuspend_button'] = 'Remove Suspension';
$l['managemembers_moderate_renew_suspension'] = '%renew% for %input% hour(s) or %remove%';
$l['managemembers_moderate_renew_suspension_button'] = 'Renew Suspension';
$l['managemembers_moderate_ban'] = 'Ban Permanently';
$l['managemembers_moderate_unban'] = 'Remove Ban';

// ManageForum.template.php
$l['manageforum_title'] = 'Manage Forum';
$l['manageforum_header'] = 'Manage Forum';
$l['manageforum_desc'] = 'Click on one of the links below to manage your <a href="', $cmsurl, 'forum.php">forum</a>';
// Links
$l['mf_link_cats'] = 'Manage Categories';
$l['mf_link_cats_desc'] = 'Create, Edit, and Delete forum categories';
$l['mf_link_boards'] = 'Manage Boards';
$l['mf_link_boards_desc'] = 'You can create, edit and delete your boards in your forum here';

// Post.template.php
$l['forum_startnew'] = 'Post New Topic';

// Error.template.php
$l['themeerror_title'] = 'Theme Error!';
$l['themeerror_header'] = 'Theme Load Error';
$l['themeerror_msg'] = 'An error occurred while trying to load the template function %func%(); in the template %file%';
?>