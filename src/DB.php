<?php

class DB
{
	private static $instance = NULL;

	private function __construct() { }
	private function __clone() { }

	public static function instance()
	{
		if (!self::$instance)
		{
			$config = include 'DB.config.php';

			self::$instance = new PDO
			(
				$config['dsn'],
				$config['username'], 
				$config['password'], 
				array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
			);

			self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		return self::$instance;
	}

	public static function query($query)
	{
		return new Query(self::instance()->prepare($query));
	}
}