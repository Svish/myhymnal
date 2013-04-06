<?php

class Controller_SongIndex
{
	function get()
	{
		echo new View_SongIndex();
	}
}