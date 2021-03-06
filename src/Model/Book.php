<?php

/**
 * Books where songs can be found.
 */
class Model_Book extends Model
{
	public function __construct($load_foreign = TRUE)
	{
		$this->url = 'book/'.$this->id.'/'.$this->slug;

		if($load_foreign)
		{
			$songs = Model_Song::list_in_book($this->id);
			if($songs)
				$this->songs = array('list' => $songs);
		}
	}

	public static function get($id)
	{
		Timer::start(__METHOD__, func_get_args());
		$book = DB::prepare('SELECT 
									book_id "id", 
									book_title "title", 
									book_slug "slug"
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
								book.book_title "title", 
								book.book_total "total",
								book.book_slug "slug",
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
		$books = DB::prepare('SELECT 
								book.book_id "id", 
								book.book_title "title", 
								book.book_slug "slug",
								number
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
}