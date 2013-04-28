<?php

abstract class JsonEnabledView extends View
{
	protected $_accept = array(
		'application/json',
		'text/javascript',
		'text/html',
		);


	protected function toString($mime)
	{
		switch($mime)
		{
			case 'text/javascript':
			case 'application/json':
				header('content-type: '.$mime.'; charset=utf-8');
				ob_start('ob_gzhandler');
				return json_encode($this->when_json(), JSON_NUMERIC_CHECK);

			default:
				return parent::toString($mime);
		}
	}

	protected function when_json()
	{
		return $this->data;
	}
}