<?php

use Bitworking\Mimeparse;

/**
 * Returns the rendered View.
 */
abstract class View extends DynObj
{
	/**
	 * Acceptable mime types for this view.
	 */
	protected $_accept = array('text/html');

	/**
	 * Template to use as layout
	 */
	protected $_layout = 'layout';

	/**
	 * Template to use when rendering, or null to get it from view name.
	 */
	protected $_template = NULL;

	/**
	 * Flag to prevent stack overflow when a view crashes.
	 */
	private $_view_crash = FALSE;

	final public function __toString()
	{
		$mime = Mimeparse::bestMatch($this->_accept, $_SERVER['HTTP_ACCEPT']);

		Timer::start(get_class($this).'->'.__FUNCTION__, array($mime));

		try
		{
			return $this->toString($mime);
		}
		catch(Exception $e)
		{
			if($this->_view_crash)
			{
				$r = '<strong>'.get_class($this).'</strong> '.$e->getMessage();
			}
			else
			{
				$c = new Controller_Error();
				ob_start();
				$c->get($e);
				$r = ob_get_clean();
			}
		}

		Timer::stop();

		return $r;
	}


	protected function toString($mime = 'text/html')
	{
		switch($mime)
		{
			case 'text/html':
				header('content-type: text/html; charset=utf-8');
				return $this->render();

			default:
				header('Can only provide '.implode(', ', $this->_accept), true, 406);
				return '';
		}
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
		// Choose default template if none specified
		if($template === NULL)
			if($this->_template !== NULL)
				$template = $this->_template;
			else
				$template = implode(DIRECTORY_SEPARATOR, array_splice(explode('_', get_class($this)), 1));

		Timer::start(get_class($this).'->'.__FUNCTION__, array($layout ? 'with layout' : 'no layout', $template));

		// With layout
		if($this->_layout AND $layout)
		{
			// Compile style sheet
			Less::compile(
				DOCROOT.'less'.DIRECTORY_SEPARATOR.'styles.less',
				DOCROOT.'_'.DIRECTORY_SEPARATOR.'styles.css');

			// Set content partial
			self::engine()->setPartials(array('content' => self::engine()->render($template, $this)));

			// Render
			$r = self::engine()->render($this->_layout, $this);
		}
		// No layout
		else
		{
			$r = self::engine()->render($template, $this);
		}

		Timer::stop();
		return $r;
	}


	private static $_engine;
	public static function engine()
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