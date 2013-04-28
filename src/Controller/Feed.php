<?php

class Controller_Feed extends Controller
{
	function get($type)
	{
		echo new View_Feed($type);
	}
}