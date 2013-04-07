<?php

class Cache
{
	private static $instance;
	public static function instance()
	{
		if( ! self::$instance)
			self::$instance = new Cache();

		return self::$instance;
	}

	private $dir;
	private function __construct()
	{
		$this->dir = DOCROOT.'tmp'.DIRECTORY_SEPARATOR;

		if( ! is_dir($this->dir))
			mkdir($this->dir);
	}

	public function get($key, $max_age = NULL)
	{
		$file = $this->dir.$key;

		if( ! file_exists($file))
			return FALSE;

		if($max_age === NULL 
		|| time() - filemtime($file) < $max_age)
			return unserialize(file_get_contents($file));

		return FALSE;
	}

	public function set($key, $data)
	{
		$file = $this->dir.$key;
		file_put_contents($file, serialize($data));
	}

	public function delete($key = NULL, $age = NULL)
	{
		foreach(glob($this->dir.$key) as $file)
			if($age === NULL || time() - filemtime($file) > $age)
				unlink($file);
	}
}