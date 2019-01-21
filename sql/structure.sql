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
INSERT INTO `user` VALUES (1,'Pamintxa','iturri.jon@gmail.com',NULL,'$2y$14$OHIf0Zq2F1kfLdQAiRzOm.onMwULghwUpizRJvqokJBHUPD18tmUC',NULL,'a:1:{i:0;s:4:\"user\";}','2018-11-27 17:40:09','0000-00-00 00:00:00'),(13,'JJOxemaiB','jjoxemaib@example.com',NULL,'$2y$14$WzYaePOiSukHEuAtfkasle6cPP4EGIELTRjA4KwaVAGWH7UUGTLp.',NULL,'a:1:{i:0;s:4:\"user\";}','2018-11-30 07:28:30','2018-11-30 07:28:30'),(14,'AITOR','diaz@imaltuna.com',NULL,'$2y$14$5XB/qM.PPOPFEtB/Cgz9uu04prFbvurOG.dSTjCEkyrzcxygz4Nmm',NULL,'a:1:{i:0;s:4:\"user\";}','2018-11-30 11:10:29','2018-11-30 11:10:29'),(15,'Unai Ziarsolo','unai@maltuna.eus',NULL,'$2y$14$UDAQji1qKcGa9xT4rqglYu5jUauFftnWvmdh.g40r1Sop4WK/YXZi',NULL,'a:1:{i:0;s:4:\"user\";}','2018-11-30 11:30:09','2018-11-30 11:30:09'),(16,'UnaiEcenarro','ucornes@ecenarro.com',NULL,'$2y$14$ABytkkPetNyLbgydbQ5/TOO5o7VJa9C83BX8m9ad/X9ZYB9r/7B7S',NULL,'a:1:{i:0;s:4:\"user\";}','2018-12-17 16:05:48','2018-12-17 16:05:48'),(17,'MariaAzpeitia','mazpeitia@ecenarro.com',NULL,'$2y$14$7n/2a876TJjzjvIu5qrhz.Hlc8/O0WfjWomh2dE3YRuphWzlZTz3C',NULL,'a:1:{i:0;s:4:\"user\";}','2018-12-17 16:07:07','2018-12-17 16:07:07'),(18,'Jon Agirre','jagirre@ecenarro.com',NULL,'$2y$14$MjGrviJbGArW91k6VxGzAepQhV.p4obk9SQVSc7OEBBjR4SyZ1Z3a',NULL,'a:1:{i:0;s:4:\"user\";}','2018-12-17 16:07:35','2018-12-17 16:07:35'),(19,'Patxi Molina','pmolina@ecenarro.com',NULL,'$2y$14$EcWGPnORV8/ZvPzM0FtjGeVLrQS0mLtF/Gqpl1DeDLmJKrqUTwMAq',NULL,'a:1:{i:0;s:4:\"user\";}','2018-12-17 16:08:03','2018-12-17 16:08:03'),(20,'Jon Arriaran','jarriaran@ecenarro.com',NULL,'$2y$14$jMRCkszHBdY.Ozz6g971pOy/jHOnwX6LmJs8tjPk8HhHlqyrn41ki',NULL,'a:1:{i:0;s:4:\"user\";}','2018-12-17 16:11:58','2018-12-17 16:11:58');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `process_op_type`
--

