<?php

class Controller_Search
{
	function get()
	{
		$term = isset($_GET['term']) ? $_GET['term'] : NULL;

		Timer::start(__METHOD__, array($term));
		$songs = Model_Song::search($term);
		
		if(count($songs) == 1)
		{
			header('Location: '.WEBROOT.$songs[0]->url);
			exit;
		}

		echo new View_Search($songs, $term);
		Timer::stop();
	}

	function get_xhr()
	{
		if( ! isset($_GET['term']) || $_GET['term'] === '')
			return;

		$list = Model_Song::search($_GET['term']);

		foreach($list as &$song)
			$song = array('label' => $song->title, 'value' => $song->id);

		echo json_encode($list, JSON_NUMERIC_CHECK);
	}
}