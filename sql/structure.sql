-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 16, 2018 at 06:09 PM
-- Server version: 5.5.60-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ma_drbfm`
--
DROP DATABASE ma_drbfm;
CREATE DATABASE IF NOT EXISTS `ma_drbfm` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ma_drbfm`;

-- --------------------------------------------------------

--
-- Table structure for table `action`
--

DROP TABLE IF EXISTS `action`;
CREATE TABLE IF NOT EXISTS `action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `src_id` int(11) NOT NULL COMMENT 'source',
  `name` varchar(255) NOT NULL,
  `content` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `discr` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=74 ;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `phone` varchar(64) NOT NULL,
  `contact` varchar(64) NOT NULL,
  `uid` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `descr` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `src_id` int(11) DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `size` int(6) NOT NULL,
  `descr` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=62 ;

-- --------------------------------------------------------

--
-- Table structure for table `note`
--

DROP TABLE IF EXISTS `note`;
CREATE TABLE IF NOT EXISTS `note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `src_id` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `discr` varchar(24) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=82 ;

-- --------------------------------------------------------

--
-- Table structure for table `process`
--

DROP TABLE IF EXISTS `process`;
CREATE TABLE IF NOT EXISTS `process` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `number` int(4) NOT NULL,
  `line` int(2) NOT NULL,
  `code` int(11) NOT NULL,
  `plant` varchar(64) NOT NULL,
  `machine` varchar(64) NOT NULL,
  `p_num` varchar(64) NOT NULL COMMENT 'piece number',
  `p_name` varchar(64) NOT NULL COMMENT 'piece name',
  `complex` varchar(4) NOT NULL DEFAULT 'AA',
  `ctm_id` int(11) NOT NULL COMMENT 'customer',
  `body` text,
  `uid` int(11) NOT NULL COMMENT 'owner',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Table structure for table `process_action`
--

DROP TABLE IF EXISTS `process_action`;
CREATE TABLE IF NOT EXISTS `process_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `pcs_id` int(11) NOT NULL COMMENT 'process',
  `src_id` int(11) NOT NULL COMMENT 'source',
  `name` varchar(255) NOT NULL,
  `content` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `discr` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `src-process` (`pcs_id`,`src_id`,`discr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=408 ;

-- --------------------------------------------------------

--
-- Table structure for table `process_hint`
--

DROP TABLE IF EXISTS `process_hint`;
CREATE TABLE IF NOT EXISTS `process_hint` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stg_id` int(11) NOT NULL COMMENT 'stage',
  `type_id` int(11) DEFAULT NULL,
  `uid` int(11) NOT NULL COMMENT 'owner',
  `prior` int(2) NOT NULL DEFAULT '1',
  `text` varchar(255) DEFAULT NULL,
  `who` varchar(255) DEFAULT NULL COMMENT 'who modelizes',
  `whn` timestamp NULL DEFAULT NULL COMMENT 'when modelizes',
  `eff` text COMMENT 'effect',
  `prev` text COMMENT 'prevention',
  `state` int(2) NOT NULL DEFAULT '0' COMMENT 'modelize state',
  `descr` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `stg_id` (`stg_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=116 ;

-- --------------------------------------------------------

--
-- Table structure for table `process_hint_rel`
--

