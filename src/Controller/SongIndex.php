<?php

class Controller_SongIndex extends CachedController
{
	function get()
	{
		echo new View_SongIndex();
	}
}