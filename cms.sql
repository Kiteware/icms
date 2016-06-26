/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;

DROP TABLE `pages`;
DROP TABLE `navigation`;
DROP TABLE `permissions`;
DROP TABLE `posts`;
DROP TABLE `users`;
DROP TABLE `messages`;

CREATE TABLE `navigation` (
  `nav_name` char(16) NOT NULL,
  `nav_link` varchar(32) NOT NULL,
  `nav_position` int(2) unsigned NOT NULL,
  `nav_permission` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;.
ALTER TABLE messages COMMENT = 'The main menu';
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `navigation` VALUES ('Home','/home',1,1),('Blog','/blog',2,1),('Contact','/user/contact',3,1),('Admin','/admin',5,5);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `page_id` int(128) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `url` varchar(32) NOT NULL,
  `content` text NOT NULL,
  `ip` varchar(16) NOT NULL,
  `time` datetime NOT NULL,
  `views` int(255) NOT NULL DEFAULT 1,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE messages COMMENT = 'A backup for the flat file pages';
INSERT INTO `pages` (page_id, title, url, content, ip, time, views) VALUES (1, 'Home', 'home', '', '127.0.0.1', '2016-01-14 12:57:45', 0);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `userID` int(128) DEFAULT NULL,
  `pageName` varchar(32) DEFAULT NULL,
  `usergroupID` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE messages COMMENT = 'User permissions';
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
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
ALTER TABLE messages COMMENT = 'Blog posts';
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `posts` VALUES (1,'Sample Blog Post','Sample Blog Post','Thank you for trying out ICMS, this is a sample blog post, you can go ahead and delete it now.','2016-02-20 14:20:59', '1', '127.0.0.1', 0),(2,'Sample Draft','Sample Draft Blog Post','Drafts are only visible in the admin area, use them when you are not done with a post. ' ,'2016-02-08 12:13:29', '0', '127.0.0.1', 0);

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `username` varchar(16) NOT NULL,
  `full_name` varchar(32) NOT NULL,
  `gender` varchar(16) NOT NULL DEFAULT 'undisclosed',
  `bio` text NOT NULL,
  `image_location` varchar(128) NOT NULL DEFAULT 'avatars/default_avatar.png',
  `password` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `email_code` varchar(128) NOT NULL,
  `time` datetime NOT NULL,
  `confirmed` BOOL NOT NULL DEFAULT '0',
  `generated_string` varchar(35) NOT NULL DEFAULT '0',
  `ip` varchar(32) NOT NULL,
  `permission` int(2) NOT NULL DEFAULT '0',
  `usergroup` varchar(16) DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
ALTER TABLE messages COMMENT = 'Users and their settings';

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
);
ALTER TABLE messages COMMENT = 'Stores all system and user transcripts';