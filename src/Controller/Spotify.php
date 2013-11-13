<?php

class Controller_Spotify extends Controller
{
	function get()
	{
		echo new View_Spotify();
	}
}