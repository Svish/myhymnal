<?php

class Cache
{
	private static function dir()
	{
		$dir = DOCROOT.'tmp'.DIRECTORY_SEPARATOR;

		if( ! is_dir($dir))
			mkdir($dir);

		return $dir;
	}

	public function get($key, $max_age = NULL)
	{
		$file = self::dir().$key;

		if( ! file_exists($file))
			return FALSE;

		if($max_age === NULL 
		|| time() - filemtime($file) < $max_age)
			return unserialize(file_get_contents($file));

		return FALSE;
	}

	public function set($key, $data)
	{
		$file = self::dir().$key;
		file_put_contents($file, serialize($data));
	}

	public function delete($key = NULL, $age = NULL)
	{
		foreach(glob(self::dir().$key) as $file)
			if($age === NULL || time() - filemtime($file) > $age)
				unlink($file);
	}
}