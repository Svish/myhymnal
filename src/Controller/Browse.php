<?php

class Controller_Browse
{
	function get()
	{
		echo new View_BrowseSongs;
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