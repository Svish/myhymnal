<?php

class Controller_Debug extends Controller
{
	function get($rid)
	{
		header('content-type: text/plain;charset=utf-8');
		echo Cache::get('rid', $rid);
	}
}