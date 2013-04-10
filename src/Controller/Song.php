<?php

class Controller_Song
{
	function get($id)
	{
		Timer::start(__METHOD__, func_get_args());

		echo new View_Song($id);

		Timer::stop();
	}
}