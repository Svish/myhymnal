ALTER TABLE `song`
	ADD COLUMN `song_lastmod` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `song_id`;

UPDATE `song` SET `song_lastmod` = CURRENT_TIMESTAMP;
