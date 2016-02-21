/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;

DROP TABLE `pages`;
DROP TABLE `navigation`;
DROP TABLE `permissions`;
DROP TABLE `posts`;
DROP TABLE `users`;
DROP TABLE `addons`;


CREATE TABLE `navigation` (
  `nav_name` char(15) NOT NULL,
  `nav_link` varchar(30) NOT NULL,
  `nav_position` int(2) unsigned NOT NULL,
  `nav_permission` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `navigation` VALUES ('Home','/',1,1),('Blog','/blog',2,1),('Admin','/admin',5,5);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
INSERT INTO `pages` (page_id, title, url, constants, content, ip, time) VALUES (1, 'Home', 'home', 'home', '<?php
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit(''400: Bad Request'');
}
$posts =$blog->get_posts();
?>
<div class="wrapper">
    <section class="content">
        <article>
            <h1>Welcome</h1>
            <p>ICMS was made to help kickstart websites. A simple and fast engine that supports user registration, permission levels,
                blogging, and static page creation.</p>
            <blockquote>
                All content not saved will be lost. - Nintendo
            </blockquote>
            <code data-lang="php" class="lang">
                $login = $users->login($username, $password);
                if ($login === false) {
                $errors[] = ''Sorry, that username/password is invalid'';
                }
            </code>
            <?php
            foreach ($posts as $post) {
            ?>
            <div class="post-info right">
                <?php echo date(''F j, Y'', strtotime($post[''post_date''])) ?>
            </div>
            <h1><?php echo $post[''post_name'']?></h1>
            <hr />
            <p>
                <?php echo $post[''post_content'']?> <br />
                <a href="index.php?page=blog&postid=<?php echo $post[''post_id'']?>">Read more</a>
                <?php
                }
                ?>
            </p>
        </article>
    </section>
</div>
</body>', '127.0.0.1', '2014-12-14 12:57:45');
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `userID` int(11) DEFAULT NULL,
  `pageName` varchar(20) DEFAULT NULL,
  `usergroupID` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `permissions` VALUES (NULL,'login','guest'),(NULL,'register','guest'),(NULL,'recover','guest'),
                                  (NULL,'blog','guest'),(NULL,'changepassword','user'),(NULL,'profile','user'),(NULL,'settings','user'),
                                  (NULL,'administrator','administrator'),(NULL,'logout','guest'), (NULL,'home','guest'),
                                  (NULL,'index','guest'), (NULL,'activate','guest');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_name` varchar(100) NOT NULL,
  `post_preview` text NOT NULL,
  `post_content` text NOT NULL,
  `post_date` datetime NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `posts` VALUES (1,'Hello World','Lorem ipsum dolor sit amet, per ea nusquam eligendi offendit. Duo cu menandri urbanitas. Cu unum consetetur cum. Has at sale singulis democritum. Cu velit interpretaris mei. Ut gubergren disputationi conclusionemque eum, ei discere accusam pertinacia pro. Per ad paulo iracundia.','Lorem ipsum dolor sit amet, per ea nusquam eligendi offendit. Duo cu menandri urbanitas. Cu unum consetetur cum. Has at sale singulis democritum. Cu velit interpretaris mei. Ut gubergren disputationi conclusionemque eum, ei discere accusam pertinacia pro. Per ad paulo iracundia.\r\n\r\nAccusata facilisis vix ne, sit at nonumy melius. Inani altera quidam te per, cu nec veniam tractatos democritum, agam movet ne nam. Phaedrum dignissim persecuti vix no, vel id ferri verear incorrupte, porro reformidans no qui. Harum laboramus ex mea, no mea evertitur consequuntur. Pro ne illum scripta postulant, usu equidem repudiandae ei, nobis adolescens inciderint has et. Menandri comprehensam no ius, duo ad tale simul.\r\n\r\nDico tempor gloriatur mei at. Mutat partem consetetur at duo. Porro salutandi abhorreant qui eu, eu pri saepe dicunt concludaturque, iriure regione ex mei. Pro ea dicat mnesarchum, nec vitae dolore tacimates ex, quo dolore perfecto posidonium an. Natum error nam no, an quo clita ceteros, ridens lucilius deterruisset vis at. Scripta integre eos in, mea dico summo consequat at, ad augue atomorum consequat mea. Ad essent laoreet est.\r\n\r\nAffert perfecto no mel, ex sit falli nostrum dignissim, ad nec mentitum complectitur. Nisl mundi nullam mei at, legere repudiare mei ea, pro in verterem quaestio conclusionemque. Quod nonumes antiopam eu est, consul adipisci eloquentiam qui cu. Semper noluisse apeirian usu ad. Sonet propriae invenire usu ne, consul tacimates pertinax id vis. Sit ut copiosae eloquentiam definitionem, cu electram eloquentiam mei, ancillae evertitur eu sea.\r\n\r\nTe qui possit invenire definitiones, facilisi efficiantur intellegebat id vim, suas solum deterruisset vel cu. Exerci nullam qualisque eu vim. Dico porro viderer eam ex, te per ignota cetero. Cu vis graece democritum, ei mel unum dicunt, volumus noluisse repudiare eu mel.','2014-07-30 14:20:59'),(7,'test','','<h1>Minimalist Online Markdown Editor</h1>\r\n\r\n<p>This is the <strong>simplest</strong> and <strong>slickest</strong> online Markdown editor.  </p>\r\n\r\n<h2>Getting started</h2>\r\n\r\n<h3>How?</h3>\r\n\r\n<p>Just start typing in the left panel.</p>\r\n\r\n<h3>Buttons you might want to use</h3>\r\n\r\n<ul>\r\n<li><strong>Quick Reference</strong>: that\'s a reminder of the most basic rules of Markdown</li>\r\n<li><strong>HTML | Preview</strong>: <em>HTML</em> to see the markup generated from your Markdown text, <em>Preview</em> to see how it looks like</li>\r\n</ul>\r\n\r\n<h3>Privacy</h3>\r\n\r\n<ul>\r\n<li>No data is sent to any server Ã¯Â¿Â½ everything you type stays in your browser</li>\r\n<li>The editor automatically saves what you write locally in case you accidentally close it. <br />\r\nIf using a public computer, either empty the left panel before leaving the editor or use your browser\'s privacy mode</li>\r\n</ul>','2014-08-08 12:13:29');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addons` (
  `name` varchar(20) NOT NULL,
  `location` varchar(50) NOT NULL,
  PRIMARY KEY (`name`),
  UNIQUE KEY `unique_location` (`location`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `addons` VALUES ('tournament-admin','addons/tournament/tournament-admin.php'),('tournament','addons/tournament/tournament.php');
