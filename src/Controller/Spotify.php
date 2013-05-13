<?php

class Controller_Spotify extends CachedController
{
	function get()
	{
		echo new View_Spotify();
	}
}