<?php

class Controller_About extends CachedController
{
	function get()
	{
		echo new View_About();
	}
}