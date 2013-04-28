<?php

class Website
{
	public static function init(array $routes, $error_handler = NULL)
	{
		return new Website($routes, $error_handler);
	}
	
	private $tokens = array
		(
			':alpha' => '([\p{L}_]+)',
			':number' => '([\p{Nd}]+)',
			':alphanum'  => '([\p{L}\p{Nd}\p{Pd}_]+)',
		);

	private $routes;
	private $error_handler;

	public function __construct(array $routes, $error_handler = NULL)
	{
		if(empty($routes))
			throw new Exception('Need routes to serve', 500);

		$this->routes = $routes;
		$this->error_handler = $error_handler;
	}

	public function serve($path)
	{
		extract($this->parse($path), EXTR_OVERWRITE);

		try
		{
			if($handler === NULL)
				throw new Exception('Page not found: '.$path, 404);

			$handler = new $handler();

			if($xhr AND method_exists($handler, $method.'_xhr'))
			{
				header('content-type: application/json; charset=utf-8');
				$method .= '_xhr';
			}
			else
			{
				header('content-type: text/html; charset=utf-8');
			}

			if( ! method_exists($handler, $method))
				$method = 'get';

			call_user_func_array(array($handler, $method), $params);
		}
		catch(Exception $e)
		{
			if($this->error_handler)
				call_user_func_array(array($this->error_handler, 'get'), array($e));
			else
				throw $e;
		}
	}

	public function parse($path)
	{
		if($path === NULL)
			$path = isset($_SERVER['PATH_INFO']) 
				? $_SERVER['PATH_INFO'] 
				: (
					isset($_SERVER['ORIG_PATH_INFO'])
						? $_SERVER['ORIG_PATH_INFO'] 
						: '/'
				);

		$info = array(
			'path' => $path,
			'method' => strtolower($_SERVER['REQUEST_METHOD']),
			'xhr' => $this->is_xhr_request(),
			'handler' => NULL,
			'params' => array(),
			);

		if(isset($this->routes[$path]))
			return array(
				'handler' => $this->routes[$path]
				) + $info;

		foreach ($this->routes as $pattern => $handler)
		{
			$pattern = strtr($pattern, $this->tokens);

			if (preg_match('#^/?' . $pattern . '/?$#u', $path, $matches))
			{
				unset($matches[0]);
				return array(
					'handler' => $handler, 
					'params' => $matches,
					) + $info;
			}
		}
		return $info;
	}

	private static function is_xhr_request()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
			&& $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
	}
}