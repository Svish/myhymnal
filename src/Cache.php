<?php

class Cache
{
	public static function get($store, $key, $max_age = NULL)
	{
		$file = self::file($store, $key);

		if( ! file_exists($file))
			return FALSE;

		if(self::valid($file, $max_age))
			return unserialize(file_get_contents($file));

		return FALSE;
	}

	public static function set($store, $key, $data)
	{
		self::prep_dir(self::dir($store, $key));
		$file = self::file($store, $key);
		file_put_contents($file, serialize($data));
		return $data;
	}

	public static function delete($store = NULL, $key = NULL, $max_age = 0)
	{
		$dir = self::dir($store, $key);
		if( ! file_exists($dir))
			return;

		$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir),
			RecursiveIteratorIterator::CHILD_FIRST);

		foreach($it as $path => $f)
		{
			if($f->isDir())
			{
				@rmdir($path);
			}
			elseif( ! self::valid($path, $max_age))
			{
				@unlink($path);
			}
		}
		@rmdir($dir);
	}

	private static function prep_dir($dir)
	{
		if( ! is_dir($dir))
		{
			mkdir($dir, 02755, true);
			chmod($dir, 02755);
		}
		return $dir;
	}

	private static function dir($store = NULL, $key = NULL)
	{
		$dir = DOCROOT.'tmp';

		if($store)	$dir .= DIRECTORY_SEPARATOR.$store;
		if($key) 	$dir .= DIRECTORY_SEPARATOR.substr($key, 0, 2);

		return $dir.DIRECTORY_SEPARATOR;
	}

	private static function file($store, $key)
	{
		return self::dir($store, $key).$key;
	}

	private static function valid($file, $max_age)
	{
		return $max_age === NULL 
			OR time() - filemtime($file) < $max_age;
	}
}