<?php

class Timer
{
	public $name;
	public $time;
	public $data;
	public $memory;

	public $timers = array();

	public function __construct($name, array $data)
	{
		$this->name = $name;
		$this->time = microtime(TRUE);
		$this->data = $data;
		$this->memory = memory_get_peak_usage();
	}

	public function end()
	{
		$this->time = microtime(TRUE) - $this->time;
		$this->memory = memory_get_peak_usage() - $this->memory;
		return $this;
	}

	public function __toString()
	{
		ob_start();
		$this->printStats();
		return ob_get_clean();
	}

	public function printStats($level = 0)
	{
		echo $level == 0
			? $this->name
			: str_repeat(' │ ', $level-1).' └ '.$this->name;
		echo '('.implode(', ', $this->data).')'."\r\n";

		$level += 1;

		echo str_repeat(' │ ', $level)."\r\n";
		echo str_repeat(' │ ', $level).number_format($this->time, 3)." s\r\n";
		echo str_repeat(' │ ', $level).Util::bytes_to_human($this->memory)."\r\n";
	
		foreach($this->timers as $timer)
		{
			echo str_repeat(' │ ', $level)."\r\n";
			$timer->printStats($level);
			echo str_repeat(' │ ', $level)." ┘ \r\n";
		}
	}




	private static $all = array();
	private static $level = array();

	public static function start($name, array $data = array())
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