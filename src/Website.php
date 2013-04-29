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
		$info = $this->parse($path);

		try
		{
			if($info['handler'] === NULL)
				throw new Exception('No route found for '.$path, 404);

			$handler = new $info['handler'];

			if( ! method_exists($info['handler'], $info['method']))
				$info['method'] = 'get';

			if( method_exists($handler, 'before'))
				call_user_func_array(array($handler, 'before'), array(&$info));


			call_user_func_array(array($handler, $info['method']), $info['params']);

			if( method_exists($handler, 'after'))
				call_user_func_array(array($handler, 'after'), array(&$info));
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
}