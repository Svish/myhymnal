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

	public static function get_list()
	{
		$query = DB::instance()->prepare('SELECT id, title FROM song WHERE text IS NOT NULL ORDER BY title');
		$query->execute();
		return $query->fetchAll(PDO::FETCH_CLASS, __CLASS__);
	}
}