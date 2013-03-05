<?php

class Controller_Song
{
	function get($id, $key = NULL)
	{
		echo new View_Song($id, $key);
	}
}