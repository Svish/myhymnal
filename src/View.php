<?php

use Bitworking\Mimeparse;

abstract class View extends DynObj
{
	/**
	 * Default layout.
	 */
	protected $_layout = 'layout';

	/**
	 * Returns the rendered View.
	 */
	public function __toString()
	{
		Timer::start(get_class($this).'->'.__FUNCTION__);
		try
		{
			$accept = array(
				'application/json',
				'text/javascript',
				'text/html');

			$match = Mimeparse::bestMatch($accept, $_SERVER['HTTP_ACCEPT']);

			switch($match)
			{
				case 'text/javascript':
				case 'application/json':
					header('content-type: '.$match.'; charset=utf-8');
					ob_start('ob_gzhandler');
					return json_encode($this->when_json(), JSON_NUMERIC_CHECK);

				case 'text/html':
				default:
					header('content-type: text/html; charset=utf-8');
					return $this->render();

			}
		}
		catch(Exception $e)
		{
			return '<strong>'.get_class($this).'</strong> '.$e->getMessage();
		}
		Timer::stop();
	}

	protected function when_json()
	{
		return $this->data;
	}

	/**
	 * Factory method to create views.
	 */
	public static function factory($view)
	{
		$params = func_get_args();
		array_shift($params);

		$class = new ReflectionClass('View_'.$view);
		return $class->newInstanceArgs($params);
	}

	/**
	 * Renders this view.
	 * 
	 * @param $layout If true, this View will be rendered as the 'content' partial of $_layout.
	 * @param $template Name of Mustache template to use. If NULL the name of the View will be used.
	 */
	public function render($layout = TRUE, $template = NULL)
	{
		// Default template if none given
		if($template === NULL)
			$template = implode(DIRECTORY_SEPARATOR, array_splice(explode('_', get_class($this)), 1));


		// Render with layout
		Timer::start(get_class($this).'->'.__FUNCTION__, array($layout ? 'with layout' : 'no layout', $template));
		if($layout)
		{
			// Compile style sheet
			Less::compile(
				DOCROOT.'less'.DIRECTORY_SEPARATOR.'styles.less',
				DOCROOT.'_'.DIRECTORY_SEPARATOR.'styles.css');

			// Set helpers.
			self::engine()->setHelpers(include CONFROOT.'mustache_globals.php');
			self::engine()->setPartials(array(
				'content' => self::engine()->render($template, $this),
				));
			$r = self::engine()->render($this->_layout, $this);
		}
		// Plain render
		else
		{
			$r = self::engine()->render($template, $this);
		}
		Timer::stop();
		return $r;
	}


	private static $_engine;
	private static function engine()
	{
		if( ! self::$_engine)
			self::$_engine = new Mustache_Engine
			(
				array
				(
					'loader' => new Mustache_MyLoader(DOCROOT.'views'),
					'partials_loader' => new Mustache_MyLoader(DOCROOT.'views'.DIRECTORY_SEPARATOR.'partials'),
				)
			);

		return self::$_engine;
	}
}