<?php

class Controller_Debug
{
	function get($sid)
	{
		$stats = Cache::get('sid_'.$sid);

		header('content-type: text/plain;charset=utf-8');
		print_r($stats);
	}
}