LOCK TABLES `process_op_type` WRITE;
/*!40000 ALTER TABLE `process_op_type` DISABLE KEYS */;
INSERT INTO `process_op_type` VALUES (8,'Corte','<p>Corte transversal del alambre ya enderezado a la longitud deseada, es importante el acabado de la zona deformada para evitar problemas posteriores.<br/></p>',1,'2018-12-16 17:50:37','2018-12-16 17:50:37'),(9,'Preparación',NULL,1,'2018-12-16 17:53:47','2018-12-16 17:53:47'),(10,'Extrusión Hacia Delante','<p>Forward extrusion</p>',1,'2018-12-16 17:54:31','2018-12-16 17:54:31'),(11,'Transporte',NULL,1,'2018-12-16 17:58:42','2018-12-16 17:58:42'),(12,'Recalcado',NULL,1,'2018-12-16 17:59:56','2018-12-16 17:59:56'),(13,'Punzonado (borratzekoa da)',NULL,1,'2018-12-16 18:15:25','2018-12-16 18:15:25'),(14,'Empuje',NULL,1,'2018-12-16 19:13:01','2018-12-16 19:13:01'),(15,'Frogak','<p>Frogak egiteko operazio taldea</p>',1,'2018-12-16 20:12:54','2018-12-16 20:12:54'),(16,'Extrusión Inversa','<p>Backward Extrusion, Taladrado, Punzonado<br/></p>',15,'2018-12-26 15:24:16','2018-12-26 15:24:16'),(17,'Recorte','<p>Arrastre  de material /Eliminación de material sobrante.</p>',15,'2018-12-26 15:59:25','2018-12-26 15:59:25');
/*!40000 ALTER TABLE `process_op_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `process_op_rel`
--

LOCK TABLES `process_op_rel` WRITE;
/*!40000 ALTER TABLE `process_op_rel` DISABLE KEYS */;
INSERT INTO `process_op_rel` VALUES (97,99),(98,99),(97,131);
/*!40000 ALTER TABLE `process_op_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `process_op`
--

LOCK TABLES `process_op` WRITE;
/*!40000 ALTER TABLE `process_op` DISABLE KEYS */;
INSERT INTO `process_op` VALUES (95,'Standard',NULL,8,1,'2018-12-16 17:51:24','2018-12-16 17:51:24'),(96,'En radio',NULL,9,1,'2018-12-16 17:53:54','2018-12-16 17:53:54'),(97,'Severa (50-70%)','<ul><li>No cilíndricas. (estrias, ranuras, cuadrados etc)</li><li>Con ángulo en vez de radio</li><li>Grandes longitudes de extrusión (L&gt;3d??)</li><li>Con estacion previa de preparación</li><li>Distribuida entre buterola/matriz<br/></li></ul>',10,1,'2018-12-16 17:54:54','2018-12-16 17:54:54'),(98,'En ángulo',NULL,10,1,'2018-12-16 17:55:25','2018-12-16 17:55:25'),(99,'Severa (50-70%) + En ángulo','<p>Deskripzio desbesdin bat batura honentzako</p>',10,1,'2018-12-16 17:55:44','2018-12-16 17:55:44'),(100,'Extracción','<p>Mecanismo que posibilita la extracción de la pieza de los utillajes.<br/></p>',11,1,'2018-12-16 17:59:05','2018-12-16 17:59:05'),(101,'Introducción','<p>Mecanismo que guia la introduccón de la pieza hasta su posición de conformado.<br/></p>',11,1,'2018-12-16 17:59:21','2018-12-16 17:59:21'),(102,'Sujeción','<p>Conjunto de elementos que sujetan la pieza al salir, meter y mover la pieza entre estaciones.<br/></p>',11,1,'2018-12-16 17:59:36','2018-12-16 17:59:36'),(103,'Matriz abierta','<p>Tipo de recalcado usando matrices segmentadas con geometrias con contrasalidas.<br/></p>',12,1,'2018-12-16 18:00:47','2018-12-16 18:00:47'),(104,'Abierto','<p>Aumentar la sección de una parte del material sin sujetar el material dentro de los utillajes. Límite de recalcado libre r=2<br/></p>',12,1,'2018-12-16 18:01:28','2018-12-16 18:01:28'),(105,'Cerrado','<p>Aumentar la seccion de una parte del material sujetando el material dentro de los utillajes. Límite de recalcado libre r=2<br/></p>',12,1,'2018-12-16 18:01:43','2018-12-16 18:01:43'),(108,'Grandes deformaciones',NULL,8,1,'2018-12-16 18:03:41','2018-12-16 18:03:41'),(109,'Escuadrado',NULL,9,1,'2018-12-16 18:04:02','2018-12-16 18:04:02'),(111,'Punzonado',NULL,9,1,'2018-12-16 18:04:36','2018-12-16 18:04:36'),(117,'De paso',NULL,11,1,'2018-12-16 19:10:05','2018-12-16 19:10:05'),(120,'Desde parte fija',NULL,14,1,'2018-12-16 19:13:13','2018-12-16 19:13:13'),(122,'Desde parte móvil',NULL,14,1,'2018-12-16 19:13:54','2018-12-16 19:13:54'),(129,'Standard (30-50%)',NULL,10,15,'2018-12-17 07:09:55','2018-12-17 07:09:55'),(131,'Severa (50-70%) + Directa (exterior)',NULL,10,1,'2018-12-17 15:16:13','2018-12-17 15:16:13'),(132,'Estandar',NULL,11,1,'2018-12-17 15:36:07','2018-12-17 15:36:07'),(157,'Cerrada','<p>Extrusión inversa con todo el material contenido</p>',16,15,'2018-12-26 15:51:31','2018-12-26 15:51:31'),(158,'Parcialmente cerrada','<p>Con herramienta flotante</p>',16,15,'2018-12-26 15:52:07','2018-12-26 15:52:07'),(159,'Abierta','<p>El material no está radialmente contenido. </p>',16,15,'2018-12-26 15:52:55','2018-12-26 15:52:55'),(160,'Recalcado de preforma',NULL,12,15,'2018-12-26 15:57:00','2018-12-26 15:57:00'),(161,'Recorte','<p>Eliminación del sobrante de material<br/></p>',17,15,'2018-12-26 16:00:24','2018-12-26 16:00:24'),(162,'Parcial','<p>Operación previa al recortado total donde se recorta material sin hacerlo pasante</p>',17,15,'2018-12-26 16:01:44','2018-12-26 16:01:44'),(163,'Piercing','<p>Eliminación de pepita</p>',17,15,'2018-12-26 16:10:21','2018-12-26 16:10:21');

/*!40000 ALTER TABLE `process_op` ENABLE KEYS */;
UNLOCK TABLES;

ALTER TABLE  `process_hint_type` ADD  `h_count` INT NOT NULL DEFAULT  '0' COMMENT  'error count' AFTER  `prior` ;

DROP TABLE IF EXISTS `process_hint_simulation`;
CREATE TABLE IF NOT EXISTS `process_hint_simulation` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `uid` int(11) NOT NULL,
	  `hint_id` int(11) NOT NULL,
	  `state` int(2) NOT NULL DEFAULT '0',
	  `who` text,
	  `whn` text,
	  `eff` text,
	  `prev` text,
	  `descr` text,
	  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
