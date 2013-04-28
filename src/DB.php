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
			$config = include CONFROOT.'db.'.ENV.'.php';

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

		$current = (int) DB::query('SELECT * FROM version')
			->execute()
			->fetchColumn();

		$dir = DOCROOT.'schema'.DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR;
		foreach(glob($dir.'*.sql') as $m)
		{
			$version = (int) str_replace($dir, NULL, $m);

			if($version > $current)
			{
				Timer::start(__METHOD__, array($version));
				try
				{
					$script = preg_replace('/#.++/m', NULL, file_get_contents($m));
					$queries = preg_split('/;$\s*+/m', $script, -1, PREG_SPLIT_NO_EMPTY);

					foreach($queries as $q)
						if(trim($q) != '')
							DB::prepare($q)->execute();

					if(file_exists($dir.$version.'.php'))
						require $dir.$version.'.php';

					DB::query('UPDATE version SET version = '.$version);
				}
				catch(Exception $e)
				{
					var_dump($e->getMessage());
					Timer::stop();
					break;
				}
				Timer::stop();
			}
		}

		Timer::stop();
	}
}