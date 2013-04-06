<?php

class HTTP
{
	public static function get($url)
	{
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
		curl_close($request);

		return $response;
	}
}