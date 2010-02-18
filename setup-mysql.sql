----
-- Holds all the PHP errors which have occurred!
----
CREATE TABLE `{$db_prefix}error_log`
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

----
-- Holds members extra information, this is what a plugin should use to store extra member stuffs!
----
CREATE TABLE `{$db_prefix}member_data`
(
  `member_id` INT(11) UNSIGNED NOT NULL,
  `variable` VARCHAR(255) NOT NULL,
  `value` TEXT NOT NULL,
  PRIMARY KEY (`member_id`, `variable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

----
-- The members table, holding, you guessed it! MEMBERS!
---
CREATE TABLE `{$db_prefix}members`
(
  `member_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_name` VARCHAR(80) NOT NULL,
  `member_pass` VARCHAR(40) NOT NULL,
  `member_hash` VARCHAR(16) NOT NULL,
  `display_name` VARCHAR(255) NOT NULL,
  `member_email` VARCHAR(100) NOT NULL,
  `member_groups` VARCHAR(255) NOT NULL,
  `member_registered` INT(10) UNSIGNED NOT NULL,
  `member_ip` VARCHAR(150) NOT NULL,
  `member_activated` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `member_acode` VARCHAR(40) NOT NULL,
  PRIMARY KEY (`member_id`),
  KEY (`member_name`),
  KEY (`display_name`),
  KEY (`member_activated`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

----
-- Used via the Messages API
----
CREATE TABLE `{$db_prefix}messages`
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

----
-- This is where currently enabled plugins are held
----
CREATE TABLE `{$db_prefix}plugins`
(
  `dependency_name` VARCHAR(255) NOT NULL,
  `dependency_names` TEXT NOT NULL,
  `dependencies` TINYINT(3) UNSIGNED NOT NULL,
  `directory` VARCHAR(255) NOT NULL,
  `runtime_error` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `is_activated` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`dependency_name`),
  KEY (`dependencies`),
  KEY (`runtime_error`),
  KEY (`is_activated`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

----
-- A table holding various settings and what not xD
----
CREATE TABLE `{$db_prefix}settings`
(
  `variable` VARCHAR(255) NOT NULL,
  `value` TEXT NOT NULL,
  PRIMARY KEY (`variable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `{$db_prefix}settings` (`variable`, `value`) VALUES('show_version', 1),('version', '2.0 SVN'),('password_security', 1),('reserved_names', ''),('disallowed_emails', '');
INSERT INTO `{$db_prefix}settings` (`variable`, `value`) VALUES('disallowed_email_domains', ''),('enable_tasks', 1),('site_name', 'SnowCMS'),('site_email', ''),('theme', 'default'),('max_tasks', 2);
INSERT INTO `{$db_prefix}settings` (`variable`, `value`) VALUES('registration_enabled', 1),('registration_type', 0),('enable_utf8', 1),('members_min_name_length', 3),('members_max_name_length', 80),('errors_log', 1);
INSERT INTO `{$db_prefix}settings` (`variable`, `value`) VALUES('mail_handler', 'mail'),('smtp_host', 'localhost'),('smtp_port', 25),('smtp_is_tls', 0),('smtp_timeout', 5),('smtp_user', ''),('smtp_pass', '');
INSERT INTO `{$db_prefix}settings` (`variable`, `value`) VALUES('mail_additional_parameters', ''),('default_member_groups', 'member'),('disable_admin_security', 0);

----
-- Need to do something?
----
CREATE TABLE `{$db_prefix}tasks`
(
  `task_name` VARCHAR(255) NOT NULL,
  `last_ran` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `run_every` INT(10) UNSIGNED NOT NULL DEFAULT '86400',
  `file` VARCHAR(255) NOT NULL DEFAULT '',
  `func` VARCHAR(255) NOT NULL DEFAULT '',
  `queued` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `enabled` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`task_name`),
  KEY (`last_ran`),
  KEY (`queued`),
  KEY (`enabled`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

----
-- Holds registered tokens...
----
CREATE TABLE `{$db_prefix}tokens`
(
  `session_id` VARCHAR(150) NOT NULL,
  `token_name` VARCHAR(100) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `token_registered` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`session_id`,`token_name`),
  KEY (`token_registered`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

----
-- Holds uploaded files, well, the files information that is.
----
CREATE TABLE `{$db_prefix}uploads`
(
  `area_name` VARCHAR(255) NOT NULL,
  `area_id` INT(11) UNSIGNED NOT NULL,
  `upload_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `member_name` VARCHAR(255) NOT NULL,
  `member_email` VARCHAR(255) NOT NULL,
  `member_ip` VARCHAR(150) NOT NULL,
  `modified_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `modified_name` VARCHAR(255) NOT NULL,
  `modified_email` VARCHAR(255) NOT NULL,
  `modified_ip` VARCHAR(150) NOT NULL,
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