<?php

class Model_Song extends Model
{
	private function __construct($load_foreign = TRUE)
	{
		Timer::start(__METHOD__, array($this->id, $load_foreign ? 'with foreign' : 'no foreign'));
		
		// Set URL
		$this->url = $this->id.'/'.$this->slug;

		if($load_foreign)
		{
			$books = Model_Book::find_with_song($this->id);
			if($books)
				$this->books = array('list' => $books);
	
			$spotify = Model_Spotify::find_for_song($this->id);
			if($spotify)
				$this->spotify = array('list' => $spotify);
		}
		Timer::stop();
	}

	public function browse()
	{
		return array
		(
			'next' => self::get_next($this->title),
			'prev' => self::get_prev($this->title),
			'rand' => self::get_rand(array($this->id)),
		);
	}


	public static function get($id)
	{
		Timer::start(__METHOD__, func_get_args());
		$song = DB::prepare('SELECT 
								song_id "id", 
								song_title "title", 
								song_slug "slug",
								song_text "text", 
								`key`
							FROM song 
							WHERE song_id=:id')
			->bindParam(':id', $id, PDO::PARAM_INT)
			->execute()
			->fetch(__CLASS__);
		Timer::stop();
		return $song;
	}

	public static function get_next($title)
	{
		Timer::start(__METHOD__, func_get_args());
		$song = DB::prepare('SELECT song_id "id", song_title "title", song_slug "slug"
							FROM song
							WHERE song_title > :title
								AND `key` IS NOT NULL
							ORDER BY song_title
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
		$song = DB::prepare('SELECT song_id "id", song_title "title", song_slug "slug"
							FROM song
							WHERE song_title < :title
								AND `key` IS NOT NULL
							ORDER BY song_title DESC
							LIMIT 1')
			->bindParam(':title', $title)
			->execute()
			->fetch(__CLASS__, array(FALSE));
		Timer::stop();
		return $song;
	}
	public static function get_rand(array $except)
	{
		Timer::start(__METHOD__, $except);
		foreach($except as &$e)
			$e = (int) $e;
		$song = DB::prepare('SELECT song_id "id", song_title "title", song_slug "slug"
							FROM song
							WHERE song_id NOT IN ('.implode(',',$except).')
								AND `key` IS NOT NULL
							ORDER BY RAND()
							LIMIT 1')
			->execute()
			->fetch(__CLASS__, array(FALSE));
		Timer::stop();
		return $song;
	}

	public static function find_all($finished = NULL)
	{
		if($finished === TRUE)
			$q = 'WHERE `key` IS NOT NULL';
		elseif($finished === FALSE)
			$q = 'WHERE `key` IS NULL';
		else
			$q = '';

		Timer::start(__METHOD__);
		$songs = DB::prepare('SELECT song_id "id", song_title "title", song_slug "slug"
							FROM song
							'.$q.'
							ORDER BY song_title')
			->execute()
			->fetchAll(__CLASS__, array(FALSE));
		Timer::stop();
		return $songs;
	}

	public static function find_in_book($book_id)
	{
		Timer::start(__METHOD__, func_get_args());
		$songs = DB::prepare('SELECT 
								song.song_id "id",
								song_title "title",
								song_slug "slug", 
								number "number"
							FROM song_book
							INNER JOIN song ON song_book.song_id = song.song_id
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
		if(is_numeric($term))
		{
			$songs = DB::prepare('SELECT song.song_id "id", song.song_slug "slug", book.book_title "title"
								FROM song
								INNER JOIN song_book ON song_book.song_id = song.song_id
								INNER JOIN book ON song_book.book_id = book.book_id
								WHERE number = :number
								ORDER BY book.book_title')
				->bindValue(':number', $term, PDO::PARAM_INT)
				->execute()
				->fetchAll();
		}
		else
		{
			$songs = DB::prepare('SELECT song_id "id", song_title "title", song_slug "slug"
								FROM song 
								WHERE song_title LIKE ? 
								ORDER BY 
									CASE WHEN song_title LIKE ? THEN 1 ELSE 2 END, 
									song_title')
				->bindValue(1, '%'.$term.'%')
				->bindValue(2, $term.'%')
				->execute()
				->fetchAll(__CLASS__, array(FALSE));
		}
		Timer::stop();
		return $songs;
	}
}