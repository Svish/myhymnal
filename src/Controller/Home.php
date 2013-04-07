<?php

class Controller_Home
{
	function get()
	{
		Timer::start(__METHOD__);
		echo new View_Home();
		Timer::stop();
	}
}