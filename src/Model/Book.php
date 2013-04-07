<?php

/**
 * Books where songs can be found.
 */
class Model_Book extends Model
{
	public function __construct($load_foreign = TRUE)
	{
		if( ! $load_foreign)
			return;

		$songs = Model_Song::find_in_book($this->id);
		if($songs)
			$this->songs = array('list' => $songs);
	}

	public static function get($id)
	{
		Timer::start(__METHOD__, func_get_args());
		$book = DB::query('SELECT * FROM book WHERE id=:id')
			->bindParam(':id', $id, PDO::PARAM_INT)
			->execute()
			->fetch(__CLASS__);
		Timer::stop();
		return $book;
	}


	public static function find_all()
	{
		Timer::start(__METHOD__);
		$books = DB::query('SELECT id, name, COUNT(book_id) AS "count"
							FROM book
							LEFT OUTER JOIN song_book ON book.id = song_book.book_id
							GROUP BY id
							ORDER BY name')
			->execute()
			->fetchAll(__CLASS__, array(FALSE));
		Timer::stop();
		return $books;
	}

	public static function find_with_song($song_id)
	{
		Timer::start(__METHOD__, func_get_args());
		$books = DB::query('SELECT book.id, book.name, song_book.number
							FROM song_book
							INNER JOIN book ON song_book.book_id = book.id
							WHERE song_book.song_id = :id
							ORDER BY book.name')
			->bindValue(':id', $song_id, PDO::PARAM_INT)
			->execute()
			->fetchAll(__CLASS__, array(FALSE));
		Timer::stop();
		return $books;
	}
}