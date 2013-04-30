<?php

class View_Error extends View
{
	public function __construct(Exception $e)
	{
		$this->error = $e;

		if(array_key_exists($e->getCode(), HTTP::$codes))
		{
			$this->title = HTTP::$codes[$e->getCode()];
			$this->code = $e->getCode();
		}
		else
		{
			$this->title = HTTP::$codes[500];
			$this->code = 500;
		}

		header(HTTP::status($this->code));
	}

	
}