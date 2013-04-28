<?php

class Controller_Error
{
	function get(Exception $e)
	{
		Timer::start(__METHOD__);
		echo new View_Error($e);
		Timer::stop();
	}
}