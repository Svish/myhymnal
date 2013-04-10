ALTER TABLE `song`
	CHANGE COLUMN `key` `key` CHAR(2) NULL DEFAULT NULL COLLATE 'utf8_danish_ci' AFTER `song_text`;
