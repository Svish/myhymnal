<?php

class View_Error extends JsonEnabledView
{
	public function __construct($e)
	{
		if($e['context'] instanceof HTTP_Exception
		&& array_key_exists($e['context']->getHttpStatus(), HTTP::$codes))
		{
			$this->title = HTTP::$codes[$e['context']->getHttpStatus()];
			$this->code = $e['context']->getHttpStatus();
		}
		else
		{
			$this->title = HTTP::$codes[500];
			$this->code = 500;
		}

		ob_start();
		var_dump($e['context']);
		$e['context'] = ob_get_clean();

		header(HTTP::status($this->code));

		if(ENV !== 'dev')
			$e = array('message' => $e['message']);

		$this->error = $e;
	}

	
}