<?php

class Controller_SongIndex
{
	function get()
	{
		Timer::start(__METHOD__);
		echo new View_SongIndex();
		Timer::stop();
	}
}