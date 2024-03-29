##
# Authentication tokens are stored here, and used in place of storing a
# users password hash in their log in cookie.
##
CREATE TABLE `{db->prefix}auth_tokens`
(
	`member_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`token_id` VARCHAR(255) NOT NULL,
	`token_assigned` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`token_expires` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`token_data` TEXT NOT NULL,
	PRIMARY KEY (`member_id`, `token_id`),
	UNIQUE KEY (`token_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

##
# Holds all the PHP errors which have occurred!
##
CREATE TABLE `{db->prefix}error_log`
(
	`error_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`error_time` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`member_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`member_name` VARCHAR(255) NOT NULL,
	`member_ip` VARCHAR(150) NOT NULL,
	`error_type` VARCHAR(40) NOT NULL,
	`error_message` TEXT NOT NULL,
	`error_file` VARCHAR(255) NOT NULL,
	`error_line` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`error_url` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`error_id`),
	KEY (`error_time`),
	KEY (`member_name`),
	KEY (`member_ip`),
	KEY (`error_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

##
# Holds members extra information, this is what a plugin should use to store extra member stuffs!
##
CREATE TABLE `{db->prefix}member_data`
(
	`member_id` INT(11) UNSIGNED NOT NULL,
	`variable` VARCHAR(255) NOT NULL,
	`value` TEXT NOT NULL,
	PRIMARY KEY (`member_id`, `variable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

##
# The members table, holding, you guessed it! MEMBERS!
#-
CREATE TABLE `{db->prefix}members`
(
	`member_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`member_name` VARCHAR(80) NOT NULL,
	`member_pass` VARCHAR(40) NOT NULL,
	`display_name` VARCHAR(255) NOT NULL,
	`member_email` VARCHAR(100) NOT NULL,
	`member_groups` VARCHAR(255) NOT NULL DEFAULT 'member',
	`member_last_active` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`member_last_login` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`member_registered` INT(10) UNSIGNED NOT NULL,
	`member_ip` VARCHAR(150) NOT NULL DEFAULT '127.0.0.1',
	`member_activated` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	`member_acode` VARCHAR(40) NULL,
	PRIMARY KEY (`member_id`),
	KEY (`member_name`),
	KEY (`display_name`),
	KEY (`member_activated`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

##
# Used via the Messages API
##
CREATE TABLE `{db->prefix}messages`
(
	`area_name` VARCHAR(255) NOT NULL,
	`area_id` INT(11) UNSIGNED NOT NULL,
	`message_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`member_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`member_name` VARCHAR(255) NOT NULL,
	`member_email` VARCHAR(255) NOT NULL,
	`member_ip` VARCHAR(150) NOT NULL,
	`modified_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`modified_name` VARCHAR(255) NOT NULL DEFAULT '',
	`modified_email` VARCHAR(255) NOT NULL DEFAULT '',
	`modified_ip` VARCHAR(150) NOT NULL DEFAULT '',
	`subject` VARCHAR(255) NOT NULL DEFAULT '',
	`poster_time` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`modified_time` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`message` TEXT NOT NULL,
	`message_type` VARCHAR(16) NOT NULL DEFAULT '',
	`message_status` VARCHAR(40) NOT NULL DEFAULT 'unapproved',
	`extra` TEXT NOT NULL,
	PRIMARY KEY (`area_name`, `area_id`, `message_id`),
	KEY (`poster_time`),
	KEY (`modified_time`),
	KEY (`message_status`),
	KEY (`extra`(255))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

##
# All permissions here! ;)
##
CREATE TABLE `{db->prefix}permissions`
(
	`group_id` VARCHAR(128) NOT NULL,
	`permission` VARCHAR(128) NOT NULL,
	`status` TINYINT(1) NOT NULL DEFAULT '1',
	PRIMARY KEY (`group_id`, `permission`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `{db->prefix}permissions` (`group_id`, `permission`, `status`) VALUES('member', 'manage_system_settings', 0),('member', 'manage_themes', 0),('member', 'update_system', 0),('member', 'view_error_log', 0);
INSERT INTO `{db->prefix}permissions` (`group_id`, `permission`, `status`) VALUES('member', 'add_new_member', 0),('member', 'manage_members', 0),('member', 'search_members', 0),('member', 'manage_member_settings', 0);
INSERT INTO `{db->prefix}permissions` (`group_id`, `permission`, `status`) VALUES('member', 'manage_permissions', 0),('member', 'add_plugins', 0),('member', 'manage_plugins', 0),('member', 'manage_plugin_settings', 0);
INSERT INTO `{db->prefix}permissions` (`group_id`, `permission`, `status`) VALUES('member', 'view_other_profiles', 0),('member', 'edit_other_profiles', 0),('guest', 'manage_system_settings', -1),('guest', 'manage_themes', -1);
INSERT INTO `{db->prefix}permissions` (`group_id`, `permission`, `status`) VALUES('guest', 'update_system', -1),('guest', 'view_error_log', -1),('guest', 'add_new_member', -1),('guest', 'manage_members', -1);
INSERT INTO `{db->prefix}permissions` (`group_id`, `permission`, `status`) VALUES('guest', 'search_members', -1),('guest', 'manage_member_settings', -1),('guest', 'manage_permissions', -1),('guest', 'add_plugins', -1);
INSERT INTO `{db->prefix}permissions` (`group_id`, `permission`, `status`) VALUES('guest', 'manage_plugins', -1),('guest', 'manage_plugin_settings', -1),('guest', 'view_other_profiles', 0),('guest', 'edit_other_profiles', -1);

##
# This is where currently enabled plugins are held
##
CREATE TABLE `{db->prefix}plugins`
(
	`directory` VARCHAR(255) NOT NULL,
	`runtime_error` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	`error_message` TEXT NULL,
	`is_activated` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`directory`),
	KEY (`runtime_error`),
	KEY (`is_activated`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

##
# A table holding various settings and what not xD
##
CREATE TABLE `{db->prefix}settings`
(
	`variable` VARCHAR(255) NOT NULL,
	`type` VARCHAR(30) NOT NULL DEFAULT 'string',
	`value` TEXT NOT NULL,
	PRIMARY KEY (`variable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `{db->prefix}settings` (`variable`, `value`) VALUES('show_version', 1),('version', '2.0-beta2'),('password_security', 1),('disallowed_names', ''),('disallowed_emails', ''),('default_event', '');
INSERT INTO `{db->prefix}settings` (`variable`, `value`) VALUES('enable_tasks', 1),('site_name', 'SnowCMS'),('site_email', ''),('theme', 'default'),('max_tasks', 2);
INSERT INTO `{db->prefix}settings` (`variable`, `value`) VALUES('registration_type', 1),('enable_utf8', 1),('members_min_name_length', 3),('members_max_name_length', 80),('errors_log', 1);
INSERT INTO `{db->prefix}settings` (`variable`, `value`) VALUES('mail_handler', 'mail'),('smtp_host', 'localhost'),('smtp_port', 25),('smtp_is_tls', 0),('smtp_timeout', 5),('smtp_user', ''),('smtp_pass', '');
INSERT INTO `{db->prefix}settings` (`variable`, `value`) VALUES('mail_additional_parameters', ''),('default_member_groups', 'member'),('disable_admin_security', 0),('admin_login_timeout', 15),('admin_news_fetch_every', 43200);
INSERT INTO `{db->prefix}settings` (`variable`, `value`) VALUES('date_format', '%B %d, %Y'),('time_format', '%I:%M:%S %p'),('datetime_format', '%B %d, %Y, %I:%M:%S %p');

##
# Need to do something?
##
CREATE TABLE `{db->prefix}tasks`
(
	`task_name` VARCHAR(255) NOT NULL,
	`last_ran` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`run_every` INT(10) UNSIGNED NOT NULL DEFAULT '86400',
	`file` VARCHAR(255) NOT NULL DEFAULT '',
	`location` VARCHAR(32) NOT NULL DEFAULT '',
	`func` VARCHAR(255) NOT NULL DEFAULT '',
	`queued` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	`enabled` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
	PRIMARY KEY (`task_name`),
	KEY (`last_ran`),
	KEY (`queued`),
	KEY (`enabled`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `{db->prefix}tasks` (`task_name`, `file`, `location`, `func`) VALUES('plugins_update_check', 'admin/admin_plugins_manage.php', 'coredir', 'admin_plugins_check_updates');
INSERT INTO `{db->prefix}tasks` (`task_name`, `file`, `location`, `func`) VALUES('themes_update_check', 'admin/admin_themes_manage.php', 'coredir', 'admin_themes_check_updates');
INSERT INTO `{db->prefix}tasks` (`task_name`, `file`, `location`, `func`) VALUES('system_update_check', 'admin/admin_update.php', 'coredir', 'admin_update_check');

##
# Holds uploaded files, well, the files information that is.
##
CREATE TABLE `{db->prefix}uploads`
(
	`area_name` VARCHAR(255) NOT NULL,
	`area_id` INT(11) UNSIGNED NOT NULL,
	`upload_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`upload_time` INT(10) UNSIGNED NOT NULL,
	`member_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`member_name` VARCHAR(255) NOT NULL,
	`member_email` VARCHAR(255) NOT NULL,
	`member_ip` VARCHAR(150) NOT NULL,
	`modified_time` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`modified_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`modified_name` VARCHAR(255) NULL,
	`modified_email` VARCHAR(255) NULL,
	`modified_ip` VARCHAR(150) NULL,
	`filename` VARCHAR(255) NOT NULL,
	`file_ext` VARCHAR(100) NOT NULL,
	`filelocation` VARCHAR(255) NOT NULL,
	`filesize` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`downloads` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`upload_type` VARCHAR(100) NOT NULL,
	`mime_type` VARCHAR(255) NOT NULL,
	`checksum` VARCHAR(40) NOT NULL,
	PRIMARY KEY (`area_name`, `area_id`, `upload_id`),
	KEY (`member_id`),
	KEY (`member_name`),
	KEY (`member_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;