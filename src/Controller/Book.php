<?php

class Controller_Book
{
	function get($id)
	{
		Timer::start(__METHOD__, func_get_args());
		echo new View_Book($id);
		Timer::stop();
	}
}