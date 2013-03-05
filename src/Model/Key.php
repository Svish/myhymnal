<?php

class Model_Key extends Model
{
	public static function get_all()
	{
		$query = DB::instance()->prepare('SELECT * FROM `key`');
		$query->execute();
		return $query->fetchAll(PDO::FETCH_CLASS, __CLASS__);
	}

	public static function get_map()
	{
		$f = "
		SELECT id, `key`
		FROM `key`
		ORDER BY id < 7, id; ";
	}
}