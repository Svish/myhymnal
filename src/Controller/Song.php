<?php

class Controller_Song extends Controller
{
	function get($id = NULL, $slug = NULL)
	{
		// Random song
		if($id === NULL)
		{
			$song = Model_Song::get_random();
			header('Location: '.WEBROOT.$song->url, true, 307);
			exit;
		}

		// Specific song
		$song = Model_Song::get($id);

		if($song === FALSE)
			throw new Exception('Song not found.', 404);

		// Redirect if missing slug in URL
		if($slug === NULL || $slug !== $song->slug)
		{
			header('Location: '.WEBROOT.$song->url, true, 301);
			exit;
		}
		
		echo new View_Song($song);
	}
}