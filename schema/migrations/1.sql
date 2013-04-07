UPDATE `version` SET `version` = 1;
ALTER TABLE `song_book`
	DROP FOREIGN KEY `FK_songs`,
	DROP FOREIGN KEY `FK_books`;
ALTER TABLE `spotify`
	DROP FOREIGN KEY `FK_examples_song`;
ALTER TABLE `book`
	ALTER `name` DROP DEFAULT;
ALTER TABLE `book`
	CHANGE COLUMN `id` `book_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST,
	CHANGE COLUMN `name` `book_title` VARCHAR(255) NOT NULL COLLATE 'utf8_danish_ci' AFTER `book_id`;
ALTER TABLE `song`
	ALTER `title` DROP DEFAULT;
ALTER TABLE `song`
	CHANGE COLUMN `id` `song_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST,
	CHANGE COLUMN `title` `song_title` VARCHAR(255) NOT NULL COLLATE 'utf8_danish_ci' AFTER `song_id`,
	CHANGE COLUMN `text` `song_text` TEXT NOT NULL COLLATE 'utf8_danish_ci' AFTER `song_title`;
ALTER TABLE `spotify`
	CHANGE COLUMN `url` `spotify_url` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_danish_ci' AFTER `song_id`,
	CHANGE COLUMN `artists` `spotify_artists` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_danish_ci' AFTER `spotify_url`;
ALTER TABLE `song_book`
	ADD CONSTRAINT `FK_books` 
		FOREIGN KEY (`book_id`) 
		REFERENCES `book` (`book_id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `FK_songs` 
		FOREIGN KEY (`song_id`) 
		REFERENCES `song` (`song_id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `spotify`
	ADD CONSTRAINT `FK_spotify_song` 
		FOREIGN KEY (`song_id`) 
		REFERENCES `song` (`song_id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;
