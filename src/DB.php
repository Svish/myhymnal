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

			self::migrate();
		}

		return self::$instance;
	}

	public static function prepare($query)
	{
		return new Query(self::instance()->prepare($query));
	}

	public static function query($query, int $col = NULL)
	{
		if($col === NULL)
			return new Query(self::instance()->query($query));
		return new Query(self::instance()->query($query, PDO::FETCH_COLUMN, $col));
	}

	public static function migrate()
	{
		Timer::start(__METHOD__);

		// Check current DB version
		$current = (int) DB::query('SELECT * FROM version')
			->execute()
			->fetchColumn();

		// For each migration script
		$dir = DOCROOT.'schema'.DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR;
		foreach(glob($dir.'*.sql') as $m)
		{
			// Get version from filename
			$version = (int) str_replace($dir, NULL, $m);

			// Execute all queries if newer
			if($version > $current)
			{
				Timer::start(__METHOD__, array($version));

				$queries = preg_split('/;$\s*+/m', file_get_contents($m), -1, PREG_SPLIT_NO_EMPTY);
				foreach($queries as $q)
					DB::prepare($q)->execute();

				DB::query('UPDATE version SET version = '.$version);

				Timer::stop();
			}
		}

		Timer::stop();
	}
}