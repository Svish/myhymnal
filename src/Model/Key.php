<?php

class Model_Key extends Model
{
	public static function find_all()
	{
		$query = DB::instance()->prepare('SELECT * FROM `key`');
		$query->execute();
		return $query->fetchAll(PDO::FETCH_CLASS, __CLASS__);
	}
}