<?php

class Controller_About
{
	function get()
	{
		Timer::start(__METHOD__);
		echo new View_About();
		Timer::stop();
	}
}