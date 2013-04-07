<?php

class Controller_Debug
{
	function get($sid)
	{
		header('content-type: text/plain;charset=utf-8');
		echo Cache::get('sid_'.$sid);
	}
}