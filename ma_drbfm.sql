-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 10, 2018 at 03:46 PM
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
CREATE DATABASE IF NOT EXISTS `ma_drbfm` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ma_drbfm`;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `src_id` int(11) DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `size` int(6) NOT NULL,
  `descr` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `process`
--

DROP TABLE IF EXISTS `process`;
CREATE TABLE IF NOT EXISTS `process` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `body` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `process_hint`
--

DROP TABLE IF EXISTS `process_hint`;
CREATE TABLE IF NOT EXISTS `process_hint` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stg_id` int(11) NOT NULL COMMENT 'stage',
  `prior` int(2) NOT NULL DEFAULT '1',
  `text` varchar(255) NOT NULL,
  `descr` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

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
-- Table structure for table `process_stage`
--

DROP TABLE IF EXISTS `process_stage`;
CREATE TABLE IF NOT EXISTS `process_stage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `body` text,
  `prc_id` int(11) NOT NULL COMMENT 'process',
  `stg_id` int(11) DEFAULT NULL COMMENT 'parent stage',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `prc_id` (`prc_id`),
  KEY `stg_id` (`stg_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

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
-- Constraints for table `process_stage`
--
ALTER TABLE `process_stage`
  ADD CONSTRAINT `process` FOREIGN KEY (`prc_id`) REFERENCES `process` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
