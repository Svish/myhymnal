<?php

class Controller_SongIndex extends Controller
{
	function get()
	{
		echo new View_SongIndex();
	}
}