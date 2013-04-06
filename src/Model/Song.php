<?php

class Model_Song extends Model
{
	private function __construct($load_foreign = TRUE)
	{
		if( ! $load_foreign)
			return;

		$books = Model_Book::find_with_song($this->id);
		if($books)
			$this->books = array('list' => $books);

		$examples = Model_Example::find_for_song($this->id);
		if($examples)
			$this->examples = array('list' => $examples);
	}


	public static function get($id)
	{
		return DB::query('SELECT * 
							FROM song 
							WHERE id=:id')
			->bindParam(':id', $id, PDO::PARAM_INT)
			->execute()
			->fetch(__CLASS__);
	}

	public static function find_all()
	{

		return DB::query('SELECT id, title 
							FROM song 
							WHERE text IS NOT NULL
							ORDER BY title')
			->execute()
			->fetchAll(__CLASS__, array(FALSE));
	}

	public static function find_in_book($book_id)
	{
		return DB::query('SELECT song.id, song.title, song_book.number
							FROM song_book
							INNER JOIN song ON song_book.song_id = song.id
							WHERE song_book.book_id = :id
							ORDER BY song_book.number')
			->bindValue(':id', $book_id, PDO::PARAM_INT)
			->execute()
			->fetchAll(__CLASS__, array(FALSE));
	}

	public static function search($term)
	{
		return DB::query('SELECT id, title 
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
	}
}