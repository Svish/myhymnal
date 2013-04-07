<?php

class Controller_BookIndex
{
	function get()
	{
		Timer::start(__METHOD__);
		echo new View_BookIndex();
		Timer::stop();
	}
}