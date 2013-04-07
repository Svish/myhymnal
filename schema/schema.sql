DROP DATABASE IF EXISTS `hymnal`;
CREATE DATABASE IF NOT EXISTS `hymnal`;

ALTER DATABASE `hymnal`
	DEFAULT CHARACTER SET = 'utf8'
	DEFAULT COLLATE 'utf8_danish_ci';

USE `hymnal`;

CREATE TABLE IF NOT EXISTS `version`
(
  `version` int(10) UNSIGNED NOT NULL
)
ENGINE=InnoDB;
INSERT INTO `version` VALUES(0);

CREATE TABLE IF NOT EXISTS `book` 
(
	`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
)
ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `key`
(
	`key` char(2) NOT NULL,
	PRIMARY KEY (`key`)
)
ENGINE=InnoDB;
INSERT INTO `key` (`key`) VALUES
	('A'),('A♭'),('A♯'),
	('B'),('B♭'),('B♯'),
	('C'),('C♭'),('C♯'),
	('D'),('D♭'),('D♯'),
	('E'),('E♭'),('E♯'),
	('F'),('F♭'),('F♯'),
	('G'),('G♭'),('G♯');

CREATE TABLE IF NOT EXISTS `song`
(
	`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`title` varchar(255) NOT NULL,
	`text` text NOT NULL,
	`key` char(2) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `FK_song_key` (`key`),
	CONSTRAINT `FK_song_key` FOREIGN KEY (`key`) REFERENCES `key` (`key`)
)
ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `song_book`
(
	`song_id` int(10) UNSIGNED NOT NULL,
	`book_id` int(10) UNSIGNED NOT NULL,
	`number` smallint(5) UNSIGNED NOT NULL,
	PRIMARY KEY (`book_id`,`song_id`),
	UNIQUE KEY `book_id_number` (`book_id`,`number`),
	KEY `FK_songs` (`song_id`),
	CONSTRAINT `FK_books` 
		FOREIGN KEY (`book_id`) 
		REFERENCES `book` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT `FK_songs` 
		FOREIGN KEY (`song_id`) 
		REFERENCES `song` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `spotify`
(
	`spotify_id` varchar(255) NOT NULL,
	`song_id` int(10) UNSIGNED NOT NULL,
	`url` varchar(255) DEFAULT NULL,
	`artists` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`song_id`,`spotify_id`),
	KEY `FK_examples_song` (`song_id`),
	CONSTRAINT `FK_examples_song` 
		FOREIGN KEY (`song_id`) 
		REFERENCES `song` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB;
