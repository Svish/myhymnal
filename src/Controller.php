<?php

abstract class Controller
{
	public function before(array $info)
	{
		Timer::start(get_class($this).'::'.$info['method'], $info['params']);
	}

	public function after()
	{
		Timer::stop();
	}
}