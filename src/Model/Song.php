<?php

class Model_Song extends Model
{
	public static function get($id)
	{
		$query = DB::instance()->prepare('SELECT * FROM song WHERE id=:id');
		$query->bindParam(':id', $id, PDO::PARAM_INT);
		$query->execute();
		return $query->fetchObject(__CLASS__);
	}

	public static function get_list($term = NULL)
	{
		$query = DB::instance()->prepare('SELECT id, title 
											FROM song 
											WHERE text IS NOT NULL
											ORDER BY title');
		$query->execute();
		return $query->fetchAll(PDO::FETCH_CLASS, __CLASS__);
	}

	public static function search($term)
	{
		$query = DB::instance()->prepare('SELECT id, title 
											FROM song 
											WHERE text IS NOT NULL
											  AND title LIKE ? 
											ORDER BY 
												CASE WHEN title LIKE ? THEN 1 ELSE 2 END, 
												title');

		$query->bindValue(1, '%'.$term.'%', PDO::PARAM_STR);
		$query->bindValue(2, $term.'%', PDO::PARAM_STR);

		$query->execute();
		return $query->fetchAll(PDO::FETCH_CLASS, __CLASS__);
	}
}