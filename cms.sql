/*
SQLyog Ultimate v11.42 (64 bit)
MySQL - 10.0.13-MariaDB : Database - cms
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`cms` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `cms`;

/*Table structure for table `Settings` */

DROP TABLE IF EXISTS `Settings`;

CREATE TABLE `Settings` (
  `usergroups` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `Settings` */

/*Table structure for table `navigation` */

DROP TABLE IF EXISTS `navigation`;

CREATE TABLE `navigation` (
  `nav_name` char(15) NOT NULL,
  `nav_link` varchar(30) NOT NULL,
  `nav_position` int(2) unsigned NOT NULL,
  `nav_permission` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `navigation` */

insert  into `navigation`(`nav_name`,`nav_link`,`nav_position`,`nav_permission`) values ('Home','index.php',1,1),('Admin','admincp/index.php',5,5);

/*Table structure for table `pages` */

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

/*Data for the table `pages` */

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `userID` int(11) DEFAULT NULL,
  `pageName` varchar(20) DEFAULT NULL,
  `usergroupID` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `permissions` */

insert  into `permissions`(`userID`,`pageName`,`usergroupID`) values (NULL,'login','guest'),(NULL,'register','guest'),(NULL,'confirm-recover','guest'),(NULL,'change-password','user'),(NULL,'settings','user'),(NULL,'administrator','administrator'),(NULL,'logout','guest');

/*Table structure for table `posts` */

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_name` varchar(100) NOT NULL,
  `post_preview` text NOT NULL,
  `post_content` text NOT NULL,
  `post_date` datetime NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `posts` */

insert  into `posts`(`post_id`,`post_name`,`post_preview`,`post_content`,`post_date`) values (6,'Hello World','Lorem ipsum dolor sit amet, per ea nusquam eligendi offendit. Duo cu menandri urbanitas. Cu unum consetetur cum. Has at sale singulis democritum. Cu velit interpretaris mei. Ut gubergren disputationi conclusionemque eum, ei discere accusam pertinacia pro. Per ad paulo iracundia.','Lorem ipsum dolor sit amet, per ea nusquam eligendi offendit. Duo cu menandri urbanitas. Cu unum consetetur cum. Has at sale singulis democritum. Cu velit interpretaris mei. Ut gubergren disputationi conclusionemque eum, ei discere accusam pertinacia pro. Per ad paulo iracundia.\r\n\r\nAccusata facilisis vix ne, sit at nonumy melius. Inani altera quidam te per, cu nec veniam tractatos democritum, agam movet ne nam. Phaedrum dignissim persecuti vix no, vel id ferri verear incorrupte, porro reformidans no qui. Harum laboramus ex mea, no mea evertitur consequuntur. Pro ne illum scripta postulant, usu equidem repudiandae ei, nobis adolescens inciderint has et. Menandri comprehensam no ius, duo ad tale simul.\r\n\r\nDico tempor gloriatur mei at. Mutat partem consetetur at duo. Porro salutandi abhorreant qui eu, eu pri saepe dicunt concludaturque, iriure regione ex mei. Pro ea dicat mnesarchum, nec vitae dolore tacimates ex, quo dolore perfecto posidonium an. Natum error nam no, an quo clita ceteros, ridens lucilius deterruisset vis at. Scripta integre eos in, mea dico summo consequat at, ad augue atomorum consequat mea. Ad essent laoreet est.\r\n\r\nAffert perfecto no mel, ex sit falli nostrum dignissim, ad nec mentitum complectitur. Nisl mundi nullam mei at, legere repudiare mei ea, pro in verterem quaestio conclusionemque. Quod nonumes antiopam eu est, consul adipisci eloquentiam qui cu. Semper noluisse apeirian usu ad. Sonet propriae invenire usu ne, consul tacimates pertinax id vis. Sit ut copiosae eloquentiam definitionem, cu electram eloquentiam mei, ancillae evertitur eu sea.\r\n\r\nTe qui possit invenire definitiones, facilisi efficiantur intellegebat id vim, suas solum deterruisset vel cu. Exerci nullam qualisque eu vim. Dico porro viderer eam ex, te per ignota cetero. Cu vis graece democritum, ei mel unum dicunt, volumus noluisse repudiare eu mel.','2014-07-30 14:20:59'),(7,'test','','<h1>Minimalist Online Markdown Editor</h1>\r\n\r\n<p>This is the <strong>simplest</strong> and <strong>slickest</strong> online Markdown editor.  </p>\r\n\r\n<h2>Getting started</h2>\r\n\r\n<h3>How?</h3>\r\n\r\n<p>Just start typing in the left panel.</p>\r\n\r\n<h3>Buttons you might want to use</h3>\r\n\r\n<ul>\r\n<li><strong>Quick Reference</strong>: that\'s a reminder of the most basic rules of Markdown</li>\r\n<li><strong>HTML | Preview</strong>: <em>HTML</em> to see the markup generated from your Markdown text, <em>Preview</em> to see how it looks like</li>\r\n</ul>\r\n\r\n<h3>Privacy</h3>\r\n\r\n<ul>\r\n<li>No data is sent to any server ï¿½ everything you type stays in your browser</li>\r\n<li>The editor automatically saves what you write locally in case you accidentally close it. <br />\r\nIf using a public computer, either empty the left panel before leaving the editor or use your browser\'s privacy mode</li>\r\n</ul>','2014-08-08 12:13:29');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(18) NOT NULL,
  `full_name` varchar(32) NOT NULL,
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
  `permission` int(2) NOT NULL DEFAULT '0',
  `usergroup` varchar(20) DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`full_name`,`gender`,`bio`,`image_location`,`password`,`email`,`email_code`,`time`,`confirmed`,`generated_string`,`ip`,`permission`,`usergroup`) values (1,'user','Dillon','male','','avatars/default_avatar.png','$2y$12$5976570425302383fd200OMWk2I.17wrOMh5McG9fmdV8X2KA3Mra','user@nixx.co','code_5302383fd1ff02.02414527',1392654399,1,'0','127.0.0.1',0,'administrator');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
