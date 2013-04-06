<?php

class Model_Book extends Model
{
	public static function get($id)
	{
		$query = DB::instance()->prepare('SELECT * FROM book WHERE id=:id');
		$query->bindParam(':id', $id, PDO::PARAM_INT);
		$query->execute();

		$book = $query->fetchObject(__CLASS__);

		$query = DB::instance()->prepare('SELECT song_book.number, song.id, song.title
											FROM song_book
											INNER JOIN song ON song_book.song_id = song.id
											WHERE song_book.book_id = :id
											ORDER BY song_book.number');
		$query->bindParam(':id', $id, PDO::PARAM_INT);
		$query->execute();
		$book->songs = $query->fetchAll(PDO::FETCH_CLASS, 'Model_Song');

		return $book;
	}

	public static function get_list()
	{
		$query = DB::instance()->prepare('SELECT id, name, COUNT(book_id) AS "count"
											FROM book
											LEFT OUTER JOIN song_book ON book.id = song_book.book_id
											GROUP BY id
											ORDER BY name');
		$query->execute();
		return $query->fetchAll(PDO::FETCH_CLASS, __CLASS__);
	}
}