<?php

class Controller_Search extends CachedController
{
	private $term;

	public function __construct()
	{
		$this->term = isset($_GET['term']) ? $_GET['term'] : '';
	}

	public function get()
	{
		echo View::factory('Search', $this->term);
	}
}