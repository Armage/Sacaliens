-- MySQL dump 10.13  Distrib 5.1.36, for debian-linux-gnu (x86_64)
--
-- Sacaliens
-- 

--
-- Table structure for table `sac_tag`
--

DROP TABLE IF EXISTS `sac_tag`;
CREATE TABLE `sac_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `sac_url`
--

DROP TABLE IF EXISTS `sac_url`;
CREATE TABLE `sac_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timecreate` datetime DEFAULT '2009-01-01 00:00:00',
  `url` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `lastmodif` datetime DEFAULT NULL,
  `nbvisit` int(11) DEFAULT '0',
  `lastvisit` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `sac_url_tag`
--

DROP TABLE IF EXISTS `sac_url_tag`;
CREATE TABLE `sac_url_tag` (
  `url_id` int(11) DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `sac_user`
--

DROP TABLE IF EXISTS `sac_user`;
CREATE TABLE `sac_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(32) DEFAULT NULL,
  `lang` varchar(3) DEFAULT 'EN',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `sac_fragments` (
  `id_url` int(11) NOT NULL,
  `scheme` varchar(255) DEFAULT NULL,
  `host` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `query` varchar(255) DEFAULT NULL,
  `fragment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
