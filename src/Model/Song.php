<?php

class Model_Song extends Model
{
	private function __construct($load_foreign = TRUE)
	{
		Timer::start(__METHOD__, array($load_foreign ? 'with foreign' : 'no foreign'));
		if($load_foreign)
		{
			$books = Model_Book::find_with_song($this->id);
			if($books)
				$this->books = array('list' => $books);
	
			$spotify = Model_Spotify::find_for_song($this->id);
			if($spotify)
				$this->spotify = array('list' => $spotify);
	
			$next = self::get_next($this->title);
			if($next)
				$this->next = $next;
	
			$prev = self::get_prev($this->title);
			if($prev)
				$this->prev = $prev;
		}
		Timer::stop();
	}


	public static function get($id)
	{
		Timer::start(__METHOD__, func_get_args());
		$song = DB::query('SELECT * 
							FROM song 
							WHERE id=:id')
			->bindParam(':id', $id, PDO::PARAM_INT)
			->execute()
			->fetch(__CLASS__);
		Timer::stop();
		return $song;
	}

	public static function get_next($title)
	{
		Timer::start(__METHOD__, func_get_args());
		$song = DB::query('SELECT id, title 
							FROM song
							WHERE title>:title
							ORDER BY title
							LIMIT 1')
			->bindParam(':title', $title)
			->execute()
			->fetch(__CLASS__, array(FALSE));
		Timer::stop();
		return $song;
	}
	public static function get_prev($title)
	{
		Timer::start(__METHOD__, func_get_args());
		$song = DB::query('SELECT id, title 
							FROM song
							WHERE title<:title
							ORDER BY title DESC
							LIMIT 1')
			->bindParam(':title', $title)
			->execute()
			->fetch(__CLASS__, array(FALSE));
		Timer::stop();
		return $song;
	}

	public static function find_all()
	{
		Timer::start(__METHOD__);
		$songs = DB::query('SELECT id, title 
							FROM song 
							WHERE text IS NOT NULL
							ORDER BY title')
			->execute()
			->fetchAll(__CLASS__, array(FALSE));
		Timer::stop();
		return $songs;
	}

	public static function find_in_book($book_id)
	{
		Timer::start(__METHOD__, func_get_args());
		$songs = DB::query('SELECT song.id, song.title, song_book.number
							FROM song_book
							INNER JOIN song ON song_book.song_id = song.id
							WHERE song_book.book_id = :id
							ORDER BY song_book.number')
			->bindValue(':id', $book_id, PDO::PARAM_INT)
			->execute()
			->fetchAll(__CLASS__, array(FALSE));
		Timer::stop();
		return $songs;
	}

	public static function search($term)
	{
		Timer::start(__METHOD__, func_get_args());
		$songs = DB::query('SELECT id, title 
							FROM song 
							WHERE text IS NOT NULL
							  AND title LIKE ? 
							ORDER BY 
								CASE WHEN title LIKE ? THEN 1 ELSE 2 END, 
								title')
			->bindValue(1, '%'.$term.'%')
			->bindValue(2, $term.'%')
			->execute()
			->fetchAll(__CLASS__, array(FALSE));
		Timer::stop();
		return $songs;
	}
}