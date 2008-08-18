DROP TABLE IF EXISTS `{$db_prefix}board_logs`;

CREATE TABLE `{$db_prefix}board_logs` (
  `bid` int(11) NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  UNIQUE KEY (`bid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$db_prefix}board_permissions`;

CREATE TABLE `{$db_prefix}board_permissions` (
  `bid` int(11) NOT NULL default '0',
  `group_id` int(11) NOT NULL default '0',
  `what` VARCHAR(50) NOT NULL,
  `can` int(1) NOT NULL default '1',
  PRIMARY KEY (`group_id`,`what`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$db_prefix}boards`;

CREATE TABLE `{$db_prefix}boards` (
  `bid` int(11) NOT NULL auto_increment,
  `cid` int(11) NOT NULL default '0',
  `border` int(11) NOT NULL default '0',
  `who_view` varchar(255) NOT NULL default '',
  `name` text NOT NULL,
  `bdesc` text NOT NULL,
  `numtopics` int(11) NOT NULL default '0',
  `numposts` int(11) NOT NULL default '0',
  `last_msg` int(11) NOT NULL default '0',
  `last_uid` int(11) NOT NULL default '0',
  `last_name` text NOT NULL,
  PRIMARY KEY  (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$db_prefix}categories`;

CREATE TABLE `{$db_prefix}categories` (
  `cid` int(11) NOT NULL auto_increment,
  `corder` int(11) NOT NULL default '0',
  `cname` tinytext NOT NULL,
  `cdesc` text NOT NULL,
  PRIMARY KEY  (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$db_prefix}membergroups`;

CREATE TABLE `{$db_prefix}membergroups` (
  `group_id` int(11) NOT NULL auto_increment,
  `groupname` text NOT NULL,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `{$db_prefix}membergroups` VALUES ('1','Administrator'),('2','Regular Member');

DROP TABLE IF EXISTS `{$db_prefix}members`;

CREATE TABLE `{$db_prefix}members` (
  `id` int(11) NOT NULL auto_increment,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `display_name` text NOT NULL,
  `reg_date` int(10) NOT NULL default '0',
  `reg_ip` text NOT NULL,
  `last_login` int(10) NOT NULL default '0',
  `last_ip` text NOT NULL,
  `group` int(11) NOT NULL default '0',
  `numposts` int(11) NOT NULL default '0',
  `signature` text NOT NULL,
  `activated` int(1) NOT NULL default '0',
  `suspension` int(10) NOT NULL default '0',
  `banned` int(1) NOT NULL default '0',
  `acode` text NOT NULL,
  `sc` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$db_prefix}menus`;

CREATE TABLE `{$db_prefix}menus` (
  `link_id` int(11) NOT NULL auto_increment,
  `order` int(11) NOT NULL default '0',
  `link_name` text NOT NULL,
  `href` text NOT NULL,
  `target` int(1) NOT NULL default '0',
  `menu` int(1) NOT NULL default '0',
  PRIMARY KEY  (`link_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `{$db_prefix}menus` VALUES ('1','1','Home','http://snowcms.northsalemcrew.net/','0','1');

DROP TABLE IF EXISTS `{$db_prefix}messages`;

CREATE TABLE `{$db_prefix}messages` (
  `mid` int(11) NOT NULL auto_increment,
  `tid` int(11) NOT NULL default '0',
  `bid` int(11) NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  `uid_editor` int(11) NOT NULL default '0',
  `edit_reason` text NOT NULL,
  `edit_time` int(10) NOT NULL default '0',
  `editor_name` text NOT NULL,
  `subject` text NOT NULL,
  `post_time` int(10) NOT NULL default '0',
  `poster_name` text NOT NULL,
  `poster_email` text NOT NULL,
  `ip` text NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY  (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$db_prefix}online`;

CREATE TABLE `{$db_prefix}online` (
  `user_id` int(11) NOT NULL default '0',
  `ip` text NOT NULL,
  `page` text NOT NULL,
  `last_active` int(10) NOT NULL default '0',
  UNIQUE KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$db_prefix}pages`;

CREATE TABLE `{$db_prefix}pages` (
  `page_id` int(11) NOT NULL auto_increment,
  `page_owner` int(11) NOT NULL default '0',
  `owner_name` text NOT NULL,
  `create_date` int(10) NOT NULL default '0',
  `modify_date` int(10) NOT NULL default '0',
  `title` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$db_prefix}permissions`;

CREATE TABLE `{$db_prefix}permissions` (
  `group_id` int(11) NOT NULL default '0',
  `what` varchar(50) NOT NULL default '',
  `can` int(1) NOT NULL default '0',
  PRIMARY KEY  (`group_id`,`what`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `{$db_prefix}permissions` VALUES ('1','admin','1'),('1','manage_pages','1'),('1','manage_basic-settings','1'),('1','manage_members','1'),('1','manage_menus','1'),('1','manage_news','1'),('1','manage_permissions','1'),('1','manage_forum_perms','1'),('1','view_forum','1'),('1','view_online','1'),('1','view_profile','1'),('2','manage_menus','1'),('2','manage_news','1'),('2','manage_pages','1'),('2','manage_permissions','1'),('2','manage_forum_perms','1'),('2','view_forum','1'),('2','view_online','1'),('1','manage_mail_settings','1'),('1','manage_groups','1');

DROP TABLE IF EXISTS `{$db_prefix}settings`;

CREATE TABLE `{$db_prefix}settings` (
  `variable` VARCHAR(100) NOT NULL,
  `value` text NOT NULL,
  UNIQUE KEY (`variable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `{$db_prefix}settings` VALUES ('site_name','SnowCMS'),('slogan','Its a CMS alright...'),('language','english'),('theme','default'),('login_threshold','15'),('version','0.7'),('main_page','1'),('main_page_id','1'),('remember_time','120'),('timeformat','H:i:s'),('dateformat','F jS, Y'),('mail_with_fsockopen','0'),('smtp_host','mail.northsalemcrew.net'),('smtp_user','admin@northsalemcrew.net'),('smtp_pass','4487699'),('smtp_from','admin@northsalemcrew.net'),('smtp_port','25'),('account_activation','1'),('webmaster_email','me@goaway.com'),('board_posts_per_page','20'),('topic_posts_per_page','10'),('num_news_items','6'),('manage_members_per_page','20'),('default_group',2);

DROP TABLE IF EXISTS `{$db_prefix}topics`;

CREATE TABLE `{$db_prefix}topics` (
  `tid` int(11) NOT NULL auto_increment,
  `sticky` int(1) NOT NULL default '0',
  `locked` int(1) NOT NULL default '0',
  `bid` int(11) NOT NULL default '0',
  `first_msg` int(11) NOT NULL default '0',
  `last_msg` int(11) NOT NULL default '0',
  `starter_id` int(11) NOT NULL default '0',
  `topic_starter` text NOT NULL,
  `ender_id` int(11) NOT NULL default '0',
  `topic_ender` text NOT NULL,
  `num_replies` int(11) NOT NULL default '0',
  `numviews` int(11) NOT NULL default '0',
  PRIMARY KEY  (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$db_prefix}news`;

CREATE TABLE `{$db_prefix}news` (
  `news_id` INT(11) NOT NULL AUTO_INCREMENT,
  `poster_id` INT(11) NOT NULL default '0',
  `poster_name` TEXT NOT NULL,
  `subject` TEXT NOT NULL,
  `body` TEXT NOT NULL,
  `post_time` INT(10) NOT NULL,
  `modify_time` INT(10) NOT NULL default '0',
  `numViews` INT(11) NOT NULL default '0',
  `numComments` INT(11) NOT NULL default '0',
  `allow_comments` INT(1) NOT NULL default '1',
  PRIMARY KEY(`news_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$db_prefix}news_comments`;

CREATE TABLE `{$db_prefix}news_comments` (
  `cid` INT(11) NOT NULL AUTO_INCREMENT,
  `nid` INT(11) NOT NULL,
  `poster_id` INT(11) NOT NULL default '0',
  `poster_name` TEXT NOT NULL,
  `subject` TEXT NOT NULL,
  `body` TEXT NOT NULL,
  `post_time` INT(10) NOT NULL,
  `modify_time` INT(10) NOT NULL default '0',
  `isApproved` INT(1) NOT NULL default '1',
  `isSpam` INT(1) NOT NULL default '0',
  PRIMARY KEY (`cid`),
  UNIQUE KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;