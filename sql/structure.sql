-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 17, 2019 at 06:55 PM
-- Server version: 5.7.26-0ubuntu0.16.04.1
-- PHP Version: 7.0.33-5+ubuntu14.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `drbfm`
--
CREATE DATABASE `drbfm` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `drbfm`;

-- --------------------------------------------------------

--
-- Table structure for table `action`
--

CREATE TABLE `action` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `src_id` int(11) NOT NULL COMMENT 'source',
  `name` varchar(255) NOT NULL,
  `content` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `discr` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `src_id` int(11) NOT NULL COMMENT 'source',
  `prt_id` int(11) DEFAULT NULL COMMENT 'parent',
  `body` text NOT NULL,
  `cmm_c` int(4) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `discr` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comment_suscriber_rel`
--

CREATE TABLE `comment_suscriber_rel` (
  `cmm_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `phone` varchar(64) NOT NULL,
  `contact` varchar(64) NOT NULL,
  `uid` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `descr` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `src_id` int(11) DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `size` int(6) NOT NULL,
  `descr` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `discr` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `note`
--

CREATE TABLE `note` (
  `id` int(11) NOT NULL,
  `text` text NOT NULL,
  `src_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `cmm_c` int(4) NOT NULL DEFAULT '0' COMMENT 'comment count',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `discr` varchar(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `sid` int(11) NOT NULL COMMENT 'subscriber',
  `a_id` int(11) NOT NULL COMMENT 'action',
  `r` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process`
--

CREATE TABLE `process` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `number` varchar(24) NOT NULL,
  `line` int(2) NOT NULL,
  `code` varchar(24) NOT NULL,
  `tpl` int(1) DEFAULT '0' COMMENT 'is template',
  `plt_id` int(11) NOT NULL COMMENT 'productive plant',
  `mch_id` int(11) NOT NULL COMMENT 'machine',
  `p_num` varchar(64) NOT NULL COMMENT 'piece number',
  `p_name` varchar(64) NOT NULL COMMENT 'piece name',
  `cpl_id` int(11) NOT NULL COMMENT 'complexity',
  `ctm_id` int(11) NOT NULL COMMENT 'customer',
  `body` text,
  `uid` int(11) NOT NULL COMMENT 'owner',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_action`
--

CREATE TABLE `process_action` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `pcs_id` int(11) NOT NULL COMMENT 'process',
  `src_id` int(11) NOT NULL COMMENT 'source',
  `name` varchar(255) NOT NULL,
  `content` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `discr` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_complexity`
--

CREATE TABLE `process_complexity` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `state` int(2) NOT NULL DEFAULT '0',
  `descr` text,
  `uid` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_hint`
--

CREATE TABLE `process_hint` (
  `id` int(11) NOT NULL,
  `stg_id` int(11) NOT NULL COMMENT 'stage',
  `type_id` int(11) DEFAULT NULL COMMENT 'error type',
  `uid` int(11) NOT NULL COMMENT 'owner',
  `prior` int(2) NOT NULL DEFAULT '1' COMMENT 'priority',
  `text` varchar(255) DEFAULT NULL,
  `who` varchar(255) DEFAULT NULL COMMENT 'who modelizes',
  `whn` timestamp NULL DEFAULT NULL COMMENT 'when modelizes',
  `eff` text COMMENT 'effect',
  `prev` text COMMENT 'prevention',
  `state` int(2) NOT NULL DEFAULT '0' COMMENT 'modelize state',
  `descr` text,
  `cmm_c` int(4) NOT NULL DEFAULT '0' COMMENT 'comment count',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_hint_context`
--

CREATE TABLE `process_hint_context` (
  `id` int(11) NOT NULL,
  `hint_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `cmm_c` int(4) NOT NULL DEFAULT '0' COMMENT 'comment count',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_hint_influence`
--

CREATE TABLE `process_hint_influence` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT 'user',
  `rsn_id` int(11) NOT NULL COMMENT 'reason',
  `cmm_c` int(4) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_hint_reason`
--

CREATE TABLE `process_hint_reason` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `hint_id` int(11) NOT NULL,
  `cmm_c` int(4) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_hint_rel`
--

CREATE TABLE `process_hint_rel` (
  `parent_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_hint_relation`
--

CREATE TABLE `process_hint_relation` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `src_id` int(11) NOT NULL COMMENT 'reason source',
  `rel_id` int(11) NOT NULL COMMENT 'influence rel',
  `descr` text,
  `cmm_c` int(4) NOT NULL DEFAULT '0' COMMENT 'comment count',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_hint_simulation`
--

CREATE TABLE `process_hint_simulation` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `src_id` int(11) NOT NULL COMMENT 'influence',
  `state` int(2) NOT NULL DEFAULT '0',
  `who` int(11) DEFAULT NULL,
  `whn` text,
  `eff` text,
  `prev` text,
  `descr` text,
  `cmm_c` int(4) NOT NULL DEFAULT '0' COMMENT 'comment count',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_hint_type`
--

CREATE TABLE `process_hint_type` (
  `id` int(11) NOT NULL,
  `op_id` int(11) NOT NULL COMMENT 'operation',
  `std` int(1) NOT NULL DEFAULT '0' COMMENT 'is standard',
  `prior` int(2) NOT NULL DEFAULT '0' COMMENT 'priority',
  `h_count` int(11) NOT NULL DEFAULT '0' COMMENT 'error count',
  `title` varchar(255) NOT NULL,
  `descr` text,
  `uid` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_machine`
--

CREATE TABLE `process_machine` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `state` int(2) NOT NULL DEFAULT '0',
  `descr` text,
  `uid` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_material`
--

CREATE TABLE `process_material` (
  `id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `prior` int(2) NOT NULL DEFAULT '0',
  `descr` text,
  `uid` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_op`
--

CREATE TABLE `process_op` (
  `id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `descr` text,
  `type_id` int(11) NOT NULL COMMENT 'operation type',
  `uid` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_op_rel`
--

CREATE TABLE `process_op_rel` (
  `parent_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_op_stg_rel`
--

CREATE TABLE `process_op_stg_rel` (
  `op_id` int(11) NOT NULL,
  `stg_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_op_type`
--

CREATE TABLE `process_op_type` (
  `id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `descr` text,
  `uid` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_plant`
--

CREATE TABLE `process_plant` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `state` int(2) NOT NULL DEFAULT '0',
  `descr` text,
  `uid` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_stage`
--

CREATE TABLE `process_stage` (
  `id` int(11) NOT NULL,
  `body` text,
  `v_id` int(11) NOT NULL COMMENT 'version',
  `ord` int(4) NOT NULL DEFAULT '0' COMMENT 'order',
  `uid` int(11) NOT NULL COMMENT 'owner',
  `cmm_c` int(4) NOT NULL DEFAULT '0' COMMENT 'comment count',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_version`
--

CREATE TABLE `process_version` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `prt_id` int(11) DEFAULT NULL COMMENT 'parent version',
  `type_id` int(11) NOT NULL COMMENT 'version type',
  `prc_id` int(11) NOT NULL COMMENT 'process',
  `mtl_id` int(11) NOT NULL COMMENT 'material',
  `uid` int(11) NOT NULL,
  `state` int(4) NOT NULL DEFAULT '0',
  `descr` text,
  `cmm_c` int(4) NOT NULL DEFAULT '0' COMMENT 'comment count',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_version_type`
--

CREATE TABLE `process_version_type` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `descr` text,
  `uid` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `display_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `state` smallint(6) DEFAULT NULL,
  `roles` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `action`
--
ALTER TABLE `action`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent` (`prt_id`),
  ADD KEY `source` (`src_id`),
  ADD KEY `user` (`uid`);

--
-- Indexes for table `comment_suscriber_rel`
--
ALTER TABLE `comment_suscriber_rel`
  ADD UNIQUE KEY `suscription` (`cmm_id`,`uid`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `source` (`src_id`),
  ADD KEY `user` (`uid`);

--
-- Indexes for table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`id`),
  ADD KEY `source` (`src_id`),
  ADD KEY `user` (`uid`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`a_id`),
  ADD KEY `subscriber` (`sid`),
  ADD KEY `read` (`r`);

--
-- Indexes for table `process`
--
ALTER TABLE `process`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`uid`),
  ADD KEY `customer` (`ctm_id`),
  ADD KEY `plant` (`plt_id`),
  ADD KEY `machine` (`mch_id`);

--
-- Indexes for table `process_action`
--
ALTER TABLE `process_action`
  ADD PRIMARY KEY (`id`),
  ADD KEY `src-process` (`pcs_id`,`src_id`,`discr`),
  ADD KEY `process` (`pcs_id`),
  ADD KEY `source` (`src_id`),
  ADD KEY `discr` (`discr`),
  ADD KEY `user` (`uid`);

--
-- Indexes for table `process_complexity`
--
ALTER TABLE `process_complexity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `process_hint`
--
ALTER TABLE `process_hint`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stg_id` (`stg_id`),
  ADD KEY `user` (`uid`),
  ADD KEY `error_type` (`type_id`);

--
-- Indexes for table `process_hint_context`
--
ALTER TABLE `process_hint_context`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `process_hint_influence`
--
ALTER TABLE `process_hint_influence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reason` (`rsn_id`),
  ADD KEY `user` (`uid`);

--
-- Indexes for table `process_hint_reason`
--
ALTER TABLE `process_hint_reason`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hint` (`hint_id`),
  ADD KEY `user` (`uid`);

--
-- Indexes for table `process_hint_rel`
--
ALTER TABLE `process_hint_rel`
  ADD UNIQUE KEY `rel` (`parent_id`,`child_id`),
  ADD KEY `child_id` (`child_id`);

--
-- Indexes for table `process_hint_relation`
--
ALTER TABLE `process_hint_relation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`uid`),
  ADD KEY `source` (`src_id`),
  ADD KEY `relation` (`rel_id`);

--
-- Indexes for table `process_hint_simulation`
--
ALTER TABLE `process_hint_simulation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`uid`),
  ADD KEY `source` (`src_id`);

--
-- Indexes for table `process_hint_type`
--
ALTER TABLE `process_hint_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operation` (`op_id`);

--
-- Indexes for table `process_machine`
--
ALTER TABLE `process_machine`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `process_material`
--
ALTER TABLE `process_material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `process_op`
--
ALTER TABLE `process_op`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operation_type` (`type_id`),
  ADD KEY `user` (`uid`);

--
-- Indexes for table `process_op_rel`
--
ALTER TABLE `process_op_rel`
  ADD UNIQUE KEY `rel` (`parent_id`,`child_id`),
  ADD KEY `child_id` (`child_id`);

--
-- Indexes for table `process_op_stg_rel`
--
ALTER TABLE `process_op_stg_rel`
  ADD UNIQUE KEY `op_stg_rel` (`op_id`,`stg_id`);

--
-- Indexes for table `process_op_type`
--
ALTER TABLE `process_op_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`uid`);

--
-- Indexes for table `process_plant`
--
ALTER TABLE `process_plant`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `process_stage`
--
ALTER TABLE `process_stage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `version` (`v_id`),
  ADD KEY `user` (`uid`);

--
-- Indexes for table `process_version`
--
ALTER TABLE `process_version`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent` (`mtl_id`),
  ADD KEY `material` (`mtl_id`),
  ADD KEY `type` (`type_id`),
  ADD KEY `process` (`prc_id`),
  ADD KEY `user` (`uid`);

--
-- Indexes for table `process_version_type`
--
ALTER TABLE `process_version_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `action`
--
ALTER TABLE `action`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=306;
--
-- AUTO_INCREMENT for table `note`
--
ALTER TABLE `note`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=707;
--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `process`
--
ALTER TABLE `process`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT for table `process_action`
--
ALTER TABLE `process_action`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1221;
--
-- AUTO_INCREMENT for table `process_complexity`
--
ALTER TABLE `process_complexity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `process_hint`
--
ALTER TABLE `process_hint`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=347;
--
-- AUTO_INCREMENT for table `process_hint_context`
--
ALTER TABLE `process_hint_context`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `process_hint_influence`
--
ALTER TABLE `process_hint_influence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=291;
--
-- AUTO_INCREMENT for table `process_hint_reason`
--
ALTER TABLE `process_hint_reason`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=337;
--
-- AUTO_INCREMENT for table `process_hint_relation`
--
ALTER TABLE `process_hint_relation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `process_hint_simulation`
--
ALTER TABLE `process_hint_simulation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;
--
-- AUTO_INCREMENT for table `process_hint_type`
--
ALTER TABLE `process_hint_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;
--
-- AUTO_INCREMENT for table `process_machine`
--
ALTER TABLE `process_machine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `process_material`
--
ALTER TABLE `process_material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `process_op`
--
ALTER TABLE `process_op`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `process_op_type`
--
ALTER TABLE `process_op_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `process_plant`
--
ALTER TABLE `process_plant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `process_stage`
--
ALTER TABLE `process_stage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=368;
--
-- AUTO_INCREMENT for table `process_version`
--
ALTER TABLE `process_version`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;
--
-- AUTO_INCREMENT for table `process_version_type`
--
ALTER TABLE `process_version_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`prt_id`) REFERENCES `comment` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `image_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `note_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `notification_ibfk_2` FOREIGN KEY (`a_id`) REFERENCES `process_action` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `process`
--
ALTER TABLE `process`
  ADD CONSTRAINT `process_ibfk_1` FOREIGN KEY (`ctm_id`) REFERENCES `customer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `process_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `process_ibfk_3` FOREIGN KEY (`plt_id`) REFERENCES `process_plant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `process_ibfk_4` FOREIGN KEY (`mch_id`) REFERENCES `process_machine` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `process_action`
--
ALTER TABLE `process_action`
  ADD CONSTRAINT `process_action_ibfk_1` FOREIGN KEY (`pcs_id`) REFERENCES `process` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `process_action_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `process_hint`
--
ALTER TABLE `process_hint`
  ADD CONSTRAINT `process_hint_ibfk_1` FOREIGN KEY (`stg_id`) REFERENCES `process_stage` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `process_hint_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `process_hint_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `process_hint_ibfk_3` FOREIGN KEY (`uid`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `process_hint_influence`
--
ALTER TABLE `process_hint_influence`
  ADD CONSTRAINT `process_hint_influence_ibfk_1` FOREIGN KEY (`rsn_id`) REFERENCES `process_hint_reason` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `process_hint_influence_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `process_hint_reason`
--
ALTER TABLE `process_hint_reason`
  ADD CONSTRAINT `process_hint_reason_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `process_hint_reason_ibfk_2` FOREIGN KEY (`hint_id`) REFERENCES `process_hint` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `process_hint_rel`
--
ALTER TABLE `process_hint_rel`
  ADD CONSTRAINT `process_hint_rel_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `process_hint` (`id`),
  ADD CONSTRAINT `process_hint_rel_ibfk_2` FOREIGN KEY (`child_id`) REFERENCES `process_hint` (`id`);

--
-- Constraints for table `process_hint_relation`
--
ALTER TABLE `process_hint_relation`
  ADD CONSTRAINT `process_hint_relation_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `process_hint_relation_ibfk_2` FOREIGN KEY (`src_id`) REFERENCES `process_hint_reason` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `process_hint_relation_ibfk_3` FOREIGN KEY (`rel_id`) REFERENCES `process_hint_influence` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `process_hint_simulation`
--
ALTER TABLE `process_hint_simulation`
  ADD CONSTRAINT `process_hint_simulation_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `process_hint_simulation_ibfk_2` FOREIGN KEY (`src_id`) REFERENCES `process_hint_influence` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `process_hint_type`
--
ALTER TABLE `process_hint_type`
  ADD CONSTRAINT `process_hint_type_ibfk_1` FOREIGN KEY (`op_id`) REFERENCES `process_op` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `process_op`
--
ALTER TABLE `process_op`
  ADD CONSTRAINT `process_op_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `process_op_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `process_op_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `process_op_rel`
--
ALTER TABLE `process_op_rel`
  ADD CONSTRAINT `process_op_rel_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `process_op` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `process_op_rel_ibfk_2` FOREIGN KEY (`child_id`) REFERENCES `process_op` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `process_op_type`
--
ALTER TABLE `process_op_type`
  ADD CONSTRAINT `process_op_type_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `process_stage`
--
ALTER TABLE `process_stage`
  ADD CONSTRAINT `process_stage_ibfk_1` FOREIGN KEY (`v_id`) REFERENCES `process_version` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `process_stage_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `process_version`
--
ALTER TABLE `process_version`
  ADD CONSTRAINT `process_version_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `process_version_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `process_version_ibfk_2` FOREIGN KEY (`prc_id`) REFERENCES `process` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `process_version_ibfk_3` FOREIGN KEY (`mtl_id`) REFERENCES `process_material` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `process_version_ibfk_4` FOREIGN KEY (`uid`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `email`, `display_name`, `password`, `state`, `roles`) VALUES
('Admin', 'admin.opendrbfm@iurretalhi.eus', NULL, '$2y$14$nlhr6mXbR7QTkNB0ahYh3O9bvv5rRfvMSDyNxu4EdRJkrFl.TLt7S', NULL, 'a:1:{i:0;s:5:"admin";}');