DROP TABLE IF EXISTS `process_hint_rel`;
CREATE TABLE IF NOT EXISTS `process_hint_rel` (
  `parent_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  UNIQUE KEY `rel` (`parent_id`,`child_id`),
  KEY `child_id` (`child_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_hint_type`
--

DROP TABLE IF EXISTS `process_hint_type`;
CREATE TABLE IF NOT EXISTS `process_hint_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `op_id` int(11) NOT NULL,
  `prior` int(2) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `descr` text,
  `uid` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=63 ;

-- --------------------------------------------------------

--
-- Table structure for table `process_material`
--

DROP TABLE IF EXISTS `process_material`;
CREATE TABLE IF NOT EXISTS `process_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(255) NOT NULL,
  `prior` int(2) NOT NULL DEFAULT '0',
  `descr` text,
  `uid` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `process_op`
--

DROP TABLE IF EXISTS `process_op`;
CREATE TABLE IF NOT EXISTS `process_op` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(255) NOT NULL,
  `descr` text,
  `type_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `type` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=95 ;

-- --------------------------------------------------------

--
-- Table structure for table `process_op_rel`
--

DROP TABLE IF EXISTS `process_op_rel`;
CREATE TABLE IF NOT EXISTS `process_op_rel` (
  `parent_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  UNIQUE KEY `rel` (`parent_id`,`child_id`),
  KEY `child_id` (`child_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_op_stg_rel`
--

DROP TABLE IF EXISTS `process_op_stg_rel`;
CREATE TABLE IF NOT EXISTS `process_op_stg_rel` (
  `op_id` int(11) NOT NULL,
  `stg_id` int(11) NOT NULL,
  UNIQUE KEY `op_stg_rel` (`op_id`,`stg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_op_type`
--

DROP TABLE IF EXISTS `process_op_type`;
CREATE TABLE IF NOT EXISTS `process_op_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(255) NOT NULL,
  `descr` text,
  `uid` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `process_stage`
--

DROP TABLE IF EXISTS `process_stage`;
CREATE TABLE IF NOT EXISTS `process_stage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `body` text,
  `prc_id` int(11) NOT NULL COMMENT 'process',
  `stg_id` int(11) DEFAULT NULL COMMENT 'parent stage',
  `mtl_id` int(11) NOT NULL COMMENT 'material',
  `uid` int(11) NOT NULL COMMENT 'owner',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `prc_id` (`prc_id`),
  KEY `stg_id` (`stg_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=89 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `display_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `state` smallint(6) DEFAULT NULL,
  `roles` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `process_hint_rel`
--
ALTER TABLE `process_hint_rel`
  ADD CONSTRAINT `process_hint_rel_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `process_hint` (`id`),
  ADD CONSTRAINT `process_hint_rel_ibfk_2` FOREIGN KEY (`child_id`) REFERENCES `process_hint` (`id`);

--
-- Constraints for table `process_op`
--
ALTER TABLE `process_op`
  ADD CONSTRAINT `process_op_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `process_op_type` (`id`);

--
-- Constraints for table `process_stage`
--
ALTER TABLE `process_stage`
  ADD CONSTRAINT `process` FOREIGN KEY (`prc_id`) REFERENCES `process` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Pamintxa','iturri.jon@gmail.com',NULL,'$2y$14$OHIf0Zq2F1kfLdQAiRzOm.onMwULghwUpizRJvqokJBHUPD18tmUC',NULL,'a:1:{i:0;s:4:\"user\";}','2018-11-27 17:40:09','0000-00-00 00:00:00'),(13,'JJOxemaiB','jjoxemaib@example.com',NULL,'$2y$14$WzYaePOiSukHEuAtfkasle6cPP4EGIELTRjA4KwaVAGWH7UUGTLp.',NULL,'a:1:{i:0;s:4:\"user\";}','2018-11-30 07:28:30','2018-11-30 07:28:30'),(14,'AITOR','diaz@imaltuna.com',NULL,'$2y$14$5XB/qM.PPOPFEtB/Cgz9uu04prFbvurOG.dSTjCEkyrzcxygz4Nmm',NULL,'a:1:{i:0;s:4:\"user\";}','2018-11-30 11:10:29','2018-11-30 11:10:29'),(15,'Unai Ziarsolo','unai@maltuna.eus',NULL,'$2y$14$UDAQji1qKcGa9xT4rqglYu5jUauFftnWvmdh.g40r1Sop4WK/YXZi',NULL,'a:1:{i:0;s:4:\"user\";}','2018-11-30 11:30:09','2018-11-30 11:30:09');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
