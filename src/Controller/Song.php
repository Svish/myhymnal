<?php

class Controller_Song
{
	function get($id, $key = NULL)
	{
		Timer::start(__METHOD__, func_get_args());
		echo new View_Song($id, $key);
		Timer::stop();
	}
}