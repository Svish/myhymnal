<?php

class View_BrowseSongs extends View
{
	public function init()
	{
		$this->title = 'Songs';
		$this->songs = Model_Song::get_list();
	}


}