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
				HTTP::redirect(302);
				return;

			case 'prime':
				echo new View_CachePrimer;
				return;
		}
	}
}