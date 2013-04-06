<?php

abstract class View extends DynObj
{
	protected $_layout = 'layout';
	protected $_engine;

	public function __construct()
	{
		$this->_engine = new Mustache_Engine
		(
			array
			(
				'loader' => new Mustache_MyLoader(DOCROOT.'views'),
				'partials_loader' => new Mustache_MyLoader(DOCROOT.'views'.DIRECTORY_SEPARATOR.'partials'),
			)
		);

		call_user_func_array(array($this, 'init'), func_get_args());
	}

	public function init() {}

	public static function factory($view)
	{
		$class = 'View_'.$view;
		return new $class();
	}

	public function render($layout = TRUE, $template = NULL)
	{
		// Default template if none given
		if($template === NULL)
			$template = implode(DIRECTORY_SEPARATOR, array_splice(explode('_', get_class($this)), 1));

		// Render with layout
		if($layout)
		{
			$this->_engine->setHelpers(include DOCROOT.'config.php');
			$this->_engine->setPartials(array(
				'content' => $this->_engine->render($template, $this),
				));
			return $this->_engine->render($this->_layout, $this);
		}
		// Plain render
		else
		{
			return $this->_engine->render($template, $this);
		}
	}



	public function __toString()
	{
		try
		{
			// Compile style sheet
			Less::compile(
				DOCROOT.'less'.DIRECTORY_SEPARATOR.'styles.less',
				DOCROOT.'_'.DIRECTORY_SEPARATOR.'styles.css');

			// Render
			return $this->render();
		}
		catch(Exception $e)
		{
			//TODO Throw 500;
			var_dump($e->getMessage());
		}
	}
}