/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tournaments` (
`tid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `bracket` varchar(20) DEFAULT NULL,
  `size` int(11) NOT NULL DEFAULT '8',
  `prize` int(11) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `winner` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tourn_players` (
`tid` int(11) NOT NULL,
  `player_name` varchar(30) NOT NULL,
  `wins` int(11) DEFAULT '0',
  `losses` int(11) DEFAULT '0',
  `paid` tinyint(4) DEFAULT '0',
  `ready` tinyint(4) DEFAULT '0',
  `steamid` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tourn_matches` (
`mid` int(11) NOT NULL AUTO_INCREMENT,
  `home` varchar(20) NOT NULL,
  `away` varchar(20) NOT NULL,
  `winner` varchar(20) NOT NULL,
  `tid` int(11) DEFAULT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `navigation` (`nav_name`, `nav_link`, `nav_position`) VALUES ('Tournament', 'index.php?page=tournaments', 10);