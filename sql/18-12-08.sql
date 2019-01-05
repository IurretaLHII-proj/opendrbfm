USE ma_drbfm;

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

