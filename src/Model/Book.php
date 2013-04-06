<?php

/**
 * Books where songs can be found.
 */
class Model_Book extends Model
{
	public function __get($var)
	{
		switch($var)
		{
			case 'songs':
				if( ! parent::__isset('songs'))
					$this->songs = Model_Song::find_in_book($this->id);
				return parent::__get('songs');

			default:
				return parent::__get($var);
		}
	}

	public function __isset($var)
	{
		switch($var)
		{
			case 'songs':
				return TRUE;

			default:
				return parent::__isset($var);
		}
	}


	public static function get($id)
	{
		return DB::query('SELECT * FROM book WHERE id=:id')
			->bindParam(':id', $id, PDO::PARAM_INT)
			->execute()
			->fetch(__CLASS__);

		return $book;
	}


	public static function find_all()
	{
		return DB::query('SELECT id, name, COUNT(book_id) AS "count"
							FROM book
							LEFT OUTER JOIN song_book ON book.id = song_book.book_id
							GROUP BY id
							ORDER BY name')
			->execute()
			->fetchAll(__CLASS__);
	}

	public static function find_with_song($song_id)
	{
		return DB::query('SELECT book.id, book.name, song_book.number
							FROM song_book
							INNER JOIN book ON song_book.book_id = book.id
							WHERE song_book.song_id = :id
							ORDER BY book.name')
			->bindValue(':id', $song_id, PDO::PARAM_INT)
			->execute()
			->fetchAll('Model_Song');
	}
}