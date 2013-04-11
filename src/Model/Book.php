<?php

/**
 * Books where songs can be found.
 */
class Model_Book extends Model
{
	public function __construct($load_foreign = TRUE)
	{
		Timer::start(__METHOD__, array($this->id, $load_foreign ? 'with foreign' : 'no foreign'));

		if($load_foreign)
		{
			$songs = Model_Song::find_in_book($this->id);
			if($songs)
				$this->songs = array('list' => $songs);
		}
		Timer::stop();
	}

	public static function get($id)
	{
		Timer::start(__METHOD__, func_get_args());
		$book = DB::prepare('SELECT book_id "id", book_title "title"
								FROM book 
								WHERE book_id=:id')
			->bindParam(':id', $id, PDO::PARAM_INT)
			->execute()
			->fetch(__CLASS__);
		Timer::stop();
		return $book;
	}


	public static function find_all()
	{
		Timer::start(__METHOD__);
		$books = DB::prepare('SELECT
								book.book_id "id", 
								book_title "title", 
								book_total "total",
								COUNT(book.book_id) AS "count"
							FROM book
							LEFT OUTER JOIN song_book ON book.book_id = song_book.book_id
							GROUP BY book.book_id
							ORDER BY book.book_title')
			->execute()
			->fetchAll(__CLASS__, array(FALSE));
		Timer::stop();
		return $books;
	}

	public static function find_with_song($song_id)
	{
		Timer::start(__METHOD__, func_get_args());
		$books = DB::prepare('SELECT book.book_id "id", book.book_title "title", number
							FROM song_book
							INNER JOIN book ON song_book.book_id = book.book_id
							WHERE song_id = :id
							ORDER BY book_title')
			->bindValue(':id', $song_id, PDO::PARAM_INT)
			->execute()
			->fetchAll(__CLASS__, array(FALSE));
		Timer::stop();
		return $books;
	}


	private function generate_slug()
	{
		Timer::start(__METHOD__, array($this->id));
		
		$this->slug = Util::toAscii($this->title, array("'"));
		
		DB::prepare('UPDATE book
					SET book_slug=:slug
					WHERE book_id=:id')
			->bindValue(':id', $this->id, PDO::PARAM_INT)
			->bindValue(':slug', $this->slug)
			->execute();
		Timer::stop();
	}
}