<?php

class Controller_Search extends Controller
{
	public function before(array &$info)
	{
		$info['params'][] = isset($_GET['term']) 
			? $_GET['term'] 
			: '';

		parent::before($info);
	}

	public function get($term)
	{
		echo View::factory('Search', $term);
	}
}