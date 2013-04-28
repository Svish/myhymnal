<?php

class Controller_Song extends Controller
{
	function get($id, $slug = NULL)
	{
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
	}
}