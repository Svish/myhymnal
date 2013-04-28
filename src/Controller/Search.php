<?php

class Controller_Search extends Controller
{
	private $term;

	public function __construct()
	{
		$this->term = isset($_GET['term']) ? $_GET['term'] : '';
	}

	public function before(array $info)
	{
		parent::before(array('params' => array($this->term)) + $info);
	}

	public function get()
	{
		$songs = Model_Song::search($this->term);
		
		if(count($songs) == 1)
		{
			header('Location: '.WEBROOT.$songs[0]->url);
			exit;
		}

		echo new View_Search($songs, $this->term);
	}

	public function get_xhr()
	{
		if($this->term === '')
			return;

		$list = Model_Song::search($this->term);

		foreach($list as &$song)
			$song = array('label' => $song->title, 'value' => $song->url);

		echo json_encode($list, JSON_NUMERIC_CHECK);
	}
}