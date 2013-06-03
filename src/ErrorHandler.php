<?php

class ErrorHandler
{
	public static function handle($e)
	{
		switch($e['number'])
		{
			case E_WARNING:
				return;
			default:
				echo new View_Error($e);
				exit;
		}
	}
}