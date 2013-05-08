<?php

class HTTP
{

	public static function status($code)
	{
		return implode(' ', array($_SERVER['SERVER_PROTOCOL'], $code, self::$codes[$code]));
	}


	public static $codes = array
	(
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Switch Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a teapot',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		425 => 'Unordered Collection',
		426 => 'Upgrade Required',
		449 => 'Retry With',
		450 => 'Blocked by Windows Parental Controls',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		509 => 'Bandwidth Limit Exceeded',
		510 => 'Not Extended'
	);


	public static function head($url)
	{
		Timer::start(__METHOD__, func_get_args());

		$request = curl_init();
		curl_setopt_array($request, array
		(
		     CURLOPT_URL => $url,
		     
		     CURLOPT_RETURNTRANSFER => TRUE,
		     CURLOPT_HEADER => TRUE,
		     CURLOPT_NOBODY => TRUE,

		     CURLOPT_FOLLOWLOCATION => TRUE,
		     CURLOPT_MAXREDIRS => 10,
		));
		$response = curl_exec($request);

		if($response === FALSE)
			$response = new Exception(curl_error($request), curl_errno($request));

		curl_close($request);
		Timer::stop();

		if($response instanceof Exception)
			throw $response;

		return $response;
	}

	public static function get($url, array $params = array())
	{
		Timer::start(__METHOD__, array($url));

		if( ! empty($params))
			$url .= '?'.http_build_query($params);

		$request = curl_init();
		curl_setopt_array($request, array
		(
		     CURLOPT_URL => $url,
		     
		     CURLOPT_RETURNTRANSFER => TRUE,
		     CURLOPT_HEADER => FALSE,

		     CURLOPT_FOLLOWLOCATION => TRUE,
		     CURLOPT_MAXREDIRS => 10,
		));
		$response = curl_exec($request);

		if($response === FALSE)
			$response = new Exception(curl_error($request), curl_errno($request));

		curl_close($request);
		Timer::stop();

		if($response instanceof Exception)
			throw $response;

		return $response;
	}
}