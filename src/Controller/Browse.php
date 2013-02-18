<?php

class Controller_Browse
{
	function get()
	{

		$term = isset($_GET['term']) ? $_GET['term'] : NULL;
		$songs = Model_Song::get_list($term);

		// Redirect if only one hit
		if(count($songs) == 1)
		{
			header('Location: '.WEBROOT.'song/'.$songs[0]->id);
			exit;
		}

		echo new View_BrowseSongs($songs, $term);
	}

	function get_xhr()
	{
		if( ! isset($_GET['term']) || $_GET['term'] === '')
			return;

		$list = Model_Song::get_list($_GET['term']);

		foreach($list as &$song)
			$song = array('label' => $song->title, 'value' => $song->id);

		echo json_encode($list, JSON_NUMERIC_CHECK);
	}
}