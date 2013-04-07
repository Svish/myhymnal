<?php

class Timer
{
	public $name;
	public $time;
	public $data;

	public $timers = array();


	public function __construct($name, $data)
	{
		$this->name = $name;
		$this->time = microtime(TRUE);
		$this->data = $data;
	}

	public function end()
	{
		$this->time = microtime(TRUE) - $this->time;
		return $this;
	}


	private static $all = array();
	private static $level = array();

	public static function start($name, $data = NULL)
	{
		$t = new Timer($name, $data);

		if( ! empty(self::$level))
			array_push(end(self::$level)->timers, $t);

		array_push(self::$level, $t);
	}

	public static function stop()
	{
		return array_pop(self::$level)->end();
	}

	public static function result()
	{
		while( ! empty(self::$level))
			$last = self::stop();
		return $last;
	}
}