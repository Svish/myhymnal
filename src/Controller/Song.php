<?php

class Controller_Song extends Controller
{
	function get($id = NULL, $slug = NULL)
	{
		// Random song
		if($id === NULL)
			HTTP::redirect(307, Model_Song::get_random()->url);

		// Specific song
		$song = Model_Song::get($id);

		if($song === FALSE)
			throw new HTTP_Exception('Song not found.', 404);

		// Redirect if missing slug in URL
		if($slug === NULL || $slug !== $song->slug)
			HTTP::redirect(301, $song->url);
		
		echo new View_Song($song);
	}
}