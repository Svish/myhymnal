ALTER TABLE `song`
	ADD COLUMN `song_slug` VARCHAR(255) NOT NULL AFTER `song_title`;
