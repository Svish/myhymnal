<?php

class HTTP
{
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

	public static function get($url)
	{
		Timer::start(__METHOD__, func_get_args());

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