<?php

abstract class XmlView extends View
{
	protected $_layout = FALSE;
	protected $_accept = array(
		'application/xml',
		'text/xml',
		);

	protected function toString($mime)
	{
		if( ! in_array($mime, $this->_accept))
			return parent::toString($mime);

		header('content-type: '.$mime.'; charset=utf-8');
		return $this->render();
	}
}