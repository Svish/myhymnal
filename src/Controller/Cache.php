<?php

class Controller_Cache extends Controller
{
	function get($task)
	{
		switch($task)
		{
			case 'clear':
				header('content-type: text/plain');
				Cache::delete();
				echo 'done'.PHP_EOL;
				return;

			case 'prime':
				echo new View_CachePrimer;
				return;
		}
	}
}