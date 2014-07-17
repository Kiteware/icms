-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `navigation`;
CREATE TABLE `navigation` (
  `nav_name` char(15) NOT NULL,
  `nav_link` varchar(30) NOT NULL,
  `nav_position` int(2) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `url` varchar(15) NOT NULL,
  `constants` text NOT NULL,
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `pages` (`page_id`, `title`, `url`, `constants`, `content`, `ip`, `time`) VALUES
(1,	'url8',	'url8',	'',	'url8',	'127.0.0.1',	'0000-00-00 00:00:00'),
(2,	'url7',	'url7',	'',	'url7',	'127.0.0.1',	'0000-00-00 00:00:00'),
(3,	'url8',	'url8',	'',	'url8',	'127.0.0.1',	'0000-00-00 00:00:00'),
(4,	'url8',	'url8',	'',	'url8',	'127.0.0.1',	'0000-00-00 00:00:00'),
(5,	'url8',	'url8',	'',	'url8',	'127.0.0.1',	'0000-00-00 00:00:00'),
(6,	'url9',	'url9',	'',	'url9',	'127.0.0.1',	'0000-00-00 00:00:00'),
(7,	'firstpage',	'firstpage',	'',	'firstpage content, aww yeah',	'127.0.0.1',	'0000-00-00 00:00:00'),
(8,	'secondtry',	'secondtry',	'',	'woot second try, gonna work',	'127.0.0.1',	'0000-00-00 00:00:00');

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_name` varchar(100) NOT NULL,
  `post_preview` text NOT NULL,
  `post_content` text NOT NULL,
  `post_date` datetime NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `posts` (`post_id`, `post_name`, `post_preview`, `post_content`, `post_date`) VALUES
(1,	'5-39',	'preview539',	'content',	'2014-04-28 17:39:47'),
(3,	'533',	'preview',	'content',	'2014-04-28 17:37:11'),
(4,	'5-21',	'made at 5:21',	'nothing',	'2014-04-28 17:21:52'),
(5,	'july 8',	'using class database',	'content for july 8th test post',	'2014-07-08 23:59:08');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(18) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `gender` varchar(15) NOT NULL DEFAULT 'undisclosed',
  `bio` text NOT NULL,
  `image_location` varchar(125) NOT NULL DEFAULT 'avatars/default_avatar.png',
  `password` varchar(512) NOT NULL,
  `email` varchar(1024) NOT NULL,
  `email_code` varchar(100) NOT NULL,
  `time` int(11) NOT NULL,
  `confirmed` int(11) NOT NULL DEFAULT '0',
  `generated_string` varchar(35) NOT NULL DEFAULT '0',
  `ip` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `gender`, `bio`, `image_location`, `password`, `email`, `email_code`, `time`, `confirmed`, `generated_string`, `ip`) VALUES
(1,	'user',	'',	'',	'undisclosed',	'',	'avatars/default_avatar.png',	'$2y$12$5976570425302383fd200OMWk2I.17wrOMh5McG9fmdV8X2KA3Mra',	'user@nixx.co',	'code_5302383fd1ff02.02414527',	1392654399,	1,	'0',	'127.0.0.1');

-- 2014-07-17 17:57:25
