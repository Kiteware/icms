/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;

DROP TABLE IF EXISTS `pages`;
DROP TABLE IF EXISTS `navigation`;
DROP TABLE IF EXISTS `permissions`;
DROP TABLE IF EXISTS `posts`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `messages`;

CREATE TABLE `navigation` (
  `nav_id` tinyint NOT NULL AUTO_INCREMENT,
  `nav_name` char(32) NOT NULL,
  `nav_link` varchar(255) NOT NULL UNIQUE,
  `nav_position` tinyint NOT NULL,
  `parent` tinyint NULL DEFAULT '0' COMMENT 'The parent navs id',
  PRIMARY KEY (`nav_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `navigation` (nav_name, nav_link, nav_position, parent) VALUES ('Home','/home',1,0),('Blog','/blog',2,0),('Contact','/contact',3,0),('Admin','/admin',5,0);
ALTER TABLE `navigation` COMMENT = 'Site main menu.';
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `page_id` int(128) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `url` varchar(32) NOT NULL UNIQUE,
  `content` text NOT NULL,
  `permissions` varchar(64) NOT NULL DEFAULT 'guest',
  `ip` varchar(16) NOT NULL,
  `time` datetime NOT NULL,
  `views` int(255) NOT NULL DEFAULT 1,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `pages` COMMENT = 'A backup for the flat file pages';
INSERT INTO `pages` (page_id, title, url, content, ip, time, views) VALUES (1, 'Home', 'home', 'Welcome to ICMS.', '127.0.0.1', '2016-01-14 12:00:00', 0);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `userID` int(128) NULL,
  `pageName` varchar(32) NOT NULL,
  `usergroupID` varchar(16) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `permissions` COMMENT = 'User permissions';
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `permissions` VALUES (NULL,'login','guest'),(NULL,'register','guest'),(NULL,'recover','guest'),
                                  (NULL,'blog','guest'),(NULL,'changepassword','user'),(NULL,'profile','user'),(NULL,'settings','user'),
                                  (NULL,'administrator','administrator'),(NULL,'logout','guest'), (NULL,'home','guest'),
                                  (NULL,'activate','guest'), (NULL,'contact','guest'), (NULL,'shop','guest');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `post_id` int(128) NOT NULL AUTO_INCREMENT,
  `post_title` varchar(64) NOT NULL,
  `post_description` varchar(128) NOT NULL,
  `post_content` text NOT NULL,
  `post_date` datetime NOT NULL,
  `post_published` BOOL NOT NULL DEFAULT 1,
  `post_author` VARCHAR(32) NOT NULL,
  `post_ip` VARCHAR(16) NOT NULL,
  `post_views` int(255) NOT NULL DEFAULT 1,
  `post_tags` varchar(128) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
ALTER TABLE `posts` COMMENT = 'Blog posts';
INSERT INTO `posts` (post_id, post_title, post_description, post_content, post_date, post_published, post_author, post_ip, post_views, post_tags)
    VALUES (1, 'ICMS Intro', 'A sample blog post for ICMS>', 'Thanks for trying out ICMS! We hope you enjoy using it, but let us know if you need any help or have feature ideas!', '2016-03-01 12:00:00', 1, 'ICMS','127.0.0.1', 0, 'hello-world');

/*!40101 SET character_set_client = @saved_cs_client */;

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `username` varchar(16) NOT NULL UNIQUE,
  `full_name` varchar(32) NOT NULL,
  `gender` varchar(16) NOT NULL DEFAULT 'undisclosed',
  `bio` text NOT NULL DEFAULT '...',
  `image_location` varchar(128) NOT NULL DEFAULT 'avatars/default_avatar.png',
  `password` varchar(255) NOT NULL,
  `email` varchar(64) NOT NULL,
  `email_code` varchar(128) UNIQUE,
  `joined` TIMESTAMP NOT NULL,
  `confirmed` BOOL NOT NULL DEFAULT '0',
  `generated_string` varchar(35) NOT NULL DEFAULT '0',
  `ip` varchar(32) NOT NULL,
  `permission` int(2) NOT NULL DEFAULT '0',
  `usergroup` varchar(16) DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
ALTER TABLE `users` COMMENT = 'Users and their settings';

CREATE TABLE messages
(
    sender VARCHAR(32) NOT NULL,
    recipient VARCHAR(32) NOT NULL,
    content TEXT NOT NULL,
    msg_status VARCHAR(32) DEFAULT "unread" NOT NULL,
    msg_date DATETIME NOT NULL,
    sender_ip VARCHAR(16) NOT NULL,
    recipient_ip VARCHAR(16),
    title VARCHAR(64) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE messages COMMENT = 'Stores all system and user transcripts';
