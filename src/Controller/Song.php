<?php

class Controller_Song
{
	function get($id, $slug = NULL)
	{
		Timer::start(__METHOD__, func_get_args());

		$song = Model_Song::get($id);

		if($song === FALSE)
			throw new Exception('Song not found.', 404);

		if($slug === NULL || $slug !== $song->slug)
		{
			header('Location: '.WEBROOT.$song->url);
			exit;
		}
		else
			echo new View_Song($song);

		Timer::stop();
	}
}