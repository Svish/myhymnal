ALTER TABLE `book`
	ADD COLUMN `book_slug` VARCHAR(255) NOT NULL AFTER `book_title`;
