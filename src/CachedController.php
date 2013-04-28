<?php

abstract class CachedController extends Controller
{
	public function before(array &$info)
	{
		parent::before($info);

		$cache = Cache::get('page_'.sha1($_SERVER['REQUEST_URI']));
		if($cache !== FALSE)
		{
			foreach($cache['headers'] as $h)
				header($h);
			header('X-Cache-Hit: true');
			echo $cache['content'];
			exit;
		}

		ob_start();
	}

	public function after(array $info)
	{
		$cache = array
			(
				'headers' => headers_list(),
				'content' => ob_get_flush(),
			);

		Cache::set('page_'.sha1($_SERVER['REQUEST_URI']), $cache);

		parent::after();
	}
}